# 🏗️ Arquitetura Refatorada - Referência Rápida

## ✅ O Que Foi Criado

### 1. **Estrutura de Diretórios (Clean Architecture)**

```
app/
├── Domain/                             # ← Lógica de negócio pura
│   ├── Weight/
│   │   ├── ValueObjects/
│   │   │   ├── WeightValue.php
│   │   │   ├── Height.php
│   │   │   ├── IMCValue.php
│   │   │   └── IMCClassification.php
│   │   └── Services/
│   │       └── WeightCalculationService.php
│   ├── Water/
│   │   ├── ValueObjects/
│   │   │   └── WaterVolume.php
│   │   └── Services/
│   │       └── WaterHydrationService.php
│   └── Monitoring/
│       └── Services/
│           └── MonitoringService.php
│
├── Application/                        # ← Casos de uso
│   ├── Dto/
│   │   ├── Weight/
│   │   │   ├── WeightEntryDto.php
│   │   │   └── IMCDto.php
│   │   └── Water/
│   │       └── WaterConsumptionDto.php
│   └── ViewModels/
│       ├── ChartPointsViewModel.php
│       └── MonitoringDashboardViewModel.php
│
└── Http/
    └── Requests/                       # ← Validação centralizada
        ├── StoreWeightEntryRequest.php
        ├── UpdateWeightEntryRequest.php
        ├── StoreWaterConsumptionRequest.php
        ├── UpdateWaterConsumptionRequest.php
        ├── StorePatientRequest.php
        └── UpdatePatientRequest.php
```

### 2. **Conceitos Implementados**

#### 🎯 **SOLID Principles**
- ✅ **S**ingle Responsibility: Cada classe tem uma responsabilidade
- ✅ **O**pen/Closed: Código aberto para extensão, fechado para modificação
- ✅ **L**iskov Substitution: Subclasses substituem superclasses
- ✅ **I**nterface Segregation: Interfaces específicas
- ✅ **D**ependency Inversion: Injeção de dependências

#### 🏛️ **Domain-Driven Design (DDD)**
- ✅ **Value Objects**: WeightValue, Height, WaterVolume, IMCValue, IMCClassification
- ✅ **Domain Services**: WeightCalculationService, WaterHydrationService, MonitoringService
- ✅ **Aggregates**: Weight, Water, Monitoring

#### 🎨 **Design Patterns**
- ✅ **Service Layer**: Services encapsulam lógica
- ✅ **Repository**: Models abstraem acesso a dados
- ✅ **DTO**: Transferência de dados sem lógica
- ✅ **ViewModel**: Preparação para apresentação
- ✅ **Factory**: Métodos `from()` criam objetos
- ✅ **Value Object**: Imutáveis com validação

#### 📐 **Clean Architecture**
- ✅ **Domain Layer**: Independente de frameworks
- ✅ **Application Layer**: Casos de uso
- ✅ **HTTP Layer**: Orquestração web
- ✅ **Persistence Layer**: Acesso a dados

### 3. **Arquivos de Documentação**

```
.github/
└── prompts/                          # ← Templates de desenvolvimento
    ├── feature.prompt.md              # Template: Implementar Feature
    ├── bugfix.prompt.md               # Template: Corrigir Bug
    └── refactor.prompt.md             # Template: Refatorar Código

docs/
├── 01-docker-setup.md                # Existente
├── 02-install-breeze.md              # Existente
├── 03-prompt-templates.md            # ✨ NOVO
└── 04-architecture-definitions.md    # ✨ NOVO

copilot-instructions.md               # ✨ NOVO - Diretrizes Copilot
```

---

## 📚 Como Usar

### Para Implementar Nova Feature
```bash
# No VS Code, digite:
/feature Adicionar objetivo de peso
```
Ou abra: `.github/prompts/feature.prompt.md`

### Para Corrigir Um Bug
```bash
# No VS Code, digite:
/bugfix IMC está passando null
```
Ou abra: `.github/prompts/bugfix.prompt.md`

### Para Refatorar Código
```bash
# No VS Code, digite:
/refactor Extrair lógica do controller
```
Ou abra: `.github/prompts/refactor.prompt.md`

### Para Entender Arquitetura
Abra: `docs/04-architecture-definitions.md`

### Para Entender Conceitos
Abra: `copilot-instructions.md`

---

## 🎓 Exemplo: Fluxo Completo

### Scenario: Implementar feature "Objetivo de Peso"

#### 1️⃣ Criar Value Object
```php
// app/Domain/Objective/ValueObjects/ObjectiveTarget.php
class ObjectiveTarget {
    public function __construct(
        private readonly float $targetWeight,
        private readonly Carbon $targetDate,
    ) {}
}
```

#### 2️⃣ Criar Domain Service
```php
// app/Domain/Objective/Services/ObjectiveCalculationService.php
class ObjectiveCalculationService {
    public function calculateProgress(ObjectiveTarget $target): int {
        // Lógica de cálculo
    }
}
```

