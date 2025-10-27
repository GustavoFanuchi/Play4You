<?php
require_once __DIR__ . '/BaseRepository.php';

class ProductRepository extends BaseRepository {
    protected $table = 'products';
    
    /**
     * Busca produtos em destaque
     */
    public function getFeaturedProducts($limit = 8) {
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
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca produtos com filtros
     */
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
            $sql .= " AND (p.title LIKE :search OR p.description LIKE :search OR p.brand LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.daily_price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.daily_price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND u.city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }
        
        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition_status = :condition";
            $params[':condition'] = $filters['condition'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand = :brand";
            $params[':brand'] = $filters['brand'];
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
        
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit <= 0) $limit = 20;
        if ($offset < 0) $offset = 0;
        
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Conta resultados da busca
     */
    public function countSearchResults($filters = []) {
        $sql = "
            SELECT COUNT(DISTINCT p.id) as total
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.is_available = 1
        ";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE :search OR p.description LIKE :search OR p.brand LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.daily_price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.daily_price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND u.city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }
        
        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition_status = :condition";
            $params[':condition'] = $filters['condition'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand = :brand";
            $params[':brand'] = $filters['brand'];
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Busca detalhes completos do produto
     */
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
            WHERE p.id = :id
            GROUP BY p.id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Busca produtos similares
     */
    public function getSimilarProducts($productId, $categoryId, $limit = 4) {
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
            WHERE p.id != :product_id AND p.category_id = :category_id AND p.is_available = 1
            GROUP BY p.id
            ORDER BY p.views_count DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca todas as marcas
     */
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
    
    /**
     * Incrementa contador de visualizações
     */
    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Busca produtos do usuário
     */
    public function getUserProducts($userId) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name, c.icon as category_icon,
                   COUNT(r.id) as rental_count,
                   SUM(r.subtotal) as total_earnings
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN rentals r ON p.id = r.product_id AND r.owner_id = :user_id AND r.status = 'completed'
            WHERE p.user_id = :user_id
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Conta produtos do usuário
     */
    public function countUserProducts($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Atualiza disponibilidade do produto
     */
    public function updateAvailability($id, $isAvailable) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_available = :is_available WHERE id = :id");
        $stmt->bindParam(':is_available', $isAvailable, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Busca produtos por categoria
     */
    public function getProductsByCategory($categoryId, $limit = 20, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name, c.icon as category_icon,
                   u.name as owner_name, u.city as owner_city, u.state as owner_state,
                   AVG(r.rating) as average_rating,
                   COUNT(r.id) as review_count
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.category_id = :category_id AND p.is_available = 1
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>

