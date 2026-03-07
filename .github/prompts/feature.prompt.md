---
name: Implementar Feature
description: "Criar uma nova funcionalidade seguindo arquitetura em camadas do NuTrix"
---

# Template: Implementar Nova Feature

Use este template para implementar funcionalidades mantendo a arquitetura limpa do projeto.

## 📋 Passos Obrigatórios

### 1. Identificar o Conceito de Domínio
- [ ] Qual é o novo conceito de negócio?
- [ ] Ele é imutável e auto-validável?
- [ ] Há conversões/transformações específicas?

**Se sim → Criar Value Object**

### 2. Encapsular Lógica de Negócio
- [ ] A lógica coordena vários conceitos?
- [ ] É uma regra crítica do negócio?
- [ ] Será reutilizada em múltiplos lugares?

**Se sim → Criar Domain Service**

### 3. Validação e Autorização
- [ ] Quais são as regras de validação?
- [ ] Quem pode executar essa ação?

**Sempre → Criar Form Request**

### 4. Transferência de Dados
- [ ] Quais dados precisam ser exibidos?
- [ ] Há transformação necessária?

**Se há transformação → Criar DTO**

### 5. Preparação para Apresentação
- [ ] A view precisa de cálculos de normalização?
- [ ] São múltiplos DTOs agregados?

**Se complexo → Criar ViewModel**

### 6. Orquestração HTTP
- [ ] O Controller apenas orquestra chamadas

## 🗂️ Estrutura de Arquivos Esperada

```
Novo conceito: "Objetivo Nutritivo"

app/Domain/Objective/
├── ValueObjects/
│   ├── ObjectiveType.php
│   └── ObjectiveTarget.php
├── Services/
│   └── ObjectiveCalculationService.php

app/Application/Dto/Objective/
├── ObjectiveDto.php
└── ObjectiveProgressDto.php

app/Application/ViewModels/
└── ObjectiveProgressViewModel.php

app/Http/Requests/
├── StoreObjectiveRequest.php
└── UpdateObjectiveRequest.php

app/Http/Controllers/
└── ObjectiveController.php

resources/
└── views/objectives/
    ├── index.blade.php
    └── show.blade.php
```

## 💡 Exemplo Prático: Feature de "Objetivo de Peso"

### 1️⃣ Value Object
```php
// app/Domain/Objective/ValueObjects/ObjectiveTarget.php
class ObjectiveTarget {
    public function __construct(
        private readonly float $targetWeight,
        private readonly Carbon $targetDate,
    ) {
        if ($targetDate->isPast()) {
            throw new \InvalidArgumentException('Data deve ser no futuro');
        }
    }
}
```

### 2️⃣ Domain Service
```php
// app/Domain/Objective/Services/ObjectiveCalculationService.php
class ObjectiveCalculationService {
    public function calculateDaysRemaining(ObjectiveTarget $target): int {
        return now()->diffInDays($target->targetDate());
    }
}
```

### 3️⃣ Form Request
```php
// app/Http/Requests/StoreObjectiveRequest.php
class StoreObjectiveRequest extends FormRequest {
    public function rules(): array {
        return [
            'target_weight' => 'required|numeric|min:1|max:500',
            'target_date' => 'required|date|after:today',
        ];
    }
}
```

### 4️⃣ DTO
```php
// app/Application/Dto/Objective/ObjectiveDto.php
class ObjectiveDto {
    public static function from(Objective $objective): self {
        return new self(
            target_weight: $objective->target_weight,
            daysRemaining: app(ObjectiveCalculationService::class)
                ->calculateDaysRemaining($objective),
        );
    }
}
```

### 5️⃣ ViewModel
```php
// app/Application/ViewModels/ObjectiveProgressViewModel.php
class ObjectiveProgressViewModel {
    public function progressPercentage(): int {
        return (($this->currentWeight - $this->startWeight) 
                / ($this->targetWeight - $this->startWeight)) * 100;
    }
}
```

### 6️⃣ Controller
```php
// app/Http/Controllers/ObjectiveController.php
public function store(StoreObjectiveRequest $request) {
    $objective = auth()->user()->objectives()->create(
        $request->validated()
    );
    
    return redirect()->route('objectives.show', $objective);
}
```

## ✅ Checklist Final

- [ ] Value Objects criados com validação
- [ ] Domain Services testados
- [ ] Form Requests com regras claras
- [ ] DTOs transformam dados corretamente
- [ ] ViewModels agregam múltiplos DTOs
- [ ] Controllers apenas orquestram
- [ ] Models (Eloquent) persistem dados
- [ ] Rotas registradas
- [ ] Views criadas sem lógica complexa
- [ ] Testes unitários para domínio
- [ ] Testes de integração para cases de uso

---

**Lembrete**: Cada camada tem uma responsabilidade. Não misture!
