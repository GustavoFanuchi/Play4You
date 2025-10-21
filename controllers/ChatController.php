<?php
require_once 'models/Chat.php';
require_once 'models/Message.php';
require_once 'models/User.php';
require_once 'models/Product.php';

class ChatController extends Controller {
    private $chatModel;
    private $messageModel;
    private $userModel;
    private $productModel;
    
    public function __construct() {
        $this->chatModel = new Chat();
        $this->messageModel = new Message();
        $this->userModel = new User();
        $this->productModel = new Product();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $otherUserId = $_GET['user'] ?? null;
        $productId = $_GET['product'] ?? null;
        
        // Get or create chat
        $activeChat = null;
        if ($otherUserId) {
            $activeChat = $this->chatModel->findOrCreateChat($userId, $otherUserId, $productId);
        }
        
        // Get user's chats
        $chats = $this->chatModel->getUserChats($userId);
        
        // Get messages for active chat
        $messages = [];
        if ($activeChat) {
            $messages = $this->messageModel->getChatMessages($activeChat['id']);
            // Mark messages as read
            $this->messageModel->markAsRead($activeChat['id'], $userId);
        }
        
        // Get other user info if specified
        $otherUser = null;
        if ($otherUserId) {
            $otherUser = $this->userModel->find($otherUserId);
        }
        
        // Get product info if specified
        $product = null;
        if ($productId) {
            $product = $this->productModel->find($productId);
        }
        
        $this->view('chat/index', [
            'title' => 'Chat - Play4You',
            'chats' => $chats,
            'activeChat' => $activeChat,
            'messages' => $messages,
            'otherUser' => $otherUser,
            'product' => $product
        ]);
    }
    
    public function send() {
        $this->requireAuth();
        
        // Log para debug
        error_log('Chat send method called');
        error_log('POST data: ' . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Método não permitido']);
            return;
        }
        
        $chatId = isset($_POST['chat_id']) ? intval($_POST['chat_id']) : null;
        $message = trim($_POST['message'] ?? '');
        
        error_log('Chat ID: ' . $chatId . ', Message: ' . $message);
        
        if (!$chatId || empty($message)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Chat ID e mensagem são obrigatórios']);
            return;
        }
        
        // Verify user is part of this chat
        $chat = $this->chatModel->find($chatId);
        if (!$chat || ($chat['user1_id'] != $_SESSION['user_id'] && $chat['user2_id'] != $_SESSION['user_id'])) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Acesso negado']);
            return;
        }
        
        try {
            $messageData = [
                'chat_id' => $chatId,
                'sender_id' => $_SESSION['user_id'],
                'message' => $message
            ];
            
            $messageId = $this->messageModel->create($messageData);
            
            if ($messageId) {
                // Update chat last message
                $this->chatModel->update($chatId, [
                    'last_message' => $message,
                    'last_message_at' => date('Y-m-d H:i:s')
                ]);
                
                // Get the created message with user info
                $newMessage = $this->messageModel->getMessageWithUser($messageId);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => $newMessage
                ]);
                return;
            } else {
                throw new Exception('Falha ao criar mensagem');
            }
        } catch (Exception $e) {
            error_log('Chat send error: ' . $e->getMessage());
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Erro ao enviar mensagem: ' . $e->getMessage()]);
        }
    }
    
    public function getMessages() {
        $this->requireAuth();
        header('Content-Type: application/json');

        // Parse and validate params
        $chatId = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : null;
        $lastMessageId = isset($_GET['last_message_id']) ? intval($_GET['last_message_id']) : 0;
        
        if (!$chatId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Chat ID é obrigatório', 'messages' => []]);
            return;
        }
        
        // Verify user is part of this chat
        $chat = $this->chatModel->find($chatId);
        if (!$chat || ($chat['user1_id'] != $_SESSION['user_id'] && $chat['user2_id'] != $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Acesso negado', 'messages' => []]);
            return;
        }
        
        try {
            $messages = $this->messageModel->getNewMessages($chatId, $lastMessageId);
            // Mark messages as read
            $this->messageModel->markAsRead($chatId, $_SESSION['user_id']);
            echo json_encode(['success' => true, 'messages' => $messages]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar mensagens', 'messages' => []]);
        }
    }
}
?>
