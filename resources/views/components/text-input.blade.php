@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 rounded-md shadow-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed']) }}>
