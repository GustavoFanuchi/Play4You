<?php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        
        ob_start();
        include "views/{$view}.php";
        $content = ob_get_clean();
        
        include 'views/layouts/main.php';
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            // Check for AJAX request
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            // Also check if it's a JSON request or chat endpoint
            $isJsonRequest = strpos($_SERVER['REQUEST_URI'], '/chat/') !== false ||
                           strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false;
            
            if ($isAjax || $isJsonRequest) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
                exit;
            }
            
            $this->redirect('/login');
        }
    }
}
?>
