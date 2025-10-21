<?php
class Message extends Model {
    protected $table = 'messages';
    
    public function getChatMessages($chatId, $limit = 50) {
        // Sanitize limit to prevent SQL injection
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 50;

        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = ?
            ORDER BY m.created_at ASC
            LIMIT {$limit}
        ");
        $stmt->execute([$chatId]);
        return $stmt->fetchAll();
    }
    
    public function getNewMessages($chatId, $lastMessageId) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.chat_id = ? AND m.id > ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$chatId, $lastMessageId]);
        return $stmt->fetchAll();
    }
    
    public function getMessageWithUser($messageId) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image as sender_image
            FROM {$this->table} m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.id = ?
        ");
        $stmt->execute([$messageId]);
        return $stmt->fetch();
    }
    
    public function markAsRead($chatId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET is_read = 1 
            WHERE chat_id = ? AND sender_id != ? AND is_read = 0
        ");
        return $stmt->execute([$chatId, $userId]);
    }
    
    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as unread_count
            FROM {$this->table} m
            JOIN chats c ON m.chat_id = c.id
            WHERE (c.user1_id = ? OR c.user2_id = ?)
            AND m.sender_id != ?
            AND m.is_read = 0
        ");
        $stmt->execute([$userId, $userId, $userId]);
        $result = $stmt->fetch();
        return $result['unread_count'];
    }
}
?>
