# Segurança e Boas Práticas

## Autenticação e Sessão
- Proteja rotas sensíveis com verificação de sessão (ex.: Dashboard)
- Regenerar ID de sessão no login

## Validações e Sanitização
- Use `htmlspecialchars` nas views para evitar XSS
- Valide/normalize entradas (números, datas, enums)

## Uploads de Arquivos
- Validar MIME real (jpeg/png/webp)
- Limitar tamanho (5MB)
- Salvar fora de execuções PHP (apenas como estático)

## SQL e Banco de Dados
- Use `prepare`/`execute` com bind (PDO) para evitar SQL injection
- Índices adequados para busca e filtros

## Erros e Logs
- Não exibir detalhes sensíveis em produção
- Habilitar logs e proteger diretório `logs/`

## Cabeçalhos e HTTPS
- Usar HTTPS em produção
- Considerar headers de segurança (HSTS, X-Content-Type-Options, etc.)
