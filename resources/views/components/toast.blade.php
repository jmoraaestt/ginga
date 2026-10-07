@props([
    'variant' => 'primary',
    'title' => null,
    'delay' => 5000,
    'autohide' => true,
])

@php
    $hasIcon = isset($icon);
    $isUrgent = $variant === 'danger';

    // Mesmas cores suaves do alert
    $classes = \Illuminate\Support\Arr::toCssClasses([
        'toast',
        'bg-' . $variant . '-subtle',
        'text-' . $variant . '-emphasis',
        'border-' . $variant . '-subtle',
    ]);

    // Erros interrompem o leitor de tela; as demais mensagens esperam a leitura atual terminar
    $defaults = [
        'class' => $classes,
        'role' => $isUrgent ? 'alert' : 'status',
        'aria-live' => $isUrgent ? 'assertive' : 'polite',
        'aria-atomic' => 'true',
        'data-bs-delay' => $delay,
        'data-bs-autohide' => $autohide ? 'true' : 'false',
        'data-ginga-toast' => true,
    ];
@endphp


<div {{ $attributes->merge($defaults) }}>
    <div class="d-flex align-items-start">
        <div @class(['toast-body flex-grow-1', 'd-flex align-items-start gap-2' => $hasIcon])>
@if ($hasIcon)
            {{ $icon }}
            <div>
@endif
@if (filled($title))
            <div class="fw-semibold mb-1">{{ $title }}</div>
@endif
            {{ $slot }}
@if ($hasIcon)
            </div>
@endif
        </div>
        <button type="button" class="btn-close flex-shrink-0 me-2 mt-2" data-bs-dismiss="toast" aria-label="Fechar"></button>
    </div>
</div>

@once
<script>
    // O Bootstrap não exibe toasts sozinho: mostra todos os toasts do Ginga ao carregar a página
    document.addEventListener('DOMContentLoaded', () => {
        if (!window.bootstrap) return;

        document.querySelectorAll('[data-ginga-toast]').forEach((toast) => {
            bootstrap.Toast.getOrCreateInstance(toast).show();
        });
    });
</script>
@endonce
