<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-green-50 via-white to-white pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-gray-100 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-xl md:text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-gray-900">Meus Pacientes</h1>
                            <p class="text-xs md:text-sm text-gray-500 mt-0.5">Gerenciar e acompanhar dados dos pacientes</p>
                        </div>
                    </div>
                    <a href="{{ route('pacientes.create') }}" class="px-4 md:px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold hover:shadow-lg hover:shadow-green-200 transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-plus-circle"></i>
                        <span class="hidden md:inline">Novo Paciente</span>
                        <span class="md:hidden">Novo</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <!-- Success Alert -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-lg text-green-600 flex-shrink-0"></i>
                    <p class="font-semibold text-sm text-green-900">{{ session('success') }}</p>
                </div>
            @endif

            @if ($patients->count() > 0)
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <input
                            type="text"
                            id="patientSearch"
                            placeholder="Buscar paciente por nome ou email..."
                            class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        />
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Patients List -->
                <div class="space-y-4" id="patientsList">
                    @foreach ($patients as $patient)
                        <div class="patient-row bg-white border border-gray-200 rounded-2xl p-5 md:p-6 hover:shadow-lg hover:border-green-200 transition-all duration-300" data-search="{{ strtolower($patient->full_name . ' ' . $patient->email) }}">
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <!-- Avatar & Info -->
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <span class="text-xl font-black text-green-600">{{ substr($patient->full_name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $patient->full_name }}</h3>
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 mt-1">
                                            <p class="text-sm text-gray-600 flex items-center gap-1.5">
                                                <i class="fas fa-envelope text-blue-600"></i>
                                                {{ $patient->email }}
                                            </p>
                                            <span class="hidden sm:inline text-gray-400">•</span>
                                            <p class="text-sm text-gray-600 flex items-center gap-1.5">
                                                <i class="fas fa-key text-purple-600"></i>
                                                {{ $patient->code }}
                                            </p>
                                            <span class="hidden sm:inline text-gray-400">•</span>
                                            <p class="text-sm text-gray-600 flex items-center gap-1.5">
                                                <i class="fas fa-calendar text-gray-600"></i>
                                                {{ $patient->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex items-center gap-3">
                                    @if ($patient->user_id)
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-100 text-green-700 text-sm font-bold rounded-xl">
                                            <i class="fas fa-check-circle"></i>
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-100 text-amber-700 text-sm font-bold rounded-xl">
                                            <i class="fas fa-hourglass-end"></i>
                                            Aguardando
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2 md:gap-3">
                                    <a href="{{ route('pacientes.edit', $patient) }}" class="flex-1 md:flex-none px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-green-200 transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-id-card"></i>
                                        <span class="hidden sm:inline">Ver Perfil</span>
                                        <span class="sm:hidden">Perfil</span>
                                    </a>
                                    <form action="{{ route('pacientes.destroy', $patient) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este paciente?')" class="flex-1 md:flex-none">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-xl font-bold text-sm hover:bg-red-200 hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="hidden sm:inline">Deletar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div id="noResultsMsg" class="hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i class="fas fa-search text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-600 font-semibold mb-2 text-lg">Nenhum paciente encontrado</p>
                        <p class="text-gray-500 text-sm">Tente buscar com outros termos</p>
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-users text-3xl text-green-600"></i>
                    </div>
                    <p class="text-gray-600 font-semibold mb-2 text-lg">Nenhum paciente cadastrado ainda</p>
                    <p class="text-gray-500 text-sm mb-8">Comece adicionando seu primeiro paciente para iniciar o acompanhamento.</p>
                    <a href="{{ route('pacientes.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold hover:shadow-lg hover:shadow-green-200 transition-all duration-300">
                        <i class="fas fa-plus-circle"></i>
                        Cadastrar Primeiro Paciente
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Patient Details Modal -->
    <div id="patientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-5 border-b border-gray-200 flex justify-between items-center rounded-t-3xl">
                <h3 class="text-xl font-black text-gray-900 flex items-center gap-2" id="modalTitle">
                    <i class="fas fa-user-circle text-green-600 text-2xl"></i>
                    Detalhes do Paciente
                </h3>
                <button type="button" onclick="closePatientModal()" class="text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-xl p-2 transition duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Personal Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-2">Nome Completo</p>
                        <p class="text-lg font-bold text-gray-900 flex items-center gap-2" id="modalFullName">
                            <i class="fas fa-user text-green-600"></i>
                            -
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-2">Email</p>
                        <p class="text-sm font-semibold text-gray-900 break-all flex items-center gap-2" id="modalEmail">
                            <i class="fas fa-envelope text-blue-600"></i>
                            -
                        </p>
                    </div>
                </div>

                <!-- Measurements -->
                <div class="border-t pt-6">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-4">Medidas Corporais</p>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-4 text-center border border-blue-100 shadow-sm">
                            <p class="text-xs text-gray-600 mb-2 font-bold flex items-center justify-center gap-1">
                                <i class="fas fa-weight text-blue-600"></i>
                                Peso
                            </p>
                            <p class="text-3xl font-black text-blue-600" id="modalWeight">-</p>
                            <p class="text-xs text-gray-500 mt-1">kg</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 text-center border border-green-100 shadow-sm">
                            <p class="text-xs text-gray-600 mb-2 font-bold flex items-center justify-center gap-1">
                                <i class="fas fa-ruler text-green-600"></i>
                                Altura
                            </p>
                            <p class="text-3xl font-black text-green-600" id="modalHeight">-</p>
                            <p class="text-xs text-gray-500 mt-1">cm</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 text-center border border-purple-100 shadow-sm">
                            <p class="text-xs text-gray-600 mb-2 font-bold flex items-center justify-center gap-1">
                                <i class="fas fa-circle-check text-purple-600"></i>
                                Status
                            </p>
                            <p class="text-lg font-black text-purple-600" id="modalStatus">-</p>
                        </div>
                    </div>
                </div>

                <!-- Registration Info -->
                <div class="border-t pt-6">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-4">Informações de Cadastro</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-gray-600 text-sm font-bold flex items-center gap-2 mb-2">
                                <i class="fas fa-key text-purple-600"></i>
                                Código de Acesso
                            </p>
                            <p class="font-mono text-lg font-black text-gray-900" id="modalCode">-</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-gray-600 text-sm font-bold flex items-center gap-2 mb-2">
                                <i class="fas fa-calendar-alt text-gray-600"></i>
                                Data de Cadastro
                            </p>
                            <p class="font-bold text-gray-900" id="modalCreatedAt">-</p>
                        </div>
                    </div>
                </div>

                <!-- Registration Link -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl p-5">
                    <p class="text-xs text-gray-600 uppercase font-bold tracking-wider mb-3 flex items-center gap-2">
                        <i class="fas fa-link text-blue-600"></i>
                        Link de Registro para o Paciente
                    </p>
                    <div class="flex gap-2">
                        <input type="text" id="registrationLink" readonly class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="button" onclick="copyRegistrationLink()" class="px-5 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-blue-200 transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                            <i class="fas fa-copy"></i>
                            <span class="hidden sm:inline">Copiar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="border-t bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" onclick="closePatientModal()" class="px-5 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-100 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <script>
        function openPatientModal(patient) {
            const modal = document.getElementById('patientModal');
            document.getElementById('modalTitle').innerHTML = `<i class="fas fa-user-circle text-green-600 text-2xl"></i> ${patient.full_name}`;
            document.getElementById('modalFullName').innerHTML = `<i class="fas fa-user text-green-600"></i> ${patient.full_name}`;
            document.getElementById('modalEmail').innerHTML = `<i class="fas fa-envelope text-blue-600"></i> ${patient.email}`;
            document.getElementById('modalWeight').textContent = patient.weight ? patient.weight : '-';
            document.getElementById('modalHeight').textContent = patient.height ? patient.height : '-';
            document.getElementById('modalStatus').textContent = patient.user_id ? 'Ativo' : 'Aguardando';
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
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copiado!';
            button.classList.remove('from-blue-600', 'to-cyan-600');
            button.classList.add('from-green-600', 'to-emerald-600');
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('from-green-600', 'to-emerald-600');
                button.classList.add('from-blue-600', 'to-cyan-600');
            }, 2000);
        }

        // Search functionality
        document.getElementById('patientSearch')?.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.patient-row');
            const noResultsMsg = document.getElementById('noResultsMsg');

            let visibleCount = 0;
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                if (searchData.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleCount === 0 && searchTerm) {
                noResultsMsg.classList.remove('hidden');
            } else {
                noResultsMsg.classList.add('hidden');
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
