@props([
    'label' => 'CEP',
    // Campo do ViaCEP => name (ou id) do campo do formulário. Ex.: ['logradouro' => 'endereco', 'uf' => 'uf']
    'preencher' => [],
    // name (ou id) do campo que recebe o foco depois de preencher, normalmente o número
    'focar' => null,
])

<x-ginga::input
    mask="cep"
    :label="$label"
    :data-ginga-cep="$preencher ? json_encode($preencher) : null"
    :data-ginga-cep-focar="$focar"
    {{ $attributes }}
/>
