<?php
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/User.php';
require_once 'models/Review.php';

class ProductController extends Controller {
    private $productModel;
    private $categoryModel;
    private $userModel;
    private $reviewModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->userModel = new User();
        $this->reviewModel = new Review();
    }
    
    public function catalog() {
        // Get filters from query parameters
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'city' => $_GET['city'] ?? '',
            'condition' => $_GET['condition'] ?? '',
            'brand' => $_GET['brand'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];
        
        // Pagination
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        // Get products
        $products = $this->productModel->searchProducts($filters, $limit, $offset);
        $totalProducts = $this->productModel->countSearchResults($filters);
        $totalPages = ceil($totalProducts / $limit);
        
        // Get categories for filter
        $categories = $this->categoryModel->getWithProductCount();
        
        // Get brands for filter
        $brands = $this->productModel->getAllBrands();
        
        $this->view('products/catalog', [
            'title' => 'Catálogo - Play4You',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $filters,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ]);
    }
    
    public function show($id) {
        $product = $this->productModel->getProductDetails($id);
        
        if (!$product) {
            http_response_code(404);
            include 'views/errors/404.php';
            return;
        }
        
        // Increment view count
        $this->productModel->incrementViews($id);
        
        // Get owner details
        $owner = $this->userModel->getProfile($product['user_id']);
        
        // Get reviews
        $reviews = $this->reviewModel->getProductReviews($id);
        $reviewStats = $this->reviewModel->getProductReviewStats($id);
        
        // Get similar products
        $similarProducts = $this->productModel->getSimilarProducts($id, $product['category_id'], 4);
        
        $this->view('products/show', [
            'title' => htmlspecialchars($product['title']) . ' - Play4You',
            'product' => $product,
            'owner' => $owner,
            'reviews' => $reviews,
            'reviewStats' => $reviewStats,
            'similarProducts' => $similarProducts
        ]);
    }
}
?>
