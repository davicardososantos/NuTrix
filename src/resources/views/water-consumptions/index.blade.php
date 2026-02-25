<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="font-black text-4xl text-gray-900">
                    💧 Meu Consumo de Água
                </h2>
                <p class="text-gray-600 mt-2">Acompanhe seu hidratação diária e evolução</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition font-semibold text-sm">
                    ← Voltar
                </a>
                <a href="{{ route('water-consumptions.index') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:shadow-lg hover:scale-105 transition-all font-semibold">
                    🔄 Atualizar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-start gap-3">
                    <span class="text-xl">✅</span>
                    <div>
                        <p class="font-semibold">Consumo registrado com sucesso!</p>
                        <p class="text-sm text-green-700">Você está no caminho certo! 💪</p>
                    </div>
                </div>
            @endif

            <!-- Hero Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1: Progress Today -->
                <div class="md:col-span-2 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg p-8 border border-blue-100">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="text-sm font-bold text-blue-900 uppercase tracking-widest">⏰ Progresso Hoje</p>
                            <h3 class="text-3xl font-black text-gray-900 mt-2">{{ round(($totalToday / $dailyWaterGoal) * 100, 0) }}%</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600">{{ number_format($totalToday / 1000, 1, ',', '.') }}L</p>
                            <p class="text-sm text-gray-600 mt-2 font-medium">de {{ number_format($dailyWaterGoal / 1000, 1, ',', '.') }}L</p>
                        </div>
                    </div>

                    <div class="relative w-full bg-gray-300 rounded-full overflow-hidden h-6 shadow-md">
                        <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-1000 ease-out flex items-center justify-end pr-3" 
                             style="width: {{ min(($totalToday / $dailyWaterGoal) * 100, 100) }}%; background: linear-gradient(to right, #0077be, #00d4ff);">
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-blue-200 grid grid-cols-3 gap-4 text-center">
                        @if(round(($totalToday / $dailyWaterGoal) * 100, 0) >= 100)
                            <div class="p-3 bg-green-100 rounded-lg">
                                <p class="text-2xl">🎉</p>
                                <p class="text-xs font-bold text-green-900 mt-1">Meta Atingida!</p>
                            </div>
                        @else
                            <div class="p-3 bg-amber-100 rounded-lg">
                                <p class="text-2xl">🏃</p>
                                <p class="text-xs font-bold text-amber-900 mt-1">Em Progresso</p>
                            </div>
                        @endif
                        <div class="p-3 bg-gray-100 rounded-lg">
                            <p class="text-xl text-gray-600">{{ intval($totalToday) }}</p>
                            <p class="text-xs font-bold text-gray-700 mt-1">ml Bebidos</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <p class="text-xl text-blue-600">{{ intval($dailyWaterGoal - $totalToday) }}</p>
                            <p class="text-xs font-bold text-blue-900 mt-1">ml Restante</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Tips & Actions -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">💡 Dicas Rápidas</p>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex gap-2">
                                <span class="text-lg">🌅</span>
                                <span>Beba água morna ao despertar</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-lg">🍎</span>
                                <span>Consuma frutas com alto teor de água</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-lg">⏰</span>
                                <span>Estabeleça lembretes de 2h em 2h</span>
                            </li>
                        </ul>
                    </div>
                    <button class="mt-4 w-full py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                        📱 Definir Lembretes
                    </button>
                </div>
            </div>

            <!-- Form & History & Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Novo Consumo</h3>
                        <x-water-consumptions.form />
                    </div>
                </div>

                <!-- Right: History & Chart -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- 7 Days Chart -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900">📊 Últimos 7 Dias</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-7 gap-2">
                                @foreach($sevenDaysData as $day)
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-gray-600 mb-2 uppercase">{{ substr($day['day'], 0, 3) }}</p>
                                        <p class="text-xs text-gray-500 mb-3">{{ $day['shortDay'] }}</p>
                                        
                                        <div class="relative h-32 bg-gray-100 rounded-lg flex items-end justify-center p-1 border-2 border-gray-200 hover:border-blue-400 transition">
                                            @php
                                                $percentage = $dailyWaterGoal > 0 ? min(($day['amount_ml'] / $dailyWaterGoal) * 100, 100) : 0;
                                            @endphp
                                            <div class="w-full rounded-md transition-all duration-300" 
                                                 style="height: {{ $percentage }}%; background: linear-gradient(to top, #0077be, #00d4ff);">
                                            </div>
                                        </div>
                                        
                                        <p class="text-sm font-bold text-gray-900 mt-2">{{ number_format($day['amount_ml'] / 1000, 2, ',', '.') }}L</p>
                                        
                                        @if($day['amount_ml'] >= $dailyWaterGoal)
                                            <span class="text-lg">✓</span>
                                        @else
                                            <span class="text-xs text-red-600">{{ intval($dailyWaterGoal - $day['amount_ml']) }}ml</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- History -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">📜 Histórico</h3>
                            <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 focus:ring-2 focus:ring-blue-600">
                                <option>Últimas 24h</option>
                                <option>Última semana</option>
                                <option>Últimas 2 semanas</option>
                            </select>
                        </div>

                        <div class="p-6">
                            @if($consumptions->count() > 0)
                                <div class="space-y-3 max-h-96 overflow-y-auto">
                                    @foreach($consumptions as $consumption)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition border border-gray-200">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-xl">
                                                    💧
                                                </div>
                                                <div>
                                                        <p class="font-semibold text-gray-900">{{ $consumption->amount_ml }} ml</p>
                                                        <p class="text-xs text-gray-500">{{ $consumption->created_at->format('d/m') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                                    {{ $consumption->created_at->diffForHumans() }}
                                                </span>
                                                <form action="{{ route('water-consumptions.destroy', $consumption) }}" method="POST" onsubmit="return confirm('Deseja remover este registro?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm transition">
                                                        ✕
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                @if($consumptions->hasPages())
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        {{ $consumptions->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-12">
                                    <p class="text-5xl mb-4">💧</p>
                                    <p class="text-gray-500 font-semibold">Nenhum consumo registrado ainda.</p>
                                    <p class="text-sm text-gray-400 mt-2">Comece a registrar seu consumo para acompanhar seu progresso!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements Section -->
            <div class="mt-8 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl shadow-lg p-8 border border-amber-200">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span>🏆</span> Seus Conquistas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="flex items-center gap-4 p-4 bg-white rounded-lg border border-amber-200">
                        <span class="text-4xl">🎯</span>
                        <div>
                            <p class="font-bold text-gray-900">Meta Atingida</p>
                            <p class="text-xs text-gray-600">Beba 2.5L de água</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-lg border border-amber-200">
                        <span class="text-4xl">🔥</span>
                        <div>
                            <p class="font-bold text-gray-900">Sequência</p>
                            <p class="text-xs text-gray-600">7 dias seguidos</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-lg border border-amber-200">
                        <span class="text-4xl">⭐</span>
                        <div>
                            <p class="font-bold text-gray-900">Consistência</p>
                            <p class="text-xs text-gray-600">90%+ de meta</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-lg border border-amber-200">
                        <span class="text-4xl">💎</span>
                        <div>
                            <p class="font-bold text-gray-900">Super Hidratado</p>
                            <p class="text-xs text-gray-600">+3L em um dia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
