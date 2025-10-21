# Deploy e Configurações de Ambiente

## Opções de Deploy
- Apache/Nginx + PHP-FPM (produção)
- Servidor embutido do PHP (dev)

## Requisitos em Produção
- PHP 8+, extensões PDO/MySQL
- MySQL 8+
- Pasta `public/uploads/products` com permissão de escrita pelo usuário do servidor

## Configurações
- `config/database.php`: credenciais do banco (produzir arquivo seguro)
- `config/constants.php`: `SITE_URL`, cache, limites, logs

## Servindo Assets
- Garanta que `/public` seja servida como raiz de estáticos (ou ajuste caminhos)

## Segurança Básica
- Desativar `DEBUG_MODE` em produção
- Proteger `logs/` e arquivos de config
- Limitar tamanho de upload no servidor web

## Backup/Restore
- Dump do MySQL (mysqldump)
- Backup incremental da pasta `public/uploads/products`
