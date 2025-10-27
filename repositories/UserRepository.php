<?php
require_once __DIR__ . '/BaseRepository.php';

class UserRepository extends BaseRepository {
    protected $table = 'users';
    
    /**
     * Busca usuário por email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Busca usuário por email e senha (para login)
     */
    public function findByEmailAndPassword($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Atualiza último login do usuário
     */
    public function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Busca perfil completo do usuário com estatísticas
     */
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
            WHERE u.id = :id
            GROUP BY u.id
        ");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Atualiza perfil do usuário (apenas campos permitidos)
     */
    public function updateProfile($userId, $data) {
        $allowedFields = ['name', 'phone', 'address', 'city', 'state', 'zip_code'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        if (!empty($updateData)) {
            return $this->update($userId, $updateData);
        }
        
        return false;
    }
    
    /**
     * Atualiza senha do usuário
     */
    public function updatePassword($userId, $newPassword) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Verifica se email já existe
     */
    public function emailExists($email, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $params = [':email' => $email];
        
        if ($excludeUserId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeUserId;
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Busca usuários por nome (para busca)
     */
    public function searchByName($name, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT id, name, email, created_at 
            FROM {$this->table} 
            WHERE name LIKE :name 
            ORDER BY name 
            LIMIT :limit
        ");
        $searchTerm = "%{$name}%";
        $stmt->bindParam(':name', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca usuários ativos
     */
    public function findActiveUsers($limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active'";
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Ativa/desativa usuário
     */
    public function updateStatus($userId, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>

