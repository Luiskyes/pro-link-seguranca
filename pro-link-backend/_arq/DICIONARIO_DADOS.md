# Dicionário de Dados — CREA Pro-Link

> Gerado a partir do arquivo `_arq/estrutura.sql`. Tipos, nulabilidade, chaves e valores padrão refletem o schema SQL atual.

## `usuarios`

Contas de acesso e dados básicos dos usuários da plataforma.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `tipo_pessoa` | `ENUM( 'FISICA', 'JURIDICA' )` | Não | — | `—` |
| `perfil_acesso` | `ENUM( 'USUARIO', 'ADMIN_CREA' )` | Não | — | `'USUARIO'` |
| `tipo_conta` | `ENUM( 'COMUM', 'ESTUDANTE', 'PROFISSIONAL', 'EMPRESA' )` | Não | — | `'COMUM'` |
| `nome` | `VARCHAR(150)` | Não | — | `—` |
| `senha_hash` | `VARCHAR(255)` | Não | — | `—` |
| `telefone` | `VARCHAR(25)` | Não | — | `—` |
| `email` | `VARCHAR(254)` | Não | UNIQUE | `—` |
| `conta_ativa` | `BOOLEAN` | Não | — | `TRUE` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `ultimo_login_em` | `DATETIME` | Sim | — | `—` |
| `tentativas_login` | `SMALLINT UNSIGNED` | Não | — | `0` |
| `bloqueado_ate` | `DATETIME` | Sim | — | `—` |

## `tokens_redefinicao_senha`

Tokens de uso único para recuperação de senha.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_usuario` | `INT UNSIGNED` | Não | FK → usuarios(id) | `—` |
| `token_hash` | `CHAR(64)` | Não | UNIQUE | `—` |
| `expira_em` | `DATETIME` | Não | — | `—` |
| `usado_em` | `DATETIME` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `pessoa_fisica`

Dados específicos de usuários pessoa física.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_usuario` | `INT UNSIGNED` | Não | PK; FK → usuarios(id) | `—` |
| `cpf` | `VARCHAR(14)` | Não | UNIQUE | `—` |
| `visibilidade_publica` | `BOOLEAN` | Não | — | `TRUE` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `pessoa_juridica`

Dados específicos de usuários pessoa jurídica/empresa.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_usuario` | `INT UNSIGNED` | Não | PK; FK → usuarios(id) | `—` |
| `cnpj` | `VARCHAR(18)` | Não | UNIQUE | `—` |
| `nome_fantasia` | `VARCHAR(150)` | Sim | — | `—` |
| `razao_social` | `VARCHAR(150)` | Não | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `administradores`

Vínculo e dados de usuários administradores.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_usuario` | `INT UNSIGNED` | Não | PK; FK → usuarios(id) | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `universidades`

Cadastro de instituições de ensino.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `nome` | `VARCHAR(200)` | Não | — | `—` |
| `sigla` | `VARCHAR(30)` | Sim | — | `—` |
| `cnpj` | `VARCHAR(18)` | Sim | UNIQUE | `—` |
| `tipo` | `ENUM( 'PUBLICA_FEDERAL', 'PUBLICA_ESTADUAL', 'PUBLICA_MUNICIPAL', 'PRIVADA', 'COMUNITARIA', 'CONFESSIONAL', 'OUTRA' )` | Não | — | `'OUTRA'` |
| `cidade` | `VARCHAR(100)` | Sim | — | `—` |
| `estado` | `CHAR(2)` | Sim | — | `—` |
| `site` | `VARCHAR(255)` | Sim | — | `—` |
| `ativa` | `BOOLEAN` | Não | — | `TRUE` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `universitarios`

