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
(() => {
    if (window.gingaToast) return;
    window.gingaToast = true;

    // O Bootstrap não exibe toasts sozinho: mostra os toasts do Ginga quando a página termina de carregar
    const mostrar = () => {
        for (const toast of document.querySelectorAll('[data-ginga-toast]:not(.show)')) {
            if (window.bootstrap?.Toast) {
                bootstrap.Toast.getOrCreateInstance(toast).show();
                continue;
            }

            // Bootstrap importado como módulo (Vite) não fica em window.bootstrap: mostra e esconde à mão
            toast.classList.add('show');
            if (toast.dataset.bsAutohide !== 'false') {
                setTimeout(() => toast.classList.remove('show'), Number(toast.dataset.bsDelay) || 5000);
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mostrar);
    } else {
        mostrar();
    }
})();
</script>
@endonce
