{{--
    preencher: campo do ViaCEP => name ou id do campo do formulário, ex.: ['logradouro' => 'endereco', 'uf' => 'uf']
    focar:     name ou id do campo que recebe o foco depois de preencher, normalmente o número
--}}
@props([
    'label' => 'CEP',
    'preencher' => [],
    'focar' => null,
])

<x-ginga::input
    mask="cep"
    :label="$label"
    :data-ginga-cep="$preencher ? json_encode($preencher) : null"
    :data-ginga-cep-focar="$focar"
    {{ $attributes }}
/>
