# 04 - Definições de Arquitetura do Projeto

## 🏗️ Visão Geral

O **NuTrix** implementa uma arquitetura em camadas com os princípios de **Clean Architecture**, **Domain-Driven Design (DDD)** e **SOLID**, garantindo escalabilidade, manutenibilidade e testabilidade.

---

## 📊 Diagrama da Arquitetura

```
┌──────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                       │
│                     (Views / API Responses)                      │
│  resources/views/  ──────────────────────  app/Http/Resources/  │
└────────────────────────────┬──────────────────────────────────────┘
                             │
┌────────────────────────────▼──────────────────────────────────────┐
│                    APPLICATION LAYER                             │
│  (Controllers, Requests, DTOs, ViewModels)                       │
│  ┌──────────────┬────────────────┬──────────────────────┐       │
│  │ Controllers  │  Form Requests │ DTOs / ViewModels    │       │
│  └──────┬───────┴────────┬───────┴──────────┬───────────┘       │
│         │                │                  │                    │
│         └────────────────┼──────────────────┘                    │
│                          │                                       │
└──────────────────────────┬──────────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────────┐
│                   DOMAIN LAYER (Negócio)                        │
│  (Value Objects, Domain Services, Regras de Negócio)           │
│                                                                  │
│  ┌─────────────────────────────────────────────────────┐       │
│  │             Domain Aggregates                       │       │
│  │  ┌──────────────────────────────────────────────┐  │       │
│  │  │  Weight          | Water        | Monitoring │  │       │
│  │  └──────────────────────────────────────────────┘  │       │
│  └─────────┬──────────────┬──────────────┬────────────┘       │
│            │              │              │                     │
│  ┌─────────▼──────────────▼──────────────▼────────────┐       │
│  │     Value Objects      │    Domain Services         │       │
│  │  ┌──────────────────┐  │  ┌──────────────────────┐ │       │
│  │  │ WeightValue      │  │  │ WeightCalculation    │ │       │
│  │  │ Height           │  │  │ WaterHydration       │ │       │
│  │  │ WaterVolume      │  │  │ Monitoring           │ │       │
│  │  │ IMCValue         │  │  │                      │ │       │
│  │  │ IMCClassification│  │  │                      │ │       │
│  │  └──────────────────┘  │  └──────────────────────┘ │       │
│  └─────────────────────────────────────────────────────┘       │
└──────────────────────────┬──────────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────────┐
│               PERSISTENCE LAYER (Dados)                         │
│              (Eloquent Models, Migrations)                      │
│  ┌──────────────────────────────────────────────────┐          │
│  │  User    │  Patient   │  WeightEntry    │  ...  │          │
│  └──────────────────────────────────────────────────┘          │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🎯 As Quatro Camadas

### 1. **DOMAIN LAYER** (Núcleo do Negócio)
**Localização**: `app/Domain/`

**Responsabilidade**: Encapsular toda a lógica de negócio, completamente independente de frameworks.

**Componentes**:

#### Value Objects
Objetos imutáveis que representam conceitos do domínio:
- `WeightValue`: Peso em kg com validação (1-500kg)
- `Height`: Altura em cm com conversões
- `WaterVolume`: Volume de água em ml
- `IMCValue`: Cálculo de IMC
- `IMCClassification`: Classificação de IMC conforme OMS

**Características**:
- Imutáveis (readonly properties)
- Auto-validados no construtor
- Sem efeitos colaterais
- Comportamentos específicos (conversão de unidades)

#### Domain Services
Coordenam Value Objects e encapsulam regras de negócio complexas:
- `WeightCalculationService`: Cálculos de peso, evolução, velocidade
- `WaterHydrationService`: Meta de água, progresso, evolução
- `MonitoringService`: Orquestração integrada de peso + água

**Características**:
- Stateless (sem estado)
- Sem dependências de frameworks
- Reutilizáveis em múltiplos contextos
- Testáveis isoladamente

### 2. **APPLICATION LAYER** (Casos de Uso)
**Localização**: `app/Application/`

**Responsabilidade**: Orquestrar Domain Services para implementar casos de uso.

**Componentes**:

#### DTOs (Data Transfer Objects)
Estruturas para transferência de dados sem lógica:
- `WeightEntryDto`: Peso + datas formatadas
- `IMCDto`: IMC com classificação + badges
- `WaterConsumptionDto`: Consumo com conversões

**Métodos comuns**:
```php
public static function from(Model $model): self
public static function collection(Collection $items): array
```

#### ViewModels
Agregadores de dados para apresentação:
- `ChartPointsViewModel`: Normaliza pontos para SVG
- `MonitoringDashboardViewModel`: Agrega dados do dashboard

**Métodos comuns**:
```php
public function hasData(): bool
public function isEmpty(): bool
```

### 3. **HTTP LAYER** (Infraestrutura Web)
**Localização**: `app/Http/`

**Responsabilidade**: Lidar com requisições/respostas HTTP e orquestração.

**Componentes**:

#### Controllers
Orquestram requisição HTTP:
```php
public function show(Patient $patient) {
    // 1. Validação (via Form Request)
    // 2. Autorização (via Policy)
    // 3. Orquestração (via Service)
    // 4. Resposta
}
```

#### Form Requests
Validam dados e autorizam ações:
- `StoreWeightEntryRequest`: Validação + autorização para criar peso
- `UpdateWaterConsumptionRequest`: Validação + autorização para atualizar água
- `StorePatientRequest`: Validação para criar paciente

**Métodos**:
```php
public function authorize(): bool  // Verifica permissão
public function rules(): array     // Regras de validação
public function messages(): array  // Mensagens personalizadas
```

### 4. **PERSISTENCE LAYER** (Acesso a Dados)
**Localização**: `app/Models/`

**Responsabilidade**: Persister e recuperar dados.

**Componentes**:

#### Eloquent Models
- `User`: Usuário do sistema (paciente/nutricionista)
- `Patient`: Dados do paciente
- `WeightEntry`: Registro de peso
- `WaterConsumption`: Registro de hidratação
- `Nutritionist`: Perfil de nutricionista

**Relacionamentos**:
```php
User ←→ Patient (1:1)
User ← WeightEntry (1:N)
User ← WaterConsumption (1:N)
Nutritionist ← Patient (1:N)
```

---

## 🔄 Fluxo de Dados (Request → Response)

```
1. REQUEST HTTP
   GET /patients/{id}/monitoring
        ↓
