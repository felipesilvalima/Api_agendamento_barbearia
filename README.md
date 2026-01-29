# 🪒 API de Agendamentos para Barbearia

Esta API foi desenvolvida em **Laravel** seguindo uma **arquitetura em camadas**, com foco em **boas práticas**, **SOLID**, **Design Patterns**, **testabilidade**, **escalabilidade** e **baixo acoplamento**.

Ela gerencia **agendamentos**, **clientes**, **barbeiros**, **serviços**, **usuários autenticados**, **notificações** e **alertas automáticos**.

---

## 📐 Arquitetura em Camadas

A aplicação é organizada em camadas bem definidas:

```
Controller
   ↓
Service
   ↓
Repository (Interface → Implementação)
   ↓
Model (Eloquent)
```

### 🎯 Objetivo da arquitetura

* Separar responsabilidades
* Facilitar manutenção e testes
* Permitir evolução sem quebrar regras
* Evitar lógica no Controller

---

## 🧩 Camadas da Aplicação

### 1️⃣ Controller

📌 **Responsável por:**

* Receber requisições HTTP
* Validar entrada via Requests
* Chamar Services
* Retornar respostas padronizadas

❌ Não contém regra de negócio

```php
class AgendamentoController
{
    public function store(StoreAgendamentoRequest $request)
    {
        return $this->service->criar($request->dto());
    }
}
```

---

### 2️⃣ Service

📌 **Responsável por:**

* Regras de negócio
* Orquestração de processos
* Disparo de Events, Jobs e Notifications

```php
class AgendamentoService
{
    public function concluir(Agendamento $agendamento)
    {
        $agendamento->concluir();
        event(new StatusAlterado($agendamento));
    }
}
```

---

### 3️⃣ Repository (Abstração)

📌 **Responsável por:**

* Acesso a dados
* Isolar o Eloquent
* Facilitar troca de persistência

```php
interface AgendamentoRepositoryInterface
{
    public function criar(array $dados);
}
```

```php
class AgendamentoRepository implements AgendamentoRepositoryInterface
{
    public function criar(array $dados)
    {
        return Agendamento::create($dados);
    }
}
```

🔹 Aplicando **Dependency Inversion Principle**

---

### 4️⃣ Model

📌 **Responsável por:**

* Representar entidades
* Relacionamentos
* Mutators / Casts

