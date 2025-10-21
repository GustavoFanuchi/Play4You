# Rotas e Fluxos

As rotas são definidas em `config/routes.php`.

## Páginas Principais (GET)
- `/` | `/home` → Home
- `/catalog` → Catálogo (filtros por querystring: `search`, `category`, `min_price`, `max_price`, `city`, `condition`, `brand`, `sort`, `page`)
- `/product/{id}` → Página de produto
- `/dashboard` → Painel do usuário
- `/about`, `/contact`, `/terms`

## Ações (POST)
- `/dashboard/add-product` → cria produto (form multipart com `images[]`)
- `/dashboard/edit-product/{id}` → atualiza produto (form multipart com `images[]`)
- `/rental` → cria reserva
- `/chat/send` → envia mensagem (chat)
- `/contact/send` → envia contato

## Estrutura de Dados
- `products.images`: JSON array de strings com caminhos relativos (ex.: `public/uploads/products/img.jpg`).

## Fluxos
- Criação de Produto: formulário → validações → upload(s) → salva `images` (JSON) → redireciona `/dashboard`
- Exibição de Produto: carrega detalhes + imagens; primeira imagem vira principal; thumbnails no grid
- Catálogo: busca paginada com filtros; exibe primeira imagem de cada produto
- Reserva: captura datas e método de entrega → cria `rental`
- Chat: cria/usa chat entre proprietário e interessado → mensagens persistidas
