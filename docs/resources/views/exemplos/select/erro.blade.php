{{-- oculto --}}
@php \App\Docs\Exemplos::erros(['categoria' => 'O campo categoria é obrigatório.']); @endphp
{{-- /oculto --}}
<x-ginga::select
    name="categoria"
    label="Categoria"
    placeholder="Selecione"
    :options="['servicos' => 'Serviços', 'produtos' => 'Produtos']"
    required
/>
