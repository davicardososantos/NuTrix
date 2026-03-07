---
name: NuTrix Developer Guidelines
description: "Diretrizes de desenvolvimento para manutenção da arquitetura limpa e escalável do projeto NuTrix"
---

# NuTrix - Diretrizes para Desenvolvimento

## 🏗️ Arquitetura e Conceitos Aplicados

Este projeto implementa uma **arquitetura em camadas com Clean Architecture e DDD (Domain-Driven Design)** para garantir escalabilidade, manutenibilidade e testabilidade.

### Conceitos e Padrões Utilizados

#### 1. **SOLID Principles**
- **S**ingle Responsibility Principle (SRP): Cada classe tem uma única razão para mudar
  - Exemplo: `WeightCalculationService` apenas calcula peso, não gerencia persistência
- **O**pen/Closed Principle: Código aberto para extensão, fechado para modificação
  - Exemplo: Novos tipos de cálculo podem ser adicionados sem alterar serviços existentes
- **L**iskov Substitution Principle: Subclasses devem ser substituíveis por suas superclasses
- **I**nterface Segregation Principle: Interfaces específicas em vez de genéricas
- **D**ependency Inversion Principle: Depender de abstrações, não de implementações
  - Exemplo: Controllers injetam Services por interface/contrato

#### 2. **Domain-Driven Design (DDD)**
- **Value Objects**: Objetos imutáveis que representam conceitos do domínio
  - `WeightValue`: Encapsula peso em kg com validações
  - `Height`: Encapsula altura em cm com conversões
  - `WaterVolume`: Encapsula volume de água em ml
  - `IMCValue` e `IMCClassification`: Encapsulam cálculo e classificação de IMC
  
- **Domain Services**: Lógica de negócio que coordena Value Objects
  - `WeightCalculationService`: Cálculos de peso e evolução
  - `WaterHydrationService`: Cálculos de hidratação
  - `MonitoringService`: Orquestra dados para dashboard

- **Repositories**: Camada de persistência (via Eloquent Models)

#### 3. **Design Patterns**
- **Service Layer**: Separa lógica de negócio do controller
  - Controllers delegam para Services
- **Repository Pattern**: Acesso a dados através de abstrações
- **DTO (Data Transfer Object)**: Transferência de dados entre camadas
  - `WeightEntryDto`, `IMCDto`, `WaterConsumptionDto`
- **ViewModel**: Preparação de dados para apresentação
  - `ChartPointsViewModel`: Normaliza pontos para gráfico SVG
  - `MonitoringDashboardViewModel`: Agregador de dados do dashboard
- **Factory Pattern**: Criação de objetos (implícito em `::from()` dos DTOs)

#### 4. **Clean Architecture**
```
app/
├── Domain/                 # Núcleo do negócio (independente de frameworks)
│   ├── Weight/            # Agregado de Peso
│   │   ├── ValueObjects/  # Conceitos imutáveis
│   │   └── Services/      # Lógica de peso
│   ├── Water/             # Agregado de Água
│   │   ├── ValueObjects/
│   │   └── Services/
│   └── Monitoring/        # Agregado de Monitoramento
│       └── Services/      # Orquestração integrada
│
├── Application/           # Casos de uso da aplicação
│   ├── Dto/              # Objetos de transferência de dados
│   ├── Services/         # Orquestração de negócio
│   └── ViewModels/       # Preparação para apresentação
│
├── Http/                 # Infraestrutura Web
│   ├── Controllers/      # Orquestração de requisições HTTP
│   ├── Requests/         # Validação e autorização
│   └── Resources/        # Respostas formatadas
│
└── Models/              # Eloquent Models (persistência)
```

## 📋 Padrões de Código

### Quando Criar um Value Object

Value Objects representam **conceitos do domínio** que:
- Têm **identidade por valor** (não por ID)
- São **imutáveis**
- Encapsulam **validações**
- Geram **comportamentos específicos**

```php
// ✅ Bom: Conceito do domínio
class WeightValue {
    private readonly float $kilograms;
    
    public function __construct(float $kilograms) {
        if ($kilograms < 1 || $kilograms > 500) {
            throw new \InvalidArgumentException('Peso inválido');
        }
        $this->kilograms = $kilograms;
    }
}

// ❌ Evitar: Dados genéricos
class GenericValue {
    private $value;
}
```

### Quando Criar um Domain Service

Domain Services contêm **lógica de negócio** que:
- Coordena **múltiplos Value Objects**
- Não pertence a um único aggregado
- Representa uma **regra de negócio crítica**

```php
// ✅ Bom: Orquestra Weight e Age
$imc = $this->weightService->calculateIMC($weight, $height, $birthDate);

// ❌ Evitar: Lógica no Controller
public function show() {
    $imc = ($weight) / ($height ** 2);
}
```

### Quando Criar um DTO

