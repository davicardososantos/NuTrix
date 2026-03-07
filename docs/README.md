---
layout: default
title: NuTrix - Documentação de Desenvolvimento
---

# 📚 NuTrix - Documentação de Desenvolvimento

Bem-vindo à documentação completa do projeto NuTrix. Este projeto implementa **Clean Architecture**, **Domain-Driven Design (DDD)** e **SOLID Principles** para garantir escalabilidade e manutenibilidade.

---

## 🚀 Comece Aqui

### Para Usuários Novos no Projeto
1. **[Setup do Ambiente](docs/01-docker-setup.md)** - Configure Docker e banco de dados
2. **[Instalação do Breeze](docs/02-install-breeze.md)** - Configure autenticação
3. **[Referência Rápida de Arquitetura](ARCHITECTURE_QUICK_REFERENCE.md)** - Visão geral (5 min)
4. **[Conceitos de Arquitetura](docs/04-architecture-definitions.md)** - Detalhes completos

### Para Desenvolvedores Experimentados
1. **[Diretrizes do Copilot](copilot-instructions.md)** - Conceitos e convenções
2. **[Templates de Prompt](docs/03-prompt-templates.md)** - Como usar templates
3. **[Patterns da Arquitetura](docs/04-architecture-definitions.md)** - SOLID, DDD, Padrões

---

## 📖 Guias Completos

### 🏗️ Arquitetura (4 documentos)

| Documento | Leitura | Propósito |
|-----------|---------|----------|
| **[Referência Rápida](ARCHITECTURE_QUICK_REFERENCE.md)** | 5 min | Visão geral e checklist |
| **[Conceitos de Arquitetura](docs/04-architecture-definitions.md)** | 20 min | Definições detalhadas |
| **[Diretrizes do Copilot](copilot-instructions.md)** | 15 min | SOLID, DDD, padrões, convenções |
| **[Templates de Prompt](docs/03-prompt-templates.md)** | 10 min | Como usar templates |

### 📋 Desenvolvimento (3 templates)

| Template | Quando Usar | Link |
|----------|-------------|------|
| **Feature** | Implementar nova funcionalidade | [.github/prompts/feature.prompt.md](.github/prompts/feature.prompt.md) |
| **Bugfix** | Corrigir um bug | [.github/prompts/bugfix.prompt.md](.github/prompts/bugfix.prompt.md) |
| **Refactor** | Melhorar código existente | [.github/prompts/refactor.prompt.md](.github/prompts/refactor.prompt.md) |

### 🛠️ Setup e Instalação (2 documentos)

| Documento | Leitura | Propósito |
|-----------|---------|----------|
| **[Docker Setup](docs/01-docker-setup.md)** | 10 min | Configurar ambiente Docker |
| **[Breeze Installation](docs/02-install-breeze.md)** | 5 min | Instalar sistema de autenticação |

---

## 🎯 Fluxos por Cenário

### Cenário 1: Implementar Nova Feature

```
1. Ler: [Referência Rápida](ARCHITECTURE_QUICK_REFERENCE.md)
   └─ Entender camadas da arquitetura
   
2. Usar: [Template Feature](.github/prompts/feature.prompt.md)
   └─ Seguir passo a passo
   
3. Criar:
   ├─ Value Object em app/Domain/{Conceito}/ValueObjects/
   ├─ Domain Service em app/Domain/{Conceito}/Services/
   ├─ Form Request em app/Http/Requests/
   ├─ DTO em app/Application/Dto/
   ├─ ViewModel em app/Application/ViewModels/
   ├─ Controller
   ├─ Views
   └─ Rotas
   
4. Ler: [Conceitos de Arquitetura](docs/04-architecture-definitions.md)
   └─ Validar que padrões foram seguidos
   
5. Testar e Deploy ✅
```

### Cenário 2: Corrigir Um Bug

