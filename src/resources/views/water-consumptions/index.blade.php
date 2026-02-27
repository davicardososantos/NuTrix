<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 via-white to-white pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-gray-100 z-30">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-droplet text-xl md:text-2xl text-cyan-600"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-black text-gray-900">Hidratação</h1>
                        <p class="text-xs md:text-sm text-gray-500 mt-0.5">Acompanhe seu consumo de água</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                    <i class="fas fa-check-circle text-lg text-green-600 flex-shrink-0"></i>
                    <p class="font-semibold text-sm text-green-900">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Main Progress Card -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Progress Card -->
                <div class="bg-gradient-to-br from-blue-600 to-cyan-600 rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-blue-200 relative overflow-hidden group">
                    <!-- Animated background -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-8">
                            <div>
                                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Progresso de Hoje</p>
                                <p class="text-5xl md:text-6xl font-black mt-2">{{ round(($totalToday / $dailyWaterGoal) * 100, 0) }}<span class="text-3xl">%</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Consumido</p>
                                <p class="text-4xl font-black mt-2">{{ number_format($totalToday / 1000, 1, ',', '.') }}<span class="text-2xl">L</span></p>
                                <p class="text-blue-200 text-xs mt-1">de {{ number_format($dailyWaterGoal / 1000, 1, ',', '.') }}L</p>
                            </div>
                        </div>

                        <!-- Premium Progress Bar -->
                        <div class="relative w-full bg-white/20 backdrop-blur-sm rounded-full overflow-hidden h-4 shadow-inner mb-4">
                            <div
                                class="h-full rounded-full transition-all duration-1000 ease-out bg-gradient-to-r from-white via-blue-100 to-white shadow-lg"
                                style="width: {{ min(($totalToday / $dailyWaterGoal) * 100, 100) }}%"
                            ></div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center justify-between">
                            <p class="text-blue-100 text-sm font-semibold">
                                @if(round(($totalToday / $dailyWaterGoal) * 100, 0) >= 100)
                                    <i class="fas fa-star text-yellow-300 mr-1"></i> Meta atingida! Parabéns
                                @else
                                    <span class="text-lg">💧</span> Faltam {{ intval($dailyWaterGoal - $totalToday) }}ml
                                @endif
                            </p>
                            <div class="text-2xl opacity-20">
                                <i class="fas fa-tint"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forms Section -->
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm h-fit">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-blue-600"></i> Registrar Consumo
                    </h3>
                    <form action="{{ route('water-consumptions.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Quantidade (ml)
                            </label>
                            <input
                                type="number"
                                name="amount_ml"
                                placeholder="500"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required
                                min="1"
                                max="10000"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Data
                            </label>
                            <input
                                type="date"
                                name="consumption_date"
                                value="{{ today()->format('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required
                            />
                        </div>
                        <button
                            type="submit"
                            class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-blue-200 transition-all duration-300 flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-check-circle"></i> Adicionar
                        </button>
                    </form>
                </div>
            </div>

            <!-- 7-Day Chart -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-blue-600"></i> Últimos 7 Dias
                </h3>
                <div class="grid grid-cols-7 gap-2">
                    @foreach($sevenDaysData as $day)
                        <div class="flex flex-col items-center group">
                            <p class="text-xs font-bold text-gray-600 mb-1">{{ substr($day['day'], 0, 3) }}</p>
                            <p class="text-xs text-gray-500 mb-2">{{ $day['shortDay'] }}</p>
                            <div class="relative h-24 bg-gray-100 rounded-xl flex items-end justify-center p-2 w-full border border-gray-200 hover:border-cyan-300 transition-all">
                                @php
                                    $percentage = $dailyWaterGoal > 0 ? min(($day['amount_ml'] / $dailyWaterGoal) * 100, 100) : 0;
                                @endphp
                                <div
                                    class="w-full rounded-lg transition-all duration-500 bg-gradient-to-t from-blue-500 to-cyan-400 shadow-lg shadow-blue-200/50 group-hover:shadow-xl"
                                    style="height: {{ $percentage }}%; min-height: 2px;"
                                ></div>
                            </div>
                            <p class="text-xs font-bold text-gray-900 mt-2">{{ number_format($day['amount_ml'] / 1000, 1, ',', '.') }}L</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- History Section -->
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-transparent">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-history text-blue-600"></i> Histórico ({{ $consumptions->total() }})
                    </h3>
                </div>

                @if($consumptions->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($consumptions as $consumption)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-300 group">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:shadow-md transition-shadow">
                                        <i class="fas fa-droplet text-lg text-cyan-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm md:text-base font-bold text-gray-900">{{ $consumption->amount_ml }} ml</p>
                                        <p class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ $consumption->consumption_date->format('d/m/Y') }}
                                            <span class="text-gray-400">•</span>
                                            {{ $consumption->created_at->locale('pt_BR')->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <x-delete-modal
                                    :action="route('water-consumptions.destroy', $consumption)"
                                    title="Deletar Registro"
                                    message="Tem certeza que deseja deletar este consumo de água?"
                                    button-text="Deletar"
                                    class="ml-4 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                >
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </x-delete-modal>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($consumptions->hasPages())
                        <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                            {{ $consumptions->links() }}
                        </div>
                    @endif
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i class="fas fa-droplet text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-600 font-semibold mb-1">Nenhum consumo registrado</p>
                        <p class="text-sm text-gray-500">Comece a registrar acima para acompanhar seu progresso 💧</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
