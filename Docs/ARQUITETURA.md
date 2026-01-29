# 🏗️ Arquitetura da API

Este documento descreve a **arquitetura em camadas** adotada na API de Agendamentos para Barbearia, bem como as **decisões técnicas** que guiaram o projeto.

---

## 🎯 Objetivos da Arquitetura

* Separação clara de responsabilidades
* Baixo acoplamento entre camadas
* Alta coesão
* Facilidade de manutenção
* Testabilidade
* Escalabilidade

---

## 📐 Arquitetura em Camadas

A API segue o seguinte fluxo:

```
Request HTTP
   ↓
Middleware (Auth / Permissões)
   ↓
Controller
   ↓
Service
   ↓
Repository (Interface → Implementação)
   ↓
Model (Eloquent)
```

Cada camada possui uma **responsabilidade única**, alinhada ao **Single Responsibility Principle**.

---

## 🧩 Camadas Detalhadas

### Controller

📌 Responsabilidades:

* Receber requisições HTTP
* Validar dados via Form Requests
* Converter dados para DTOs
* Delegar ações ao Service
* Retornar respostas

❌ Não contém regras de negócio

---

### Service

📌 Responsabilidades:

* Implementar regras de negócio
* Orquestrar processos
* Controlar fluxos
* Disparar Events e Jobs

📌 Exemplo:

> Criar agendamento → validar horário → persistir → disparar evento

---

### Repository

📌 Responsabilidades:

* Persistência de dados
* Isolar o Eloquent
* Facilitar mocks e testes

Utiliza abstração por **interfaces**, aplicando o **Dependency Inversion Principle**.

---

### Model

📌 Responsabilidades:

* Representar entidades
* Definir relacionamentos
* Casts e mutators

Evita conter regras de negócio complexas.

---

## 🔗 Injeção de Dependência

Os Services dependem de **interfaces**, não de implementações concretas.

Isso permite:

* Substituição de repositórios
* Testes isolados
* Evolução do sistema

---

## 📦 DTOs (Data Transfer Objects)

DTOs são utilizados para:

* Padronizar entrada e saída de dados
* Evitar arrays soltos
* Melhorar legibilidade
* Facilitar testes

---

## 🧠 Princípios Aplicados

### SOLID

* Single Responsibility Principle
* Dependency Inversion Principle

### Design Patterns

* Repository
* Service Layer
* DTO
* Observer (Events)

---

## 📌 Conclusão

A arquitetura foi projetada para suportar crescimento, mudanças de regra e novas funcionalidades sem comprometer a estabilidade do sistema.
