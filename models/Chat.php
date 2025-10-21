<?php
class Chat extends Model {
    protected $table = 'chats';
    
    public function findOrCreateChat($user1Id, $user2Id, $productId = null) {
        // Ensure user1_id is always the smaller ID to avoid duplicates
        if ($user1Id > $user2Id) {
            $temp = $user1Id;
            $user1Id = $user2Id;
            $user2Id = $temp;
        }
        
        // Try to find existing chat
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user1_id = ? AND user2_id = ?
            AND (product_id = ? OR (product_id IS NULL AND ? IS NULL))
            LIMIT 1
        ");
        $stmt->execute([$user1Id, $user2Id, $productId, $productId]);
        $chat = $stmt->fetch();
        
        if ($chat) {
            return $chat;
        }
        
        // Create new chat
        $chatData = [
            'user1_id' => $user1Id,
            'user2_id' => $user2Id,
            'product_id' => $productId
        ];
        
        try {
            $chatId = $this->create($chatData);
            return $this->find($chatId);
        } catch (Exception $e) {
            // If duplicate, find existing
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table} 
                WHERE user1_id = ? AND user2_id = ?
                LIMIT 1
            ");
            $stmt->execute([$user1Id, $user2Id]);
            return $stmt->fetch();
        }
    }
    
    public function getUserChats($userId) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   CASE 
                       WHEN c.user1_id = ? THEN u2.name 
                       ELSE u1.name 
                   END as other_user_name,
                   CASE 
                       WHEN c.user1_id = ? THEN u2.id 
                       ELSE u1.id 
                   END as other_user_id,
                   CASE 
                       WHEN c.user1_id = ? THEN u2.profile_image 
                       ELSE u1.profile_image 
                   END as other_user_image,
                   p.title as product_title,
                   p.images as product_images,
                   (SELECT COUNT(*) FROM messages m 
                    WHERE m.chat_id = c.id 
                    AND m.sender_id != ? 
                    AND m.is_read = 0) as unread_count
            FROM {$this->table} c
            LEFT JOIN users u1 ON c.user1_id = u1.id
            LEFT JOIN users u2 ON c.user2_id = u2.id
            LEFT JOIN products p ON c.product_id = p.id
            WHERE c.user1_id = ? OR c.user2_id = ?
            ORDER BY c.last_message_at DESC, c.created_at DESC
        ");
        $stmt->execute([$userId, $userId, $userId, $userId, $userId, $userId]);
        return $stmt->fetchAll();
    }
    
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
            WHERE c.id = ?
        ");
        $stmt->execute([$chatId]);
        return $stmt->fetch();
    }
}
?>
