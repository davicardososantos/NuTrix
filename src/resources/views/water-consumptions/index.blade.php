<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-3xl text-gray-900">
                    💧 Meu Consumo de Água
                </h2>
                <p class="text-sm text-gray-500 mt-2">Acompanhe seu consumo diário e histórico</p>
            </div>
            <a href="{{ route('water-consumptions.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                🔄 Atualizar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Resumo do dia - Premium Card -->
            <div class="bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8 mb-8 border border-blue-100 overflow-hidden relative">
                <div class="relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <!-- Seção de Meta/Progresso -->
                        <div class="lg:col-span-6">                            
                            <div class="flex items-start justify-between mb-8">
                                <div class="bg-blue-100 px-4 py-2 rounded-full">
                                    <p class="text-xs font-bold text-blue-700">⏰ Agora</p>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-3xl">💧</span>
                                        <div>
                                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wide">Meta Diária</p>
                                            <p class="text-lg font-black text-gray-900">2,5 L</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-700">{{ round(($totalToday / 2500) * 100, 0) }}%</p>
                                        <p class="text-xs text-gray-500 font-semibold mt-1">concluído</p>
                                    </div>
                                </div>

                                <div class="relative w-full bg-gray-300 rounded-full overflow-hidden h-5 shadow-md">
                                    <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-1000 ease-out flex items-center justify-end pr-3" style="width: {{ min(($totalToday / 2500) * 100, 100) }}%; background: linear-gradient(to right, #3b82f6, #1d4ed8); box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.1), 0 0 10px rgba(59, 130, 246, 0.8);">
                                        <span class="text-xs font-bold text-white drop-shadow-lg">{{ round(($totalToday / 2500) * 100, 0) }}%</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-xs text-gray-500 font-medium">
                                    <span>{{ $totalToday }} ml</span>
                                    <span>2.500 ml</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Formulário -->
                <div class="lg:col-span-1">
                    <x-water-consumptions.form />
                </div>

                <!-- Lista de consumos -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Histórico
                            </h3>
                        </div>

                        <div class="p-6">
                            @if($consumptions->count() > 0)
                                @foreach($consumptions as $consumption)
                                    <x-water-consumptions.card :consumption="$consumption" />
                                @endforeach

                                <!-- Paginação -->
                                <div class="mt-6 border-t pt-6">
                                    {{ $consumptions->links() }}
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <p class="text-gray-500">{{ __('Nenhum consumo registrado ainda.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
