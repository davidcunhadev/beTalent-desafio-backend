<h1>Desafio Técnico Backend BeTalent</h1>

## Descrição
<p>O objetivo deste desafio consiste em estruturar uma API RESTful conectada a um banco de dados. Trata-se de um sistema que permite cadastrar usuários externos. Ao realizarem login, estes usuários podem registrar clientes, produtos e vendas.</p>

## Pré-requisitos
- Estruturar o sistema observando o MVC (porém, sem as views);
- Usar MySQL como banco de dados;
- Respostas devem ser em JSON;

## Tecnologias Utilizadas
![Laravel](https://img.shields.io/badge/Laravel-v10-FF2D20?style=for-the-badge&logo=laravel&logoColor=FF4A4A)
![MySQL](https://img.shields.io/badge/MySQL-73618F?style=for-the-badge&logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=JSON%20web%20tokens)
![Eloquent](https://img.shields.io/badge/eloquent-ff5733?style=for-the-badge&color=FE2D20)

<hr>

## 🎲 Banco de Dados

<p>O banco de dados está estruturado da seguinte maneira:</p>

- usuários: id, email, password;
- clientes: id, name, cpf;
- endereço: id, client_id, street, number, complement, city, state, zip_code;
- telefones: id, client_id, phone_number;
- produtos: id, nome, description, price, quantity, rating, image_url;
- vendas: id, client_id, product_id, quantity, unit_price, total_price, sale_date;

<br>

## ✨ Funcionalidades
- Cadastro de usuário do sistema.
- Login com JWT de usuário cadastrado.
- Listagem de todos os clientes cadastrados de maneira ordenada e paginada.
- Detalhes de um(a) cliente e vendas relacionado à ele(a) com possibilidade de filtrar as vendas por mês + ano.
- Adicionar um(a) cliente.
- Editar um(a) cliente.
- Excluir um(a) cliente e vendas relacionado à ele(a).
- Listagem de todos os produtos cadastrados de maneira ordenada e paginada.
- Detalhes de um produto.
- Adicionar um produto.
- Editar um produto.
- Exclusão lógica ("soft delete") de um produto.
- Registro de venda de 1 produto a 1 cliente.

<p><strong>Observação</strong>: as rotas de clientes, produtos e vendas são apenas acessadas por usuário <strong>autenticado</strong>.</p>

<br>

## ⚙️ Executando a aplicação

Para executar o projeto localmente, siga os passos abaixo:

### Instalação

1. Clone o repositório:

```
 git@github.com:davidcunhadev/teste-tecnico-backend-betalent.git
```

2. Vá para a pasta do projeto:

```
cd teste-tecnico-backend-betalent
```

3. Instale as dependências do projeto:
```
composer install
```

4. Suba os containers do projeto com o comando:
```
sail up -d
```

5. Rode o seguinte comando para subir a aplicação no ar:
```
sail artisan serve
```

6. Após isso, você poderá fazer as requisições seguindo os passos da seção logo abaixo.

<br>

## 📑 Documentação da API

<details>
<summary><strong>Funcionalidades dos usuários em rotas públicas.</strong></summary>

- #### Logar na rota /api/user/login

<code>POST</code> <code>/api/user/login</code>

| Parâmetros Body   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `email` | `string` | **Obrigatório** -> Email da sua conta |
| `password` | `string` | **Obrigatório** -> Senha da sua conta |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "access_token": eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE3MjEwNzY3NDUsImV4cCI6MTcyMTA4MDM0NSwibmJmIjoxNzIxMDc2NzQ1LCJqdGkiOiIwMElOb001clB0blBPWHBWIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.WnXO02SzAq2dVbAv7HqTpYEHjGgfyT0Kv_mRZVO2C5c",
      "token_type": "bearer",
      "expires_in": 3600
    }


- #### Criar conta na rota /api/user/register

<code>POST</code> <code>/api/user/register</code>


| Parâmetros Body   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `email` | `string` | **Obrigatório** -> Email da sua conta |
| `password` | `string` | **Obrigatório** -> Senha da sua conta |

#### Exemplo de retorno

<p>Status: 201 Created</p>
    
    {
      "message": "User created successfully!"
    }
    
</details>

<hr>

<details>
<summary><strong>Funcionalidades dos usuários em rotas autenticadas.</strong></summary>

- #### Obter informações do usuário na rota /api/auth/user/me

<code>GET</code> <code>/api/auth/user/me</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "id": "1",
      "email": "test@hotmail.com",
      "created_at": "2024-07-15T23:49:44.000000Z",
      "updated_at": "2024-07-15T23:49:44.000000Z"
    }

- #### Atualizar token do usuário logado na rota /api/auth/user/refresh

<code>POST</code> <code>/api/auth/user/refresh</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvdXNlci9yZWZyZXNoIiwiaWF0IjoxNzIxMDg5MDM3LCJleHAiOjE3MjEwOTMyNTYsIm5iZiI6MTcyMTA4OTY1NiwianRpIjoiaEtTbXoyNnZKcndsbTBDbiIsInN1YiI6IjIiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.kPZgjVsJx9qZJYb_sB4b-BDfCqshMHLErM9kIEkhRwg",
      "token_type": "bearer",
      "expires_in": 3600
    }

