# 03 - Templates de Prompt para Desenvolvimento

## 📋 Introdução

Este documento descreve os templates de prompt disponíveis para agilizar o desenvolvimento do NuTrix. Cada template segue a arquitetura definida no projeto e encapsula as boas práticas.

**Localização dos templates**: `.github/prompts/`

---

## 🎯 Template 1: Implementar Feature (feature.prompt.md)

### Quando Usar
- Adicionando nova funcionalidade ao sistema
- Criando novo agregado de domínio
- Expandindo features existentes com novos conceitos

### O que o Template Inclui
1. **Identificação do Conceito de Domínio**
   - Ajuda a determinar se é necessário criar Value Object
   - Questiona se o conceito é imutável e auto-validável

2. **Encapsulamento de Lógica de Negócio**
   - Orienta sobre criação de Domain Service
   - Identifica quando a lógica deve ser compartilhada

3. **Validação e Autorização**
   - Padroniza criação de Form Request
   - Garante segurança desde o início

4. **Transferência de Dados**
   - Define quando criar DTOs
   - Automatiza transformação de dados

5. **Preparação para Apresentação**
   - Orienta criação de ViewModels
   - Evita lógica em Blade

6. **Estrutura de Arquivos Esperada**
   - Espelho exato da arquitetura do projeto
   - Facilita busca e manutenção

### Fluxo Recomendado

```
┌─────────────────────────────────────────┐
│ 1. Descrever a feature                  │
│    - Qual novo conceito?                │
├─────────────────────────────────────────┤
│ 2. Criar Value Object (se necessário)   │
│    - Validações e conversões            │
├─────────────────────────────────────────┤
│ 3. Criar Domain Service                 │
│    - Lógica de negócio                  │
├─────────────────────────────────────────┤
│ 4. Criar Form Request                   │
│    - Validação e autorização            │
├─────────────────────────────────────────┤
│ 5. Criar DTO                            │
│    - Transformação de dados             │
├─────────────────────────────────────────┤
│ 6. Criar ViewModel                      │
│    - Preparação para apresentação       │
├─────────────────────────────────────────┤
│ 7. Criar Controller                     │
│    - Orquestração HTTP                  │
├─────────────────────────────────────────┤
│ 8. Registrar rotas                      │
│    - Configurar endpoints               │
├─────────────────────────────────────────┤
│ 9. Criar views                          │
│    - Renderização simples               │
└─────────────────────────────────────────┘
```

### Exemplo Prático: Feature "Objetivo de Peso"

O template inclui um exemplo completo que mostra:
- Como criar `ObjectiveTarget` Value Object
- Como implementar `ObjectiveCalculationService`
- Como validar com `StoreObjectiveRequest`
- Como transformar com `ObjectiveDto`
- Como preparar com `ObjectiveProgressViewModel`
- Como orquestrar no Controller

**Tempo estimado**: 2-3 horas para feature média

---

## 🐛 Template 2: Corrigir Bug (bugfix.prompt.md)

### Quando Usar
- Investigando comportamento inesperado
- Corrigindo validações incorretas
- Resolvendo authorization errors
- Debugando transformação de dados

### O que o Template Inclui

1. **Metodologia de Rastreamento**
   - Direciona investigação pelo fluxo de dados
   - Ajuda a localizar a camada problemática

2. **Matriz de Debug por Camada**
   - Form Request: validação/autorização
   - Controller: orquestração/parâmetros
   - Domain Service: cálculos/lógica
   - DTO: transformação
   - ViewModel: preparação
   - Model: persistência
   - View: renderização

3. **Ferramentas de Debug**
   - Como adicionar logs estratégicos
   - Como usar Tinker para testes isolados
   - Como inspecionar responses

4. **Casos Comuns**
   - "Map key does not exist"
   - "Call to undefined method"
   - "Authorization denied"
   - "Validation failed"

### Fluxo de Debug

```
┌──────────────────────────────┐
│ 1. Descrever o problema      │
│    Sintoma, esperado, cenário│
├──────────────────────────────┤
│ 2. Identificar a camada      │
│    Onde está o bug?          │
├──────────────────────────────┤
│ 3. Instrumentar código       │
│    Adicionar logs/breakpoints│
├──────────────────────────────┤
│ 4. Coletar evidências        │
│    Logs, variables, outputs  │
├──────────────────────────────┤
│ 5. Formular hipótese         │
│    Qual é a causa raiz?      │
├──────────────────────────────┤
│ 6. Corrigir minimamente      │
│    Uma mudança                │
├──────────────────────────────┤
│ 7. Validar correção          │
│    Teste, regressor?         │
└──────────────────────────────┘
```

