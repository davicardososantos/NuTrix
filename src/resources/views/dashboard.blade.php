<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="font-black text-4xl text-gray-900">
                    📊 Dashboard
                </h2>
                <p class="text-gray-600 mt-2">{{ auth()->user()->name }}, bem-vindo de volta! Veja seu progresso abaixo</p>
            </div>
            <a href="{{ route('water-consumptions.index') }}" class="px-6 py-3 bg-gradient-to-r from-amber-700 to-green-700 text-white font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200">
                💧 Registrar Consumo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Card 1: Total Consumed -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <span class="text-2xl">💧</span>
                        </div>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Hoje</span>
                    </div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Consumo Hoje</p>
                    <p class="text-3xl font-black text-gray-900">2.5L</p>
                    <p class="text-xs text-green-600 mt-2 font-semibold">↑ Meta atingida!</p>
                </div>

                <!-- Card 2: Weekly Average -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <span class="text-2xl">📈</span>
                        </div>
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full">Semanal</span>
                    </div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Média Semanal</p>
                    <p class="text-3xl font-black text-gray-900">2.3L</p>
                    <p class="text-xs text-gray-500 mt-2">+12% vs semana passada</p>
                </div>

                <!-- Card 3: Streak -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <span class="text-2xl">🔥</span>
                        </div>
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1 rounded-full">Sequência</span>
                    </div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Dias Seguidos</p>
                    <p class="text-3xl font-black text-gray-900">7</p>
                    <p class="text-xs text-orange-600 mt-2 font-semibold">👏 Você está indo bem!</p>
                </div>

                <!-- Card 4: Overall Consistency -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">Mês</span>
                    </div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Consistência</p>
                    <p class="text-3xl font-black text-gray-900">94%</p>
                    <p class="text-xs text-purple-600 mt-2 font-semibold">Excelente desempenho</p>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Progress Chart -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Progresso Semanal</h3>
                            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 focus:ring-2 focus:ring-amber-600">
                                <option>Última semana</option>
                                <option>Último mês</option>
                                <option>Últimos 3 meses</option>
                            </select>
                        </div>
                        
                        <!-- Simple Bar Chart -->
                        <div class="space-y-4">
                            <div class="flex items-end justify-between h-64">
                                <!-- Bar 1 -->
                                <div class="flex flex-col items-center w-12">
                                    <div class="w-full bg-gradient-to-t from-amber-600 to-amber-500 rounded-t-lg" style="height: 85%;"></div>
                                    <p class="text-xs text-gray-600 mt-2 font-semibold">Seg</p>
                                </div>
                                <!-- Bar 2 -->
                                <div class="flex flex-col items-center w-12">
                                    <div class="w-full bg-gradient-to-t from-amber-600 to-amber-500 rounded-t-lg" style="height: 100%;"></div>
                                    <p class="text-xs text-gray-600 mt-2 font-semibold">Ter</p>
                                </div>
                                <!-- Bar 3 -->
                                <div class="flex flex-col items-center w-12">
                                    <div class="w-full bg-gradient-to-t from-amber-600 to-amber-500 rounded-t-lg" style="height: 75%;"></div>
                                    <p class="text-xs text-gray-600 mt-2 font-semibold">Qua</p>
                                </div>
                                <!-- Bar 4 -->
                                <div class="flex flex-col items-center w-12">
                                    <div class="w-full bg-gradient-to-t from-green-600 to-green-500 rounded-t-lg" style="height: 65%;"></div>
                                    <p class="text-xs text-gray-600 mt-2 font-semibold">Qui</p>
                                </div>
                                <!-- Bar 5 -->
                                <div class="flex flex-col items-center w-12">
                                    <div class="w-full bg-gradient-to-t from-green-600 to-green-500 rounded-t-lg" style="height: 90%;"></div>
                                    <p class="text-xs text-gray-600 mt-2 font-semibold">Sex</p>
                                </div>
                                <!-- Bar 6 -->
                                <div class="flex flex-col items-center w-12">
                                    <div class="w-full bg-gradient-to-t from-green-600 to-green-500 rounded-t-lg" style="height: 95%;"></div>
                                    <p class="text-xs text-gray-600 mt-2 font-semibold">Sab</p>
                                </div>
                                <!-- Bar 7 -->
                                <div class="flex flex-col items-center w-12">
                                    <div class="w-full bg-gradient-to-t from-green-600 to-green-500 rounded-t-lg" style="height: 100%;"></div>
                                    <p class="text-xs text-gray-600 mt-2 font-semibold">Dom</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-amber-600 rounded-full"></div>
                                <span class="text-sm text-gray-600">Abaixo da meta</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                                <span class="text-sm text-gray-600">Meta atingida</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Quick Actions & Tips -->
                <div class="space-y-8">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Ações Rápidas</h3>
                        <div class="space-y-3">
                            <a href="{{ route('water-consumptions.index') }}" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition cursor-pointer">
                                <span class="text-xl">💧</span>
                                <span class="text-sm font-medium text-gray-900">Adicionar consumo</span>
                                <span class="ml-auto text-gray-400">→</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition cursor-pointer">
                                <span class="text-xl">📊</span>
                                <span class="text-sm font-medium text-gray-900">Ver relatório</span>
                                <span class="ml-auto text-gray-400">→</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition cursor-pointer">
                                <span class="text-xl">⚙️</span>
                                <span class="text-sm font-medium text-gray-900">Configurações</span>
                                <span class="ml-auto text-gray-400">→</span>
                            </a>
                        </div>
                    </div>

                    <!-- Daily Tip -->
                    <div class="bg-gradient-to-br from-amber-50 to-green-50 rounded-2xl shadow-lg p-6 border border-amber-200">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="text-2xl">💡</span>
                            <p class="text-xs font-bold text-amber-900 uppercase tracking-wider">Dica do Dia</p>
                        </div>
                        <p class="text-sm text-gray-800 leading-relaxed">
                            Beba água constantemente ao longo do dia em pequenas quantidades. Isto ajuda na absorção melhor e mantém seu corpo hidratado.
                        </p>
                        <p class="text-xs text-gray-600 mt-4 font-semibold">🌟 Você está fazendo ótimo!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
