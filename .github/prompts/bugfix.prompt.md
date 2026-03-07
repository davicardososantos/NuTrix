---
name: Corrigir Bug
description: "Template para debugging sistemático seguindo as camadas da arquitetura"
---

# Template: Diagnosticar e Corrigir Bug

## 🔍 Metodologia: Rastrear o Fluxo de Dados

O bug pode estar em qualquer camada. Vamos investigar sistematicamente:

```
REQUEST
  ↓
Form Request (Validação?)
  ↓
Controller (Orquestração?)
  ↓
Service (Lógica de negócio?)
  ↓
Domain Service (Cálculo?)
  ↓
Value Object (Validação?)
  ↓
Model (Persistência?)
  ↓
DTO (Transformação?)
  ↓
ViewModel (Preparação?)
  ↓
RESPONSE
```

## 📝 Passo 1: Descrever o Bug

```
SINTOMA: [O que está acontecendo?]
ESPERADO: [O que deveria acontecer?]
CENÁRIO: [Como reproduzir?]
IMPACTO: [Qual camada é afetada?]
```

## 🔎 Passo 2: Identificar a Camada

### Bug em Form Request?
```php
// ❌ Validação muito restritiva?
// ❌ Autorização bloqueando usuário legítimo?
// ❌ Mensagem de erro confusa?
```

### Bug em Controller?
```php
// ❌ Parâmetro não está sendo passado?
// ❌ Service não foi injetado?
// ❌ Resposta incorreta?
```

### Bug em Domain Service?
```php
// ❌ Cálculo matemático incorreto?
// ❌ Value Object rejeitando valor válido?
// ❌ Lógica de negócio quebrada?
```

### Bug em DTO?
```php
// ❌ Dados não estão sendo transformados?
// ❌ Formatação incorreta?
// ❌ Campo faltando?
```

### Bug em ViewModel?
```php
// ❌ Dados não são agregados corretamente?
// ❌ Método retorna valor errado?
// ❌ Normalização de gráfico incorreta?
```

### Bug em Model/Persistência?
```php
// ❌ Relacionamento não carrega dados?
// ❌ Query retorna valor errado?
// ❌ Casting de dados incorreto?
```

### Bug em View/Blade?
```php
// ❌ Variável não está disponível?
// ❌ Renderização incorreta?
// ❌ Lógica complexa in-place?
```

## 🛠️ Passo 3: Instrumentar para Debug

### Adionar Logs Estratégicos
```php
// No serviço que suspeita-se
\Log::debug('Calculando IMC', [
    'weight' => $weight->kilograms(),
    'height' => $height->centimeters(),
    'result' => $imc->value(),
]);
```

### Usar Tinker para Testar
```bash
php artisan tinker

// Testar Value Object
$weight = new \App\Domain\Weight\ValueObjects\WeightValue(75.5);
$weight->kilograms(); // 75.5

// Testar Domain Service
$service = app(\App\Domain\Weight\Services\WeightCalculationService::class);
$service->calculateIMC($weight, $height);
```

### Inspecionar Response
```php
// No teste ou em browser
dd($response->getData());
```

## 🐛 Passo 4: Casos Comuns

### Problema: "Map key does not exist"
**Causa provável**: Transformação em DTO ou ViewModel falhou
```php
// ❌ Evitar
$data['weight_kg']  // Se key é 'weight'

// ✅ Corrigir
$data['weight']
// ou
public readonly float $weight_kg,  // No DTO
```

### Problema: "Call to undefined method"
**Causa provável**: Service não foi injetado ou classe não existe
```php
// ❌ Evitar
$this->calculateWeight();  // Service não existe

// ✅ Corrigir
app(WeightCalculationService::class)->calculateWeight();
// ou
public function __construct(
    private WeightCalculationService $service,
) {}
```

### Problema: "Authorization denied"
**Causa provável**: Form Request ou Policy rejeitando
```php
// Verificar Form Request::authorize()
// Verificar Policy methods
```

### Problema: "Validation failed"
**Causa provável**: Regra de validação muito estrita
```php
// ❌ Muito restritivo
'weight' => 'required|numeric|min:1|max:300|regex:/^\d{1,3}\.\d{2}$/'

// ✅ Razoável
'weight' => 'required|numeric|min:1|max:300'
```

## ✅ Passo 5: Validar Correção

- [ ] Bug reproduzível antes da correção?
- [ ] Correção foca apenas na causa raiz?
- [ ] Não quebrou outras funcionalidade?
- [ ] Testes foram adicionados para evitar regressão?
- [ ] Mensagens de erro são claras?

## 📋 Exemplo: Debugar "IMC aparece como null"

```
1. SINTOMA: IMC não aparece no dashboard
   ESPERADO: IMC deve mostrar 25.3 (Normal)
   CENÁRIO: Paciente com peso 75kg, altura 172cm

2. CAMADAS SUSPEITAS:
   - Domain Service: Calcula corretamente?
   - DTO: Transforma corretamente?
   - View Model: Agrega corretamente?
   - View: Exibe corretamente?

3. DEBUG:
   // No controller/teste
   $weight = new WeightValue(75);
   $height = new Height(172);
   $imc = $this->weightService->calculateIMC($weight, $height);
   dd($imc->value());  // Saída: 25.31 ✓

   // Se OK aqui, ir para DTO
   $imcDto = IMCDto::from($classification);
   dd($imcDto->value);  // Saída: 25.31 ✓

   // Se OK aqui, ir para ViewModel
   dd($viewModel->imc?->value);  // null ❌

   // ACHADO: ViewModel não está recebendo IMC do controller!

4. CORREÇÃO:
   // No Controller
   + 'imc' => $imcDto,
```

---

**Dica**: Quando tudo falha, teste cada camada isoladamente com dados conhecidos.
