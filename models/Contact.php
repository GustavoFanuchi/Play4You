<?php
class Contact extends Model {
    protected $table = 'contacts';
    
    public function getRecentContacts($limit = 50) {
        // Sanitize limit to prevent SQL injection
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 50;

        $stmt = $this->db->prepare("
            SELECT c.*, u.name as user_name
            FROM {$this->table} c
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC
            LIMIT {$limit}
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function markAsRead($id) {
        return $this->update($id, ['is_read' => 1]);
    }
}
?>
