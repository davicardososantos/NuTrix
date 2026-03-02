@props(['patient', 'activeTab' => 'profile'])

<aside {{ $attributes->merge(['class' => 'lg:col-span-3']) }}>
    <div class="bg-gradient-to-br from-white to-green-50 rounded-3xl shadow-lg border-2 border-green-200 p-6 sticky top-6">
        <!-- Patient Info Card -->
        <div class="flex items-center gap-4 mb-8 pb-6 border-b-2 border-green-200">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-black text-2xl shadow-lg">
                {{ strtoupper(substr($patient->full_name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs uppercase text-green-600 font-bold tracking-wide mb-1">Paciente</p>
                <p class="font-black text-gray-900 text-lg leading-tight truncate">{{ $patient->full_name }}</p>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                    <i class="fas fa-key text-green-500"></i>
                    <span class="font-mono font-semibold">{{ $patient->code }}</span>
                </p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-1">
            <!-- VISÃO GERAL SECTION - O CORAÇÃO DO SISTEMA -->
            <div class="px-2 py-3">
                <p class="text-xs uppercase text-gray-500 font-bold mb-2">Visão Geral</p>

                <!-- Acompanhamento -->
                <a
                    href="{{ route('patients.monitoring', $patient) }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 {{ $activeTab === 'monitoring' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg shadow-green-200' : 'bg-white border-2 border-green-100 text-gray-700 hover:border-green-300 hover:shadow-md' }}"
                >
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $activeTab === 'monitoring' ? 'bg-white/20' : 'bg-green-50' }}">
                        <i class="fas fa-heartbeat text-sm {{ $activeTab === 'monitoring' ? 'text-white' : 'text-green-600' }}"></i>
                    </div>
                    <span class="flex-1 text-sm">Acompanhamento</span>
                    @if($activeTab === 'monitoring')
                        <i class="fas fa-chevron-right text-xs"></i>
                    @endif
                </a>
            </div>

            <!-- PACIENTE SECTION -->
            <div class="px-2 py-3">
                <p class="text-xs uppercase text-gray-500 font-bold mb-2">Paciente</p>

                <!-- Profile -->
                <a
                    href="{{ route('patients.edit', $patient) }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 {{ $activeTab === 'profile' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg shadow-green-200' : 'bg-white border-2 border-green-100 text-gray-700 hover:border-green-300 hover:shadow-md' }}"
                >
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $activeTab === 'profile' ? 'bg-white/20' : 'bg-green-50' }}">
                        <i class="fas fa-user text-sm {{ $activeTab === 'profile' ? 'text-white' : 'text-green-600' }}"></i>
                    </div>
                    <span class="flex-1 text-sm">Perfil</span>
                    @if($activeTab === 'profile')
                        <i class="fas fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- Avaliação Inicial -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed mt-2 hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-stethoscope text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Avaliação Inicial</span>
                </div>
            </div>

            <!-- EVOLUÇÃO SECTION - AQUI MORA O OURO -->
            <div class="px-2 py-3">
                <p class="text-xs uppercase text-gray-500 font-bold mb-2">Evolução</p>

                <!-- Peso -->
                <a
                    href="{{ route('patients.weights', $patient) }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 {{ $activeTab === 'weights' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg shadow-green-200' : 'bg-white border-2 border-green-100 text-gray-700 hover:border-green-300 hover:shadow-md' }}"
                >
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $activeTab === 'weights' ? 'bg-white/20' : 'bg-green-50' }}">
                        <i class="fas fa-weight-scale text-sm {{ $activeTab === 'weights' ? 'text-white' : 'text-green-600' }}"></i>
                    </div>
                    <span class="flex-1 text-sm">Peso</span>
                    @if($activeTab === 'weights')
                        <i class="fas fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- Antropometria -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed mb-2 hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-ruler text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Antropometria</span>
                </div>

                <!-- Evolução Fotográfica -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed mb-2 hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-image text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Fotos</span>
                </div>

                <!-- Registro Diário -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-book text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Registro Diário</span>
                </div>
            </div>

            <!-- ESTRATÉGIA SECTION -->
            <div class="px-2 py-3">
                <p class="text-xs uppercase text-gray-500 font-bold mb-2">Estratégia</p>

                <!-- Metas -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed mb-2 hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-bullseye text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Metas</span>
                </div>

                <!-- Plano Alimentar -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-utensils text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Plano Alimentar</span>
                </div>
            </div>

            <!-- SAÚDE SECTION - MODO AVANÇADO -->
            <div class="px-2 py-3">
                <p class="text-xs uppercase text-gray-500 font-bold mb-2">Saúde</p>

                <!-- Anamnese -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed mb-2 hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clipboard-list text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Anamnese</span>
                </div>

                <!-- Questionário de Saúde -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed mb-2 hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-heart-pulse text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Questionário</span>
                </div>

                <!-- Exames Laboratoriais -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-flask text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Exames</span>
                </div>
            </div>

            <!-- RELATÓRIOS SECTION - PREMIUM -->
            <div class="px-2 py-3">
                <p class="text-xs uppercase text-gray-500 font-bold mb-2">Relatórios</p>

                <!-- Relatório -->
                <div class="relative flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed hover:border-gray-400 transition-colors">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md border border-red-700">Em breve</div>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-chart-column text-base text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold text-sm">Relatório</span>
                </div>
            </div>
        </nav>

        <!-- Quick Actions -->
        <div class="mt-8 pt-6 border-t-2 border-green-200">
            <p class="text-xs uppercase text-gray-500 font-bold mb-3 px-2">Ações Rápidas</p>
            <div class="space-y-2">
                <a
                    href="{{ route('patients.index') }}"
                    class="flex items-center gap-2 px-4 py-3 rounded-xl bg-white border-2 border-gray-200 text-gray-700 hover:border-green-300 hover:bg-green-50 transition-all duration-300 text-sm font-bold"
                >
                    <i class="fas fa-arrow-left text-green-600"></i>
                    <span>Voltar para Lista</span>
                </a>
            </div>
        </div>
    </div>
</aside>