Perfil acadêmico de usuários estudantes.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_usuario` | `INT UNSIGNED` | Não | PK; FK → pessoa_fisica(id_usuario) | `—` |
| `universidade_id` | `INT UNSIGNED` | Não | FK → universidades(id) | `—` |
| `curso` | `VARCHAR(150)` | Não | — | `—` |
| `matricula` | `VARCHAR(50)` | Sim | — | `—` |
| `semestre_atual` | `TINYINT UNSIGNED` | Sim | — | `—` |
| `previsao_formatura` | `DATE` | Sim | — | `—` |
| `comprovante_matricula` | `VARCHAR(255)` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `grau_academico` | `ENUM( 'TECNOLOGO', 'GRADUACAO', 'POS_GRADUACAO', 'MESTRADO', 'DOUTORADO', 'POS_DOUTORADO' )` | Não | — | `'GRADUACAO'` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `profissionais`

Perfil técnico/profissional dos usuários.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_usuario` | `INT UNSIGNED` | Não | PK; FK → pessoa_fisica(id_usuario) | `—` |
| `numero_registro_confea_crea` | `VARCHAR(50)` | Não | UNIQUE | `—` |
| `categoria_profissional` | `VARCHAR(100)` | Não | — | `—` |
| `anos_experiencia` | `SMALLINT UNSIGNED` | Sim | — | `—` |
| `empresa_atual_id` | `INT UNSIGNED` | Sim | FK → pessoa_juridica(id_usuario) | `—` |
| `grau_academico` | `ENUM( 'TECNOLOGO', 'GRADUACAO', 'POS_GRADUACAO', 'MESTRADO', 'DOUTORADO', 'POS_DOUTORADO' )` | Não | — | `'GRADUACAO'` |
| `registro_validado` | `BOOLEAN` | Não | — | `FALSE` |
| `registro_validado_em` | `DATETIME` | Sim | — | `—` |
| `registro_ativo` | `BOOLEAN` | Não | — | `TRUE` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `competencias`

Catálogo de competências profissionais.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `nome` | `VARCHAR(100)` | Não | UNIQUE | `—` |
| `descricao` | `VARCHAR(255)` | Sim | — | `—` |
| `ativo` | `BOOLEAN` | Não | — | `TRUE` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `profissional_competencias`

Relacionamento N:N entre profissionais e competências.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_profissional` | `INT UNSIGNED` | Não | PK; FK → profissionais(id_usuario) | `—` |
| `id_competencia` | `INT UNSIGNED` | Não | PK; FK → competencias(id) | `—` |
| `nivel` | `ENUM( 'BASICO', 'INTERMEDIARIO', 'AVANCADO', 'ESPECIALISTA' )` | Não | — | `'INTERMEDIARIO'` |
| `anos_experiencia` | `SMALLINT UNSIGNED` | Sim | — | `—` |

## `especialidades`

Catálogo de especialidades.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `nome` | `VARCHAR(100)` | Não | UNIQUE | `—` |
| `descricao` | `VARCHAR(255)` | Sim | — | `—` |
| `ativo` | `BOOLEAN` | Não | — | `TRUE` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `profissional_especialidades`

Relacionamento N:N entre profissionais e especialidades.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_profissional` | `INT UNSIGNED` | Não | PK; FK → profissionais(id_usuario) | `—` |
| `id_especialidade` | `INT UNSIGNED` | Não | PK; FK → especialidades(id) | `—` |

## `portfolio`

Portfólio associado ao usuário.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_usuario` | `INT UNSIGNED` | Não | UNIQUE; FK → pessoa_fisica(id_usuario) | `—` |
| `resumo_profissional` | `TEXT` | Sim | — | `—` |
| `documento_identificacao` | `VARCHAR(255)` | Sim | — | `—` |
| `links_contato` | `JSON` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `projetos`

Projetos publicados em portfólios.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_portfolio` | `INT UNSIGNED` | Não | FK → portfolio(id) | `—` |
| `titulo` | `VARCHAR(150)` | Não | — | `—` |
| `descricao` | `TEXT` | Sim | — | `—` |
| `links_referencia` | `JSON` | Sim | — | `—` |
| `data_inicio` | `DATE` | Sim | — | `—` |
| `data_fim` | `DATE` | Sim | — | `—` |
| `resultados_mencionaveis` | `JSON` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `projeto_competencias`

