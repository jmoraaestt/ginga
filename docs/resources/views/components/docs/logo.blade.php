@props(['tamanho' => 28])

<svg {{ $attributes->merge(['class' => 'docs-logo']) }} width="{{ $tamanho }}" height="{{ $tamanho }}" viewBox="0 0 32 32" aria-hidden="true" focusable="false">
    <rect width="32" height="32" rx="9" fill="#C9306F"/>
    <path d="M22.5 11.25A7.75 7.75 0 1 0 23.75 16H16.75" fill="none" stroke="#fff" stroke-width="3.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
