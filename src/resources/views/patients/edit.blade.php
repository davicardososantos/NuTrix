                            <!-- Weight -->
                            <div>
                                <x-input-label for="weight" value="Peso (kg)" />
                                <x-decimal-input
                                    id="weight"
                                    class="block mt-1 w-full"
                                    type="number"
                                    name="weight"
                                    :value="old('weight', $patient->weight)"
                                    step="0.1"
                                    placeholder="75,0"
                                />
                                <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                            </div>

                            <!-- Height -->
                            <div>
                                <x-input-label for="height" value="Altura (cm)" />
                                <x-decimal-input
                                    id="height"
                                    class="block mt-1 w-full"
                                    type="number"
                                    name="height"
                                    :value="old('height', $patient->height)"
                                    step="0.1"
                                    placeholder="175,0"
                                />
                                <x-input-error :messages="$errors->get('height')" class="mt-2" />
                            </div>
                            </div>

                            <!-- Email -->
                            <div>
                                    <x-input-label for="email" value="Email" />
                                    <x-text-input 
                                        id="email" 
                                        class="block mt-1 w-full" 
                                        type="email" 
                                        name="email" 
                                        :value="old('email', $patient->email)" 
                                        required 
                                    />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Physical Measurements -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">⚖️ Medidas Físicas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Weight -->
                            <div>
                                    <x-input-label for="weight" value="Peso (kg)" />
                                    <x-decimal-input 
                                        id="weight" 
                                        class="block mt-1 w-full" 
                                        type="number" 
                                        name="weight" 
                                        :value="old('weight', $patient->weight)" 
                                        step="0.1"
                                    />
                                    <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                            </div>

                            <!-- Height -->
                            <div>
                                    <x-input-label for="height" value="Altura (cm)" />
                                    <x-decimal-input 
                                        id="height" 
                                        class="block mt-1 w-full" 
                                        type="number" 
                                        name="height" 
                                        :value="old('height', $patient->height)" 
                                        step="0.1"
                                    />
                                    <x-input-error :messages="$errors->get('height')" class="mt-2" />
                            </div>

                            <!-- Body Fat Percentage -->
                            <div>
                                    <x-input-label for="body_fat_percentage" value="Gordura Corporal (%)" />
                                    <x-decimal-input 
                                        id="body_fat_percentage" 
                                        class="block mt-1 w-full" 
                                        type="number" 
                                        name="body_fat_percentage" 
                                        :value="old('body_fat_percentage', $patient->body_fat_percentage)" 
                                        step="0.1"
                                    />
                                    <x-input-error :messages="$errors->get('body_fat_percentage')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Health Information -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">🏥 Informações de Saúde</h3>
                        
                        <!-- Calorie Target -->
                        <div class="mb-6">
                            <x-input-label for="calorie_target" value="Meta Calórica (kcal/dia)" />
                            <x-text-input 
                                id="calorie_target" 
                                class="block mt-1 w-full" 
                                type="number" 
                                name="calorie_target" 
                                :value="old('calorie_target', $patient->calorie_target)" 
                            />
                            <x-input-error :messages="$errors->get('calorie_target')" class="mt-2" />
                        </div>

                        <!-- Clinical History -->
                        <div class="mb-6">
                        </div>

                        <!-- Medical Notes -->
                        <div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('patients.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                            Cancelar
                        </a>
                        <button 
                            type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-700 to-green-700 text-white font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all"
                        >
                            ✓ Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
