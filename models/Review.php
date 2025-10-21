<?php
class Review extends Model {
    protected $table = 'reviews';
    
    public function getProductReviews($productId, $limit = 10) {
        // Sanitize limit to prevent SQL injection
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 10;

        $stmt = $this->db->prepare("
            SELECT r.*, u.name as reviewer_name, u.profile_image as reviewer_image
            FROM {$this->table} r
            LEFT JOIN users u ON r.reviewer_id = u.id
            WHERE r.product_id = ?
            ORDER BY r.created_at DESC
            LIMIT {$limit}
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }
    
    public function getProductReviewStats($productId) {
        $stmt = $this->db->prepare("
            SELECT 
                AVG(rating) as average_rating,
                COUNT(*) as total_reviews,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
            FROM {$this->table}
            WHERE product_id = ?
        ");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }
}
?>
