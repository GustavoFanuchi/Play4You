<?php
class Rental extends Model {
    protected $table = 'rentals';
    
    public function getRentalDetails($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                   p.title as product_title, p.images as product_images,
                   p.brand as product_brand, p.model as product_model,
                   owner.name as owner_name, owner.email as owner_email,
                   owner.phone as owner_phone, owner.city as owner_city,
                   renter.name as renter_name, renter.email as renter_email,
                   renter.phone as renter_phone
            FROM {$this->table} r
            LEFT JOIN products p ON r.product_id = p.id
            LEFT JOIN users owner ON r.owner_id = owner.id
            LEFT JOIN users renter ON r.renter_id = renter.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getUserRentals($userId, $limit = 10, $offset = 0) {
        // Sanitize limit and offset to prevent SQL injection
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit <= 0) $limit = 10;
        if ($offset < 0) $offset = 0;

        $stmt = $this->db->prepare("
            SELECT r.*, 
                   p.title as product_title, p.images as product_images,
                   p.brand as product_brand,
                   owner.name as owner_name, owner.city as owner_city
            FROM {$this->table} r
            LEFT JOIN products p ON r.product_id = p.id
            LEFT JOIN users owner ON r.owner_id = owner.id
            WHERE r.renter_id = ?
            ORDER BY r.created_at DESC
            LIMIT {$limit} OFFSET {$offset}
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getOwnerRentals($userId, $limit = 10, $offset = 0) {
        // Sanitize limit and offset to prevent SQL injection
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit <= 0) $limit = 10;
        if ($offset < 0) $offset = 0;

        $stmt = $this->db->prepare("
            SELECT r.*, 
                   p.title as product_title, p.images as product_images,
                   p.brand as product_brand,
                   renter.name as renter_name, renter.city as renter_city
            FROM {$this->table} r
            LEFT JOIN products p ON r.product_id = p.id
            LEFT JOIN users renter ON r.renter_id = renter.id
            WHERE r.owner_id = ?
            ORDER BY r.created_at DESC
            LIMIT {$limit} OFFSET {$offset}
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function countOwnerRentals($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE owner_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function getTotalEarnings($userId) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(subtotal), 0) as total 
            FROM {$this->table} 
            WHERE owner_id = ? AND status = 'completed'
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function countActiveRentals($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE owner_id = ? AND status IN ('confirmed', 'active')
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function countUserRentals($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE renter_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function isProductAvailable($productId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as conflicts
            FROM {$this->table}
            WHERE product_id = ? 
            AND status IN ('confirmed', 'active')
            AND (
                (start_date <= ? AND end_date >= ?) OR
                (start_date <= ? AND end_date >= ?) OR
                (start_date >= ? AND end_date <= ?)
            )
        ");
        $stmt->execute([
            $productId, 
            $startDate, $startDate,
            $endDate, $endDate,
            $startDate, $endDate
        ]);
        $result = $stmt->fetch();
        return $result['conflicts'] == 0;
    }
    
    public function getUpcomingRentals($userId, $limit = 5) {
        // Sanitize limit to prevent SQL injection
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 5;

        $stmt = $this->db->prepare("
            SELECT r.*, 
                   p.title as product_title, p.images as product_images
            FROM {$this->table} r
            LEFT JOIN products p ON r.product_id = p.id
            WHERE (r.renter_id = ? OR r.owner_id = ?)
            AND r.start_date >= CURDATE()
            AND r.status IN ('confirmed', 'pending')
            ORDER BY r.start_date ASC
            LIMIT {$limit}
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }
}
?>
