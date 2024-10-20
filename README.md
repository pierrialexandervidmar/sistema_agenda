# Projeto Agenda de Contatos (PHP + Doctrine)

Este projeto consiste em uma `API` e `views` para o gerenciamento de uma agenda de contatos, desenvolvido com PHP, Doctrine e Docker. E adicionalmente foi incluida uma interface `frontend em ReactJS` que irá consumir as rotas da API.
A aplicação permite realizar operações CRUD (Create, Read, Update, Delete) para gerenciar contatos, utilizando um banco de dados PostgreSQL.

A estrutura da aplicação é baseada em uma `arquitetura em camadas` (Layered Architecture), composta por Controller, Service, Entity e Repository. Embora seja uma aplicação pequena, o objetivo é demonstrar uma forma eficaz de separação de responsabilidades, facilitando a manutenção futura e tornando-a escalável.

Outro aspecto importante é o arquivo Container.php, que gerencia as `injeções de dependência`, simplificando a manutenção e a organização do código.

A aplicação inclui uma estrutura de views e rotas de APIs. Os recursos da API abrangem todas as operações CRUD e oferecem múltiplas maneiras de consumir dados (GET). O `sistema de roteamento` é isolado para views e APIs, o que facilita a manutenção e a inclusão de novos recursos no futuro, utilizando os arquivos routesAPI.php e routesWeb.php.


## Tecnologias Utilizadas

- PHP 8.3
- Doctrine ORM
- Apache
- Composer
- JQuery 3.7
- Http-foundation
- Docker & Docker Compose
- PostgreSQL
- PgAdmin
- ReactJS
- React Router Dom
- Axios
- Tailwind CSS

## Pré-requisitos

Antes de começar, verifique se você possui o Docker e o Docker Compose instalados em sua máquina.

## Como Baixar o Projeto

Para baixar o projeto, utilize o comando git clone

```bash
git clone git@github.com:pierrialexandervidmar/sistema_agenda.git
cd sistema_agenda
```

Na primeira execução:

```bash
docker compose up --build -d
```

Nas demais pode executar apenas:

```bash
docker compose up -d
```

### Criar o banco de dados a partir das entidades Doctrine:
Após os containers estarem em execução, execute no terminal:

```bash
sudo docker compose exec php-apache php cli-config.php orm:schema-tool:update --complete
```
Esse comando vai criar o banco de dados a partir das entidades configuradas no Doctrine.

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
- Username: root
- Password: Senha!123
4. Clique em "Save".

Após criar o servidor, você verá o banco de dados `agenda` na árvore de navegação do PgAdmin.

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

## Acesso a API

URL Base da API:

```bash
http://localhost:8080/api
```

### Rotas da API
A aplicação oferece várias rotas para gerenciar contatos e pessoas. As operações disponíveis seguem os princípios do CRUD (Create, Read, Update, Delete), com endpoints para criação, consulta, atualização e exclusão de registros. Abaixo estão os detalhes de cada rota.

Obs.: Na raiz do projeto contém o arquivo `Insomnia_Requests.json`, pode importar ele no Insomnia e executar as requests para teste.

### Contatos

1. **Criar Contato**
   - **Endpoint**: `/api/contatos`
   - **Método HTTP**: `POST`
   - **Controller**: `ContatoController::criar`
   - **Descrição**: Cria um novo contato.
   - **Payload**:
   ```json
   {
      "tipo": 0,
      "descricao": "4899995555",
      "idPessoa": 14
   }
   // Tipo 0 para telefone e tipo 1 para email.
   ```

2. **Listar Contatos**
   - **Endpoint**: `/api/contatos`
   - **Método HTTP**: `GET`
   - **Descrição**: Retorna uma lista de todos os contatos cadastrados.

3. **Listar Contatos com Pessoas**
   - **Endpoint**: `/api/contatos/pessoas`
   - **Método HTTP**: `GET`
   - **Controller**: `ContatoController::listarComPessoa`
   - **Descrição**: Retorna uma lista de contatos com suas respectivas pessoas relacionadas.

4. **Obter Contato por ID**
   - **Endpoint**: `/api/contatos/{id}`
   - **Método HTTP**: `GET`
   - **Controller**: `ContatoController::obter`
   - **Descrição**: Retorna um contato específico com base no ID fornecido.

5. **Obter Contato com Pessoa por ID**
   - **Endpoint**: `/api/contatos/pessoas/{id}`
   - **Método HTTP**: `GET`
   - **Controller**: `ContatoController::obterComPessoa`
   - **Descrição**: Retorna um contato e sua respectiva pessoa com base no ID fornecido.

6. **Atualizar Contato**
   - **Endpoint**: `/api/contatos/{id}`
   - **Método HTTP**: `PUT`
   - **Controller**: `ContatoController::atualizar`
   - **Descrição**: Atualiza as informações de um contato específico.
   - **Payload**:
   ```json
   {
		"tipo": 0,
		"descricao": "4796969622",
		"idPessoa": 9
   }
   // Tipo 0 para telefone e tipo 1 para email.
   ```

7. **Deletar Contato**
   - **Endpoint**: `/api/contatos/{id}`
   - **Método HTTP**: `DELETE`
   - **Controller**: `ContatoController::deletar`
   - **Descrição**: Remove um contato do sistema com base no ID fornecido.

### Pessoas

1. **Criar Pessoa**
   - **Endpoint**: `/api/pessoas`
   - **Método HTTP**: `POST`
   - **Controller**: `PessoaController::criar`
   - **Descrição**: Cria uma nova pessoa.
   - **Payload**:
   ```json
   {
      "nome": "Maristela Santos",
      "cpf": "83736329059"
   }
   ```

2. **Listar Pessoas**
   - **Endpoint**: `/api/pessoas`
   - **Método HTTP**: `GET`
   - **Controller**: `PessoaController::listar`
   - **Descrição**: Retorna uma lista de todas as pessoas cadastradas.

3. **Obter Pessoa por ID**
   - **Endpoint**: `/api/pessoas/{id}`
   - **Método HTTP**: `GET`
   - **Controller**: `PessoaController::obter`
   - **Descrição**: Retorna uma pessoa específica com base no ID fornecido.

4. **Obter Pessoa com Contatos por ID**
   - **Endpoint**: `/api/pessoas/contatos/{id}`
   - **Método HTTP**: `GET`
   - **Controller**: `PessoaController::obterComContato`
   - **Descrição**: Retorna uma pessoa e seus contatos associados com base no ID fornecido.

5. **Atualizar Pessoa**
   - **Endpoint**: `/api/pessoas/{id}`
   - **Método HTTP**: `PUT`
   - **Controller**: `PessoaController::atualizar`
   - **Descrição**: Atualiza as informações de uma pessoa específica.
   - **Payload**:
   ```json
   {
      "nome": "Maristela Santos",
      "cpf": "83736329059"
   }
   ```

6. **Deletar Pessoa**
   - **Endpoint**: `/api/pessoas/{id}`
   - **Método HTTP**: `DELETE`
   - **Controller**: `PessoaController::deletar`
   - **Descrição**: Remove uma pessoa do sistema com base no ID fornecido.


![sistema_agenda](https://github.com/pierrialexandervidmar/sistema_agenda/blob/main/frontend/public/frontend-react.png)
