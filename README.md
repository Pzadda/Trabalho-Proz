# API - PHP
Este código PHP fornece uma API simples para gerenciar dados de usuários em um banco de dados. Suporta três métodos HTTP: `POST`, `GET` e `DELETE`. A API permite criar novos usuários, obter uma lista de usuários e excluir usuários por ID.

## Funcionalidades
* **Criar Usuário**: Permite adicionar um novo usuário com: nome, email, senha, telefone, endereço, estado e data de nascimento.
* **Obter Usuários**: Recupera uma lista de todos os usuários no banco de dados.
* **Excluir Usuário**: Exclui um usuário com base no ID fornecido.

## Requisitos
* PHP >= 7.0
* Conexão com banco de dados MySQL
* Arquivo `db.php` estabelece uma conexão válida com o banco de dados

## Endpoints da API
### 1. **POST**

Cria um novo usuário no banco de dados.

#### Corpo da Requisição
A requisição deve enviar um objeto JSON com os seguintes campos:

```json
{
    "name_": "João Silva",
    "email": "joao.silva@example.com",
    "password_": "Senha123!",
    "phone": "1234567890",
    "adress": "Rua Principal, 123, Cidade, Estado",
    "state": "Estado",
    "birthDate": "1990-01-01"
}
```

#### Validação
* A senha deve atender aos seguintes critérios:

  * Pelo menos 8 caracteres de comprimento
  * Conter pelo menos uma letra maiúscula
  * Conter pelo menos uma letra minúscula
  * Conter pelo menos um número
  * Conter pelo menos um caractere especial (`!@#$%^&*`)

#### Resposta
* **Sucesso (200)**

```json
{
    "message": "Cliente registrado com sucesso",
    "client": {
        "id": 1,
        "name_": "João Silva",
        "email": "joao.silva@example.com",
        "phone": "1234567890",
        "adress": "Rua Principal, 123, Cidade, Estado",
        "state": "Estado",
        "birthdate": "1990-01-01",
        "created": "2025-08-25 10:00:00"
    }
}
```

* **Erro (400)**
```json
{
    "error": "A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma letra minúscula, um número e um caractere especial"
}
```

* **Erro (400)**
```json
{
    "error": "Todos os campos são obrigatórios"
}
```

### 2. **GET** 
Recupera todos os usuários do banco de dados.

#### Resposta
* **Sucesso (200)**

```json
{
    "users": [
        {
            "id": 1,
            "name_": "João Silva",
            "email": "joao.silva@example.com",
            "phone": "1234567890",
            "adress": "Rua Principal, 123, Cidade, Estado",
            "state": "Estado",
            "birthdate": "1990-01-01",
            "created_at": "2025-08-25 10:00:00"
        },
        ...
    ]
}
```

* **Erro (404)**
```json
{
    "message": "Nenhum usuário encontrado"
}
```

* **Erro (500)**
```json
{
    "error": "Erro ao consultar os usuários: <mensagem_do_erro>"
}
```

### 3. **DELETE** 
Exclui um usuário com base no ID fornecido.

#### Corpo da Requisição
A requisição deve enviar um objeto JSON com o seguinte campo:

```json
{
    "id": 1
}
```

#### Resposta
* **Sucesso (200)**

```json
{
    "message": "Usuário com ID 1 excluído com sucesso"
}
```

* **Erro (404)**
```json
{
    "error": "Usuário com ID 1 não encontrado"
}
```

* **Erro (400)**
```json
{
    "error": "ID do usuário é obrigatório"
}
```

* **Erro (500)**
```json
{
    "error": "Falha ao excluir o usuário: <mensagem_do_erro>"
}
```

## Segurança
* As senhas são armazenadas de forma segura, utilizando a função `password_hash()` do PHP.