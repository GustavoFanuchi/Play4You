<?php
require_once 'models/Product.php';
require_once 'models/Category.php';

class HomeController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        // Get featured products
        $featuredProducts = $this->productModel->getFeaturedProducts(8);
        
        // Get categories
        $categories = $this->categoryModel->findAll();
        
        $this->view('home/index', [
            'title' => 'Play4You - Aluguel de Videogames e PCs Gamers',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories
        ]);
    }
}
?>
