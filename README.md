# Play4You

Marketplace de aluguel de videogames, PCs gamers e acessórios.

## Sumário
- Visão Geral
- Tecnologias
- Funcionalidades
- Estrutura do Projeto
- Comece Rápido
- Configuração
- Scripts de Banco de Dados
- Rotas Principais
- Upload de Imagens

## Visão Geral
Aplicação PHP (MVC simples) com páginas server-rendered, MySQL e assets estáticos em `public/`.

## Tecnologias
- PHP 8+
- MySQL 8+
- TailwindCSS (CDN) e Font Awesome

## Funcionalidades
- Catálogo com filtros, paginação e ordenação
- Página de produto com galeria e reservas
- Dashboard do usuário para cadastrar/editar produtos
- Upload de múltiplas imagens por produto
- Chat entre usuários

## Estrutura do Projeto
```
config/           # constantes, rotas, banco
controllers/      # controladores MVC
core/             # base Model/Controller/DB
models/           # modelos de dados
public/           # assets estáticos (inclui uploads)
scripts/          # SQL de criação/migrações e seed
views/            # templates PHP (layouts + páginas)
```

## Comece Rápido
1. Banco de dados
```
mysql -u root -p < scripts/database_setup.sql
# opcional
mysql -u root -p play4you < scripts/insert_sample_data.sql
```
2. Configure `config/database.php`.
3. Rode o servidor dev:
```
php -S localhost:8000 -t .
```
Acesse `http://localhost:8000`.

## Configuração
- `config/constants.php`: SITE_URL, limites, cache, logs.
- `config/database.php`: credenciais MySQL.
- `config/routes.php`: path → controller/ação.

## Scripts de Banco de Dados
- `scripts/database_setup.sql`: cria banco e tabelas.
- `scripts/insert_sample_data.sql`: dados de exemplo.

## Rotas Principais
- `/catalog` → Catálogo
- `/product/{id}` → Produto
- `/dashboard` → Painel
- `/dashboard/add-product` → Cadastrar
- `/dashboard/edit-product/{id}` → Editar
- `/chat`, `/about`, `/contact`, `/terms`

## Upload de Imagens
- Campo: `products.images` (JSON array de caminhos relativos ex.: `public/uploads/products/arquivo.jpg`)
- Salvamento em `public/uploads/products`
- Tipos aceitos: jpg, png, webp (MIME); até 5MB
- Exibição: usa a primeira imagem; fallback para placeholder

Para detalhes completos, consulte a pasta `docs/` (a ser adicionada).
