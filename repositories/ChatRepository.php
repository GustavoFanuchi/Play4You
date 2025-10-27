<?php
require_once __DIR__ . '/BaseRepository.php';

class ChatRepository extends BaseRepository {
    protected $table = 'chats';
    
    /**
     * Busca ou cria um chat entre dois usuários
     */
    public function findOrCreateChat($user1Id, $user2Id, $productId = null) {
        // Garante que user1_id seja sempre o menor ID para evitar duplicatas
        if ($user1Id > $user2Id) {
            $temp = $user1Id;
            $user1Id = $user2Id;
            $user2Id = $temp;
        }
        
        // Tenta encontrar chat existente
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user1_id = :user1_id AND user2_id = :user2_id
            AND (product_id = :product_id OR (product_id IS NULL AND :product_id IS NULL))
            LIMIT 1
        ");
        $stmt->bindParam(':user1_id', $user1Id, PDO::PARAM_INT);
        $stmt->bindParam(':user2_id', $user2Id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $chat = $stmt->fetch();
        
        if ($chat) {
            return $chat;
        }
        
        // Cria novo chat
        $chatData = [
            'user1_id' => $user1Id,
            'user2_id' => $user2Id,
            'product_id' => $productId
        ];
        
        try {
            $chatId = $this->create($chatData);
            return $this->findById($chatId);
        } catch (Exception $e) {
            // Se duplicata, busca existente
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table} 
                WHERE user1_id = :user1_id AND user2_id = :user2_id
                LIMIT 1
            ");
            $stmt->bindParam(':user1_id', $user1Id, PDO::PARAM_INT);
            $stmt->bindParam(':user2_id', $user2Id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        }
    }
    
    /**
     * Busca chats do usuário
     */
    public function getUserChats($userId) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   CASE 
                       WHEN c.user1_id = :user_id THEN u2.name 
                       ELSE u1.name 
                   END as other_user_name,
                   CASE 
                       WHEN c.user1_id = :user_id THEN u2.id 
                       ELSE u1.id 
                   END as other_user_id,
                   CASE 
                       WHEN c.user1_id = :user_id THEN u2.profile_image 
                       ELSE u1.profile_image 
                   END as other_user_image,
                   p.title as product_title,
                   p.images as product_images,
                   (SELECT COUNT(*) FROM messages m 
                    WHERE m.chat_id = c.id 
                    AND m.sender_id != :user_id 
                    AND m.is_read = 0) as unread_count
            FROM {$this->table} c
            LEFT JOIN users u1 ON c.user1_id = u1.id
            LEFT JOIN users u2 ON c.user2_id = u2.id
            LEFT JOIN products p ON c.product_id = p.id
            WHERE c.user1_id = :user_id OR c.user2_id = :user_id
            ORDER BY c.last_message_at DESC, c.created_at DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca chat com informações dos usuários
     */
    public function getChatWithUsers($chatId) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   u1.name as user1_name, u1.profile_image as user1_image,
                   u2.name as user2_name, u2.profile_image as user2_image,
                   p.title as product_title, p.images as product_images
            FROM {$this->table} c
            LEFT JOIN users u1 ON c.user1_id = u1.id
            LEFT JOIN users u2 ON c.user2_id = u2.id
            LEFT JOIN products p ON c.product_id = p.id
            WHERE c.id = :chat_id
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Atualiza última mensagem do chat
     */
    public function updateLastMessage($chatId, $messageId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET last_message_id = :message_id, last_message_at = CURRENT_TIMESTAMP 
            WHERE id = :chat_id
        ");
        $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Busca chats com mensagens não lidas
     */
    public function getChatsWithUnreadMessages($userId) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   CASE 
                       WHEN c.user1_id = :user_id THEN u2.name 
                       ELSE u1.name 
                   END as other_user_name,
                   (SELECT COUNT(*) FROM messages m 
                    WHERE m.chat_id = c.id 
                    AND m.sender_id != :user_id 
                    AND m.is_read = 0) as unread_count
            FROM {$this->table} c
            LEFT JOIN users u1 ON c.user1_id = u1.id
            LEFT JOIN users u2 ON c.user2_id = u2.id
            WHERE (c.user1_id = :user_id OR c.user2_id = :user_id)
            AND EXISTS (
                SELECT 1 FROM messages m 
                WHERE m.chat_id = c.id 
                AND m.sender_id != :user_id 
                AND m.is_read = 0
            )
            ORDER BY c.last_message_at DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Verifica se usuário tem acesso ao chat
     */
    public function userHasAccess($chatId, $userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM {$this->table} 
            WHERE id = :chat_id 
            AND (user1_id = :user_id OR user2_id = :user_id)
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Busca chat por produto
     */
    public function getChatsByProduct($productId) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   u1.name as user1_name, u2.name as user2_name,
                   p.title as product_title
            FROM {$this->table} c
            LEFT JOIN users u1 ON c.user1_id = u1.id
            LEFT JOIN users u2 ON c.user2_id = u2.id
            LEFT JOIN products p ON c.product_id = p.id
            WHERE c.product_id = :product_id
            ORDER BY c.last_message_at DESC
        ");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Deleta chat e todas as mensagens
     */
    public function deleteChat($chatId) {
        $this->beginTransaction();
        
        try {
            // Deleta mensagens primeiro
            $stmt = $this->db->prepare("DELETE FROM messages WHERE chat_id = :chat_id");
            $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Deleta chat
            $result = $this->delete($chatId);
            
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
?>

