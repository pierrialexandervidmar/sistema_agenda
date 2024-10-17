# Projeto Agenda de Contatos (PHP + Doctrine)

Este é um projeto composto por uma API e views para gerenciamento de uma agenda de contatos, desenvolvido com PHP, Doctrine e Docker. 
A aplicação permite operações CRUD (Create, Read, Update, Delete) para gerenciar contatos, utilizando o banco de dados Postgres.

## Tecnologias Utilizadas

- PHP
- Doctrine ORM
- Docker
- PostgreSQL
- PgAdmin

## Pré-requisitos

Antes de começar, verifique se você possui o Docker e o Docker Compose instalados em sua máquina.

## Como Baixar o Projeto

Para baixar o projeto, utilize o comando git clone

```bash
git clone git@github.com:pierrialexandervidmar/sistema_agenda.git
cd sistema_agenda
```

```bash
docker-compose up
```

Esse comando irá iniciar os containers necessários, incluindo o servidor PHP, o banco de dados PostgreSQL e o PgAdmin.

## Acesso ao Banco de Dados

### Conectando ao PgAdmin

Acesse o PgAdmin pelo navegador em http://localhost:5050.

Faça login usando as credenciais:

- Email: admin@admin.com
- Senha: admin

Crie um novo servidor no PgAdmin:

1. Clique com o botão direito em "Servers" e selecione "Create" > "Server...".
2. Na aba "General", insira um nome (exemplo: Postgres Docker).
3. Na aba "Connection", use as seguintes informações:
4. Host: postgres
5. Port: 5432
  -- Username: root
  -- Password: Senha!123
4. Clique em "Save".

Após criar o servidor, você verá o banco de dados agenda na árvore de navegação do PgAdmin.