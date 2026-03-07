---
name: Refatorar Código
description: "Reestruturar código mantendo funcionalidade e melhorando qualidade da arquitetura"
---

# Template: Refatoração Sistemática

## 🎯 Objetivo da Refatoração

Antes de começar, responda:

```
O QUE: Qual código será refatorado?
POR QUÊ: Qual é o problema atual?
  - [ ] Duplicação de código
  - [ ] Responsabilidades misturadas
  - [ ] Lógica espalhada em múltiplos arquivos
  - [ ] Difícil de testar
  - [ ] Difícil de manter
  
COMO: Qual padrão de arquitetura será usado?
RESULTADO: Como o código ficará melhor?
```

## 📊 Padrões de Refatoração Comuns em NuTrix

### 1️⃣ Extrair Value Object

**Problema**: Validação espalhada
```php
// ❌ ANTES
public function store(Request $request) {
    $weight = $request->weight;
    if ($weight < 1 || $weight > 500) {
        throw new Exception('Peso inválido');
    }
    // ... resto do código
}
```

**Solução**: Mover para Value Object
```php
// ✅ DEPOIS
class WeightValue {
    public function __construct(float $kilograms) {
        if ($kilograms < 1 || $kilograms > 500) {
            throw new InvalidArgumentException('Peso inválido');
        }
        $this->kilograms = $kilograms;
    }
}

// No controller
$weight = new WeightValue($request->weight);
```

**Passo a Passo**:
1. Identifique validações repetidas
2. Crie classe Value Object em `app/Domain/{Conceito}/ValueObjects/`
3. Mova validações para `__construct()`
4. Substitua chamadas antigas pela nova classe
5. Remova validações duplicadas
6. Teste unitário para Value Object
7. Teste de integração para caso de uso

### 2️⃣ Extrair Domain Service

**Problema**: Lógica complexa no controller
```php
// ❌ ANTES
public function show(Patient $patient) {
    $weight = $patient->user->weightEntries->last();
    $previous = $patient->user->weightEntries->where('id', '!=', $weight->id)->last();
    $change = $previous->weight_kg - $weight->weight_kg;
    
    $height = $patient->height;
    $imc = $weight->weight_kg / ($height/100 ** 2);
    
    return view('patient.show', compact('weight', 'change', 'imc'));
}
```

**Solução**: Mover para Domain Service
```php
// ✅ DEPOIS
public function show(Patient $patient) {
    $weightData = $this->weightService->getWeightMetrics($patient);
    
    return view('patient.show', $weightData);
}

// app/Domain/Weight/Services/WeightCalculationService.php
public function getWeightMetrics(Patient $patient): array {
    $latest = $patient->user->weightEntries()->latest()->first();
    $previous = $patient->user->weightEntries()
        ->where('id', '!=', $latest->id)
        ->latest()
        ->first();
    
    $change = $previous->weight_kg - $latest->weight_kg;
    $imc = $this->calculateIMC($latest, $patient);
    
    return compact('weight', 'change', 'imc');
}
```

**Passo a Passo**:
1. Identifique bloco de lógica no controller
2. Crie classe Service em `app/Domain/{Conceito}/Services/`
3. Mova lógica para método público no Service
4. Injete Service no Controller via `__construct()`
5. Substitua lógica por chamada ao Service
6. Teste unitário para Service
7. Remova lógica duplicada de outros controllers

### 3️⃣ Extrair DTO

**Problema**: Transformação de dados no controller
```php
// ❌ ANTES
public function index() {
    $entries = WeightEntry::paginate();
    
    $formatted = $entries->map(function($e) {
        return [
            'weight' => $e->weight_kg,
            'date' => $e->measured_date->format('d/m/Y'),
            'time_ago' => $e->created_at->diffForHumans(),
        ];
    });
    
    return view('weights.index', compact('formatted'));
}
```

**Solução**: Mover para DTO
```php
// ✅ DEPOIS
public function index() {
    $entries = WeightEntryDto::collection(
        WeightEntry::paginate()
    );
    
    return view('weights.index', compact('entries'));
}

// app/Application/Dto/Weight/WeightEntryDto.php
class WeightEntryDto {
    public static function collection(Collection $entries): array {
        return $entries->map(fn($e) => self::from($e))->toArray();
    }
    
    public static function from(WeightEntry $entry): self {
        return new self(
            weight_kg: $entry->weight_kg,
            formatted_date: $entry->measured_date->format('d/m/Y'),
            relative_time: $entry->created_at->diffForHumans(),
        );
    }
}
```

**Passo a Passo**:
1. Identifique transformação de dados
2. Crie classe DTO em `app/Application/Dto/{Conceito}/`
3. Implemente método `from()` para conversão
4. Implemente método `collection()` para múltiplos itens
5. Substitua transformação no controller
6. Use DTO diretamente na view

### 4️⃣ Extrair ViewModel

**Problema**: Cálculos complexos na view
```html
<!-- ❌ ANTES -->
@php
  $range = $max - $min;
  $normalized = (($value - $min) / $range) * 100;
@endphp

<div style="width: {{ $normalized }}%"></div>
```

**Solução**: Mover para ViewModel
```php
// ✅ DEPOIS
class ChartPointsViewModel {
    public function normalizePoint(float $value): float {
        return (($value - $this->min) / $this->range) * 100;
    }
}

// No controller
$chartViewModel = new ChartPointsViewModel($entries);
```

**Passo a Passo**:
1. Identifique cálculos repetidos em views
2. Crie classe ViewModel em `app/Application/ViewModels/`
3. Mova cálculos para métodos públicos
4. Passe ViewModel do controller
5. Chame métodos na view

## 🔄 Ciclo de Refatoração Segura

```
1. ENTENDER CÓDIGO ATUAL
   └─ Executar testes para garantir funcionamento

2. CRIAR TESTES UNITÁRIOS
   └─ Testar comportamento atual

3. REFATORAR MINIMAMENTE
   └─ Uma mudança por vez

4. EXECUTAR TESTES
   └─ Tudo ainda funciona?

5. VALIDAR
   └─ Código está mais limpo?
   └─ Responsabilidades separadas?
   └─ Sem duplicação?

6. DOCUMENTAR
   └─ Adicionar comentários se necessário
   └─ Atualizar documentação
```

## ⚠️ Anti-padrões em Refatoração

❌ **Big Bang Refactoring**
```php
// ERRADO: Refatorar tudo de uma vez
// Resultado: Introduz vários bugs simultaneamente
```

✅ **Incremental Refactoring**
```php
// CERTO: Uma mudança por vez
// 1. Extrair método
// 2. Testar
// 3. Extrair classe
// 4. Testar
```

---

## 📋 Checklist de Refatoração Completa

- [ ] Objetivo claro e documentado
- [ ] Testes existentes passam
- [ ] Testes novos escritos para comportamento
- [ ] Refatoração incremental (mudança por mudança)
- [ ] Testes são executados após cada mudança
- [ ] Sem mudança de assinatura de API pública
- [ ] Código mais legível que antes
- [ ] Responsabilidades separadas
- [ ] Sem duplicação
- [ ] Documentação atualizada
- [ ] Code review realizado
- [ ] Deploy em produção sem problemas

---

**Mantra**: "Make it work, then make it right, then make it fast."  
Nesta ordem! 🚀
