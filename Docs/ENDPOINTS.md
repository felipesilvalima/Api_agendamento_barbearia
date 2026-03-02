# 🔗 Endpoints da API

Este documento descreve todos os **endpoints disponíveis**, organizados por **recurso/domínio**, incluindo finalidade e métodos HTTP.

> Todas as rotas (exceto autenticação) exigem header:
>
> `Authorization: Bearer {token}`

---

## 🔐 Users / Autenticação

* `POST /login` → Autenticar usuário
* `POST /logout` → Encerrar sessão (invalidar token)
* `POST /refresh` → Renovar token JWT
* `GET /me` → Dados do usuário autenticado
* `PATCH /users/password` → Atualizar senha
* `DELETE /users/desativar` → Desativar conta

---

## 👤 Clientes

* `POST /clientes` → Criar cliente
* `GET /clientes/{id}` → Detalhes do cliente
* `PATCH /clientes/{id}` → Atualizar dados do cliente
* `GET /clientes/agendamentos` → Histórico de agendamentos

---

## 💈 Barbeiros

* `POST /barbeiros` → Criar barbeiro
* `GET /barbeiros/{id}` → Detalhes do barbeiro
* `PATCH /barbeiros/{id}` → Atualizar dados do barbeiro
* `GET /barbeiros/agendamentos` → Histórico de agendamentos

---

## 📅 Agendamentos

* `POST /agendamentos` → Criar agendamento
* `GET /agendamentos` → Listar agendamentos
* `GET /agendamentos/{id}` → Buscar agendamento
* `PATCH /agendamentos/{id}/reagendar` → Reagendar agendamento
* `PATCH /agendamentos/{id}/cancelar` → Cancelar agendamento
* `PATCH /agendamentos/{id}/finalizar` → Finalizar agendamento

---

## ✂️ Serviços

* `GET /servicos` → Listar serviços
* `GET /servicos/{id}` → Detalhes do serviço
* `POST /servicos` → Cadastrar serviço
* `PATCH /servicos/{id}` → Alterar serviço
* `DELETE /servicos/{id}/desativar` → Desativar serviço
* `GET /agendamentos/{id}/total` → Calcular preço total dos servicos de um agendamento

---

## 🔗 Serviços do Agendamento

* `GET /agendamentos/{id}/servicos` → Listar serviços do agendamento
* `POST /agendamentos/{id}/servicos/{servicoId}` → Adicionar um serviço ao agendamento
* `DELETE /agendamentos/{id}/servicos/{servicoId}` → Remover serviço

---

## 🔔 Notificações

* `GET /notificacoes` → Listar notificações do usuário
* `DELETE /notificacoes/{id}` → Deletar notificação

---

## 📌 Observações

* Endpoints seguem padrão REST
* Regras de autorização são aplicadas via Policies
* Validações são feitas via Form Requests
* Respostas seguem padrão JSON