2. ROUTING
   routes/web.php → PatientController@showMonitoring
        ↓
3. CONTROLLER
   Injeta MonitoringService
   Chama: service->compileDashboardData($patient)
        ↓
4. DOMAIN SERVICE (MonitoringService)
   Injeta: WeightCalculationService, WaterHydrationService
   Coordena: calcular IMC, meta de água, progresso
        ↓
5. VALUE OBJECTS
   WeightValue::fromKilograms(75)
   IMCValue::calculate($weight, $height)
   IMCClassification::from(imc)
        ↓
6. MODELS (Eloquent)
   WeightEntry::orderBy(...)->get()
   WaterConsumption::where(...)->get()
        ↓
7. DTOs (Application Layer)
   WeightEntryDto::collection($entries)
   IMCDto::from($classification)
        ↓
8. VIEWMODELS (Application Layer)
   MonitoringDashboardViewModel::from(...)
   ChartPointsViewModel::from(...)
        ↓
9. VIEW (Blade Template)
   Recebe: $viewModel
   Renderiza HTML
        ↓
10. RESPONSE HTML
    Enviado ao cliente
```

---

## 📐 Princípios SOLID Aplicados

### S - Single Responsibility Principle
```php
// ✅ CORRETO: Cada classe tem responsabilidade única
WeightCalculationService    // Apenas cálculos de peso
WaterHydrationService       // Apenas cálculos de água
MonitoringService           // Apenas orquestração

