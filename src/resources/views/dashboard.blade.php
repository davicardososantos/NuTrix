<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-3xl text-gray-900">
                📊 Dashboard
            </h2>
            <p class="text-sm text-gray-500 mt-2">Bem-vindo de volta! Aqui está um resumo da sua atividade</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