- #### Deslogar usuário na rota /api/auth/user/logout

<code>POST</code> <code>/api/auth/user/logout</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "message": "Successfully logged out"
    }
    
</details>

<hr>

<details>
<summary><strong>Funcionalidades dos produtos.</strong></summary>

- #### Listar todos os produtos ordenados alfabeticamente na rota /api/auth/products/

<code>GET</code> <code>/api/auth/products/</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "name": "Iphone 12",
          "description": "Produto em ótimo estado de uso!",
          "price": "2199.99",
          "quantity": 10,
          "rating": "0.0",
          "image_url": null,
          "deleted_at": null,
          "created_at": "2024-07-15T20:53:50.000000Z",
          "updated_at": "2024-07-15T21:11:48.000000Z"
        },
        {
          "id": 2,
          "name": "PlayStation 5",
          "description": "Aparelho seminovo.",
          "price": "3499.99",
          "quantity": 7,
          "rating": "0.0",
          "image_url": null,
          "deleted_at": null,
          "created_at": "2024-07-15T20:07:53.000000Z",
          "updated_at": "2024-07-15T20:09:17.000000Z"
        },
        {
          "id": 3,
          "name": "Samsung S24",
          "description": "O Samsung do ano!",
          "price": "4999.99",
          "quantity": 15,
          "rating": "5.0",
          "image_url": null,
          "deleted_at": null,
          "created_at": "2024-07-15T19:54:40.000000Z",
          "updated_at": "2024-07-15T21:06:23.000000Z"
        }
      ],
      "first_page_url": "http://127.0.0.1:8000/api/auth/product?page=1",
      "from": 1,
      "last_page": 1,
      "last_page_url": "http://127.0.0.1:8000/api/auth/product?page=1",
      "links": [
        {
          "url": null,
          "label": "&laquo; Previous",
          "active": false
        },
        {
          "url": "http://127.0.0.1:8000/api/auth/product?page=1",
          "label": "1",
          "active": true
        },
        {
          "url": null,
          "label": "Next &raquo;",
          "active": false
        }
      ],
      "next_page_url": null,
      "path": "http://127.0.0.1:8000/api/auth/product",
      "per_page": 10,
      "prev_page_url": null,
      "to": 3,
      "total": 3
    }

- #### Registrar produto na rota /api/auth/products/register

<code>POST</code> <code>/api/auth/products/register</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetros Body   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Obrigatório** ->  Nome do produto |
| `description` | `number` | **Não Obrigatório** -> Descrição do produto |
| `price` | `number` | **Obrigatório** -> Preço do produto |
| `quantity` | `number` | **Não Obrigatório** -> Quantidade do produto |
| `image_url` | `string` | **Não Obrigatório** -> Imagem do produto |

#### Exemplo de retorno

<p>Status: 201 Created</p>

    {
      "message": "Product created successfully!"
    }

- #### Detalhes de um produto na rota /api/auth/products/id

<code>GET</code> <code>/api/auth/products/id</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do produto a ser detalhado |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "id": 1,
      "name": "Iphone 12",
      "description": "Produto em ótimo estado de uso!",
      "price": "2199.99",
      "quantity": 10,
      "rating": "0.0",
      "image_url": null,
      "deleted_at": null,
      "created_at": "2024-07-15T20:53:50.000000Z",
      "updated_at": "2024-07-15T21:11:48.000000Z"
    }

- #### Edição de um produto na rota /api/auth/products/update/id

<code>PUT</code> <code>/api/auth/products/update/id</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do produto a ser editado |

| Parâmetros Body   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Não Obrigatório** ->  Nome do produto |
| `description` | `number` | **Não Obrigatório** -> Descrição do produto |
| `price` | `number` | **Não Obrigatório** -> Preço do produto |
| `quantity` | `number` | **Não Obrigatório** -> Quantidade do produto |
| `rating` | `number` | **Não Obrigatório** -> Avaliação do produto |
| `image_url` | `string` | **Não Obrigatório** -> Imagem do produto |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "message": "Product updated successfully!"
    }

