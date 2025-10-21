<?php
require_once 'models/Product.php';
require_once 'models/Chat.php';
require_once 'models/Message.php';

class ApiController extends Controller {
    private $productModel;
    private $chatModel;
    private $messageModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->chatModel = new Chat();
        $this->messageModel = new Message();
    }
    
    public function products() {
        header('Content-Type: application/json');
        
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $limit = min(50, intval($_GET['limit'] ?? 20));
        $offset = intval($_GET['offset'] ?? 0);
        
        $filters = [
            'search' => $search,
            'category' => $category
        ];
        
        $products = $this->productModel->searchProducts($filters, $limit, $offset);
        $total = $this->productModel->countSearchResults($filters);
        
        echo json_encode([
            'products' => $products,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }
    
    public function chat() {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET':
                $this->getChatData();
                break;
            case 'POST':
                $this->sendChatMessage();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
        }
    }
    
    private function getChatData() {
        $chatId = $_GET['chat_id'] ?? null;
        $userId = $_SESSION['user_id'];
        
        if (!$chatId) {
            // Get user's chats
            $chats = $this->chatModel->getUserChats($userId);
            echo json_encode(['chats' => $chats]);
            return;
        }
        
        // Verify access
        $chat = $this->chatModel->find($chatId);
        if (!$chat || ($chat['user1_id'] != $userId && $chat['user2_id'] != $userId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado']);
            return;
        }
        
        $messages = $this->messageModel->getChatMessages($chatId);
        $this->messageModel->markAsRead($chatId, $userId);
        
        echo json_encode([
            'chat' => $chat,
            'messages' => $messages
        ]);
    }
    
    private function sendChatMessage() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $chatId = $input['chat_id'] ?? null;
        $message = trim($input['message'] ?? '');
        
        if (!$chatId || empty($message)) {
            http_response_code(400);
            echo json_encode(['error' => 'Chat ID e mensagem são obrigatórios']);
            return;
        }
        
        // Verify access
        $chat = $this->chatModel->find($chatId);
        if (!$chat || ($chat['user1_id'] != $_SESSION['user_id'] && $chat['user2_id'] != $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado']);
            return;
        }
        
        $messageData = [
            'chat_id' => $chatId,
            'sender_id' => $_SESSION['user_id'],
            'message' => $message
        ];
        
        $messageId = $this->messageModel->create($messageData);
        
        if ($messageId) {
            // Update chat
            $this->chatModel->update($chatId, [
                'last_message' => $message,
                'last_message_at' => date('Y-m-d H:i:s')
            ]);
            
            $newMessage = $this->messageModel->getMessageWithUser($messageId);
            echo json_encode([
                'success' => true,
                'message' => $newMessage
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao enviar mensagem']);
        }
    }
}
?>