Competências relacionadas a projetos.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_projeto` | `INT UNSIGNED` | Não | PK; FK → projetos(id) | `—` |
| `id_competencia` | `INT UNSIGNED` | Não | PK; FK → competencias(id) | `—` |

## `experiencias`

Experiências profissionais do portfólio.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_portfolio` | `INT UNSIGNED` | Não | FK → portfolio(id) | `—` |
| `titulo_posicao_servico` | `VARCHAR(150)` | Não | — | `—` |
| `organizacao_cliente` | `VARCHAR(150)` | Sim | — | `—` |
| `organizacao_id` | `INT UNSIGNED` | Sim | FK → pessoa_juridica(id_usuario) | `—` |
| `descricao_atividades` | `TEXT` | Sim | — | `—` |
| `data_inicio` | `DATE` | Sim | — | `—` |
| `data_fim` | `DATE` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `projeto_experiencia`

Relacionamento entre projetos e experiências.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_projeto` | `INT UNSIGNED` | Não | PK; FK → projetos(id) | `—` |
| `id_experiencia` | `INT UNSIGNED` | Não | PK; FK → experiencias(id) | `—` |

## `arts`

ARTs associadas ao portfólio/profissional.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_portfolio` | `INT UNSIGNED` | Não | FK → portfolio(id) | `—` |
| `id_profissional_responsavel` | `INT UNSIGNED` | Não | FK → profissionais(id_usuario) | `—` |
| `numero_art` | `VARCHAR(50)` | Não | UNIQUE | `—` |
| `tipo_art` | `VARCHAR(100)` | Sim | — | `—` |
| `status_art` | `ENUM( 'PENDENTE', 'APROVADA', 'REJEITADA', 'CANCELADA' )` | Não | — | `'PENDENTE'` |
| `validada_por_crea` | `BOOLEAN` | Não | — | `FALSE` |
| `data_emissao` | `DATE` | Sim | — | `—` |
| `data_validacao` | `DATETIME` | Sim | — | `—` |
| `documento_art` | `VARCHAR(255)` | Sim | — | `—` |
| `observacoes` | `TEXT` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `cats`

CATs associadas ao portfólio/profissional.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_portfolio` | `INT UNSIGNED` | Não | FK → portfolio(id) | `—` |
| `numero_certidao` | `VARCHAR(50)` | Não | UNIQUE | `—` |
| `codigo_autenticidade` | `VARCHAR(100)` | Não | UNIQUE | `—` |
| `data_emissao` | `DATE` | Sim | — | `—` |
| `validade` | `DATE` | Sim | — | `—` |
| `status_cat` | `ENUM( 'PENDENTE', 'VALIDA', 'EXPIRADA', 'CANCELADA', 'REJEITADA' )` | Não | — | `'PENDENTE'` |
| `id_profissional_responsavel` | `INT UNSIGNED` | Não | FK → profissionais(id_usuario) | `—` |
| `id_contratante` | `INT UNSIGNED` | Sim | FK → usuarios(id) | `—` |
| `id_proprietario` | `INT UNSIGNED` | Sim | FK → usuarios(id) | `—` |
| `documento_cat` | `VARCHAR(255)` | Sim | — | `—` |
| `observacoes` | `TEXT` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `demandas`

Demandas de serviços técnicos publicadas.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_empresa` | `INT UNSIGNED` | Não | FK → pessoa_juridica(id_usuario) | `—` |
| `titulo` | `VARCHAR(150)` | Não | — | `—` |
| `descricao` | `TEXT` | Não | — | `—` |
| `status` | `ENUM( 'ABERTA', 'FECHADA', 'CANCELADA', 'SUSPENSA_PELO_CREA' )` | Não | — | `'ABERTA'` |
| `data_publicacao` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `data_fechamento` | `DATETIME` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `demanda_competencias`

