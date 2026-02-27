<x-patient-panel-layout
    :patient="$patient"
    activeTab="profile"
    title="Perfil do Paciente"
    :subtitle="$patient->full_name"
>
    <x-slot name="headerIcon">
        <i class="fas fa-id-card text-xl md:text-2xl text-green-600"></i>
    </x-slot>

    <div class="space-y-6" x-data="{ editOpen: {{ $errors->any() ? 'true' : 'false' }} }">
                    <!-- Profile Summary -->
                    <div x-show="!editOpen" x-transition class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 md:px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-user-check text-green-600"></i>
                                    Informacoes do Perfil
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Resumo das informacoes principais</p>
                            </div>
                            <button
                                type="button"
                                @click="editOpen = !editOpen"
                                class="px-5 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold hover:shadow-lg hover:shadow-green-200 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-pen"></i>
                                <span x-text="editOpen ? 'Fechar Edicao' : 'Editar Informacoes'"></span>
                            </button>
                        </div>

                        <div class="p-6 md:p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Nome completo</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->full_name }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Email</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->email }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Telefone / WhatsApp</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->phone ?: '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Data de nascimento</p>
                                    <p class="text-gray-900 font-bold">
                                        {{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') : '-' }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Sexo biologico</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->biological_sex ?: '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Profissao</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->profession ?: '-' }}</p>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Rotina</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->work_routine ?: '-' }}</p>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Objetivo principal</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->main_goal ?: '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Indicacao</p>
                                    <p class="text-gray-900 font-bold">{{ $patient->referral_source ?: '-' }}</p>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-black text-gray-900 flex items-center gap-2 mb-4">
                                    <i class="fas fa-heart-pulse text-red-500"></i>
                                    Medidas e Saude
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-1">
                                        <p class="text-xs uppercase text-gray-500 font-semibold">Peso</p>
                                        <p class="text-gray-900 font-bold">{{ $patient->weight ? $patient->weight . ' kg' : '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs uppercase text-gray-500 font-semibold">Altura</p>
                                        <p class="text-gray-900 font-bold">{{ $patient->height ? $patient->height . ' cm' : '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs uppercase text-gray-500 font-semibold">Gordura corporal</p>
                                        <p class="text-gray-900 font-bold">{{ $patient->body_fat_percentage ? $patient->body_fat_percentage . ' %' : '-' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-1">
                                        <p class="text-xs uppercase text-gray-500 font-semibold">Meta calorica</p>
                                        <p class="text-gray-900 font-bold">{{ $patient->calorie_target ? $patient->calorie_target . ' kcal/dia' : '-' }}</p>
                                    </div>
                                    <div class="space-y-1 md:col-span-2">
                                        <p class="text-xs uppercase text-gray-500 font-semibold">Historico clinico</p>
                                        <p class="text-gray-900 font-bold">{{ $patient->clinical_history ?: '-' }}</p>
                                    </div>
                                    <div class="space-y-1 md:col-span-2">
                                        <p class="text-xs uppercase text-gray-500 font-semibold">Observacoes medicas</p>
                                        <p class="text-gray-900 font-bold">{{ $patient->medical_notes ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div x-show="editOpen" x-transition class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 md:px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                                <i class="fas fa-pen-to-square text-green-600"></i>
                                Editar Informacoes
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Atualize os dados do paciente</p>
                        </div>

                        <form action="{{ route('patients.update', $patient) }}" method="POST" class="p-6 md:p-8 space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <h3 class="text-lg font-black text-gray-900 mb-4">Dados do Paciente</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-user text-green-600"></i>
                                            <x-input-label for="full_name" value="Nome Completo" class="font-bold text-gray-900" />
                                        </div>
                                        <x-text-input
                                            id="full_name"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="text"
                                            name="full_name"
                                            :value="old('full_name', $patient->full_name)"
                                            required
                                        />
                                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-envelope text-blue-600"></i>
                                            <x-input-label for="email" value="Email" class="font-bold text-gray-900" />
                                        </div>
                                        <x-text-input
                                            id="email"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="email"
                                            name="email"
                                            :value="old('email', $patient->email)"
                                            required
                                        />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-phone text-green-600"></i>
                                            <x-input-label for="phone" value="Telefone / WhatsApp" class="font-bold text-gray-900" />
                                        </div>
                                        <x-text-input
                                            id="phone"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="text"
                                            name="phone"
                                            :value="old('phone', $patient->phone)"
                                        />
                                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-cake-candles text-pink-500"></i>
                                            <x-input-label for="birth_date" value="Data de Nascimento" class="font-bold text-gray-900" />
                                        </div>
                                        <x-text-input
                                            id="birth_date"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="date"
                                            name="birth_date"
                                            :value="old('birth_date', $patient->birth_date)"
                                        />
                                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-venus-mars text-blue-600"></i>
                                            <x-input-label for="biological_sex" value="Sexo Biologico" class="font-bold text-gray-900" />
                                        </div>
                                        <select
                                            id="biological_sex"
                                            name="biological_sex"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="Masculino" @selected(old('biological_sex', $patient->biological_sex) === 'Masculino')>Masculino</option>
                                            <option value="Feminino" @selected(old('biological_sex', $patient->biological_sex) === 'Feminino')>Feminino</option>
                                            <option value="Outro" @selected(old('biological_sex', $patient->biological_sex) === 'Outro')>Outro</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('biological_sex')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-briefcase text-gray-600"></i>
                                            <x-input-label for="profession" value="Profissao" class="font-bold text-gray-900" />
                                        </div>
                                        <x-text-input
                                            id="profession"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="text"
                                            name="profession"
                                            :value="old('profession', $patient->profession)"
                                        />
                                        <x-input-error :messages="$errors->get('profession')" class="mt-2" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-clock text-amber-600"></i>
                                            <x-input-label for="work_routine" value="Rotina (horario de trabalho)" class="font-bold text-gray-900" />
                                        </div>
                                        <textarea
                                            id="work_routine"
                                            name="work_routine"
                                            rows="3"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                        >{{ old('work_routine', $patient->work_routine) }}</textarea>
                                        <x-input-error :messages="$errors->get('work_routine')" class="mt-2" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-bullseye text-green-600"></i>
                                            <x-input-label for="main_goal" value="Objetivo principal" class="font-bold text-gray-900" />
                                        </div>
                                        <textarea
                                            id="main_goal"
                                            name="main_goal"
                                            rows="3"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                        >{{ old('main_goal', $patient->main_goal) }}</textarea>
                                        <x-input-error :messages="$errors->get('main_goal')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-link text-blue-600"></i>
                                            <x-input-label for="referral_source" value="Indicacao" class="font-bold text-gray-900" />
                                        </div>
                                        <x-text-input
                                            id="referral_source"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="text"
                                            name="referral_source"
                                            :value="old('referral_source', $patient->referral_source)"
                                        />
                                        <x-input-error :messages="$errors->get('referral_source')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-black text-gray-900 mb-4">Medidas e Saude</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-weight text-blue-600"></i>
                                            <x-input-label for="weight" value="Peso (kg)" class="font-bold text-gray-900" />
                                        </div>
                                        <x-decimal-input
                                            id="weight"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="number"
                                            name="weight"
                                            :value="old('weight', $patient->weight)"
                                            step="0.1"
                                        />
                                        <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-ruler text-green-600"></i>
                                            <x-input-label for="height" value="Altura (cm)" class="font-bold text-gray-900" />
                                        </div>
                                        <x-decimal-input
                                            id="height"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="number"
                                            name="height"
                                            :value="old('height', $patient->height)"
                                            step="0.1"
                                        />
                                        <x-input-error :messages="$errors->get('height')" class="mt-2" />
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-percentage text-emerald-600"></i>
                                            <x-input-label for="body_fat_percentage" value="Gordura Corporal (%)" class="font-bold text-gray-900" />
                                        </div>
                                        <x-decimal-input
                                            id="body_fat_percentage"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="number"
                                            name="body_fat_percentage"
                                            :value="old('body_fat_percentage', $patient->body_fat_percentage)"
                                            step="0.1"
                                        />
                                        <x-input-error :messages="$errors->get('body_fat_percentage')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-fire text-orange-500"></i>
                                            <x-input-label for="calorie_target" value="Meta Calorica (kcal/dia)" class="font-bold text-gray-900" />
                                        </div>
                                        <x-text-input
                                            id="calorie_target"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                            type="number"
                                            name="calorie_target"
                                            :value="old('calorie_target', $patient->calorie_target)"
                                        />
                                        <x-input-error :messages="$errors->get('calorie_target')" class="mt-2" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-notes-medical text-red-500"></i>
                                            <x-input-label for="clinical_history" value="Historico Clinico" class="font-bold text-gray-900" />
                                        </div>
                                        <textarea
                                            id="clinical_history"
                                            name="clinical_history"
                                            rows="3"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                        >{{ old('clinical_history', $patient->clinical_history) }}</textarea>
                                        <x-input-error :messages="$errors->get('clinical_history')" class="mt-2" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-file-medical text-blue-600"></i>
                                            <x-input-label for="medical_notes" value="Observacoes Medicas" class="font-bold text-gray-900" />
                                        </div>
                                        <textarea
                                            id="medical_notes"
                                            name="medical_notes"
                                            rows="3"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                        >{{ old('medical_notes', $patient->medical_notes) }}</textarea>
                                        <x-input-error :messages="$errors->get('medical_notes')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('patients.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300 text-center flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Cancelar
                                </a>
                                <button
                                    type="submit"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-green-200 transition-all duration-300 flex items-center justify-center gap-2"
                                >
                                    <i class="fas fa-check-circle"></i>
                                    Salvar Alteracoes
                                </button>
                            </div>
                        </form>
                    </div>
    </div>
</x-patient-panel-layout>
