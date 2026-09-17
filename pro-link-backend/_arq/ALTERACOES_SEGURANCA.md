# Alterações realizadas — revisão de segurança

- Corrigido fluxo duplicado/defeituoso do login em `AuthController`.
- Ativado bloqueio temporário por tentativas repetidas de login e registro de login bem-sucedido.
- Adicionada validação mínima de senha (8 caracteres) no cadastro e redefinição.
- Sessões endurecidas com strict mode, cookies-only, HttpOnly e regeneração de ID no login.
- PDO configurado com prepared statements nativos (`ATTR_EMULATE_PREPARES=false`).
- CSRF passou a aceitar `X-CSRF-Token`, necessário para o cliente SPA.
- Criada rota `GET /csrf-token` e `SecurityController`.
- Adicionado CSRF ao logout e curtidas; upload de anexo agora também exige autenticação.
- CORS restringido a `FRONTEND_ORIGIN` em vez de aceitar origem arbitrária.
- Adicionados cabeçalhos CSP, anti-clickjacking, nosniff, referrer e permissions policy.
- Erros detalhados são ocultados quando `APP_ENV=production`.
- Front-end de autenticação atualizado para buscar/enviar CSRF e usar a rota correta de logout.
- Cliente HTTP compartilhado atualizado para anexar CSRF automaticamente em operações de escrita.
- Criados `SEGURANCA.md` e `DICIONARIO_DADOS.md` em `_arq/`.

## Atenção antes do Demo Day

Há problemas preexistentes fora do escopo estrito da segurança que foram documentados em `SEGURANCA.md`: roteamento com `{id}`, divergência do `DemandaRepository` com `estrutura.sql`, autorização por propriedade em alguns recursos e cobertura incompleta da auditoria. Eles devem ser corrigidos/testados antes da demonstração.
