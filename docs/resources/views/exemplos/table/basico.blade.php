<x-ginga::table :columns="['Pedido', 'Cliente', 'Status', 'Total']" caption="Últimos pedidos">
    @foreach ([
        ['#1042', 'Maria Silva', 'Pago', 'success', 289.9],
        ['#1041', 'João Souza', 'Aguardando', 'warning', 1520],
        ['#1040', 'Ana Lima', 'Cancelado', 'danger', 74.5],
    ] as [$pedido, $cliente, $status, $cor, $total])
        <tr>
            <td class="fw-semibold">{{ $pedido }}</td>
            <td>{{ $cliente }}</td>
            <td><x-ginga::badge :variant="$cor" subtle pill>{{ $status }}</x-ginga::badge></td>
            <td>@dinheiro($total)</td>
        </tr>
    @endforeach
</x-ginga::table>
