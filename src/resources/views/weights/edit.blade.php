<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-emerald-50 via-white to-white pb-20 md:pb-6">
        <!-- Back Navigation -->
        <div class="sticky top-14 md:top-16 bg-white border-b border-gray-100 z-30">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <a href="{{ route('weights.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold transition-colors">
                    <i class="fas fa-chevron-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900">Editar Registro</h1>
                        <p class="text-sm text-gray-500 mt-1">Atualize seus dados de peso</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <x-weights.form :entry="$entry" />
                </div>

                <!-- Info Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm sticky top-20 md:top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-info-circle text-emerald-600"></i> Informacoes
                        </h3>

                        <div class="space-y-5">
                            <!-- Created Date -->
                            <div class="pb-5 border-b border-gray-200">
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Criado em</p>
                                <p class="text-gray-900 font-semibold mt-2 flex items-center gap-2">
                                    <i class="fas fa-calendar-plus text-emerald-400"></i>
                                    {{ $entry->created_at->format('d/m/Y \a\s H:i') }}
                                </p>
                            </div>

                            <!-- Updated Date -->
                            <div class="pb-5 border-b border-gray-200">
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Ultima atualizacao</p>
                                <p class="text-gray-900 font-semibold mt-2 flex items-center gap-2">
                                    <i class="fas fa-clock text-green-400"></i>
                                    {{ $entry->updated_at->format('d/m/Y \a\s H:i') }}
                                </p>
                            </div>

                            <!-- Weight -->
                            <div class="pb-5 border-b border-gray-200">
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Peso</p>
                                <p class="text-2xl font-black text-emerald-600 mt-2 flex items-center gap-2">
                                    <i class="fas fa-scale-balanced"></i>
                                    {{ number_format($entry->weight_kg, 1, ',', '.') }}kg
                                </p>
                            </div>

                            <!-- Measured Date -->
                            <div>
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Data da Medicao</p>
                                <p class="text-gray-900 font-semibold mt-2 flex items-center gap-2">
                                    <i class="fas fa-calendar text-purple-400"></i>
                                    {{ $entry->measured_date->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Delete Button -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <x-delete-modal
                                :action="route('weights.destroy', $entry)"
                                title="Deletar Registro de Peso"
                                message="Tem certeza que deseja deletar este registro de peso? Esta acao nao pode ser desfeita."
                                button-text="Deletar"
                                class="w-full px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-red-200 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-trash-alt"></i> Deletar Registro
                            </x-delete-modal>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
