# Projeto Agenda de Contatos (PHP + Doctrine)

Este projeto consiste em uma API e views para o gerenciamento de uma agenda de contatos, desenvolvido com PHP, Doctrine e Docker. A aplicação permite realizar operações CRUD (Create, Read, Update, Delete) para gerenciar contatos, utilizando um banco de dados PostgreSQL.

A estrutura da aplicação é baseada em uma arquitetura em camadas (Layered Architecture), composta por Controller, Service, Entity e Repository. Embora seja uma aplicação pequena, o objetivo é demonstrar uma forma eficaz de separação de responsabilidades, facilitando a manutenção futura e tornando-a escalável.

Outro aspecto importante é o arquivo Container.php, que gerencia as injeções de dependência, simplificando a manutenção e a organização do código.

A aplicação inclui uma estrutura de views e rotas de APIs. Os recursos da API abrangem todas as operações CRUD e oferecem múltiplas maneiras de consumir dados (GET). O sistema de roteamento é isolado para views e APIs, o que facilita a manutenção e a inclusão de novos recursos no futuro, utilizando os arquivos routesAPI.php e routesWeb.php.


## Tecnologias Utilizadas

- PHP
- Doctrine ORM
- Http-foundation
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

Para executar a criação do banco de dados a partir das entidades Doctrine, execute no terminal:

```bash
sudo docker compose exec php-apache php cli-config.php orm:schema-tool:update --complete
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

## Acessos da Aplicação

### A aplicação possui duas interfaces

A View renderizada pelo servidor PHP:

```bash
http://localhost:8080/
```

E o Frontend ReactJS consumindo a API:

```bash
http://localhost:5173/
```