// ❌ INCORRETO: Múltiplas responsabilidades
AllMetricsService           // Calcula peso E água E IMC E água
```

### O - Open/Closed Principle
```php
// ✅ CORRETO: Aberto para extensão
// Adicione novo tipo de cálculo sem alterar classe existente
abstract class HealthMetricService { }
class WeightMetricService extends HealthMetricService { }
class BodyFatMetricService extends HealthMetricService { }
```

### L - Liskov Substitution Principle
```php
// ✅ CORRETO: Subclasse substitui superclasse
interface MetricCalculator { }
class WeightCalculator implements MetricCalculator { }
class WaterCalculator implements MetricCalculator { }

// Ambos podem ser usados intercambiávelmente
function calculate(MetricCalculator $calc) { }
```

### I - Interface Segregation Principle
```php
// ✅ CORRETO: Interfaces específicas
interface WeightCalculation { public function calculate(); }
interface IMCCalculation { public function calculate(); }

// ❌ INCORRETO: Interface genérica
interface AllCalculations { 
    public function calculateWeight();
    public function calculateWater();
    public function calculateIMC();
}
```

### D - Dependency Inversion Principle
```php
// ✅ CORRETO: Depender de abstrações
public function __construct(
    private WeightCalculationService $service  // Injeção
) {}

// ❌ INCORRETO: Instanciar direto
$service = new WeightCalculationService();
```

---

## 🎁 Design Patterns Usados

### 1. Service Layer Pattern
Encapsula lógica de negócio separada dos controllers.

```
Controller → Service → Value Objects → Models
```

### 2. Repository Pattern (Implícito)
Acesso a dados via models abstraindo SQL.

```php
$user->weightEntries()  // Ao invés de SQL direto
```

### 3. DTO (Data Transfer Object) Pattern
Transferência de dados sem lógica de negócio.

```php
WeightEntryDto::from($model)  // Transformação limpa
```

### 4. ViewModel Pattern
Preparação de dados para renderização.

```php
ChartPointsViewModel  // Normaliza pontos para gráfico SVG
```

### 5. Value Object Pattern
Objetos imutáveis que representam conceitos.

```php
new WeightValue(75.5)  // Validação automática
```

### 6. Factory Pattern
Criação de objetos via métodos estáticos.

```php
WeightValue::fromPounds(166)  // Factory method
IMCValue::calculate($weight, $height)  // Factory
```

---

## 📦 Agregados de Domínio (DDD)

Um agregado é um grupo de entidades/value objects que formam uma unidade coesa:

### Agregado 1: Weight
```
Weight Aggregado
├── WeightValue (Value Object)
├── Height (Value Object)
├── IMCValue (Value Object)
├── IMCClassification (Value Object)
└── WeightCalculationService (Domain Service)
```

Responsabilidades:
- Calcular peso
- Calcular IMC
- Classificar IMC
- Visualizar evolução de peso

### Agregado 2: Water
```
Water Aggregado
├── WaterVolume (Value Object)
└── WaterHydrationService (Domain Service)
```

Responsabilidades:
- Calcular meta
- Rastrear consumo
- Visualizar evolução de hidratação

### Agregado 3: Monitoring
```
Monitoring Agregado
├── WeightCalculationService (injeta)
├── WaterHydrationService (injeta)
└── MonitoringService (Domain Service)
```

Responsabilidades:
- Orquestrar dados de peso
- Orquestrar dados de água
- Gerar insights integrados

---

## 🔒 Authorização e Validação

### Níveis de Segurança

```
1. Form Request Level
   └─ Validação de dados
   └─ Autorização básica (role check)

2. Policy Level (quando necessário)
   └─ Verificação de propriedade
   └─ Regras de negócio complexas

3. Service Level
   └─ Validação de business rules
```

**Exemplo**:
```php
// Form Request: Apenas pacientes podem criar peso
class StoreWeightEntryRequest {
    public function authorize(): bool {
        return auth()->user()->hasRole('patient');
    }
}

