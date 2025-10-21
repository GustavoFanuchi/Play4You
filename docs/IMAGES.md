# Upload e Exibição de Imagens

## Upload
- Telas: `dashboard/add-product` e `dashboard/edit-product`
- Campo: `images[]` (multiple)
- Tipos aceitos (MIME): `image/jpeg`, `image/png`, `image/webp`
- Tamanho máximo por arquivo: 5MB
- Destino: `public/uploads/products`
- Extensão definida pelo MIME real (`.jpg`, `.png`, `.webp`)

## Persistência
- `products.images` recebe JSON array de caminhos relativos (ex.: `public/uploads/products/abc.jpg`)

## Exibição
- Produto (`views/products/show.php`): usa a primeira imagem como principal e demais como thumbnails; normaliza caminho com `/` no início.
- Catálogo (`views/products/catalog.php`): usa a primeira imagem; fallback para placeholder.

## Dicas/Debug
- Verifique se o arquivo existe em `public/uploads/products` e se há permissão de leitura.
- Verifique o DevTools → Network (status 200/404) e URL gerada.
- Consulte logs: `logs/error.log` se `LOG_ERRORS` estiver habilitado.