#### 3️⃣ Criar Form Request
```php
// app/Http/Requests/StoreObjectiveRequest.php
class StoreObjectiveRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->user()->hasRole('patient');
    }
    
    public function rules(): array {
        return [
            'target_weight' => 'required|numeric|min:1|max:500',
            'target_date' => 'required|date|after:today',
        ];
    }
}
```

#### 4️⃣ Criar DTO
```php
// app/Application/Dto/Objective/ObjectiveDto.php
class ObjectiveDto {
    public static function from(Objective $objective): self {
        return new self(...);
    }
}
```

#### 5️⃣ Criar ViewModel
```php
// app/Application/ViewModels/ObjectiveProgressViewModel.php
class ObjectiveProgressViewModel {
    public function progressPercentage(): int {
        // Cálculo
    }
}
```

#### 6️⃣ Criar Controller
```php
// app/Http/Controllers/ObjectiveController.php
public function store(StoreObjectiveRequest $request) {
    $objective = auth()->user()->objectives()->create(
        $request->validated()
    );
    return redirect()->route('objectives.show', $objective);
}
```

#### 7️⃣ Registrar Rotas
```php
// routes/web.php
Route::resource('objectives', ObjectiveController::class);
```

#### 8️⃣ Criar Views
```blade
<!-- resources/views/objectives/create.blade.php -->
<form action="{{ route('objectives.store') }}" method="POST">
    @csrf
    <!-- Form -->
</form>
```

---

## 🔄 Fluxo de Dados

```
REQUEST
  ↓
Form Request (Validação)
  ↓
Controller (Orquestração)
  ↓
Domain Service (Lógica)
  ↓
Value Objects (Conceitos)
  ↓
Models (Persistência)
  ↓
DTO (Transformação)
  ↓
ViewModel (Preparação)
  ↓
View (Renderização)
  ↓
RESPONSE
```

---

## 📋 Checklist: Nova Feature

- [ ] Value Object criado em `app/Domain/{Conceito}/ValueObjects/`
- [ ] Domain Service criado em `app/Domain/{Conceito}/Services/`
- [ ] Form Request criado em `app/Http/Requests/`
- [ ] DTO criado em `app/Application/Dto/`
- [ ] ViewModel criado em `app/Application/ViewModels/` (se necessário)
- [ ] Controller orquestra chamadas
- [ ] Rotas registradas em `routes/web.php`
- [ ] Views criadas sem lógica complexa
- [ ] Testes unitários para Domain
- [ ] Testes integração para Application
- [ ] Testes feature para HTTP

---

## 🚫 Anti-padrões a Evitar

```php
// ❌ ERRADO: Lógica no Controller
public function show() {
    $imc = ($weight) / ($height ** 2);
}

// ✅ CORRETO: Lógica no Domain Service
$imc = $this->weightService->calculateIMC($weight, $height);

// ❌ ERRADO: Validação espalhada
if ($weight < 1 || $weight > 500) { ... }

// ✅ CORRETO: Validação no Value Object
new WeightValue($weight);  // Lança exception se inválido

// ❌ ERRADO: Transformação na View
@php
    $formatted = $entry->created_at->format('d/m/Y');
@endphp

// ✅ CORRETO: Transformação no DTO
public readonly string $formatted_date;
```

---

## 📞 Referências Rápidas

| Arquivo | Propósito |
|---------|-----------|
| `copilot-instructions.md` | Diretrizes de desenvolvimento |
| `docs/03-prompt-templates.md` | Como usar templates |
| `docs/04-architecture-definitions.md` | Definições detalhadas |
| `.github/prompts/feature.prompt.md` | Template para features |
| `.github/prompts/bugfix.prompt.md` | Template para bugs |
| `.github/prompts/refactor.prompt.md` | Template para refatoração |

---

## 🚀 Próximos Passos

1. **Refatorar Controllers Existentes**
   - Aplicar Service Layer
   - Usar Form Requests

2. **Criar Testes**
   - Unitários para Domain
   - Integração para Application
   - Feature para HTTP

3. **Implementar Novas Features**
   - Objetivo de Peso
   - Relatórios
   - Notificações

4. **Adicionar Padrões Avançados**
   - Event Sourcing
   - CQRS
   - Microserviços

---

## 📊 Estatísticas do Projeto

| Item | Quantidade |
|------|-----------|
| Value Objects | 5 |
| Domain Services | 3 |
| DTOs | 3 |
| ViewModels | 2 |
| Form Requests | 6 |
| Conceitos SOLID | 5/5 |
| Design Patterns | 6 |
| Documentação Pages | 4 |
| Prompt Templates | 3 |

---

## 💡 Dicas

1. **Sempre comece pelo Domain**: Defina o conceito antes de escrever código
2. **Value Objects são imutáveis**: Use `readonly` properties
3. **Services são stateless**: Sem propriedades instância
4. **DTOs apenas transformam**: Sem lógica de negócio
5. **Controllers apenas orquestram**: Delegam para Services
6. **Use Form Requests**: Validação centralizada
7. **Testes unitários para Domain**: Mais rápidos e focados

---

**Versão**: 1.0  
**Data**: 2026-03-06  
**Status**: ✅ Completo e Funcionando
