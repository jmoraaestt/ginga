<x-docs.pagina :pagina="$pagina">
    <x-docs.exemplo arquivo="badge.variantes" centro />

    <x-docs.secao titulo="Suave e arredondado" />
    <p>Com <code>subtle</code>, o badge usa fundo claro e borda, nas mesmas cores do alert. Com <code>pill</code>, os cantos ficam arredondados.</p>
    <x-docs.exemplo arquivo="badge.suaves" centro />

    <x-docs.secao titulo="Acessibilidade" />
    <x-docs.nota tipo="a11y">
        Não use só a cor para passar a informação: o texto do badge (“Ativo”, “Inativo”) deve fazer sentido sozinho.
    </x-docs.nota>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['variant', 'string', 'primary', 'Variante do Bootstrap.'],
        ['subtle', 'bool', 'false', 'Versão suave, com fundo claro e borda.'],
        ['pill', 'bool', 'false', 'Cantos arredondados.'],
    ]" />
</x-docs.pagina>
