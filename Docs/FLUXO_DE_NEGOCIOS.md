# ⚡ Fluxos de Negócio

Este documento descreve os principais **fluxos de negócio** da API de Agendamentos para Barbearia, detalhando **como os processos ocorrem entre camadas e recursos**.

---

## 1️⃣ Mudança de Status de Agendamento

**Objetivo:** Notificar cliente e/ou barbeiro sobre a alteração de status.

**Fluxo:**

```
Controller → Service → Event(StatusAlterado)
           ↓
           Listener(EnviarNotificacaoStatus)
           ↓
           Notification(mail | database | broadcast)
```

**Explicação:**

* Service atualiza o status do agendamento
* Event dispara o Listener
* Listener envia a Notification
* Notification pode usar múltiplos canais (mail, database, broadcast)

**Observação:**

* Notificações assíncronas podem ser gerenciadas via **Jobs / Queue**

---

## 2️⃣ Troca de Senha

**Objetivo:** Permitir ao usuário atualizar sua senha com segurança.

**Fluxo:**

```
Controller → FormRequest → Service → Job(EnviarEmailConfirmacao) → Notification
```

**Explicação:**

* FormRequest valida dados de entrada
* Service aplica regra de negócio (hash da senha, persistência)
* Job envia email de confirmação de alteração
* Notification registra evento no banco ou envia email

**Observação:**

* O Job permite execução assíncrona para não travar o request

---

## 3️⃣ Alerta de Horário

**Objetivo:** Alertar usuário sobre agendamentos próximos.

**Fluxo:**

```
Scheduler → Job(AlertaAgendamento) → Notification(mail | database | broadcast)
```

**Explicação:**

* Scheduler roda periodicamente (ex: everyMinute)
* Job verifica agendamentos futuros em um intervalo específico
* Job dispara Notification para cada usuário

**Observação:**

* Scheduler não depende de requisição HTTP
* Jobs podem ser enfileirados e executados por workers

---

## 📌 Observações Gerais

* Todos os fluxos usam **Separation of Concerns**: Controller não processa regra de negócio pesada
* **Event → Listener** desacopla lógica de notificação
* **Jobs** garantem tarefas assíncronas
* **Notifications** padronizam envio de alertas para múltiplos canais
* **Policies** garantem que apenas usuários autorizados executem ações
