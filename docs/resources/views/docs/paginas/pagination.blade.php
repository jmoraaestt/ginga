<x-docs.pagina :pagina="$pagina">
    <p>Paginação em português para qualquer paginator do Laravel, com o resumo “Mostrando 1 a 10 de 57 resultados”.</p>

    <x-docs.exemplo arquivo="pagination.basico" />

    <x-docs.codigo lang="php">
        $clientes = Cliente::orderBy('nome')->paginate(10);
    </x-docs.codigo>

    <x-docs.secao titulo="Muitas páginas" />
    <p>As páginas do meio viram “…”, e os números usam ponto de milhar.</p>
    <x-docs.exemplo arquivo="pagination.longa" />

    <x-docs.secao titulo="Acessibilidade" />
    <x-docs.nota tipo="a11y">
        A navegação tem <code>aria-label="Paginação"</code>, a página atual tem <code>aria-current="page"</code> e os números são lidos como “Página 2”.
    </x-docs.nota>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['paginator', 'Paginator', '—', 'Resultado de `paginate()` ou `simplePaginate()`. Com o `simplePaginate()`, aparecem só Anterior e Próxima.'],
        ['summary', 'bool', 'true', 'Mostra o resumo “Mostrando 1 a 10 de 57 resultados”.'],
    ]" />
</x-docs.pagina>
