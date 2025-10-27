<?php
require_once __DIR__ . '/BaseRepository.php';

class MessageRepository extends BaseRepository {
    protected $table = 'messages';
    
    /**
     * Busca mensagens do chat
     */
    public function getChatMessages($chatId, $limit = 50) {
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 50;

        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = :chat_id
            ORDER BY m.created_at ASC
            LIMIT :limit
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca novas mensagens desde o último ID
     */
    public function getNewMessages($chatId, $lastMessageId) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = :chat_id AND m.id > :last_message_id
            ORDER BY m.created_at ASC
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':last_message_id', $lastMessageId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca mensagem com informações do remetente
     */
    public function getMessageWithUser($messageId) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.id = :message_id
        ");
        $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Marca mensagens como lidas
     */
    public function markAsRead($chatId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET is_read = 1 
            WHERE chat_id = :chat_id AND sender_id != :user_id AND is_read = 0
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Conta mensagens não lidas do usuário
     */
    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as unread_count
            FROM {$this->table} m
            JOIN chats c ON m.chat_id = c.id
            WHERE (c.user1_id = :user_id OR c.user2_id = :user_id)
            AND m.sender_id != :user_id
            AND m.is_read = 0
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['unread_count'];
    }
    
    /**
     * Busca última mensagem do chat
     */
    public function getLastMessage($chatId) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = :chat_id
            ORDER BY m.created_at DESC
            LIMIT 1
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Busca mensagens não lidas de um chat específico
     */
    public function getUnreadMessages($chatId, $userId) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = :chat_id 
            AND m.sender_id != :user_id 
            AND m.is_read = 0
            ORDER BY m.created_at ASC
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca mensagens por período
     */
    public function getMessagesByDateRange($chatId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = :chat_id
            AND m.created_at BETWEEN :start_date AND :end_date
            ORDER BY m.created_at ASC
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca mensagens de texto (exclui imagens/arquivos)
     */
    public function getTextMessages($chatId, $limit = 50) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = :chat_id
            AND m.message_type = 'text'
            ORDER BY m.created_at ASC
            LIMIT :limit
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca mensagens por tipo
     */
    public function getMessagesByType($chatId, $messageType, $limit = 50) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = :chat_id
            AND m.message_type = :message_type
            ORDER BY m.created_at ASC
            LIMIT :limit
        ");
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':message_type', $messageType);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Deleta mensagem
     */
    public function deleteMessage($messageId, $userId) {
        $stmt = $this->db->prepare("
            DELETE FROM {$this->table} 
            WHERE id = :message_id AND sender_id = :user_id
        ");
        $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Busca estatísticas de mensagens
     */
    public function getMessageStats($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_messages,
                COUNT(CASE WHEN sender_id = :user_id THEN 1 END) as sent_messages,
                COUNT(CASE WHEN sender_id != :user_id THEN 1 END) as received_messages,
                COUNT(CASE WHEN is_read = 0 AND sender_id != :user_id THEN 1 END) as unread_messages
            FROM {$this->table} m
            JOIN chats c ON m.chat_id = c.id
            WHERE c.user1_id = :user_id OR c.user2_id = :user_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>

