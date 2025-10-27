# Repositories

Esta pasta contém as classes Repository que implementam o padrão Repository para gerenciar as comunicações com o banco de dados.

## Estrutura

- `BaseRepository.php` - Classe base com métodos comuns para todas as operações CRUD
- `UserRepository.php` - Repositório para operações de usuário
- `ProductRepository.php` - Repositório para operações de produto
- `RentalRepository.php` - Repositório para operações de aluguel
- `ChatRepository.php` - Repositório para operações de chat
- `MessageRepository.php` - Repositório para operações de mensagem
- `RepositoryManager.php` - Gerenciador centralizado dos repositories

## Como usar

### 1. Usando o RepositoryManager (Recomendado)

```php
<?php
require_once 'repositories/RepositoryManager.php';

// Obter repositório de usuário
$userRepo = RepositoryManager::getUserRepository();

// Buscar usuário por email
$user = $userRepo->findByEmail('usuario@exemplo.com');

// Criar novo usuário
$userId = $userRepo->create([
    'name' => 'João Silva',
    'email' => 'joao@exemplo.com',
    'password' => 'senha123'
]);
```

### 2. Usando diretamente

```php
<?php
require_once 'repositories/UserRepository.php';

$userRepo = new UserRepository();
$user = $userRepo->findById(1);
```

## Métodos disponíveis

### BaseRepository (métodos comuns)

- `findById($id)` - Busca por ID
- `findAll($limit, $offset)` - Busca todos os registros
- `findBy($conditions, $limit, $offset)` - Busca por condições
- `findOneBy($conditions)` - Busca um registro por condições
- `create($data)` - Cria novo registro
- `update($id, $data)` - Atualiza registro
- `delete($id)` - Deleta registro
- `count($conditions)` - Conta registros
- `beginTransaction()` - Inicia transação
- `commit()` - Confirma transação
- `rollback()` - Desfaz transação

### UserRepository

- `findByEmail($email)` - Busca por email
- `findByEmailAndPassword($email, $password)` - Login
- `updateLastLogin($userId)` - Atualiza último login
- `getProfile($userId)` - Perfil completo com estatísticas
- `updateProfile($userId, $data)` - Atualiza perfil
- `updatePassword($userId, $newPassword)` - Atualiza senha
- `emailExists($email, $excludeUserId)` - Verifica se email existe
- `searchByName($name, $limit)` - Busca por nome
- `findActiveUsers($limit, $offset)` - Usuários ativos
- `updateStatus($userId, $status)` - Atualiza status

### ProductRepository

- `getFeaturedProducts($limit)` - Produtos em destaque
- `searchProducts($filters, $limit, $offset)` - Busca com filtros
- `countSearchResults($filters)` - Conta resultados da busca
- `getProductDetails($id)` - Detalhes completos
- `getSimilarProducts($productId, $categoryId, $limit)` - Produtos similares
- `getAllBrands()` - Todas as marcas
- `incrementViews($id)` - Incrementa visualizações
- `getUserProducts($userId)` - Produtos do usuário
- `countUserProducts($userId)` - Conta produtos do usuário
- `updateAvailability($id, $isAvailable)` - Atualiza disponibilidade
- `getProductsByCategory($categoryId, $limit, $offset)` - Por categoria

### RentalRepository

- `getRentalDetails($id)` - Detalhes completos do aluguel
- `getUserRentals($userId, $limit, $offset)` - Aluguéis do usuário
- `getOwnerRentals($userId, $limit, $offset)` - Aluguéis do proprietário
- `countOwnerRentals($userId)` - Conta aluguéis do proprietário
- `getTotalEarnings($userId)` - Total de ganhos
- `countActiveRentals($userId)` - Aluguéis ativos
- `countUserRentals($userId)` - Conta aluguéis do usuário
- `isProductAvailable($productId, $startDate, $endDate)` - Verifica disponibilidade
- `getUpcomingRentals($userId, $limit)` - Próximos aluguéis
- `updateStatus($id, $status)` - Atualiza status
- `getRentalsByStatus($status, $userId, $limit, $offset)` - Por status
- `getExpiringRentals($days)` - Aluguéis que expiram
- `getRentalStats($userId)` - Estatísticas

### ChatRepository

- `findOrCreateChat($user1Id, $user2Id, $productId)` - Busca ou cria chat
- `getUserChats($userId)` - Chats do usuário
- `getChatWithUsers($chatId)` - Chat com informações dos usuários
- `updateLastMessage($chatId, $messageId)` - Atualiza última mensagem
- `getChatsWithUnreadMessages($userId)` - Chats com mensagens não lidas
- `userHasAccess($chatId, $userId)` - Verifica acesso ao chat
- `getChatsByProduct($productId)` - Chats por produto
- `deleteChat($chatId)` - Deleta chat

### MessageRepository

- `getChatMessages($chatId, $limit)` - Mensagens do chat
- `getNewMessages($chatId, $lastMessageId)` - Novas mensagens
- `getMessageWithUser($messageId)` - Mensagem com remetente
- `markAsRead($chatId, $userId)` - Marca como lida
- `getUnreadCount($userId)` - Conta mensagens não lidas
- `getLastMessage($chatId)` - Última mensagem
- `getUnreadMessages($chatId, $userId)` - Mensagens não lidas
- `getMessagesByDateRange($chatId, $startDate, $endDate)` - Por período
- `getTextMessages($chatId, $limit)` - Apenas texto
- `getMessagesByType($chatId, $messageType, $limit)` - Por tipo
- `deleteMessage($messageId, $userId)` - Deleta mensagem
- `getMessageStats($userId)` - Estatísticas

## Vantagens do padrão Repository

1. **Separação de responsabilidades** - Lógica de acesso aos dados separada da lógica de negócio
2. **Facilita testes** - Pode ser facilmente mockado para testes unitários
3. **Reutilização** - Métodos podem ser reutilizados em diferentes controllers
4. **Manutenibilidade** - Mudanças na estrutura do banco afetam apenas os repositories
5. **Consistência** - Padrão uniforme para todas as operações de banco de dados
6. **Segurança** - Uso de prepared statements em todos os métodos
7. **Transações** - Suporte nativo a transações de banco de dados

## Migração dos Models

Os models existentes podem ser gradualmente migrados para usar os repositories. Os controllers devem ser atualizados para usar os repositories em vez de chamar diretamente os models.

