<x-docs.pagina :pagina="$pagina">
    <p>Tabela sem busca, já dentro de um <code>table-responsive</code>. Para busca e paginação no banco, use a <a href="{{ route('docs.pagina', 'datatable') }}">datatable</a>.</p>

    <x-docs.exemplo arquivo="table.basico" />

    <x-docs.secao titulo="Sem registros" />
    <p>Quando o <code>@@foreach</code> não gera nenhuma linha, a tabela mostra a mensagem de <code>empty</code>. Para usar HTML na mensagem, passe pelo slot <code>empty</code>.</p>
    <x-docs.exemplo arquivo="table.vazio" />

    <x-docs.secao titulo="Zebrada e compacta" />
    <x-docs.exemplo arquivo="table.compacta" />

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['columns', 'array', '[]', 'Títulos das colunas. Para um cabeçalho mais elaborado, use o slot `head`.'],
        ['empty', 'string', 'Nenhum registro encontrado.', 'Mensagem da tabela vazia.'],
        ['caption', 'string|null', 'null', 'Nome da tabela para leitores de tela (`<caption>` escondido).'],
        ['striped', 'bool', 'false', 'Linhas zebradas.'],
        ['hover', 'bool', 'true', 'Destaca a linha sob o mouse.'],
        ['small', 'bool', 'false', 'Linhas mais baixas.'],
    ]" />
</x-docs.pagina>
