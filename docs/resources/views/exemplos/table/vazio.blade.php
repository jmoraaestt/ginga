<x-ginga::table :columns="['Nome', 'E-mail']" empty="Nenhum cliente cadastrado ainda.">
    @foreach ([] as $cliente)
        <tr>
            <td>{{ $cliente->nome }}</td>
            <td>{{ $cliente->email }}</td>
        </tr>
    @endforeach
</x-ginga::table>
