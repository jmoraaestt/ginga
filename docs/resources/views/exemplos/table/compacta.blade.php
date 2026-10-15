<x-ginga::table :columns="['Sigla', 'Estado', 'Região']" striped small :hover="false">
    @foreach ([['SP', 'São Paulo', 'Sudeste'], ['BA', 'Bahia', 'Nordeste'], ['RS', 'Rio Grande do Sul', 'Sul'], ['AM', 'Amazonas', 'Norte']] as [$sigla, $estado, $regiao])
        <tr>
            <td>{{ $sigla }}</td>
            <td>{{ $estado }}</td>
            <td>{{ $regiao }}</td>
        </tr>
    @endforeach
</x-ginga::table>