Competências requeridas pelas demandas.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_demanda` | `INT UNSIGNED` | Não | PK; FK → demandas(id) | `—` |
| `id_competencia` | `INT UNSIGNED` | Não | PK; FK → competencias(id) | `—` |
| `obrigatoria` | `BOOLEAN` | Não | — | `TRUE` |
| `nivel_minimo` | `ENUM( 'BASICO', 'INTERMEDIARIO', 'AVANCADO', 'ESPECIALISTA' )` | Não | — | `'BASICO'` |

## `demonstracoes_interesse`

Manifestações de interesse de usuários em demandas.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_demanda` | `INT UNSIGNED` | Não | FK → demandas(id) | `—` |
| `id_usuario` | `INT UNSIGNED` | Não | FK → pessoa_fisica(id_usuario) | `—` |
| `titulo` | `VARCHAR(150)` | Não | — | `—` |
| `mensagem` | `TEXT` | Não | — | `—` |
| `status` | `ENUM( 'ENVIADA', 'EM_ANALISE', 'ACEITA', 'RECUSADA', 'CANCELADA' )` | Não | — | `'ENVIADA'` |
| `data_interesse` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `posts`

Publicações sociais/comunicacionais da plataforma.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_autor` | `INT UNSIGNED` | Não | FK → usuarios(id) | `—` |
| `titulo` | `VARCHAR(150)` | Sim | — | `—` |
| `conteudo` | `TEXT` | Não | — | `—` |
| `status_post` | `ENUM( 'PUBLICO', 'PRIVADO', 'ARQUIVADO', 'SUSPENSO_PELO_CREA' )` | Não | — | `'PUBLICO'` |
| `data_postagem` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `anexos`

Arquivos anexados às publicações.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_post` | `INT UNSIGNED` | Não | FK → posts(id) | `—` |
| `nome_arquivo` | `VARCHAR(255)` | Não | — | `—` |
| `nome_armazenado` | `VARCHAR(255)` | Sim | — | `—` |
| `tipo_mime` | `VARCHAR(100)` | Sim | — | `—` |
| `status_anexo` | `ENUM( 'PUBLICO', 'PRIVADO', 'ARQUIVADO', 'SUSPENSO_PELO_CREA' )` | Não | — | `'PUBLICO'` |
| `tamanho` | `BIGINT UNSIGNED` | Sim | — | `—` |
| `caminho_armazenamento` | `VARCHAR(500)` | Não | — | `—` |
| `hash_arquivo` | `VARCHAR(128)` | Sim | — | `—` |
| `data_upload` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `comentarios`

Comentários feitos em publicações.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_post` | `INT UNSIGNED` | Não | FK → posts(id) | `—` |
| `id_autor` | `INT UNSIGNED` | Não | FK → usuarios(id) | `—` |
| `id_comentario_pai` | `INT UNSIGNED` | Sim | FK → comentarios(id) | `—` |
| `conteudo` | `TEXT` | Não | — | `—` |
| `status_comentario` | `ENUM( 'PUBLICO', 'PRIVADO', 'ARQUIVADO', 'SUSPENSO_PELO_CREA' )` | Não | — | `'PUBLICO'` |
| `data_comentario` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `likes_posts`

Curtidas em publicações.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_usuario` | `INT UNSIGNED` | Não | PK; FK → usuarios(id) | `—` |
| `id_post` | `INT UNSIGNED` | Não | PK; FK → posts(id) | `—` |
| `data_curtida` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `likes_comentarios`

