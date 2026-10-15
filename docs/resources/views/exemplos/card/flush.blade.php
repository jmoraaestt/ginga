<x-ginga::card title="Próximos vencimentos" flush>
    <x-ginga::table :columns="['Cliente', 'Vencimento', 'Valor']">
        <tr><td>Maria Silva</td><td>20/10</td><td>@dinheiro(149.9)</td></tr>
        <tr><td>João Souza</td><td>22/10</td><td>@dinheiro(89.9)</td></tr>
    </x-ginga::table>
</x-ginga::card>
