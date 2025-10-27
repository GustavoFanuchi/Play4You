<?php
require_once __DIR__ . '/../repositories/RepositoryManager.php';

class Category extends Model {
    protected $table = 'categories';
    private $repository;
    
    public function __construct() {
        parent::__construct();
        $this->repository = RepositoryManager::getCategoryRepository();
    }
    
    public function getWithProductCount() {
        return $this->repository->getWithProductCount();
    }
    
    // Métodos adicionais usando o repository
    public function getActiveCategories() {
        return $this->repository->getActiveCategories();
    }
    
    public function findBySlug($slug) {
        return $this->repository->findBySlug($slug);
    }
    
    public function getPopularCategories($limit = 10) {
        return $this->repository->getPopularCategories($limit);
    }
    
    public function updateStatus($id, $isActive) {
        return $this->repository->updateStatus($id, $isActive);
    }
    
    public function searchCategories($search = '', $limit = 20, $offset = 0) {
        return $this->repository->searchCategories($search, $limit, $offset);
    }
    
    // Métodos CRUD básicos usando o repository
    public function find($id) {
        return $this->repository->findById($id);
    }
    
    public function create($data) {
        return $this->repository->create($data);
    }
    
    public function update($id, $data) {
        return $this->repository->update($id, $data);
    }
    
    public function delete($id) {
        return $this->repository->delete($id);
    }
    
    public function findAll($conditions = [], $limit = null, $offset = null) {
        return $this->repository->findAll($limit, $offset);
    }
}
?>