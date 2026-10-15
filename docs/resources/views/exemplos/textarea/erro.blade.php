{{-- oculto --}}
@php \App\Docs\Exemplos::erros(['motivo' => 'O campo motivo deve ter pelo menos 20 caracteres.'], ['motivo' => 'Não gostei.']); @endphp
{{-- /oculto --}}
<x-ginga::textarea name="motivo" label="Motivo do cancelamento" required />
