<?php
class Product extends Model {
    protected $table = 'products';
    
    public function getFeaturedProducts($limit = 8) {
        // Sanitize limit to prevent SQL injection
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 8;
        
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name, c.icon as category_icon,
                   u.name as owner_name, u.city as owner_city, u.state as owner_state,
                   AVG(r.rating) as average_rating,
                   COUNT(r.id) as review_count
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.is_available = 1
            GROUP BY p.id
            ORDER BY p.views_count DESC, p.created_at DESC
            LIMIT {$limit}
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function searchProducts($filters = [], $limit = 20, $offset = 0) {
        $sql = "
            SELECT p.*, c.name as category_name, c.icon as category_icon,
                   u.name as owner_name, u.city as owner_city, u.state as owner_state,
                   AVG(r.rating) as average_rating,
                   COUNT(r.id) as review_count
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.is_available = 1
        ";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.daily_price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.daily_price <= ?";
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND u.city LIKE ?";
            $params[] = '%' . $filters['city'] . '%';
        }
        
        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition_status = ?";
            $params[] = $filters['condition'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand = ?";
            $params[] = $filters['brand'];
        }
        
        $sql .= " GROUP BY p.id";
        
        // Order by
        $orderBy = $filters['sort'] ?? 'newest';
        switch ($orderBy) {
            case 'price_low':
                $sql .= " ORDER BY p.daily_price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY p.daily_price DESC";
                break;
            case 'rating':
                $sql .= " ORDER BY average_rating DESC";
                break;
            case 'popular':
                $sql .= " ORDER BY p.views_count DESC";
                break;
            default:
                $sql .= " ORDER BY p.created_at DESC";
        }
        
        // Sanitize limit and offset to prevent SQL injection
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit <= 0) $limit = 20;
        if ($offset < 0) $offset = 0;
        
        $sql .= " LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function countSearchResults($filters = []) {
        $sql = "
            SELECT COUNT(DISTINCT p.id) as total
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.is_available = 1
        ";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.daily_price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.daily_price <= ?";
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND u.city LIKE ?";
            $params[] = '%' . $filters['city'] . '%';
        }
        
        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition_status = ?";
            $params[] = $filters['condition'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand = ?";
            $params[] = $filters['brand'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function getProductDetails($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name, c.icon as category_icon,
                   u.name as owner_name, u.city as owner_city, u.state as owner_state,
                   u.phone as owner_phone, u.profile_image as owner_image,
                   AVG(r.rating) as average_rating,
                   COUNT(r.id) as review_count
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.id = ?
            GROUP BY p.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getSimilarProducts($productId, $categoryId, $limit = 4) {
        // Sanitize limit to prevent SQL injection
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 4;
        
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name,
                   u.name as owner_name, u.city as owner_city, u.state as owner_state,
                   AVG(r.rating) as average_rating,
                   COUNT(r.id) as review_count
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.id != ? AND p.category_id = ? AND p.is_available = 1
            GROUP BY p.id
            ORDER BY p.views_count DESC
            LIMIT {$limit}
        ");
        $stmt->execute([$productId, $categoryId]);
        return $stmt->fetchAll();
    }
    
    public function getAllBrands() {
        $stmt = $this->db->prepare("
            SELECT DISTINCT brand 
            FROM {$this->table} 
            WHERE brand IS NOT NULL AND brand != '' 
            ORDER BY brand
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getUserProducts($userId) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name, c.icon as category_icon,
                   COUNT(r.id) as rental_count,
                   SUM(r.subtotal) as total_earnings
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN rentals r ON p.id = r.product_id AND r.owner_id = ? AND r.status = 'completed'
            WHERE p.user_id = ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }
    
    public function countUserProducts($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
?>
