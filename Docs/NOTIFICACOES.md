# 🔔 Sistema de Notificações

Este documento descreve o funcionamento do **sistema de notificações** da API de Agendamentos para Barbearia, utilizando recursos nativos do Laravel como **Events, Listeners, Jobs, Scheduler e Notifications**.

---

## 🎯 Objetivo

* Notificar usuários sobre eventos importantes
* Desacoplar regras de negócio de efeitos colaterais
* Garantir performance com processamento assíncrono

---

## 🧩 Componentes do Sistema

### Event

📌 **O que é:**

* Representa algo que aconteceu no domínio

📌 **Responsabilidade:**

* Carregar o contexto do acontecimento
* Não executa lógica

📌 **Exemplo:**

> Status do agendamento foi alterado

---

### Listener

📌 **O que é:**

* Reage ao Event

📌 **Responsabilidade:**

* Decidir o que fazer quando o evento ocorre
* Chamar Notifications ou Jobs

📌 **Quando usar:**

* Quando um evento pode gerar múltiplos efeitos

---

### Notification

📌 **O que é:**

* Representa a mensagem enviada ao usuário

📌 **Responsabilidade:**

* Definir conteúdo
* Definir canais (mail, database, broadcast)

📌 **Exemplo de canais:**

```php
public function via($notifiable)
{
    return ['mail', 'database'];
}
```

---

### Job

📌 **O que é:**

* Tarefa assíncrona

📌 **Responsabilidade:**

* Executar tarefas pesadas
* Evitar lentidão no request

📌 **Exemplos:**

* Envio de email
* Processamento de notificações

---

### Scheduler

📌 **O que é:**

* Agendador de tarefas baseadas em tempo

📌 **Responsabilidade:**

* Disparar Jobs periodicamente

📌 **Exemplo:**

```php
$schedule->job(new AlertaAgendamentoJob)->everyMinute();
```

---

## 🔄 Fluxos Comuns

### Mudança de Status

```
Service → Event → Listener → Notification
```

### Alerta Automático

```
Scheduler → Job → Notification
```

---

## ⚙️ Execução Assíncrona

* Jobs e Notifications podem implementar `ShouldQueue`
* Requer worker rodando:

```bash
php artisan queue:work
```

---

## 📌 Boas Práticas

* Não colocar lógica pesada em Listeners
* Usar Jobs para emails
* Usar Events para desacoplamento
* Centralizar mensagens em Notifications
* Usar múltiplos canais quando necessário

---

## 🧠 Conclusão

O sistema de notificações foi projetado para ser escalável, desacoplado e eficiente, garantindo boa experiência ao usuário e facilidade de manutenção.
