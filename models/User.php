<?php
class User extends Model {
    protected $table = 'users';
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$userId]);
    }
    
    public function getProfile($userId) {
        $stmt = $this->db->prepare("
            SELECT u.*, 
                   COUNT(DISTINCT p.id) as total_products,
                   COUNT(DISTINCT r.id) as total_rentals,
                   AVG(rev.rating) as average_rating
            FROM {$this->table} u
            LEFT JOIN products p ON u.id = p.user_id
            LEFT JOIN rentals r ON u.id = r.owner_id
            LEFT JOIN reviews rev ON u.id = rev.reviewed_id
            WHERE u.id = ?
            GROUP BY u.id
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
    public function updateProfile($userId, $data) {
        $allowedFields = ['name', 'phone', 'address', 'city', 'state', 'zip_code'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        if (!empty($updateData)) {
            return $this->update($userId, $updateData);
        }
        
        return false;
    }
}
?>
