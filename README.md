# CREA Pro-Link

Plataforma desenvolvida pela **Equipe Otho** para o **Desafio CREA Pro-Link — II CENATEC 2026**, promovido pelo CREA-AM.

O Pro-Link tem como objetivo integrar profissionais, empresas e oportunidades relacionadas ao ecossistema do Sistema CONFEA/CREA, reunindo funcionalidades de networking profissional em uma aplicação web.

---

## 👥 Equipe

| Integrante | Responsabilidade |
|---|---|
| Arielle Silva Tavares | Testes / QA |
| Felipe André Freire Trindade | Backend |
| Hanna Nunes Reis | Backend |
| Luan da Silva Palma | Engenharia de Dados e Front-end |
| Luis Rogerio Cavalcante de Melo | Segurança da Informação |

---

## 🏗️ Arquitetura

O projeto está dividido entre **Frontend** e **Backend**, executados em containers Docker.

```text
┌──────────────────────────────┐
│          Frontend            │
│        Nginx :8081           │
└──────────────┬───────────────┘
               │ HTTP / API
               ▼
┌──────────────────────────────┐
│       Backend / API          │
│        Nginx :8080           │
└──────────────┬───────────────┘
               │ FastCGI
               ▼
┌──────────────────────────────┐
│        PHP 8.2 FPM           │
│   Controllers / Services     │
│       Repositories           │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│       MariaDB 10.11          │
│          :3306               │
└──────────────────────────────┘
```

O Backend utiliza uma arquitetura em camadas com **MVC, Front Controller, Services e Repository**, mantendo as responsabilidades da aplicação separadas.

Fluxo simplificado:

```text
Request
   ↓
Front Controller
   ↓
Router
   ↓
Middlewares
   ↓
Controller
   ↓
Service
   ↓
Repository / Model
   ↓
MariaDB
```

---

## 📁 Estrutura do repositório

```text
.
├── pro-link-backend/
│   ├── _arq/
│   ├── database/
│   ├── docker/
│   ├── public/
│   ├── routes/
│   ├── src/
│   ├── storage/
│   ├── tests/
│   ├── views/
│   ├── .env.example
│   ├── _config.php
│   ├── composer.json
│   └── docker-compose.yml
│
└── pro-link-do-check-in-ao-check-out/
    ├── public/
    ├── docker-compose.yml
    └── README.md
```

---

## 🛠️ Tecnologias

### Backend

- PHP 8.2+
- PHP-FPM
- Composer
- Nginx
- MariaDB 10.11+
- Docker / Docker Compose

### Frontend

- HTML5
- CSS3
- JavaScript
- Nginx
- Docker

### Banco de Dados

- MariaDB 10.11+
- Charset `utf8mb4`
- Collation `utf8mb4_unicode_ci`

---

# 🚀 Executando o projeto

## Pré-requisitos

É necessário possuir:

- Docker
- Docker Compose
- Git

Clone o repositório e entre na pasta do projeto.

---

## 1. Configurar o Backend

Entre na pasta:

```bash
cd pro-link-backend
```

Crie o arquivo `.env` a partir do modelo:

### Windows PowerShell

