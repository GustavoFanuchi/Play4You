# Setup e Desenvolvimento Local

## Requisitos
- PHP 8+
- MySQL 8+
- Navegador moderno

## Configuração do Banco de Dados
1. Crie o schema e tabelas:
```
mysql -u root -p < scripts/database_setup.sql
```
2. (Opcional) Popular com dados de exemplo:
```
mysql -u root -p play4you < scripts/insert_sample_data.sql
```
3. Edite `config/database.php` com host, porta, usuário, senha e nome do banco.

## Variáveis e Constantes
- Ajuste `config/constants.php` (SITE_URL, limites, cache, logs, etc.).

## Rodando a Aplicação em Dev
Na raiz do projeto:
```
php -S localhost:8000 -t .
```
Abra `http://localhost:8000` no navegador.

## Fluxo de Login/Registro
- Crie um usuário em `/register` e autentique em `/login`.

## Logs e Debug
- Ative `DEBUG_MODE` e `LOG_ERRORS` em `config/constants.php`.
- Verifique `logs/error.log` (crie a pasta `logs/` se necessário).

## Estrutura de Pastas (resumo)
```
config/  controllers/  core/  models/  public/  scripts/  views/
```

## Dicas (Windows/PowerShell)
- Execute os comandos no diretório do projeto.
- Permissões de `public/uploads/products` devem permitir leitura pelo servidor web.
