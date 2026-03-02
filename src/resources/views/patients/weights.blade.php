@php
    use App\Helpers\IMCHelper;
    
    $imc = $patient && $patient->weight && $patient->height ? IMCHelper::calculate($patient->weight, $patient->height) : null;
    $imcClass = $imc ? IMCHelper::classify($imc, $patient->birth_date) : null;
@endphp

<x-patient-panel-layout
    :patient="$patient"
    activeTab="weights"
    title="Evolução de Peso"
    :subtitle="$patient->full_name"
>
    <x-slot name="headerIcon">
        <i class="fas fa-scale-balanced text-xl md:text-2xl text-green-600"></i>
    </x-slot>

    <div class="space-y-6">
        <!-- Progress Card -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-emerald-600 to-green-600 rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-emerald-200 relative overflow-hidden group">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                </div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest">Peso Atual</p>
                            <p class="text-5xl md:text-6xl font-black mt-2">
                                @if($latestEntry)
                                    {{ number_format($latestEntry->weight_kg, 1, ',', '.') }}<span class="text-3xl">kg</span>
                                @else
                                    --
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest">Variacao</p>
                            <p class="text-4xl font-black mt-2">
                                @if($weightChange !== null)
                                    {{ $weightChange > 0 ? '+' : '' }}{{ number_format($weightChange, 1, ',', '.') }}<span class="text-2xl">kg</span>
                                @else
                                    --
                                @endif
                            </p>
                            <p class="text-emerald-200 text-xs mt-1">
                                @if($latestEntry)
                                    Ultima medicao: {{ $latestEntry->measured_date->format('d/m/Y') }}
                                @else
                                    Sem registros
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-emerald-100 text-sm font-semibold">
                            @if($weightChange !== null)
                                @if($weightChange > 0)
                                    <i class="fas fa-arrow-trend-up text-yellow-200 mr-1"></i> Subiu desde a ultima medida
                                @elseif($weightChange < 0)
                                    <i class="fas fa-arrow-trend-down text-yellow-200 mr-1"></i> Desceu desde a ultima medida
                                @else
                                    <i class="fas fa-minus text-yellow-200 mr-1"></i> Sem alteracao
                                @endif
                            @else
                                <span class="text-lg">⚖️</span> Nenhum peso registrado
                            @endif
                        </p>
                        <div class="text-2xl opacity-20">
                            <i class="fas fa-scale-balanced"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="space-y-4">
                @if($imc && $imcClass)
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-xs uppercase text-gray-500 font-bold tracking-wider mb-1">Indice de Massa Corporal</p>
                                <p class="text-4xl font-black text-gray-900">{{ $imc }}<span class="text-lg text-gray-600">kg/m²</span></p>
                            </div>
                            <div class="w-12 h-12 rounded-xl {{ \App\Helpers\IMCHelper::getBadgeClasses($imcClass['badge']) }} flex items-center justify-center">
                                <i class="fas fa-chart-pie text-lg"></i>
                            </div>
                        </div>
                        <div class="px-3 py-2 rounded-lg {{ \App\Helpers\IMCHelper::getBadgeClasses($imcClass['badge']) }} text-center mb-4">
                            <p class="font-bold text-sm">{{ $imcClass['classificacao'] }}</p>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            {{ $imcClass['observacoes'] }}
                        </p>
                    </div>
                @else
                    <div class="bg-white border-2 border-dashed border-gray-300 rounded-2xl p-6 shadow-sm text-center">
                        <i class="fas fa-info-circle text-2xl text-gray-400 mb-2"></i>
                        <p class="text-sm font-semibold text-gray-600">Dados incompletos para calcular IMC</p>
                        <p class="text-xs text-gray-500 mt-1">Atualize o peso e altura do paciente ao editar seu perfil</p>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs uppercase text-gray-500 font-bold mb-2">Total de Registros</p>
                        <p class="text-3xl font-black text-gray-900">{{ $entries->total() }}</p>
                    </div>
                    @if($chartEntries->count() > 0)
                        <div>
                            <p class="text-xs uppercase text-gray-500 font-bold mb-2">Maior Peso</p>
                            <p class="text-3xl font-black text-gray-900">{{ number_format($chartEntries->pluck('weight_kg')->max(), 1, ',', '.') }}<span class="text-lg">kg</span></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500 font-bold mb-2">Menor Peso</p>
                        <p class="text-3xl font-black text-gray-900">{{ number_format($chartEntries->pluck('weight_kg')->min(), 1, ',', '.') }}<span class="text-lg">kg</span></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500 font-bold mb-2">Amplitude</p>
                        <p class="text-3xl font-black text-gray-900">
                            {{ number_format($chartEntries->pluck('weight_kg')->max() - $chartEntries->pluck('weight_kg')->min(), 1, ',', '.') }}<span class="text-lg">kg</span>
                        </p>
                    </div>
                    @else
                        <div class="col-span-2 text-center py-4">
                            <p class="text-gray-500 font-semibold">Nenhum registro</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($chartEntries->count() > 1)
            <!-- Line Chart (Ultimos 10 Registros) -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-line text-emerald-600"></i> Ultimos registros
                </h3>
                @php
                    $weights = $chartEntries->pluck('weight_kg')->map(fn ($value) => (float) $value);
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
                            'date' => $chartEntries[$index]->measured_date->format('d/m')
                        ];
                    }

                    $pointsAttr = implode(' ', $points);
                    $ySteps = 4;
                @endphp
                <div class="relative">
                    <svg viewBox="0 0 1200 400" class="w-full" preserveAspectRatio="xMidYMid meet" style="max-height: 300px;">
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
                                <!-- Outer glow -->
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="8" fill="#10b981" opacity="0.1" />
                                <!-- Main circle -->
                                <circle cx="{{ number_format($cx, 1, '.', '') }}" cy="{{ number_format($cy, 1, '.', '') }}" r="5" fill="white" stroke="#10b981" stroke-width="2.5" />
                                <!-- Inner dot -->
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

                    <!-- Tooltip (Hidden by default) -->
                    <div id="chart-tooltip" class="absolute hidden bg-gray-900 text-white px-3 py-2 rounded-lg text-xs font-semibold pointer-events-none z-10 whitespace-nowrap" style="left: 0; top: 0;">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400">●</span>
                            <span id="tooltip-text">75.5 kg</span>
                        </div>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('.weight-point').forEach(point => {
                        point.addEventListener('mouseenter', function(e) {
                            const tooltip = document.getElementById('chart-tooltip');
                            const weight = this.getAttribute('data-weight');
                            const date = this.getAttribute('data-date');
                            tooltip.querySelector('#tooltip-text').textContent = weight + ' kg • ' + date;
                            tooltip.classList.remove('hidden');

                            const rect = this.getBoundingClientRect();
                            const containerRect = this.closest('.relative').getBoundingClientRect();
                            tooltip.style.left = (rect.left - containerRect.left) + 'px';
                            tooltip.style.top = (rect.top - containerRect.top - 35) + 'px';
                        });

                        point.addEventListener('mouseleave', function() {
                            document.getElementById('chart-tooltip').classList.add('hidden');
                        });
                    });
                </script>
            </div>
        @endif

        <!-- History Section -->
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-transparent">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-history text-emerald-600"></i> Historico ({{ $entries->total() }})
                </h3>
            </div>

            @if($entries->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($entries as $entry)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gradient-to-r hover:from-emerald-50 hover:to-transparent transition-all duration-300 group">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:shadow-md transition-shadow">
                                    <i class="fas fa-scale-balanced text-lg text-emerald-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm md:text-base font-bold text-gray-900">{{ number_format($entry->weight_kg, 1, ',', '.') }} kg</p>
                                    <p class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $entry->measured_date->format('d/m/Y') }}
                                        <span class="text-gray-400">•</span>
                                        {{ $entry->created_at->locale('pt_BR')->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($entries->hasPages())
                    <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                        {{ $entries->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-16 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-scale-balanced text-3xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-600 font-semibold mb-1">Nenhum peso registrado</p>
                    <p class="text-sm text-gray-500">O paciente ainda não registrou nenhum peso</p>
                </div>
            @endif
        </div>
    </div>
</x-patient-panel-layout>
