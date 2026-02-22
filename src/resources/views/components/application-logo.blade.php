<div {{ $attributes->merge(['class' => 'shrink-0 flex items-center gap-3']) }}>
    <!-- Icon (square) shown on all sizes -->
    <img src="/logo/icone.png" alt="NuTrix icon" class="h-9 w-9 object-contain" />

    <!-- Wide wordmark and textual brand shown on md+ screens -->
    <img src="/logo/logo.png" alt="NuTrix wordmark" class="hidden md:block h-9 object-contain" />

    <!-- Fallback textual brand for better accessibility / SEO -->
    <span class="hidden md:inline-block font-black text-xl text-nutri" aria-hidden="false">
        NuTrix <span class="font-medium text-sm text-nutri-dark">Meta</span>
    </span>
</div>
