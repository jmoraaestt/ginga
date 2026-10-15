<x-docs.pagina :pagina="$pagina">
    <x-docs.exemplo arquivo="button.variantes" centro />

    <x-docs.secao titulo="Tamanhos e formato" />
    <x-docs.exemplo arquivo="button.tamanhos" centro />
    <p>Com <code>block</code>, o botão ocupa a largura toda:</p>
    <x-docs.exemplo arquivo="button.bloco" />

    <x-docs.secao titulo="Link com cara de botão" />
    <p>Com <code>href</code>, o componente gera um <code>&lt;a role="button"&gt;</code>. Link não aceita <code>disabled</code>: o componente usa a classe <code>disabled</code>, <code>aria-disabled="true"</code> e <code>tabindex="-1"</code> no lugar.</p>
    <x-docs.exemplo arquivo="button.link" centro />

    <x-docs.secao titulo="Carregando e desabilitado" />
    <p><code>loading</code> mostra um spinner, desabilita o botão e adiciona <code>aria-busy="true"</code>. O texto “Carregando...” fica visível só para leitores de tela.</p>
    <x-docs.exemplo arquivo="button.carregando" centro />
    <x-docs.codigo>
        @verbatim
        <x-ginga::button type="submit" :loading="$salvando">Salvar</x-ginga::button>
        @endverbatim
    </x-docs.codigo>

    <x-docs.secao titulo="Ícones" />
    <p>Use qualquer biblioteca de ícones ou SVG. Ícones decorativos levam <code>aria-hidden="true"</code>. Enquanto <code>loading</code> está ativo, o spinner ocupa o lugar do <code>iconLeft</code>.</p>
    <x-docs.exemplo arquivo="button.icones" centro />

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['variant', 'string', 'primary', 'Variante do Bootstrap: `primary`, `secondary`, `success`, `danger`, `outline-primary`, `link` etc.'],
        ['size', 'string|null', 'null', '`sm` ou `lg`.'],
        ['href', 'string|null', 'null', 'Renderiza um link `<a>` com papel de botão no lugar do `<button>`.'],
        ['pill', 'bool', 'false', 'Cantos totalmente arredondados.'],
        ['block', 'bool', 'false', 'Largura total.'],
        ['loading', 'bool', 'false', 'Spinner, botão desabilitado e `aria-busy`.'],
        ['disabled', 'bool', 'false', 'Desabilita o botão. Também funciona em links.'],
    ]" />
    <p>O <code>type</code> padrão é <code>button</code>, para não enviar formulários sem querer. Passe <code>type="submit"</code> no botão de envio.</p>
    <x-docs.props legenda="Slots" :colunas="['Slot', 'Descrição']" :itens="[
        ['(padrão)', 'Texto do botão.'],
        ['iconLeft', 'Ícone antes do texto.'],
        ['iconRight', 'Ícone depois do texto.'],
    ]" />
</x-docs.pagina>
