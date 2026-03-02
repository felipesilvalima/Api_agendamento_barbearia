# 🔐 Autenticação e Autorização

Este documento descreve como funciona a **autenticação** e a **autorização** da API de Agendamentos para Barbearia, utilizando **JWT (JSON Web Token)** e recursos nativos do Laravel.

---

## 🎯 Objetivo

* Garantir acesso seguro à API
* Manter autenticação stateless
* Controlar permissões por recurso
* Proteger dados sensíveis

---

## 🔑 Autenticação com JWT

A API utiliza **JWT (JSON Web Token)** para autenticação **stateless**.

### Características

* Não utiliza sessão
* Token assinado
* Enviado a cada requisição
* Expiração configurável

---

## 🔄 Fluxo de Autenticação

### 1️⃣ Login

```
POST /login
```

* Usuário envia credenciais
* API valida dados
* Token JWT é gerado

Resposta:

```json
{
  "access_token": "token",
  "token_type": "bearer",
  "expires_in": 120
}
```

---

### 2️⃣ Uso do Token

O token deve ser enviado em todas as rotas protegidas:

```
Authorization: Bearer {token}
```

---

### 3️⃣ Refresh Token

```
POST /refresh
```

* Gera um novo token
* Mantém o usuário autenticado

---

### 4️⃣ Logout

```
POST /logout
```

* Token atual é invalidado

---

## 🛡️ Middleware de Autenticação

A API utiliza middlewares para proteger rotas:

* `auth:api`
* `jwt.auth`

Exemplo:

```php
Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', ...);
});
```

---

## 🛂 Autorização

Além de autenticar, a API controla **o que o usuário pode fazer**.

---

## 🧩 Policies

Policies são usadas para garantir que o usuário só acesse recursos permitidos.

### Exemplos de regras

* Cliente acessa apenas seus agendamentos
* Barbeiro acessa apenas sua agenda
* Admin gerencia serviços

Exemplo:

```php
public function view(User $user, Agendamento $agendamento)
{
    return $user->id === $agendamento->user_id;
}
```

---

## 🧠 Separação de Responsabilidades

* **Middleware** → valida autenticação
* **Policy** → valida autorização
* **Service** → aplica regra de negócio

---

## 🔒 Boas Práticas

* Tokens com tempo de expiração
* HTTPS obrigatório
* Não expor dados sensíveis no token
* Rotas sensíveis sempre protegidas

---

## 📌 Conclusão

O uso de JWT combinado com Middleware e Policies garante segurança, controle de acesso e escalabilidade para a API.
