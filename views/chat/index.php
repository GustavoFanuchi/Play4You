<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" style="height: calc(100vh - 8rem);">
            <div class="flex h-full">
                <!-- Chat List Sidebar -->
                <div class="w-1/3 border-r border-gray-200 flex flex-col">
                    <!-- Header -->
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Conversas</h2>
                    </div>
                    
                    <!-- Chat List -->
                    <div class="flex-1 overflow-y-auto">
                        <?php if (empty($chats)): ?>
                            <div class="p-6 text-center">
                                <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Nenhuma conversa ainda</p>
                                <p class="text-sm text-gray-400 mt-2">
                                    Inicie uma conversa através de um produto
                                </p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($chats as $chat): ?>
                                <div class="chat-item p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors <?= $activeChat && $activeChat['id'] == $chat['id'] ? 'bg-primary-50 border-primary-200' : '' ?>"
                                     onclick="loadChat(<?= $chat['id'] ?>, <?= $chat['other_user_id'] ?>)">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <img 
                                                src="/placeholder.svg?height=50&width=50" 
                                                alt="<?= htmlspecialchars($chat['other_user_name']) ?>"
                                                class="w-12 h-12 rounded-full object-cover"
                                            >
                                            <?php if ($chat['unread_count'] > 0): ?>
                                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                                    <?= $chat['unread_count'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="ml-3 flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    <?= htmlspecialchars($chat['other_user_name']) ?>
                                                </p>
                                                <?php if ($chat['last_message_at']): ?>
                                                    <p class="text-xs text-gray-500">
                                                        <?= date('H:i', strtotime($chat['last_message_at'])) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($chat['product_title']): ?>
                                                <p class="text-xs text-primary-600 mb-1">
                                                    <i class="fas fa-gamepad mr-1"></i>
                                                    <?= htmlspecialchars($chat['product_title']) ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <?php if ($chat['last_message']): ?>
                                                <p class="text-sm text-gray-500 truncate">
                                                    <?= htmlspecialchars($chat['last_message']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="flex-1 flex flex-col">
                    <?php if ($activeChat): ?>
                        <!-- Chat Header -->
                        <div class="p-6 border-b border-gray-200 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img 
                                        src="/placeholder.svg?height=40&width=40" 
                                        alt="<?= htmlspecialchars($otherUser['name']) ?>"
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <?= htmlspecialchars($otherUser['name']) ?>
                                        </h3>
                                        <?php if ($product): ?>
                                            <p class="text-sm text-primary-600">
                                                <i class="fas fa-gamepad mr-1"></i>
                                                <?= htmlspecialchars($product['title']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <?php if ($product): ?>
                                        <a 
                                            href="/product/<?= $product['id'] ?>"
                                            class="bg-primary-100 hover:bg-primary-200 text-primary-700 px-3 py-1 rounded-lg text-sm font-medium transition-colors"
                                        >
                                            Ver Produto
                                        </a>
                                    <?php endif; ?>
                                    <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
                            <?php foreach ($messages as $message): ?>
                                <div class="message-item flex <?= $message['sender_id'] == $_SESSION['user_id'] ? 'justify-end' : 'justify-start' ?>"
                                     data-message-id="<?= $message['id'] ?>">
                                    <div class="max-w-xs lg:max-w-md">
                                        <?php if ($message['sender_id'] != $_SESSION['user_id']): ?>
                                            <div class="flex items-end space-x-2">
                                                <img 
                                                    src="/placeholder.svg?height=32&width=32" 
                                                    alt="<?= htmlspecialchars($message['sender_name']) ?>"
                                                    class="w-8 h-8 rounded-full object-cover"
                                                >
                                                <div class="bg-gray-200 text-gray-900 p-3 rounded-lg rounded-bl-none">
                                                    <p class="text-sm"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <?= date('H:i', strtotime($message['created_at'])) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-primary-500 text-white p-3 rounded-lg rounded-br-none">
                                                <p class="text-sm"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                                <p class="text-xs text-primary-100 mt-1">
                                                    <?= date('H:i', strtotime($message['created_at'])) ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Message Input -->
                        <div class="p-6 border-t border-gray-200 bg-white">
                            <form id="message-form" class="flex space-x-4">
                                <input type="hidden" id="chat-id" value="<?= $activeChat['id'] ?>">
                                <div class="flex-1">
                                    <textarea 
                                        id="message-input"
                                        placeholder="Digite sua mensagem..."
                                        rows="1"
                                        class="block w-full resize-none border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        onkeydown="handleKeyDown(event)"
                                    ></textarea>
                                </div>
                                <button 
                                    type="submit"
                                    class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                                >
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Empty Chat State -->
                        <div class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Selecione uma conversa</h3>
                                <p class="text-gray-600">Escolha uma conversa da lista para começar a conversar</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let lastMessageId = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
let chatId = <?= $activeChat ? $activeChat['id'] : 'null' ?>;
let pollInterval;

// Auto-resize textarea
document.getElementById('message-input')?.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

// Handle form submission
document.getElementById('message-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    sendMessage();
});

// Handle Enter key
function handleKeyDown(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
}

// Send message
function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (!message || !chatId) {
        console.log('Mensagem vazia ou chat não selecionado');
        return;
    }
    
    console.log('Enviando mensagem:', message, 'Chat ID:', chatId);
    
    const formData = new FormData();
    formData.append('chat_id', chatId);
    formData.append('message', message);
    
    // Desabilitar botão temporariamente
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch('/chat/send', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
            
            if (data.message) {
                addMessageToChat(data.message, true);
                lastMessageId = Math.max(lastMessageId, parseInt(data.message.id));
                scrollToBottom();
            }
        } else {
            alert('Erro ao enviar mensagem: ' + (data.error || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao enviar mensagem: ' + error.message);
    })
    .finally(() => {
        // Reabilitar botão
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Add message to chat
function addMessageToChat(message, isOwn = false) {
    const container = document.getElementById('messages-container');
    
    // Check if message already exists
    if (document.querySelector(`[data-message-id="${message.id}"]`)) {
        console.log('Message already exists:', message.id);
        return;
    }
    
    console.log('Adding message:', message, 'isOwn:', isOwn);
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message-item flex ${isOwn ? 'justify-end' : 'justify-start'}`;
    messageDiv.setAttribute('data-message-id', message.id);
    
    const time = message.created_at ? new Date(message.created_at).toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit'
    }) : new Date().toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit'
    });
    
    const messageText = (message.message || '').replace(/\n/g, '<br>');
    
    if (isOwn) {
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="bg-primary-500 text-white p-3 rounded-lg rounded-br-none">
                    <p class="text-sm">${messageText}</p>
                    <p class="text-xs text-primary-100 mt-1">${time}</p>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="flex items-end space-x-2">
                    <img src="/placeholder.svg?height=32&width=32" alt="${message.sender_name || 'Usuário'}" class="w-8 h-8 rounded-full object-cover">
                    <div class="bg-gray-200 text-gray-900 p-3 rounded-lg rounded-bl-none">
                        <p class="text-sm">${messageText}</p>
                        <p class="text-xs text-gray-500 mt-1">${time}</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    container.appendChild(messageDiv);
}

// Scroll to bottom
function scrollToBottom() {
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

// Poll for new messages
function pollMessages() {
    if (!chatId) {
        console.log('No chatId for polling');
        return;
    }
    
    console.log('Polling for messages, chatId:', chatId, 'lastMessageId:', lastMessageId);
    
    fetch(`/chat/messages?chat_id=${chatId}&last_message_id=${lastMessageId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Polling response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Polling response data:', data);
        if (data.success && data.messages && data.messages.length > 0) {
            console.log('New messages found:', data.messages.length);
            data.messages.forEach(message => {
                const isOwn = message.sender_id == <?= $_SESSION['user_id'] ?>;
                console.log('Adding polled message:', message.id, 'isOwn:', isOwn);
                addMessageToChat(message, isOwn);
                lastMessageId = Math.max(lastMessageId, parseInt(message.id));
            });
            scrollToBottom();
        } else {
            console.log('No new messages or error:', data);
        }
    })
    .catch(error => {
        console.error('Error polling messages:', error);
    });
}

// Load chat
function loadChat(newChatId, otherUserId) {
    window.location.href = `/chat?user=${otherUserId}`;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    if (chatId) {
        // Start polling for new messages
        console.log('Starting polling with chatId:', chatId);
        pollInterval = setInterval(pollMessages, 1000);
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});
</script>
