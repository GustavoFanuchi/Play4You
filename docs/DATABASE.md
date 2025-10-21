# Banco de Dados

## Engine e Charset
- MySQL 8+, InnoDB, `utf8mb4_unicode_ci`

## Tabelas Principais (resumo)
- `users`: perfil do usuário (name, email, phone, city/state, profile_image, etc.)
- `categories`: catálogo de categorias
- `products`: item anunciável (user_id, category_id, title, description, prices, location, flags, `images` JSON, etc.)
- `rentals`: reservas (datas, valores, status)
- `reviews`: avaliações e notas
- `chats`/`messages`: comunicação

## Criação do Schema
Use `scripts/database_setup.sql` para criar todas as tabelas e FKs.

## Coluna `products.images`
- Tipo: `JSON`
- Conteúdo: array de strings com caminhos relativos (ex.: `public/uploads/products/arquivo.jpg`)
- Motivação: suportar múltiplas imagens por produto mantendo simplicidade

## Migrações
- `scripts/migrate_chat_schema.sql`: atualizações de chat/mensagens

## Seeds
- `scripts/insert_sample_data.sql`: dados de exemplo para catálogo e testes
