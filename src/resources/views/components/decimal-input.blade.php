@props(['disabled' => false, 'step' => '0.1'])

<input 
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge(['class' => 'border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}
    step="{{ $step }}"
    @change="this.value = this.value.replace('.', ',');"
    @blur="this.value = parseFloat(this.value.replace(',', '.')).toLocaleString('pt-BR', {minimumFractionDigits: {{ $step == '0.1' ? '1' : '2' }}, maximumFractionDigits: {{ $step == '0.1' ? '1' : '2' }}})"
/>