### Exemplo Incluído: "IMC aparece como null"

Mostra o processo completo:
1. Reprodução do problema
2. Testes isolados de cada camada
3. Identificação da causa (ViewModel não recebendo IMC)
4. Correção no controller
5. Validação de sucesso

**Tempo estimado**: 30 minutos para bug simples, até 2 horas para complexo

---

## ♻️ Template 3: Refatorar Código (refactor.prompt.md)

### Quando Usar
- Removendo duplicação de código
- Melhorando legibilidade
- Reorganizando responsabilidades
- Migrando código para novos padrões

### O que o Template Inclui

1. **Definição de Objetivo**
   - Por que refatorar?
   - Qual resultado esperado?

2. **Padrões de Refatoração Comuns**
   - Extrair Value Object
   - Extrair Domain Service
   - Extrair DTO
   - Extrair ViewModel

3. **Ciclo de Refatoração Segura**
   - Entender código atual
   - Criar testes
   - Refatorar incrementalmente
   - Validar a cada passo

4. **Anti-padrões em Refatoração**
   - Evitar refatoração "Big Bang"
   - Sempre refatorar incrementalmente

### Fluxo de Refatoração

```
ENTENDER código atual ──> TESTES existentes passam
         │
         ▼
   CRIAR testes novos
         │
         ▼
   REFATORAR incrementalmente (MUDANÇA POR MUDANÇA)
         │
         ├─> Mudança 1 ──> TESTAR ✓
         ├─> Mudança 2 ──> TESTAR ✓
         ├─> Mudança 3 ──> TESTAR ✓
         │
         ▼
   VALIDAR qualidade
         │
         ├─> Código mais legível?
         ├─> Sem duplicação?
         ├─> Responsabilidades separadas?
         │
         ▼
   DEPLOY com confiança
```

### Exemplo Incluído: "Extrair Value Object"

Mostra transformação de:
```php
// ❌ ANTES: Validação espalhada
if ($weight < 1 || $weight > 500) { ... }

// ✅ DEPOIS: Validação encapsulada
new WeightValue($weight);
```

Com o passo a passo completo de refatoração.

**Tempo estimado**: 1-2 horas por padrão aplicado

---

## 🎓 Como Usar os Templates

### Opção 1: Copilot Inline (Recomendado)
```
/feature Seu pedido aqui
/bugfix Descrição do bug
/refactor Código a refatorar
```

### Opção 2: Referência Manual
Abra `.github/prompts/{nome}.prompt.md` e siga os passos.

### Opção 3: Integração com Editor
Configure VS Code para carregar prompts automaticamente:
```json
// settings.json
"copilot.prompts.locations": [
    ".github/prompts"
]
```

---

## 📊 Matriz de Decisão

| Situação | Template | Tempo |
|----------|----------|-------|
| Adicionando feature nova | feature | 2-3h |
| Corrigindo bug | bugfix | 30m-2h |
| Limpando código | refactor | 1-2h |
| Um Value Object | feature (seção 3) | 30m |
| Um Domain Service | feature (seção 4) | 45m |
| Um DTO | feature (seção 5) | 30m |
| Extracting logic | refactor | 1h |

---

## 🔗 Relacionamento com copilot-instructions.md

- **copilot-instructions.md**: Explica *conceitos* (SOLID, DDD, padrões)
- **Este documento**: Mostra *como aplicar* usando templates
- **Templates de prompt**: Guiam passo a passo

**Fluxo recomendado**:
1. Entender conceito em `copilot-instructions.md`
2. Seguir passo a passo em template correspondente
3. Implementar conforme exemplo

---

## 📝 Customizando Templates

Os templates podem ser adaptados para o seu contexto:

```markdown
---
name: Implementar Feature (Customizado)
description: "Versão para features de relatórios específicos"
---

# Seções customizadas...
```

Copie e customize em `.github/prompts/seu-template.prompt.md`

---

## ✅ Checklist: Templates Implementados

- ✅ Feature template com exemplo completo
- ✅ Bugfix template com casos comuns
- ✅ Refactor template com padrões
- ✅ Documentação de uso
- ✅ Matriz de decisão
- ✅ Exemplos práticos

---

**Versão**: 1.0  
**Última atualização**: 2026-03-06  
**Próximas melhorias**: Templates para testes, templates para performance
