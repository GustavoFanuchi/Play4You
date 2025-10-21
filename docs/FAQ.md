# FAQ & Troubleshooting

## As imagens não aparecem no catálogo
- Verifique se `products.images` contém JSON válido
- Confirme que os arquivos existem em `public/uploads/products`
- Veja no DevTools se a URL retorna 200

## O upload falha
- Verifique limite de tamanho (5MB)
- Verifique permissões da pasta `public/uploads/products`
- Cheque logs em `logs/error.log`

## Página quebra com erro de banco
- Confirme credenciais em `config/database.php`
- Rode `scripts/database_setup.sql`

## Como redefinir a base local?
- Drop no schema e rode novamente os scripts do diretório `scripts/`