Curtidas em comentários.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id_usuario` | `INT UNSIGNED` | Não | PK; FK → usuarios(id) | `—` |
| `id_comentario` | `INT UNSIGNED` | Não | PK; FK → comentarios(id) | `—` |
| `data_curtida` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `denuncias`

Denúncias e fluxo de moderação.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_denunciante` | `INT UNSIGNED` | Sim | FK → usuarios(id) | `—` |
| `id_denunciado` | `INT UNSIGNED` | Sim | FK → usuarios(id) | `—` |
| `motivo` | `TEXT` | Não | — | `—` |
| `status_denuncia` | `ENUM( 'PENDENTE', 'EM_ANALISE', 'APROVADA', 'REJEITADA', 'ARQUIVADA' )` | Não | — | `'PENDENTE'` |
| `observacao_moderador` | `TEXT` | Sim | — | `—` |
| `id_moderador` | `INT UNSIGNED` | Sim | FK → administradores(id_usuario) | `—` |
| `data_denuncia` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `data_analise` | `DATETIME` | Sim | — | `—` |

## `sis_logs_acesso`

Registros de eventos de acesso.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `log_id` | `BIGINT UNSIGNED` | Não | PK | `—` |
| `usu_id` | `INT UNSIGNED` | Sim | FK → usuarios(id) | `—` |
| `log_acao` | `ENUM( 'LOGIN_SUCESSO', 'LOGIN_FALHA', 'LOGOUT' )` | Não | — | `—` |
| `log_ip` | `VARCHAR(45)` | Não | — | `—` |
| `log_user_agent` | `VARCHAR(500)` | Sim | — | `—` |
| `log_dt_registro` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `sis_auditoria`

Trilha de auditoria de alterações e ações.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `aud_id` | `BIGINT UNSIGNED` | Não | PK | `—` |
| `usu_id` | `INT UNSIGNED` | Sim | FK → usuarios(id) | `—` |
| `aud_tabela` | `VARCHAR(100)` | Não | — | `—` |
| `aud_registro_id` | `BIGINT UNSIGNED` | Não | — | `—` |
| `aud_acao` | `ENUM( 'INSERT', 'UPDATE', 'DELETE', 'EXCLUSAO_LOGICA' )` | Não | — | `—` |
| `aud_dados_antigos` | `JSON` | Sim | — | `—` |
| `aud_dados_novos` | `JSON` | Sim | — | `—` |
| `aud_ip` | `VARCHAR(45)` | Sim | — | `—` |
| `aud_user_agent` | `VARCHAR(500)` | Sim | — | `—` |
| `aud_dt_registro` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `notificacoes`

Notificações destinadas aos usuários.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_usuario` | `INT UNSIGNED` | Não | FK → usuarios(id) | `—` |
| `mensagem` | `TEXT` | Não | — | `—` |
| `lida` | `BOOLEAN` | Não | — | `FALSE` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |

## `cartas_virtuais`

Cartas virtuais privadas criadas por usuários.

| Campo | Tipo | Nulo? | Chave/Restrição | Padrão |
|---|---|---|---|---|
| `id` | `INT UNSIGNED` | Não | PK | `—` |
| `id_usuario` | `INT UNSIGNED` | Não | FK → usuarios(id) | `—` |
| `id_demanda` | `INT UNSIGNED` | Sim | FK → demandas(id) | `—` |
| `titulo` | `VARCHAR(150)` | Não | — | `—` |
| `legenda` | `TEXT` | Sim | — | `—` |
| `remetente_email` | `VARCHAR(254)` | Não | — | `—` |
| `destinatario_email` | `VARCHAR(254)` | Não | — | `—` |
| `nome_arquivo` | `VARCHAR(255)` | Sim | — | `—` |
| `nome_armazenado` | `VARCHAR(255)` | Sim | — | `—` |
| `tipo_mime` | `VARCHAR(100)` | Sim | — | `—` |
| `tamanho_arquivo` | `BIGINT UNSIGNED` | Sim | — | `—` |
| `caminho_armazenamento` | `VARCHAR(500)` | Sim | — | `—` |
| `criado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
| `atualizado_em` | `DATETIME` | Não | — | `CURRENT_TIMESTAMP` |
