{{-- oculto --}}
@php $pedidos = \App\Docs\Exemplos::paginator(total: 1380, pagina: 37, porPagina: 25); @endphp
{{-- /oculto --}}
<x-ginga::pagination :paginator="$pedidos" />