```php
class Agendamento extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 🔐 Autenticação e Autorização

### JWT (JSON Web Token)

* Autenticação stateless
* Token enviado via header:

```
Authorization: Bearer {token}
```

### Middleware

* `auth:api`
* `jwt.auth`
* `permission`

```php
Route::middleware(['auth:api'])->group(function () {
    Route::post('/agendamentos', ...);
});
```

---

## 🛂 Policies (Autorização)

📌 Usadas para garantir acesso correto aos recursos

```php
public function view(User $user, Agendamento $agendamento)
{
    return $user->id === $agendamento->user_id;
}
```

---

## 🗂️ Recursos da API

A API expõe endpoints organizados por **domínio**, respeitando responsabilidades e permissões.

---

## 🔐 Users (Auth / Conta)

Responsável por autenticação, sessão e gestão da conta do usuário.

**Recursos:**

* `POST /login` → Login
* `POST /logout` → Logout
* `GET /me` → Dados do usuário autenticado
* `POST /refresh` → Renovar token JWT
* `PUT /users/password` → Atualizar senha
* `PATCH /users/deactivate` → Desativar conta

---

## 👤 Clientes

Representa o cliente final da barbearia.

**Recursos:**

* `POST /clientes` → Criar cliente
* `GET /clientes/{id}` → Detalhes do cliente
* `PUT /clientes/{id}` → Atualizar dados do cliente
* `GET /clientes/{id}/agendamentos` → Histórico de agendamentos

---

## 💈 Barbeiros

Representa os profissionais que realizam os serviços.

**Recursos:**

* `POST /barbeiros` → Criar barbeiro
* `GET /barbeiros/{id}` → Detalhes do barbeiro
* `PUT /barbeiros/{id}` → Atualizar dados do barbeiro
* `GET /barbeiros/{id}/agendamentos` → Histórico de agendamentos

---

## 📅 Agendamentos

Domínio central do sistema.

**Recursos:**

* `POST /agendamentos` → Criar agendamento
* `GET /agendamentos` → Listar agendamentos
* `GET /agendamentos/{id}` → Buscar agendamento
* `PATCH /agendamentos/{id}/reagendar` → Reagendar
* `PATCH /agendamentos/{id}/cancelar` → Cancelar
* `PATCH /agendamentos/{id}/finalizar` → Finalizar

---

## ✂️ Serviços

Serviços oferecidos pela barbearia.

**Recursos:**

* `GET /servicos` → Listar serviços
* `GET /servicos/{id}` → Detalhes do serviço
* `POST /servicos` → Cadastrar serviço
* `PUT /servicos/{id}` → Alterar serviço
* `PATCH /servicos/{id}/desativar` → Desativar serviço

---

## 🔗 Serviços do Agendamento

Relacionamento entre **Agendamento** e **Serviços**.

**Recursos:**

* `GET /agendamentos/{id}/servicos` → Listar serviços do agendamento
* `POST /agendamentos/{id}/servicos` → Adicionar serviço ao agendamento
* `DELETE /agendamentos/{id}/servicos/{servicoId}` → Remover serviço
* `GET /agendamentos/{id}/total` → Preço total do agendamento

---

## 🔔 Notificações

Gerenciamento de notificações do usuário.

**Recursos:**

* `GET /notificacoes` → Listar notificações
* `DELETE /notificacoes/{id}` → Deletar notificação

---

## 🔔 Sistema de Notificações

### Event → Listener → Notification

```
Service
  ↓
Event (StatusAlterado)
  ↓
Listener (EnviarNotificacaoStatus)
  ↓
Notification
```

📌 Exemplo:

> "Sempre que o status do agendamento mudar, avise o usuário"

---

### Channels

```php
public function via($notifiable)
{
    return ['mail', 'database'];
}
```

* `mail`: envio de email
* `database`: persistência
* `broadcast`: tempo real

---

## ⏱️ Scheduler (Alertas Automáticos)

📌 Responsável por tarefas baseadas em tempo

```php
$schedule->job(new AlertaAgendamentoJob)->everyMinute();
```

🔹 Executado via **cron**, não por requisição

---

## ⚙️ Jobs (Fila)

📌 Usados para:

* Emails
* Notificações
* Processos pesados

```php
class EnviarEmailJob implements ShouldQueue
```

✔ Executados por:

```bash
php artisan queue:work
```

---

## 📦 DTOs (Data Transfer Objects)

📌 Padronizam entrada e saída de dados

```php
class CriarAgendamentoDTO
{
    public function __construct(
        public int $userId,
        public string $data
    ) {}
}
```

✔ Facilita testes
✔ Evita arrays soltos

---

## ✅ Validação

### Form Requests

```php
class StoreAgendamentoRequest extends FormRequest
```

* Entrada validada
* Mensagens customizadas

---

## ❗ Exceptions Personalizadas

📌 Tratamento de regras inválidas

```php
throw new AgendamentoIndisponivelException();
```

Centralizadas no `Handler`

---

## 🧪 Testabilidade

* Services testáveis
* Repositories mockáveis
* DTOs previsíveis
* Baixo acoplamento

---

## 🧠 Princípios Aplicados

### SOLID

* ✅ Single Responsibility
* ✅ Dependency Inversion

### Design Patterns

* Repository
* Service Layer
* DTO
* Observer (Events)

---

## 🚀 Conclusão

Esta API foi projetada para:

* Crescer sem dor
* Suportar alto volume
* Ser fácil de manter
* Seguir padrões profissionais

💈 **Uma base sólida para sistemas de agendamento modernos**
