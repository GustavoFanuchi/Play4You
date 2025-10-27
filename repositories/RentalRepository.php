<?php
require_once __DIR__ . '/BaseRepository.php';

class RentalRepository extends BaseRepository {
    protected $table = 'rentals';
    
    /**
     * Busca detalhes completos do aluguel
     */
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
            WHERE r.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Busca aluguéis do usuário (como locatário)
     */
    public function getUserRentals($userId, $limit = 10, $offset = 0) {
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
            WHERE r.renter_id = :user_id
            ORDER BY r.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca aluguéis do proprietário
     */
    public function getOwnerRentals($userId, $limit = 10, $offset = 0) {
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
            WHERE r.owner_id = :user_id
            ORDER BY r.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Conta aluguéis do proprietário
     */
    public function countOwnerRentals($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE owner_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Calcula total de ganhos do proprietário
     */
    public function getTotalEarnings($userId) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(subtotal), 0) as total 
            FROM {$this->table} 
            WHERE owner_id = :user_id AND status = 'completed'
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Conta aluguéis ativos do proprietário
     */
    public function countActiveRentals($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE owner_id = :user_id AND status IN ('confirmed', 'active')
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Conta aluguéis do usuário
     */
    public function countUserRentals($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE renter_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Verifica se produto está disponível no período
     */
    public function isProductAvailable($productId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as conflicts
            FROM {$this->table}
            WHERE product_id = :product_id 
            AND status IN ('confirmed', 'active')
            AND (
                (start_date <= :start_date AND end_date >= :start_date) OR
                (start_date <= :end_date AND end_date >= :end_date) OR
                (start_date >= :start_date AND end_date <= :end_date)
            )
        ");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['conflicts'] == 0;
    }
    
    /**
     * Busca próximos aluguéis
     */
    public function getUpcomingRentals($userId, $limit = 5) {
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 5;

        $stmt = $this->db->prepare("
            SELECT r.*, 
                   p.title as product_title, p.images as product_images
            FROM {$this->table} r
            LEFT JOIN products p ON r.product_id = p.id
            WHERE (r.renter_id = :user_id OR r.owner_id = :user_id)
            AND r.start_date >= CURDATE()
            AND r.status IN ('confirmed', 'pending')
            ORDER BY r.start_date ASC
            LIMIT :limit
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Atualiza status do aluguel
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Busca aluguéis por status
     */
    public function getRentalsByStatus($status, $userId = null, $limit = 20, $offset = 0) {
        $sql = "
            SELECT r.*, 
                   p.title as product_title, p.images as product_images,
                   p.brand as product_brand,
                   owner.name as owner_name, renter.name as renter_name
            FROM {$this->table} r
            LEFT JOIN products p ON r.product_id = p.id
            LEFT JOIN users owner ON r.owner_id = owner.id
            LEFT JOIN users renter ON r.renter_id = renter.id
            WHERE r.status = :status
        ";
        
        $params = [':status' => $status];
        
        if ($userId) {
            $sql .= " AND (r.owner_id = :user_id OR r.renter_id = :user_id)";
            $params[':user_id'] = $userId;
        }
        
        $sql .= " ORDER BY r.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        if ($userId) {
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Busca aluguéis que expiram em breve
     */
    public function getExpiringRentals($days = 3) {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                   p.title as product_title,
                   owner.name as owner_name, renter.name as renter_name
            FROM {$this->table} r
            LEFT JOIN products p ON r.product_id = p.id
            LEFT JOIN users owner ON r.owner_id = owner.id
            LEFT JOIN users renter ON r.renter_id = renter.id
            WHERE r.status = 'active'
            AND r.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
            ORDER BY r.end_date ASC
        ");
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Calcula estatísticas de aluguéis
     */
    public function getRentalStats($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_rentals,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_rentals,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_rentals,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_rentals,
                COALESCE(SUM(CASE WHEN status = 'completed' THEN subtotal ELSE 0 END), 0) as total_earnings
            FROM {$this->table}
            WHERE owner_id = :user_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>

