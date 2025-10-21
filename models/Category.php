<?php
class Category extends Model {
    protected $table = 'categories';
    
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
}
?>
