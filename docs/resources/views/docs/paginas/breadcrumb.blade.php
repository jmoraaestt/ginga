<x-docs.pagina :pagina="$pagina">
    <p>Os itens são <code>texto => link</code>. O último é a página atual: não vira link e recebe <code>aria-current="page"</code>.</p>

    <x-docs.exemplo arquivo="breadcrumb.basico" />

    <x-docs.codigo>
        @verbatim
        <x-ginga::breadcrumb :items="[
            'Início' => route('home'),
            'Clientes' => route('clientes.index'),
            $cliente->nome,
        ]" />
        @endverbatim
    </x-docs.codigo>

    <x-docs.secao titulo="Acessibilidade" />
    <x-docs.nota tipo="a11y">
        A trilha fica num <code>&lt;nav aria-label="Trilha de navegação"&gt;</code>, que o leitor de tela lista entre os pontos de referência da página.
    </x-docs.nota>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['items', 'array', '[]', '`texto => link`. O último item é a página atual.'],
    ]" />
</x-docs.pagina>
