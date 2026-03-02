@php
    use App\Helpers\IMCHelper;

    $imc = $patient && $patient->weight && $patient->height ? IMCHelper::calculate($patient->weight, $patient->height) : null;
    $imcClass = $imc ? IMCHelper::classify($imc, $patient->birth_date) : null;
@endphp

<x-patient-panel-layout
    :patient="$patient"
    activeTab="monitoring"
    title="Acompanhamento Integrado"
    :subtitle="$patient->full_name"
>
    <x-slot name="headerIcon">
        <i class="fas fa-heartbeat text-xl md:text-2xl text-green-600"></i>
    </x-slot>

    <div class="space-y-6">
        <!-- KPI Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Peso -->
            <div class="bg-gradient-to-br from-emerald-50 to-green-50 border-2 border-emerald-200 rounded-2xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs uppercase text-emerald-600 font-bold">Peso Atual</p>
                    <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-scale-balanced text-emerald-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-gray-900">
                    @if($latestWeight)
                        {{ number_format($latestWeight->weight_kg, 1, ',', '.') }}<span class="text-lg">kg</span>
                    @else
                        <span class="text-gray-400">--</span>
                    @endif
                </p>
                @if($latestWeight)
                    <p class="text-xs text-gray-600 mt-2">{{ $latestWeight->measured_date->format('d/m/Y') }}</p>
                @endif
            </div>

            <!-- IMC -->
            <div class="bg-gradient-to-br {{ $imcClass ? ($imcClass['badge'] == 'green' ? 'from-green-50 to-emerald-50 border-2 border-green-200' : ($imcClass['badge'] == 'yellow' ? 'from-yellow-50 to-orange-50 border-2 border-yellow-200' : 'from-red-50 to-orange-50 border-2 border-red-200')) : 'from-gray-50 to-gray-50 border-2 border-gray-200' }} rounded-2xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs uppercase {{ $imcClass ? ($imcClass['badge'] == 'green' ? 'text-green-600' : ($imcClass['badge'] == 'yellow' ? 'text-yellow-600' : 'text-red-600')) : 'text-gray-600' }} font-bold">IMC</p>
                    <div class="w-9 h-9 {{ \App\Helpers\IMCHelper::getBadgeClasses($imcClass['badge'] ?? 'gray') }} rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <p class="text-3xl font-black {{ $imcClass ? ($imcClass['badge'] == 'green' ? 'text-green-900' : ($imcClass['badge'] == 'yellow' ? 'text-yellow-900' : 'text-red-900')) : 'text-gray-700' }}">
                    @if($imc)
                        {{ $imc }}<span class="text-lg">kg/m²</span>
                    @else
                        <span class="text-gray-400">--</span>
                    @endif
                </p>
                @if($imcClass)
                    <p class="text-xs {{ $imcClass['badge'] == 'green' ? 'text-green-700' : ($imcClass['badge'] == 'yellow' ? 'text-yellow-700' : 'text-red-700') }} font-semibold mt-2">{{ $imcClass['classificacao'] }}</p>
                @endif
            </div>

            <!-- Agua -->
            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-2xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs uppercase text-blue-600 font-bold">Ultima Hidratacao</p>
                    <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-droplet text-blue-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-gray-900">
                    @if($latestWater)
                        {{ number_format($latestWater->quantity_liters, 1, ',', '.') }}<span class="text-lg">L</span>
                    @else
                        <span class="text-gray-400">--</span>
                    @endif
                </p>
                @if($latestWater)
                    <p class="text-xs text-gray-600 mt-2">{{ $latestWater->consumption_date->format('d/m/Y') }}</p>
                @else
                    <p class="text-xs text-gray-500 mt-2">Sem registros</p>
                @endif
            </div>
        </div>

        <!-- Weight Chart -->
        @if($weightChartEntries->count() > 1)
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-line text-emerald-600"></i> Evolucao de Peso (Ultimos 10 Registros)
                </h3>
                @php
                    $weights = $weightChartEntries->pluck('weight_kg')->map(fn ($value) => (float) $value);
                    $minWeight = $weights->min();
                    $maxWeight = $weights->max();
                    $padding = ($maxWeight - $minWeight) * 0.15;
                    $displayMin = floor(($minWeight - $padding) * 2) / 2;
                    $displayMax = ceil(($maxWeight + $padding) * 2) / 2;
                    $displayRange = $displayMax - $displayMin;
                    $count = $weights->count();
                    $points = [];
                    $circleData = [];

                    foreach ($weights as $index => $weight) {
                        $x = $count > 1 ? ($index / ($count - 1)) * 100 : 50;
                        $y = 100 - ((($weight - $displayMin) / $displayRange) * 100);
                        $points[] = number_format($x, 2, '.', '') . ',' . number_format($y, 2, '.', '');
                        $circleData[] = [
                            'x' => number_format($x, 2, '.', ''),
                            'y' => number_format($y, 2, '.', ''),
                            'weight' => $weight,
                            'date' => $weightChartEntries[$index]->measured_date->format('d/m')
                        ];
                    }

                    $pointsAttr = implode(' ', $points);
                    $ySteps = 4;
                @endphp
                <div class="relative">
                    <svg viewBox="0 0 1200 400" class="w-full" preserveAspectRatio="xMidYMid meet" style="max-height: 280px;">
                        <defs>
                            <linearGradient id="weightLine" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#059669" />
                                <stop offset="100%" stop-color="#10b981" />
                            </linearGradient>
                            <linearGradient id="weightFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.15" />
                                <stop offset="100%" stop-color="#10b981" stop-opacity="0" />
                            </linearGradient>
                            <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                                <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.15" />
                            </filter>
                        </defs>

                        <!-- Y Axis Labels -->
                        @for ($i = 0; $i <= $ySteps; $i++)
                            @php
                                $stepValue = $displayMin + ($displayRange / $ySteps) * $i;
                                $yPos = 100 - ($i / $ySteps) * 100;
                            @endphp
                            <text x="40" y="{{ number_format($yPos * 4 + 8, 1, '.', '') }}" class="text-xs fill-gray-400 font-semibold" text-anchor="end">
                                {{ number_format($stepValue, 1, ',', '.') }}kg
                            </text>
                            <line x1="50" y1="{{ number_format($yPos * 4, 1, '.', '') }}" x2="1180" y2="{{ number_format($yPos * 4, 1, '.', '') }}" stroke="#f3f4f6" stroke-width="1" />
                        @endfor

                        <!-- Y Axis -->
                        <line x1="50" y1="0" x2="50" y2="400" stroke="#d1d5db" stroke-width="2" />
                        <!-- X Axis -->
                        <line x1="50" y1="400" x2="1180" y2="400" stroke="#d1d5db" stroke-width="2" />

                        <!-- Area Fill -->
                        <path d="M 50 400 L {{ implode(' L ', array_map(fn($p) => 50 + ($p['x'] / 100 * 1130) . ',' . ($p['y'] / 100 * 400), $circleData)) }} L 1180 400 Z" fill="url(#weightFill)" />

                        <!-- Line -->
                        <polyline points="@foreach($circleData as $data){{ 50 + ($data['x'] / 100 * 1130) }},{{ ($data['y'] / 100 * 400) }} @endforeach" fill="none" stroke="url(#weightLine)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" filter="url(#shadow)" />

                        <!-- Circles (Data Points) -->
                        @foreach($circleData as $index => $data)
                            @php
                                $cx = 50 + ($data['x'] / 100 * 1130);
                                $cy = $data['y'] / 100 * 400;
                            @endphp
                            <g class="weight-point" data-weight="{{ $data['weight'] }}" data-date="{{ $data['date'] }}">
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="8" fill="#10b981" opacity="0.1" />
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="5" fill="white" stroke="#10b981" stroke-width="2.5" />
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="2" fill="#10b981" />
                            </g>
                        @endforeach

                        <!-- X Axis Labels -->
                        @foreach($circleData as $index => $data)
                            @php
                                $cx = 50 + ($data['x'] / 100 * 1130);
                            @endphp
                            <text x="{{ number_format($cx, 1, '.', '') }}" y="425" class="text-xs fill-gray-500 font-medium" text-anchor="middle">
                                {{ $data['date'] }}
                            </text>
                        @endforeach
                    </svg>

                    <div id="weight-chart-tooltip" class="absolute hidden bg-gray-900 text-white px-3 py-2 rounded-lg text-xs font-semibold pointer-events-none z-10 whitespace-nowrap" style="left: 0; top: 0;">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400">●</span>
                            <span id="weight-tooltip-text">75.5 kg</span>
                        </div>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('.weight-point').forEach(point => {
                        point.addEventListener('mouseenter', function(e) {
                            const tooltip = document.getElementById('weight-chart-tooltip');
                            const weight = this.getAttribute('data-weight');
                            const date = this.getAttribute('data-date');
                            tooltip.querySelector('#weight-tooltip-text').textContent = weight + ' kg • ' + date;
                            tooltip.classList.remove('hidden');

                            const rect = this.getBoundingClientRect();
                            const containerRect = this.closest('.relative').getBoundingClientRect();
                            tooltip.style.left = (rect.left - containerRect.left) + 'px';
                            tooltip.style.top = (rect.top - containerRect.top - 35) + 'px';
                        });

                        point.addEventListener('mouseleave', function() {
                            document.getElementById('weight-chart-tooltip').classList.add('hidden');
                        });
                    });
                </script>
            </div>
        @endif

        <!-- Water Chart -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-600"></i> Evolucao de Hidratacao (Ultimos 30 Dias)
            </h3>
            
            @if($waterChartEntries->count() > 1)
                @php
                    $waterQuantities = $waterChartEntries->pluck('quantity_liters')->map(fn ($value) => (float) $value);
                    $minWater = $waterQuantities->min();
                    $maxWater = $waterQuantities->max();
                    $waterPadding = ($maxWater - $minWater) * 0.15;
                    $waterDisplayMin = max(0, floor(($minWater - $waterPadding) * 2) / 2);
                    $waterDisplayMax = ceil(($maxWater + $waterPadding) * 2) / 2;
                    $waterDisplayRange = max($waterDisplayMax - $waterDisplayMin, 1);
                    $waterCount = $waterQuantities->count();
                    $waterCircleData = [];

                    foreach ($waterQuantities as $index => $quantity) {
                        $x = $waterCount > 1 ? ($index / ($waterCount - 1)) * 100 : 50;
                        $y = 100 - ((($quantity - $waterDisplayMin) / $waterDisplayRange) * 100);
                        $waterCircleData[] = [
                            'x' => number_format($x, 2, '.', ''),
                            'y' => number_format($y, 2, '.', ''),
                            'quantity' => $quantity,
                            'date' => $waterChartEntries[$index]->consumption_date->format('d/m')
                        ];
                    }
                @endphp
                <div class="relative">
                    <svg viewBox="0 0 1200 400" class="w-full" preserveAspectRatio="xMidYMid meet" style="max-height: 280px;">
                        <defs>
                            <linearGradient id="waterLine" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#0369a1" />
                                <stop offset="100%" stop-color="#0ea5e9" />
                            </linearGradient>
                            <linearGradient id="waterFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#0ea5e9" stop-opacity="0.15" />
                                <stop offset="100%" stop-color="#0ea5e9" stop-opacity="0" />
                            </linearGradient>
                            <filter id="waterShadow" x="-50%" y="-50%" width="200%" height="200%">
                                <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.15" />
                            </filter>
                        </defs>

                        <!-- Y Axis Labels -->
                        @for ($i = 0; $i <= 4; $i++)
                            @php
                                $stepValue = $waterDisplayMin + ($waterDisplayRange / 4) * $i;
                                $yPos = 100 - ($i / 4) * 100;
                            @endphp
                            <text x="40" y="{{ number_format($yPos * 4 + 8, 1, '.', '') }}" class="text-xs fill-gray-400 font-semibold" text-anchor="end">
                                {{ number_format($stepValue, 1, ',', '.') }}L
                            </text>
                            <line x1="50" y1="{{ number_format($yPos * 4, 1, '.', '') }}" x2="1180" y2="{{ number_format($yPos * 4, 1, '.', '') }}" stroke="#f3f4f6" stroke-width="1" />
                        @endfor

                        <!-- Y Axis -->
                        <line x1="50" y1="0" x2="50" y2="400" stroke="#d1d5db" stroke-width="2" />
                        <!-- X Axis -->
                        <line x1="50" y1="400" x2="1180" y2="400" stroke="#d1d5db" stroke-width="2" />

                        <!-- Area Fill -->
                        <path d="M 50 400 L {{ implode(' L ', array_map(fn($p) => 50 + ($p['x'] / 100 * 1130) . ',' . ($p['y'] / 100 * 400), $waterCircleData)) }} L 1180 400 Z" fill="url(#waterFill)" />

                        <!-- Line -->
                        <polyline points="@foreach($waterCircleData as $data){{ 50 + ($data['x'] / 100 * 1130) }},{{ ($data['y'] / 100 * 400) }} @endforeach" fill="none" stroke="url(#waterLine)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" filter="url(#waterShadow)" />

                        <!-- Circles (Data Points) -->
                        @foreach($waterCircleData as $index => $data)
                            @php
                                $cx = 50 + ($data['x'] / 100 * 1130);
                                $cy = $data['y'] / 100 * 400;
                            @endphp
                            <g class="water-point" data-quantity="{{ $data['quantity'] }}" data-date="{{ $data['date'] }}">
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="8" fill="#0ea5e9" opacity="0.1" />
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="5" fill="white" stroke="#0ea5e9" stroke-width="2.5" />
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="2" fill="#0ea5e9" />
                            </g>
                        @endforeach

                        <!-- X Axis Labels -->
                        @foreach($waterCircleData as $index => $data)
                            @php
                                $cx = 50 + ($data['x'] / 100 * 1130);
                            @endphp
                            <text x="{{ number_format($cx, 1, '.', '') }}" y="425" class="text-xs fill-gray-500 font-medium" text-anchor="middle">
                                {{ $data['date'] }}
                            </text>
                        @endforeach
                    </svg>

                    <div id="water-chart-tooltip" class="absolute hidden bg-gray-900 text-white px-3 py-2 rounded-lg text-xs font-semibold pointer-events-none z-10 whitespace-nowrap" style="left: 0; top: 0;">
                        <div class="flex items-center gap-2">
                            <span class="text-cyan-400">●</span>
                            <span id="water-tooltip-text">2.0 L</span>
                        </div>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('.water-point').forEach(point => {
                        point.addEventListener('mouseenter', function(e) {
                            const tooltip = document.getElementById('water-chart-tooltip');
                            const quantity = this.getAttribute('data-quantity');
                            const date = this.getAttribute('data-date');
                            tooltip.querySelector('#water-tooltip-text').textContent = quantity + ' L • ' + date;
                            tooltip.classList.remove('hidden');

                            const rect = this.getBoundingClientRect();
                            const containerRect = this.closest('.relative').getBoundingClientRect();
                            tooltip.style.left = (rect.left - containerRect.left) + 'px';
                            tooltip.style.top = (rect.top - containerRect.top - 35) + 'px';
                        });

                        point.addEventListener('mouseleave', function() {
                            document.getElementById('water-chart-tooltip').classList.add('hidden');
                        });
                    });
                </script>
            @else
                <!-- Mensagem quando não há dados suficientes -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-droplet text-4xl text-blue-400"></i>
                    </div>
                    <p class="text-gray-600 font-bold mb-2">
                        @if($waterChartEntries->count() == 0)
                            Nenhum registro de hidratacao encontrado
                        @else
                            Apenas 1 registro encontrado
                        @endif
                    </p>
                    <p class="text-sm text-gray-500">
                        @if($waterChartEntries->count() == 0)
                            O paciente ainda não registrou consumo de água nos últimos 30 dias.
                        @else
                            São necessários pelo menos 2 registros para visualizar o gráfico de evolução.
                        @endif
                    </p>
                    @if($waterChartEntries->count() == 1)
                        <div class="mt-6 inline-block bg-blue-50 border border-blue-200 rounded-xl px-6 py-4">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-droplet text-blue-600 mr-2"></i>
                                <strong>{{ number_format($waterChartEntries->first()->quantity_liters, 1, ',', '.') }}L</strong>
                                em {{ $waterChartEntries->first()->consumption_date->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- IMC Information -->
        @if($imc && $imcClass)
            <div class="bg-gradient-to-r {{ $imcClass['badge'] == 'green' ? 'from-green-50 to-emerald-50 border border-green-200' : ($imcClass['badge'] == 'yellow' ? 'from-yellow-50 to-orange-50 border border-yellow-200' : 'from-red-50 to-orange-50 border border-red-200') }} rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 {{ \App\Helpers\IMCHelper::getBadgeClasses($imcClass['badge']) }} rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info-circle text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold {{ $imcClass['badge'] == 'green' ? 'text-green-900' : ($imcClass['badge'] == 'yellow' ? 'text-yellow-900' : 'text-red-900') }} mb-2">{{ $imcClass['classificacao'] }} (IMC: {{ $imc }} kg/m²)</h4>
                        <p class="text-sm {{ $imcClass['badge'] == 'green' ? 'text-green-800' : ($imcClass['badge'] == 'yellow' ? 'text-yellow-800' : 'text-red-800') }} leading-relaxed">
                            {{ $imcClass['observacoes'] }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-patient-panel-layout>