- #### Exclusão de um produto na rota /api/auth/products/delete/id

<code>DELETE</code> <code>/api/auth/products/delete/id</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do produto a ser excluído |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "message": "Product deleted successfully!"
    }

- #### Restauração de um produto previamente excluído na rota /api/auth/products/restore/id

<code>PATCH</code> <code>/api/auth/products/restore/id</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do produto a ser restaurado |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "message": "Product restored successfully!"
    }
    
</details>

<hr>

<details>
<summary><strong>Funcionalidades dos clientes.</strong></summary>

- #### Listar todos os clientes ordenados por ID na rota /api/auth/clients/

<code>GET</code> <code>/api/auth/clients/</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "name": "Silvio",
          "cpf": "09876543100",
          "created_at": "2024-07-15T21:08:42.000000Z",
          "updated_at": "2024-07-15T21:21:06.000000Z",
          "addresses": [
            {
              "id": 1,
              "street": "João Cabral",
              "number": "23",
              "complement": "Casa rosa",
              "city": "São José Dos Campos",
              "state": "São Paulo",
              "zip_code": "43346145",
              "created_at": "2024-07-15T21:08:42.000000Z",
              "updated_at": "2024-07-15T21:21:06.000000Z"
            }
          ],
          "phones": [
            {
              "id": 1,
              "phone_number": "47991152825",
              "created_at": "2024-07-15T21:08:42.000000Z",
              "updated_at": "2024-07-15T21:21:06.000000Z"
            }
          ]
        },
        {
          "id": 2,
          "name": "Fernando",
          "cpf": "14657689085",
          "created_at": "2024-07-16T01:16:32.000000Z",
          "updated_at": "2024-07-16T01:16:32.000000Z",
          "addresses": [
            {
              "id": 2,
              "street": "José Fischer",
              "number": "862",
              "complement": "Apto 101",
              "city": "Guabiruba",
              "state": "Santa Catarina",
              "zip_code": "12345679",
              "created_at": "2024-07-16T01:16:32.000000Z",
              "updated_at": "2024-07-16T01:16:32.000000Z"
            }
          ],
          "phones": [
            {
              "id": 2,
              "phone_number": "12345678912",
              "created_at": "2024-07-16T01:16:32.000000Z",
              "updated_at": "2024-07-16T01:16:32.000000Z"
            }
          ]
        }
      ],
      "first_page_url": "http://127.0.0.1:8000/api/auth/client?page=1",
      "from": 1,
      "last_page": 1,
      "last_page_url": "http://127.0.0.1:8000/api/auth/client?page=1",
      "links": [
        {
          "url": null,
          "label": "&laquo; Previous",
          "active": false
        },
        {
          "url": "http://127.0.0.1:8000/api/auth/client?page=1",
          "label": "1",
          "active": true
        },
        {
          "url": null,
          "label": "Next &raquo;",
          "active": false
        }
      ],
      "next_page_url": null,
      "path": "http://127.0.0.1:8000/api/auth/client",
      "per_page": 10,
      "prev_page_url": null,
      "to": 2,
      "total": 2
    }

- #### Detalhes de um(a) cliente e vendas mais recentes relacionados à ele(a) na rota /api/auth/clients/id

<code>GET</code> <code>/api/auth/clients/id</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do cliente a ser detalhado |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "id": 1,
      "name": "Silvio",
      "cpf": "09876543100",
      "created_at": "2024-07-15T21:08:42.000000Z",
      "updated_at": "2024-07-15T21:21:06.000000Z",
      "sales": [
        {
          "id": 1,
          "client_id": 1,
          "product_id": 1,
          "quantity": 1,
          "unit_price": "2199.99",
          "total_price": "2199.99",
          "sale_date": "2024-07-16 22:49:32",
          "created_at": "2024-07-16T22:49:32.000000Z",
          "updated_at": "2024-07-16T22:49:32.000000Z"
        },
        {
          "id": 2,
          "client_id": 1,
          "product_id": 3,
          "quantity": 3,
          "unit_price": "4999.99",
          "total_price": "14999,97",
          "sale_date": "2024-07-16 22:49:17",
          "created_at": "2024-07-16T22:49:17.000000Z",
          "updated_at": "2024-07-16T22:49:17.000000Z"
        }
      ]
    }

- #### Detalhes de um(a) cliente e vendas filtradas por mês/ano relacionados à ele(a) na rota /api/auth/clients/id/sales

<code>GET</code> <code>/api/auth/clients/id/sales</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do cliente a ser detalhado |

