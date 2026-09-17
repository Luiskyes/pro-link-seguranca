# Segurança da Informação — CREA Pro-Link

## Objetivo

Este documento registra os controles de segurança implementados no protótipo, alinhados ao item 8.5 do Anexo I do Edital CREA Pro-Link: autenticação segura, hash de senhas, proteção contra SQL Injection, XSS e CSRF, controle de perfis e auditoria.

## Controles implementados

### 1. Autenticação e senhas

- Autenticação baseada em sessão PHP e cookie `HttpOnly`.
- `session.use_strict_mode` e `session.use_only_cookies` habilitados.
- Regeneração do ID da sessão após login para mitigar session fixation.
- Senhas armazenadas exclusivamente por `password_hash()` e verificadas por `password_verify()`.
- Cadastro e redefinição exigem senha com pelo menos 8 caracteres.
- Mensagem de login genérica para reduzir enumeração de contas.
- Contador de tentativas falhas e bloqueio temporário da conta após repetidas falhas.
- Recuperação de senha usa token aleatório de 256 bits; somente o SHA-256 do token é persistido e o token expira.

### 2. SQL Injection

- A camada Repository utiliza PDO e prepared statements para valores fornecidos pelo usuário.
- `PDO::ATTR_EMULATE_PREPARES = false` força prepared statements nativos do MariaDB.
- Credenciais do banco ficam em variáveis de ambiente e não no código-fonte.

### 3. CSRF

- Operações de escrita protegidas por `CsrfMiddleware`.
- Tokens gerados com `random_bytes(32)` e comparados por `hash_equals()`.
- O backend aceita token pelo campo `_csrf` ou cabeçalho `X-CSRF-Token`.
- A rota `GET /csrf-token` permite que o cliente SPA obtenha o token vinculado à sessão.
- O cliente envia `X-CSRF-Token` nas operações de escrita.

### 4. XSS e segurança do navegador

- Conteúdo HTML gerado pelo backend deve ser escapado com `htmlspecialchars()` no contexto de saída.
- O backend envia Content Security Policy (CSP), `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy` e `Permissions-Policy`.
- Respostas da API utilizam JSON, reduzindo a inserção direta de dados em contexto HTML; o front-end deve continuar usando APIs seguras de DOM (`textContent`) para dados não confiáveis.
- Uploads existentes validam MIME real e tamanho antes de armazenamento.

### 5. Controle de acesso

- `AuthMiddleware` exige sessão nas rotas privadas.
- `RoleMiddleware` restringe operações administrativas ao perfil `ADMIN_CREA`.
- Rotas de upload, logout e curtidas também exigem autenticação/CSRF após a revisão.
- Recursos privados devem validar também a propriedade do registro (usuário autenticado versus dono do recurso), não apenas a existência de sessão.

### 6. CORS e sessão

- CORS é limitado à origem configurada por `FRONTEND_ORIGIN` e usa `Access-Control-Allow-Credentials` somente para essa origem.
- Não é utilizada reflexão irrestrita do cabeçalho `Origin`.
- Em produção, `SESSION_SECURE_COOKIE=true` deve ser usado obrigatoriamente com HTTPS.

### 7. Auditoria

- O schema possui `sis_logs_acesso` e `sis_auditoria`.
- Há `AuditoriaRepository` e `AuditoriaService` para persistência de eventos.
- A trilha deve registrar especialmente login administrativo, alteração de status, moderação, validações e ações sobre dados pessoais.

## Configuração obrigatória em produção

```env
APP_ENV=production
APP_URL=https://dominio-do-backend
FRONTEND_ORIGIN=https://dominio-do-frontend
SESSION_SECURE_COOKIE=true
DB_PASSWORD=<senha-forte-e-exclusiva>
CREA_API_TOKEN=<token-fornecido-pelo-crea>
```

Nunca versionar `.env` real. O repositório deve conter somente `.env.example` sem credenciais válidas.

## Pontos identificados para revisão adicional

1. **Autorização por propriedade (IDOR):** alguns controllers autenticados consultam/alteram registros somente pelo ID. Antes de produção, toda atualização/exclusão deve verificar se o registro pertence ao usuário ou se ele possui papel administrativo apropriado.
2. **Rotas parametrizadas:** o `Router` atual registra caminhos como `/demandas/{id}`, mas o `dispatch()` faz correspondência exata. Isso deve ser corrigido para que os parâmetros de rota sejam realmente resolvidos e validados.
3. **Divergência entre schema e Repository de demandas:** `DemandaRepository` referencia colunas (`area`, `tipo`, `cidade`, `uf`, `modalidade` e, na hidratação, nomes ainda diferentes) que não existem na tabela `demandas` do `estrutura.sql` atual. Deve ser reconciliado antes da demonstração.
4. **Sanitização:** `SanitizeInputMiddleware` altera `$_POST/$_GET` depois de `Request` já ter capturado os dados. Além disso, escape HTML deve ocorrer preferencialmente na saída, não sobre senhas ou valores de domínio. A CSP e o escape contextual devem ser a defesa principal contra XSS.
5. **Auditoria completa:** a infraestrutura existe, mas deve-se confirmar que todas as ações sensíveis chamam efetivamente o serviço de auditoria.

## Checklist do Edital — item 8.5

| Requisito | Situação após revisão |
|---|---|
| Autenticação segura | Implementado, com sessão endurecida e bloqueio por tentativas |
| `password_hash()` | Implementado |
| Proteção contra SQL Injection | Implementado por PDO/prepared statements; emulação desativada |
| Proteção contra XSS | CSP + escape de saída; revisão de templates/front-end ainda recomendada |
| Proteção contra CSRF | Implementado para POSTs protegidos e integrado ao SPA |
| Controle de perfis | Implementado por `AuthMiddleware` + `RoleMiddleware`; revisar propriedade dos recursos |
| Registro de auditoria | Estrutura implementada; cobertura de eventos precisa ser conferida |