DTOs **transferem dados** entre camadas:
- Models → Controllers
- Controllers → Views
- Sem lógica de negócio
- Apenas transformação de dados

```php
// ✅ Bom: DTO estruturado
class WeightEntryDto {
    public function __construct(
        public readonly float $weight_kg,
        public readonly string $formatted_date,
    ) {}
}

// ❌ Evitar: Lógica no DTO
class WeightEntryDto {
    public function calculateIMC() { /* ... */ }
}
```

### Quando Usar ViewModel

ViewModels **preparam dados** para renderização:
- Normalizam valores para gráficos
- Agregam múltiplos DTOs
- Sem efeitos colaterais

```php
// ✅ Bom: ViewModel em Blade
<div>{{ $viewModel->waterProgressLabel() }}</div>

// ❌ Evitar: Lógica complexa em Blade
<div>@php if ($progress >= 100) ... @endphp</div>
```

## 🔄 Fluxo de Dados Recomendado

```
User Request
    ↓
Form Request (Validação + Autorização)
    ↓
Controller (Orquestração)
    ↓
Application Service (Casos de uso)
    ↓
Domain Service (Lógica de negócio)
    ↓
Value Objects (Conceitos do domínio)
    ↓
Models/Repository (Persistência)
    ↓
DTO (Transformação)
    ↓
ViewModel (Preparação para apresentação)
    ↓
View (Renderização)
```

## ✅ Checklist para Novas Features

- [ ] Há um novo conceito de domínio? Criar **Value Object**
- [ ] Há lógica que coordena vários conceitos? Criar **Domain Service**
- [ ] Há validação/autorização? Criar **Form Request**
- [ ] Há transformação de dados? Criar **DTO**
- [ ] Há renderização complexa? Criar **ViewModel**
- [ ] Testes unitários para regras de negócio (Domain Layer)
- [ ] Testes de integração para casos de uso (Application Layer)
- [ ] Testes de feature para fluxo completo (HTTP)

## 🚫 Anti-padrões a Evitar

❌ **Lógica de negócio em Controllers**
```php
// Evitar
public function show(Patient $patient) {
    $imc = $weight / ($height ** 2);  // Lógica aqui
}
```

❌ **Validação disparsa**
```php
// Evitar
$request->validate(['weight' => 'numeric']);
$request->validate(['height' => 'numeric']);
```

❌ **Modelos anêmicos (sem comportamento)**
```php
// Evitar
$model->weight_kg;  // Sem conversão, sem validação
```

❌ **Herança de valor**
```php
// Evitar
class ProductPrice extends int { }  // Value Objects não herdam

// Usar
class Price {
    public function __construct(
        private readonly float $amount,
        private readonly Currency $currency,
    ) {}
}
```

## 📚 Referências Rápidas

| Conceito | Localização | Responsabilidade |
|----------|-------------|-------------------|
| **Value Object** | `app/Domain/*/ValueObjects/` | Encapsular conceito + validação |
| **Domain Service** | `app/Domain/*/Services/` | Orquestrar Value Objects |
| **DTO** | `app/Application/Dto/*/` | Transferir dados entre camadas |
| **ViewModel** | `app/Application/ViewModels/` | Preparar dados para apresentação |
| **Form Request** | `app/Http/Requests/` | Validar request + autorizar |
| **Controller** | `app/Http/Controllers/` | Orquestrar requisição HTTP |
| **Model** | `app/Models/` | Persistência (Eloquent) |

## 🔐 Autorização

Todos os Form Requests verificam autorização:
```php
public function authorize(): bool {
    return auth()->check() && auth()->user()->hasRole('patient');
}
```

Policies adicionais para recursos específicos:
- `WeightEntryPolicy`: Verifica propriedade de entrada de peso
- `WaterConsumptionPolicy`: Verifica propriedade de consumo

## 📝 Convenções de Nomes

| Tipo | Convenção | Exemplo |
|------|-----------|---------|
| Value Object | `{Conceito}Value` ou `{Conceito}` | `WeightValue`, `IMCClassification` |
| Domain Service | `{Conceito}Service` | `WeightCalculationService` |
| Application Service | `{Conceito}Service` | `WeightService` |
| DTO | `{Modelo}Dto` | `WeightEntryDto` |
| ViewModel | `{Tela}ViewModel` | `MonitoringDashboardViewModel` |
| Form Request | `{Action}{Model}Request` | `StoreWeightEntryRequest` |
| Repository | `{Modelo}Repository` (interface) | `WeightEntryRepository` |

## 🚀 Próximos Passos Sugeridos

1. Criar testes unitários para Value Objects
2. Implementar Repositories com interfaces
3. Adicionar Events para notificações (ex: Meta atingida)
4. Criar Queries para relatórios complexos
5. Implementar Specifications para filtros avançados

---

**Última atualização**: 2026-03-06  
**Versão da arquitetura**: 1.0
