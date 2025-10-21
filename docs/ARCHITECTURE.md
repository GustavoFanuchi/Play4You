# Arquitetura e Estrutura de Diretórios

## Visão Geral
Arquitetura MVC simples em PHP:
- controllers/ tratam as rotas, recebem input e orquestram modelos e views
- models/ encapsulam o acesso ao banco e lógica de dados
- views/ renderizam HTML com PHP

## Principais Componentes
- `core/Controller.php`: base para controllers (render, helpers)
- `core/Model.php`: CRUD genérico (find, findAll, create, update, delete)
- `core/Router.php`: roteador simples baseado em `config/routes.php`
- `config/routes.php`: path → controller/ação
- `views/layouts/main.php`: layout base (header, footer, conteúdo)

## Estrutura
```
config/
controllers/
core/
models/
public/
scripts/
views/
```

## Fluxos Importantes
- Catálogo → `ProductController::catalog` → `Product::searchProducts` → `views/products/catalog.php`
- Produto → `ProductController::show` → `Product::getProductDetails` → `views/products/show.php`
- Dashboard → `DashboardController::index|addProduct|editProduct` → `views/dashboard/*`
- Chat → `ChatController::index|send|getMessages` → `views/chat/index.php`

## Dados e Relacionamentos (alto nível)
- `users` (proprietário/locatário)
- `categories` (categoria do produto)
- `products` (referencia `users` e `categories`, contém `images` JSON)
- `rentals` (alugueis, referencia `products` e usuários)
- `reviews` (avaliações)
- `chats`/`messages` (comunicação)
