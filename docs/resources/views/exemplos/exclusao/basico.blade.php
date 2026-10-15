<x-ginga::table :columns="['Nome', 'Cidade', 'Ações']">
    @foreach (['Maria Silva' => 'São Paulo', 'João Souza' => 'Recife', 'Ana Lima' => 'Curitiba'] as $nome => $cidade)
        <tr>
            <td>{{ $nome }}</td>
            <td>{{ $cidade }}</td>
            <td>
                <x-ginga::delete-button action="/clientes/{{ $loop->iteration }}" :item="$nome" />
            </td>
        </tr>
    @endforeach
</x-ginga::table>