```powershell
Copy-Item .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

Revise as variáveis do `.env` antes de executar a aplicação.

> O arquivo `.env` contém configurações locais e informações sensíveis e não deve ser versionado.

---

## 2. Iniciar o Backend

Dentro de `pro-link-backend`:

```bash
docker compose up -d --build
```

Serão iniciados os serviços:

```text
Nginx       → localhost:8080
PHP-FPM     → porta interna 9000
MariaDB     → localhost:3306
```

A porta `8080` corresponde à **API**.

Por isso, acessar diretamente:

```text
http://localhost:8080/
```

pode retornar:

```text
404 - Página não encontrada
```

Isso não representa necessariamente uma falha, pois a API trabalha com rotas específicas.

Para verificar a comunicação com o Backend, pode ser utilizado:

```text
http://localhost:8080/csrf-token
```

---

## 3. Iniciar o Frontend

Em outro terminal:

```bash
cd pro-link-do-check-in-ao-check-out
docker compose up -d
```

A aplicação estará disponível em:

```text
http://localhost:8081
```

---

## 4. Verificar os containers

```bash
docker ps
```

O ambiente deve possuir os serviços do Frontend e Backend em execução.

---

# 🔐 Segurança da Informação

A aplicação foi estruturada considerando princípios de **Security by Design** e proteção dos dados tratados pelo sistema.

Entre os mecanismos implementados estão:

### Autenticação e sessão

- autenticação controlada pelo Backend;
- senhas tratadas com mecanismos seguros de hash;
- gerenciamento de sessão pelo servidor;
- renovação da sessão durante o processo de autenticação;
- encerramento da sessão no logout;
- cookies de sessão configuráveis conforme o ambiente.

### CSRF

Operações sensíveis utilizam proteção contra **Cross-Site Request Forgery (CSRF)**.

O Backend disponibiliza token de sessão através do endpoint:

```text
GET /csrf-token
```

Requisições protegidas devem apresentar um token válido.

### XSS

Foram aplicadas medidas para reduzir riscos de **Cross-Site Scripting (XSS)**, incluindo tratamento seguro de conteúdo recebido e renderização de dados não confiáveis.

### SQL Injection

O acesso ao banco de dados é realizado através da camada de persistência da aplicação, utilizando mecanismos de consulta parametrizada para evitar a concatenação insegura de entradas do usuário em comandos SQL.

### Controle de acesso

A aplicação possui mecanismos de:

- autenticação;
- autorização por perfil;
- proteção de rotas;
- validação de propriedade de recursos;
- segregação de responsabilidades.

Essas verificações também reduzem riscos relacionados a acesso indevido a objetos e recursos pertencentes a outros usuários.

### Headers HTTP

O Backend configura headers adicionais de segurança, incluindo mecanismos relacionados a:

- prevenção de MIME sniffing;
- proteção contra carregamento em frames;
- política de referrer;
- controle de permissões do navegador;
- HTTPS/HSTS quando aplicável ao ambiente.

### Variáveis sensíveis

Credenciais, tokens e demais segredos não devem permanecer diretamente no código-fonte.

Essas informações são fornecidas através de variáveis de ambiente.

O repositório mantém apenas:

```text
.env.example
```

como referência de configuração.

---

# 🗄️ Banco de Dados

O projeto utiliza **MariaDB 10.11+**.

A estrutura contempla:

- chaves primárias;
- chaves estrangeiras;
- integridade referencial;
- índices;
- status de registros;
- exclusão lógica quando aplicável;
- scripts de criação da estrutura.

O script principal está disponível em:

```text
pro-link-backend/_arq/estrutura.sql
```

---

# 📚 Documentação técnica

A documentação do Backend está concentrada em:

```text
pro-link-backend/_arq/
```

Entre os documentos do projeto estão:

```text
estrutura.sql
DICIONARIO_DADOS.md
SEGURANCA.md
```

Também podem ser adicionados nessa pasta documentos relacionados ao modelo de dados, arquitetura e procedimentos de QA.

---

# 🧪 Testes e QA

A validação funcional e de segurança deve considerar, entre outros pontos:

- autenticação;
- cadastro;
- logout;
- gerenciamento de sessão;
- CSRF;
- controle de acesso;
- autorização por perfil;
- acesso a recursos pertencentes a outros usuários;
- tratamento de entradas;
- XSS;
- SQL Injection;
- uploads;
- auditoria;
- respostas HTTP;
- integração Frontend ↔ Backend.

Os resultados devem ser registrados pela equipe de **Testes / QA**.

---

# 📝 Logs e auditoria

O Backend possui estrutura destinada ao armazenamento de logs em:

```text
pro-link-backend/storage/logs/
```

Eventos relevantes para segurança e funcionamento da aplicação podem ser registrados para fins de rastreabilidade e auditoria.

---

# ⚠️ Ambiente de desenvolvimento

As configurações fornecidas no repositório são destinadas ao ambiente de desenvolvimento.

Antes de uma implantação em produção devem ser utilizados:

- credenciais próprias e fortes;
- `APP_KEY` segura;
- HTTPS;
- cookies seguros;
- segredos reais exclusivamente por variáveis de ambiente;
- configuração apropriada de CORS;
- configuração de logs;
- permissões adequadas de arquivos e diretórios.

Nunca publique o arquivo `.env` ou credenciais reais no repositório.

---

## 📌 Portas utilizadas

| Serviço | Porta |
|---|---:|
| Frontend | `8081` |
| Backend / API | `8080` |
| MariaDB | `3306` |
| PHP-FPM | `9000` (interna) |

---

## 📄 Licença e utilização

Projeto desenvolvido no contexto do **Desafio CREA Pro-Link — II CENATEC 2026** pela Equipe Otho.
