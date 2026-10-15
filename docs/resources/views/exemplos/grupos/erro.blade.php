{{-- oculto --}}
@php \App\Docs\Exemplos::erros(['dias' => 'Escolha pelo menos um dia.']); @endphp
{{-- /oculto --}}
<x-ginga::checkbox-group
    name="dias"
    label="Dias de atendimento"
    inline
    required
    :options="['seg' => 'Seg', 'ter' => 'Ter', 'qua' => 'Qua', 'qui' => 'Qui', 'sex' => 'Sex']"
/>
