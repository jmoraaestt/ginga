{{-- oculto --}}
@php $clientes = \App\Docs\Exemplos::tabelaClientes(); @endphp
{{-- /oculto --}}
<x-ginga::datatable
    :tabela="$clientes"
    placeholder="Buscar por nome, CPF ou cidade"
    caption="Clientes"
    :columns="[
        'nome' => 'Nome',
        'cpf' => 'CPF',
        'cidade' => 'Cidade',
        'mensalidade' => ['label' => 'Mensalidade', 'class' => 'text-end'],
        'acoes' => ['label' => 'Ações', 'hidden' => true],
    ]"
>
    @foreach ($clientes as $cliente)
        <tr>
            <td>
                {{ $cliente->nome }}
                @unless ($cliente->ativo)
                    <x-ginga::badge variant="secondary" subtle>Inativo</x-ginga::badge>
                @endunless
            </td>
            <td class="text-nowrap">@cpf($cliente->cpf)</td>
            <td>{{ $cliente->cidade }}/{{ $cliente->uf }}</td>
            <td class="text-end text-nowrap">@dinheiro($cliente->mensalidade)</td>
            <td class="text-end">
                <x-ginga::delete-button action="/clientes/{{ $cliente->id }}" :item="$cliente->nome" />
            </td>
        </tr>
    @endforeach
</x-ginga::datatable>
