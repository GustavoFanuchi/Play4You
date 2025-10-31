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
- Autenticação: login e logout
- Cadastro de usuário
- Recuperação/Reset de senha (fluxo básico)
- Edição de perfil e avatar
- Catálogo com filtros, paginação e ordenação
- Busca por nome/categoria
- Página de produto com galeria de imagens
- Publicação de produtos para aluguel
- Edição e remoção de produtos
- Upload de múltiplas imagens por produto
- Preço, disponibilidade e regras de aluguel
- Processo de reserva/checkout de aluguel
- Confirmação e resumo da reserva
- Minhas locações (histórico e status)
- Chat em tempo real entre usuários
- Avaliações e comentários pós-aluguel
- Página institucional: sobre, termos, contato, FAQ
- Painel (dashboard) do usuário
- Logs básicos de aplicação
- Rotas e segurança (CSRF/sanitização básica)

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
### Instalação Automática (recomendado)
1. Execute o instalador via PHP CLI:
```
php scripts/install.php
```
2. Siga as instruções no terminal (host, banco, usuário e senha). Opcionalmente aplique dados de exemplo.
3. Inicie o servidor embutido do PHP:
```
php -S localhost:8000 -t .
```
4. Acesse `http://localhost:8000`.

### Instalação Manual
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
