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
        <nav class="space-y-3">
            <!-- Profile -->
            <a
                href="{{ route('patients.edit', $patient) }}"
                class="group flex items-center gap-3 px-5 py-4 rounded-2xl font-bold transition-all duration-300 {{ $activeTab === 'profile' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg shadow-green-200' : 'bg-white border-2 border-green-100 text-gray-700 hover:border-green-300 hover:shadow-md' }}"
            >
                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $activeTab === 'profile' ? 'bg-white/20' : 'bg-green-50' }}">
                    <i class="fas fa-user text-lg {{ $activeTab === 'profile' ? 'text-white' : 'text-green-600' }}"></i>
                </div>
                <span class="flex-1">Perfil</span>
                @if($activeTab === 'profile')
                    <i class="fas fa-chevron-right text-sm"></i>
                @endif
            </a>

            <!-- Evolution (Coming Soon) -->
            <div class="relative group">
                <div class="flex items-center gap-3 px-5 py-4 rounded-2xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-chart-line text-lg text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold">Evolução</span>
                    <span class="text-xs font-semibold bg-gray-100 px-2 py-1 rounded-lg">Em breve</span>
                </div>
            </div>

            <!-- Diet Plan (Coming Soon) -->
            <div class="relative group">
                <div class="flex items-center gap-3 px-5 py-4 rounded-2xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-utensils text-lg text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold">Plano Alimentar</span>
                    <span class="text-xs font-semibold bg-gray-100 px-2 py-1 rounded-lg">Em breve</span>
                </div>
            </div>

            <!-- Documents (Coming Soon) -->
            <div class="relative group">
                <div class="flex items-center gap-3 px-5 py-4 rounded-2xl border-2 border-dashed border-gray-300 text-gray-400 cursor-not-allowed">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-file-lines text-lg text-gray-400"></i>
                    </div>
                    <span class="flex-1 font-bold">Documentos</span>
                    <span class="text-xs font-semibold bg-gray-100 px-2 py-1 rounded-lg">Em breve</span>
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