```
1. Usar: [Template Bugfix](.github/prompts/bugfix.prompt.md)
   └─ Seguir metodologia de debugging
   
2. Instrumentar código:
   ├─ Adicionar logs
   ├─ Usar Tinker para testes isolados
   └─ Inspecionar response
   
3. Rastrear por camada:
   ├─ Form Request
   ├─ Controller
   ├─ Domain Service
   ├─ Value Objects
   ├─ Models
   ├─ DTO
   ├─ ViewModel
   └─ View
   
4. Corrigir na camada identificada
   
5. Validar sem regredir ✅
```

### Cenário 3: Refatorar Código

```
1. Usar: [Template Refactor](.github/prompts/refactor.prompt.md)
   └─ Seguir ciclo de refatoração segura
   
2. Identificar padrão aplicável:
   ├─ Extrair Value Object
   ├─ Extrair Domain Service
   ├─ Extrair DTO
   ├─ Extrair ViewModel
   └─ Outro padrão
   
3. Implementar incrementalmente:
   ├─ Mudança 1 → Testar
   ├─ Mudança 2 → Testar
   ├─ Mudança 3 → Testar
   └─ Validar qualidade
   
4. Deploy com confiança ✅
```

---

## 📚 Estrutura de Documentação

```
RootProject/
├── ARCHITECTURE_QUICK_REFERENCE.md     ← Você está aqui!
├── copilot-instructions.md              ← Diretrizes Copilot
├── docs/
│   ├── 01-docker-setup.md              ← Setup Docker
│   ├── 02-install-breeze.md            ← Autenticação
│   ├── 03-prompt-templates.md          ← Uso de templates
│   └── 04-architecture-definitions.md  ← Definições detalhadas
├── .github/prompts/
│   ├── feature.prompt.md               ← Template: Feature
│   ├── bugfix.prompt.md                ← Template: Bug
│   └── refactor.prompt.md              ← Template: Refactor
└── app/
    ├── Domain/                         ← Lógica de domínio
    ├── Application/                    ← Casos de uso
    ├── Http/                           ← Web & Requests
    └── Models/                         ← Persistência
```

---

## 🔍 Índice por Conceito

