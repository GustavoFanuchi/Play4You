<?php
require_once __DIR__ . '/BaseRepository.php';

class CategoryRepository extends BaseRepository {
    protected $table = 'categories';
    
    /**
     * Busca categorias com contagem de produtos
     */
    public function getWithProductCount() {
        $stmt = $this->db->prepare("
            SELECT c.*, COUNT(p.id) as product_count
            FROM {$this->table} c
            LEFT JOIN products p ON c.id = p.category_id AND p.is_available = 1
            GROUP BY c.id
            ORDER BY c.name
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca categorias ativas
     */
    public function getActiveCategories() {
        $stmt = $this->db->prepare("
            SELECT c.*, COUNT(p.id) as product_count
            FROM {$this->table} c
            LEFT JOIN products p ON c.id = p.category_id AND p.is_available = 1
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY c.name
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca categoria por slug
     */
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = :slug");
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Busca categorias populares (com mais produtos)
     */
    public function getPopularCategories($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT c.*, COUNT(p.id) as product_count
            FROM {$this->table} c
            LEFT JOIN products p ON c.id = p.category_id AND p.is_available = 1
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY product_count DESC, c.name
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Atualiza status da categoria
     */
    public function updateStatus($id, $isActive) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = :is_active WHERE id = :id");
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Busca categorias com filtros
     */
    public function searchCategories($search = '', $limit = 20, $offset = 0) {
        $sql = "
            SELECT c.*, COUNT(p.id) as product_count
            FROM {$this->table} c
            LEFT JOIN products p ON c.id = p.category_id AND p.is_available = 1
            WHERE 1=1
        ";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (c.name LIKE :search OR c.description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        $sql .= " GROUP BY c.id ORDER BY c.name LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>