| Parâmetro via Query   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `year` | `string` | **Não Obrigatório** ->  Ano da venda |
| `month` | `string` | **Não Obrigatório** ->  Mês da venda |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "id": 1,
      "name": "Silvio",
      "cpf": "09876543100",
      "created_at": "2024-07-15T21:08:42.000000Z",
      "updated_at": "2024-07-15T21:21:06.000000Z",
      "sales": [
        {
          "id": 1,
          "client_id": 1,
          "product_id": 1,
          "quantity": 1,
          "unit_price": "2199.99",
          "total_price": "2199.99",
          "sale_date": "2024-07-16 22:49:32",
          "created_at": "2024-07-16T22:49:32.000000Z",
          "updated_at": "2024-07-16T22:49:32.000000Z"
        },
        {
          "id": 2,
          "client_id": 1,
          "product_id": 3,
          "quantity": 3,
          "unit_price": "4999.99",
          "total_price": "14999,97",
          "sale_date": "2024-07-16 22:49:17",
          "created_at": "2024-07-16T22:49:17.000000Z",
          "updated_at": "2024-07-16T22:49:17.000000Z"
        }
      ]
    }

- #### Registrar um(a) cliente na rota /api/auth/clients/register

<code>POST</code> <code>/api/auth/clients/register</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Body   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Obrigatório** ->  Nome do cliente |
| `cpf` | `string` | **Obrigatório** ->  CPF do cliente |
| `street` | `string` | **Obrigatório** ->  Rua do cliente |
| `complement` | `string` | **Não Obrigatório** ->  Complemento do cliente |
| `number` | `string` | **Obrigatório** ->  Número Residencial do cliente |
| `city` | `string` | **Obrigatório** ->  Cidade do cliente |
| `state` | `string` | **Obrigatório** ->  Estado do cliente |
| `zip_code` | `string` | **Obrigatório** ->  CEP do cliente |
| `phone_number` | `string` | **Obrigatório** ->  Número de Telefone do cliente |

#### Exemplo de retorno

<p>Status: 201 Created</p>

    {
      "message": "Client created successfully!"
    }

- #### Editar um(a) cliente na rota /api/auth/clients/update/id

<code>PUT</code> <code>/api/auth/clients/update/id</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do cliente a ser editado |

| Parâmetro via Body   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Não Obrigatório** ->  Nome do cliente |
| `cpf` | `string` | **Não Obrigatório** ->  CPF do cliente |
| `street` | `string` | **Não Obrigatório** ->  Rua do cliente |
| `complement` | `string` | **Não Obrigatório** ->  Complemento do cliente |
| `number` | `string` | **Não Obrigatório** ->  Número Residencial do cliente |
| `city` | `string` | **Não Obrigatório** ->  Cidade do cliente |
| `state` | `string` | **Não Obrigatório** ->  Estado do cliente |
| `zip_code` | `string` | **Não Obrigatório** ->  CEP do cliente |
| `phone_number` | `string` | **Não Obrigatório** ->  Número de Telefone do cliente |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "message": "Client updated successfully!"
    }

- #### Excluir um(a) cliente na rota /api/auth/clients/delete/id

<code>DELETE</code> <code>/api/auth/clients/delete/id</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetro via Request   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | **Obrigatório** ->  ID do cliente a ser excluído |

#### Exemplo de retorno

<p>Status: 200 OK</p>

    {
      "message": "Client deleted successfully!"
    }
    
</details>

<hr>

<details>
<summary><strong>Funcionalidade das vendas.</strong></summary>

- #### Registrar uma venda na rota /api/auth/sales/register

<code>POST</code> <code>/api/auth/sales/register</code>

| Headers   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `Accept` | `application/json` | **Obrigatório** ->  Tipos de mídia a processar e receber como resposta |
| `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
| `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

| Parâmetros Body   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `client_id` | `string` | **Obrigatório** -> ID do cliente comprador |
| `product_id` | `string` | **Obrigatório** -> ID do produto a ser comprado |
| `quantity` | `string` | **Obrigatório** -> Quantidade do produto a ser comprado |

#### Exemplo de retorno

<p>Status: 201 Created</p>

    {
      "message": "Sale registered successfully!"
    }
    
</details>

<br>

## Autor

David Luís da Cunha

<hr>

## 📫 Contato
[![Linkedin](https://img.shields.io/badge/linkedin-%230077B5.svg?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/davidlcunha/)
[![Email](https://img.shields.io/badge/Microsoft_Outlook-0078D4?style=for-the-badge&logo=microsoft-outlook&logoColor=white)](mailto:contatodavidcunha@hotmail.com)