### Conceitos SOLID
- Explicação detalhada → [copilot-instructions.md](copilot-instructions.md#-solid-principles)
- Aplicação prática → [docs/04-architecture-definitions.md](docs/04-architecture-definitions.md#-princípios-solid-aplicados)

### Domain-Driven Design (DDD)
- Explicação detalhada → [copilot-instructions.md](copilot-instructions.md#-domain-driven-design-ddd)
- Implementação → [docs/04-architecture-definitions.md](docs/04-architecture-definitions.md#-agregados-de-domínio-ddd)

### Design Patterns
- Lista completa → [copilot-instructions.md](copilot-instructions.md#-design-patterns)
- Aplicação sistemática → [docs/04-architecture-definitions.md](docs/04-architecture-definitions.md#-design-patterns-usados)

### Value Objects
- Conceito → [copilot-instructions.md](copilot-instructions.md#-domain-driven-design-ddd)
- Como criar → [copilot-instructions.md](copilot-instructions.md#quando-criar-um-value-object)
- Exemplo prático → [docs/03-prompt-templates.md](docs/03-prompt-templates.md#-quando-criar-um-value-object)

### Domain Services
- Conceito → [copilot-instructions.md](copilot-instructions.md#-domain-driven-design-ddd)
- Como criar → [copilot-instructions.md](copilot-instructions.md#quando-criar-um-domain-service)
- Exemplo prático → [docs/03-prompt-templates.md](docs/03-prompt-templates.md#-quando-criar-um-domain-service)

### DTOs
- Conceito → [copilot-instructions.md](copilot-instructions.md#-design-patterns)
- Como criar → [copilot-instructions.md](copilot-instructions.md#quando-criar-um-dto)
- Exemplo prático → [docs/03-prompt-templates.md](docs/03-prompt-templates.md#-quando-criar-um-dto)

### ViewModels
- Conceito → [copilot-instructions.md](copilot-instructions.md#-design-patterns)
- Como criar → [copilot-instructions.md](copilot-instructions.md#quando-usar-viewmodel)
- Exemplo prático → [docs/03-prompt-templates.md](docs/03-prompt-templates.md#-quando-usar-viewmodel)

---

## 🎓 Plano de Aprendizado Recomendado

### Iniciante (1-2 horas)
1. ✅ [Referência Rápida](ARCHITECTURE_QUICK_REFERENCE.md) - 5 min
2. ✅ [Docker Setup](docs/01-docker-setup.md) - 10 min
3. ✅ [Breeze Installation](docs/02-install-breeze.md) - 5 min
4. ✅ [Introdução aos Templates](docs/03-prompt-templates.md) - 10 min
5. ✅ [Value Objects](copilot-instructions.md#-domain-driven-design-ddd) - 15 min

### Intermediário (3-5 horas)
1. ✅ [Conceitos de Arquitetura Completos](docs/04-architecture-definitions.md) - 20 min
2. ✅ [Diretrizes Copilot](copilot-instructions.md) - 30 min
3. ✅ [Template Feature Completo](.github/prompts/feature.prompt.md) - 30 min
4. ✅ [Template Bugfix Completo](.github/prompts/bugfix.prompt.md) - 30 min
5. ✅ [Template Refactor Completo](.github/prompts/refactor.prompt.md) - 30 min

### Avançado (5+ horas)
1. ✅ Implementar 2-3 features novas
2. ✅ Corrigir 2-3 bugs complexos
3. ✅ Refatorar 2-3 seções de código
4. ✅ Escrever testes unitários
5. ✅ Contribuir com melhorias na arquitetura

---

## ❓ Perguntas Frequentes

### P: Por onde começo se sou novo?
**R:** Leia [Referência Rápida](ARCHITECTURE_QUICK_REFERENCE.md) (5 min), depois [Setup](docs/01-docker-setup.md).

### P: Como implemento uma nova feature?
**R:** Abra [Template Feature](.github/prompts/feature.prompt.md) e siga passo a passo.

### P: Como corrijo um bug?
**R:** Abra [Template Bugfix](.github/prompts/bugfix.prompt.md) e siga a metodologia de debugging.

### P: Qual é a estrutura de camadas?
**R:** Veja [Conceitos de Arquitetura](docs/04-architecture-definitions.md#-diagrama-da-arquitetura).

### P: O que é um Value Object?
**R:** Veja [DDD em copilot-instructions.md](copilot-instructions.md#-domain-driven-design-ddd).

### P: Como criei um Domain Service?
**R:** Veja [Quando Criar Domain Service](copilot-instructions.md#quando-criar-um-domain-service).

### P: Qual padrão devo usar?
**R:** Veja [Padrões de Código em copilot-instructions.md](copilot-instructions.md#-padrões-de-código).

---

## 📞 Suporte e Comunidade

- **Dúvidas sobre arquitetura**: Abra uma issue com tag `architecture`
- **Dúvidas sobre setup**: Consulte [docs/01](docs/01-docker-setup.md) e [docs/02](docs/02-install-breeze.md)
- **Sugestões de melhorias**: Abra uma discussion

---

## 📋 Checklist de Onboarding

- [ ] Ambiente Docker configurado ([docs/01](docs/01-docker-setup.md))
- [ ] Breeze instalado ([docs/02](docs/02-install-breeze.md))
- [ ] Leu [Referência Rápida](ARCHITECTURE_QUICK_REFERENCE.md)
- [ ] Entende as 4 camadas da arquitetura
- [ ] Conhece os 3 templates de prompt
- [ ] Leu [copilot-instructions.md](copilot-instructions.md)
- [ ] Implementou uma feature de teste
- [ ] Contribuiu com uma correção
- [ ] Refatorou um pedaço de código
- [ ] Sente-se confortável com a arquitetura

---

## 📈 Roadmap de Documentação

- ✅ Referência Rápida
- ✅ Conceitos de Arquitetura
- ✅ Diretrizes Copilot
- ✅ Templates de Prompt
- 📅 Guia de Testes (próxima)
- 📅 Guia de Performance (próxima)
- 📅 Guia de Segurança (próxima)

---

**Última atualização**: 2026-03-06  
**Versão**: 1.0  
**Status**: ✅ Completa e Funcionando  
**Mantido por**: Equipe de Desenvolvimento
