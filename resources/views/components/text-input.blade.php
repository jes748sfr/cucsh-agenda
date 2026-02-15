@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-udg-blue focus:ring-udg-blue rounded-md shadow-sm']) }}>
