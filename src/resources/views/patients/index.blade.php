<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-3xl text-gray-900">👥 Meus Pacientes</h2>
                <p class="text-gray-600 mt-1">Gerenciar e acompanhar dados dos pacientes</p>
            </div>
            <a href="{{ route('patients.create') }}" class="px-6 py-3 rounded-lg bg-gradient-to-r from-amber-700 to-green-700 text-white font-semibold hover:shadow-lg hover:scale-105 transition-all">
                ➕ Novo Paciente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-start gap-3">
                    <span class="text-xl">✅</span>
                    <div>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($patients->count() > 0)
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="patientSearch" 
                            placeholder="🔍 Buscar paciente por nome ou email..." 
                            class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                        />
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-green-50 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm font-bold text-gray-700">
                            <div>Nome</div>
                            <div>Email</div>
                            <div>Cadastro</div>
                            <div>Status</div>
                            <div class="text-right">Ações</div>
                        </div>
                    </div>

                    <!-- Table Rows -->
                    <div class="divide-y divide-gray-200" id="patientsList">
                        @foreach ($patients as $patient)
                            <div class="px-6 py-4 hover:bg-gray-50 transition patient-row" data-patient="{{ json_encode($patient) }}" data-search="{{ strtolower($patient->full_name . ' ' . $patient->email) }}">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                                    <!-- Name -->
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $patient->full_name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $patient->code }}</p>
                                    </div>

                                    <!-- Email -->
                                    <div class="text-gray-600 text-sm break-all">{{ $patient->email }}</div>

                                    <!-- Cadastro Date -->
                                    <div class="text-gray-600 text-sm">
                                        {{ $patient->created_at->format('d/m/Y') }}
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        @if ($patient->user_id)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                                ✓ Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">
                                                ⏳ Aguardando
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="openPatientModal({{ json_encode($patient) }})" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded font-semibold text-xs hover:bg-indigo-200 transition">
                                            Ver Mais
                                        </button>
                                        <a href="{{ route('patients.edit', $patient) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded font-semibold text-xs hover:bg-blue-200 transition">
                                            Editar
                                        </a>
                                        <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Tem certeza?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded font-semibold text-xs hover:bg-red-200 transition">
                                                Deletar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if ($patients->hasPages())
                    <div class="mt-6">
                        {{ $patients->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                    <p class="text-5xl mb-4">👥</p>
                    <p class="text-gray-600 font-semibold mb-2">Nenhum paciente cadastrado ainda</p>
                    <p class="text-gray-500 text-sm mb-6">Comece adicionando seu primeiro paciente para iniciar o acompanhamento.</p>
                    <a href="{{ route('patients.create') }}" class="inline-block px-6 py-3 rounded-lg bg-gradient-to-r from-amber-700 to-green-700 text-white font-semibold hover:shadow-lg transition-all">
                        ➕ Cadastrar Primeiro Paciente
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Patient Details Modal -->
    <div id="patientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r from-amber-50 to-green-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Detalhes do Paciente</h3>
                <button type="button" onclick="closePatientModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Personal Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Nome Completo</p>
                        <p class="text-lg font-semibold text-gray-900" id="modalFullName">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                        <p class="text-lg font-semibold text-gray-900 break-all" id="modalEmail">-</p>
                    </div>
                </div>

                <!-- Measurements -->
                <div class="border-t pt-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-3">Medidas</p>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-600 mb-1">Peso</p>
                            <p class="text-2xl font-bold text-blue-700" id="modalWeight">-</p>
                            <p class="text-xs text-gray-500">kg</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-600 mb-1">Altura</p>
                            <p class="text-2xl font-bold text-green-700" id="modalHeight">-</p>
                            <p class="text-xs text-gray-500">cm</p>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            <p class="text-2xl font-bold text-amber-700" id="modalStatus">-</p>
                        </div>
                    </div>
                </div>

                <!-- Registration Info -->
                <div class="border-t pt-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-3">Informações de Cadastro</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Código de Acesso</p>
                            <p class="font-mono text-lg font-bold text-gray-900" id="modalCode">-</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Data de Cadastro</p>
                            <p class="font-semibold text-gray-900" id="modalCreatedAt">-</p>
                        </div>
                    </div>
                </div>

                <!-- Registration Link -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs text-gray-600 uppercase font-semibold mb-2">Link de Registro</p>
                    <div class="flex gap-2">
                        <input type="text" id="registrationLink" readonly class="flex-1 px-3 py-2 bg-white border border-gray-300 rounded text-sm font-mono" />
                        <button type="button" onclick="copyRegistrationLink()" class="px-4 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">
                            Copiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="border-t bg-gray-50 px-6 py-4 flex justify-end gap-3">
                <button type="button" onclick="closePatientModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded font-semibold hover:bg-gray-100 transition">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <script>
        function openPatientModal(patient) {
            const modal = document.getElementById('patientModal');
            document.getElementById('modalTitle').textContent = patient.full_name;
            document.getElementById('modalFullName').textContent = patient.full_name;
            document.getElementById('modalEmail').textContent = patient.email;
            document.getElementById('modalWeight').textContent = patient.weight ? patient.weight : '-';
            document.getElementById('modalHeight').textContent = patient.height ? patient.height : '-';
            document.getElementById('modalStatus').textContent = patient.user_id ? '✓ Ativo' : '⏳ Aguardando';
            document.getElementById('modalCode').textContent = patient.code;
            document.getElementById('modalCreatedAt').textContent = new Date(patient.created_at).toLocaleDateString('pt-BR');
            document.getElementById('registrationLink').value = `{{ url('/paciente') }}/${patient.code}`;
            modal.classList.remove('hidden');
        }

        function closePatientModal() {
            document.getElementById('patientModal').classList.add('hidden');
        }

        function copyRegistrationLink() {
            const input = document.getElementById('registrationLink');
            input.select();
            document.execCommand('copy');
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '✓ Copiado!';
            setTimeout(() => {
                button.textContent = originalText;
            }, 2000);
        }

        // Search functionality
        document.getElementById('patientSearch').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.patient-row');
            
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                if (searchData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide empty message
            const visibleRows = document.querySelectorAll('.patient-row:not([style*="display: none"])');
            if (visibleRows.length === 0 && searchTerm) {
                if (!document.getElementById('noResultsMsg')) {
                    const noResults = document.createElement('div');
                    noResults.id = 'noResultsMsg';
                    noResults.className = 'px-6 py-8 text-center text-gray-500';
                    noResults.textContent = '😔 Nenhum paciente encontrado com essas características.';
                    document.getElementById('patientsList').appendChild(noResults);
                }
            } else {
                const noResultsMsg = document.getElementById('noResultsMsg');
                if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }
        });

        // Close modal when clicking outside
        document.getElementById('patientModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePatientModal();
            }
        });
    </script>
</x-app-layout>
