<x-docs.pagina :pagina="$pagina">
    <x-docs.exemplo arquivo="card.basico" />

    <x-docs.secao titulo="Com tabela" />
    <p>Com <code>flush</code>, o conteúdo encosta nas bordas, sem o <code>card-body</code>. Use com <code>table</code> e <code>list-group</code>.</p>
    <x-docs.exemplo arquivo="card.flush" />

    <x-docs.secao titulo="Cabeçalho personalizado" />
    <p>O slot <code>header</code> substitui o cabeçalho inteiro.</p>
    <x-docs.exemplo arquivo="card.cabecalho" />

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['title', 'string|null', 'null', 'Título no cabeçalho.'],
        ['level', 'int', '2', 'Nível do título (`h2`, `h3`...), para manter a hierarquia da página. O tamanho visual não muda.'],
        ['flush', 'bool', 'false', 'Conteúdo sem o `card-body`, encostado nas bordas.'],
    ]" />
    <x-docs.props legenda="Slots" :colunas="['Slot', 'Descrição']" :itens="[
        ['(padrão)', 'Conteúdo do card.'],
        ['actions', 'Botões à direita do título.'],
        ['header', 'Substitui o cabeçalho inteiro.'],
        ['footer', 'Rodapé.'],
    ]" />
</x-docs.pagina>
