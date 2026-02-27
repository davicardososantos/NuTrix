@props(['patient', 'activeTab' => 'profile', 'title', 'subtitle' => null])

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                    {{ $headerIcon ?? '' }}
                </div>
                <div>
                    <h2 class="font-black text-2xl md:text-3xl text-gray-900">{{ $title }}</h2>
                    @if($subtitle)
                        <p class="text-gray-600 mt-1 text-sm md:text-base">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            {{ $headerActions ?? '' }}
        </div>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Sidebar Component -->
                <x-patient-sidebar :patient="$patient" :activeTab="$activeTab" />

                <!-- Main Content Area -->
                <section class="lg:col-span-9">
                    {{ $slot }}
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
