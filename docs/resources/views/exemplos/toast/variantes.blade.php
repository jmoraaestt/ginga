{{-- oculto --}}<div class="docs-toast-vitrine">{{-- /oculto --}}
<x-ginga::toast variant="success">Cliente cadastrado com sucesso.</x-ginga::toast>

<x-ginga::toast variant="danger" title="Não foi possível salvar" :delay="8000">
    Corrija os 2 campos destacados.
</x-ginga::toast>

<x-ginga::toast variant="warning" title="Assinatura vencendo" :autohide="false">
    <x-slot:icon>
        <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
    </x-slot:icon>
    Renove até sexta para não perder o acesso.
</x-ginga::toast>
{{-- oculto --}}</div>{{-- /oculto --}}
