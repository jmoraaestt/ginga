{{-- oculto --}}
@php $clientes = \App\Docs\Exemplos::paginator(total: 57, pagina: 2); @endphp
{{-- /oculto --}}
<x-ginga::pagination :paginator="$clientes" />