// Policy: Apenas dono pode ver seu próprio peso
class WeightEntryPolicy {
    public function view(User $user, WeightEntry $entry): bool {
        return $user->id === $entry->user_id;
    }
}
```

---

## 📈 Escalabilidade

A arquitetura suporta crescimento em múltiplas dimensões:

### Novos Conceitos de Domínio
```
Exemplo: Adicionar "Objetivo de Peso"

Novo agregado:
app/Domain/Objective/
├── ValueObjects/
│   ├── ObjectiveTarget.php
│   └── ObjectiveType.php
├── Services/
│   └── ObjectiveService.php
```

### Novos Serviços de Aplicação
```
Exemplo: Relatório de Progresso

Novo caso de uso:
app/Application/Services/
└── ProgressReportService.php
```

### Múltiplos Clientes
```
Mesma lógica de domínio. Respostas diferentes:

- Web: HTML (views)
- API: JSON (resources)
- Mobile: JSON simplificado
- CLI: Texto formatado

Domain Services reutilizáveis em todos!
```

---

## 🧪 Testabilidade

A arquitetura favorece testes em múltiplas camadas:

### Testes Unitários (Domain)
```php
class WeightValueTest extends TestCase {
    public function test_weight_value_validates_range() {
        $this->expectException(InvalidArgumentException::class);
        new WeightValue(550);  // > 500
    }
}
```

### Testes Integração (Application)
```php
class WeightCalculationServiceTest extends TestCase {
    public function test_calculates_imc_correctly() {
        $service = app(WeightCalculationService::class);
        $imc = $service->calculateIMC($weight, $height);
        $this->assertEquals(25.0, $imc->value());
    }
}
```

### Testes Feature (HTTP)
```php
class PatientMonitoringTest extends TestCase {
    public function test_nutritionist_can_view_monitoring() {
        $response = $this->actingAs($nutritionist)
            ->get("/patients/{$patient->id}/monitoring");
        $response->assertStatus(200);
    }
}
```

---

## 📊 Métricas de Qualidade

| Métrica | Alvo | Método |
|---------|------|--------|
| Test Coverage | >80% | PHPUnit |
| Cyclomatic Complexity | <10 | PHPStan |
| Code Duplication | <5% | CPD |
| SOLID Compliance | 100% | Code Review |

---

## 🚀 Deployment e Versioning

### Versionamento Semântico
```
MAJOR.MINOR.PATCH

1.0.0 = Versão estável
1.1.0 = Nova feature (backward compatible)
1.1.1 = Bug fix (backward compatible)
2.0.0 = Breaking change
```

### Backward Compatibility
```php
// ✅ SEGURO: Adicionar novo método
public function newMethod() { }

// ✅ SEGURO: Adicionar novo parâmetro com padrão
public function calculate(WeightValue $weight, ?Height $height = null) { }

// ❌ BREAKING: Remover método público
// public function oldMethod() { }  // Removido!

// ❌ BREAKING: Alterar assinatura
// public function calculate(WeightValue $weight, int $age)  // Antes era (float)
```

---

## 📚 Documentação Relacionada

- `copilot-instructions.md`: Diretrizes de desenvolvimento
- `docs/03-prompt-templates.md`: Templates de prompt
- `docs/01-docker-setup.md`: Setup do ambiente
- `docs/02-install-breeze.md`: Instalação do Breeze

---

## 📋 Checklist de Conformidade Arquitetural

- [ ] Nova feature tem Value Object correspondente?
- [ ] Domain Service encapsula lógica de negócio?
- [ ] Form Request valida dados?
- [ ] DTO transforma dados adequadamente?
- [ ] ViewModel agrega dados para apresentação?
- [ ] Controller apenas orquestra?
- [ ] Model apenas persiste dados?
- [ ] Responsabilidades estão separadas?
- [ ] SOLID principles aplicados?
- [ ] Código é testável?
- [ ] Documentação está atualizada?

---

**Versão**: 1.0  
**Última atualização**: 2026-03-06  
**Arquiteto**: João Dev  
**Próximas melhorias**: Event Sourcing, CQRS, Microserviços
