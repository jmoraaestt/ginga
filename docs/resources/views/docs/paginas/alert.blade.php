<x-docs.pagina :pagina="$pagina">
    <x-docs.exemplo arquivo="alert.basico" />

    <x-docs.secao titulo="Variantes" />
    <p>Qualquer variante do Bootstrap. O tema do Ginga ajusta fundo, borda e texto de <code>primary</code>, <code>success</code>, <code>warning</code> e <code>danger</code> nos temas claro e escuro.</p>
    <x-docs.exemplo arquivo="alert.variantes" />

    <x-docs.secao titulo="Título" />
    <x-docs.exemplo arquivo="alert.titulo" />

    <x-docs.secao titulo="Botão de fechar" />
    <p>Com <code>dismissible</code>, o alerta ganha o botão de fechar e a animação de saída. Depende do JavaScript do Bootstrap.</p>
    <x-docs.exemplo arquivo="alert.fechar" />

    <x-docs.secao titulo="Ícone e link" />
    <p>O slot <code>icon</code> alinha o ícone ao texto. Use <code>alert-link</code> para o link acompanhar a cor do alerta.</p>
    <x-docs.exemplo arquivo="alert.icone" />

    <x-docs.secao titulo="Mensagem da sessão" />
    <x-docs.codigo>
        @verbatim
        @if (session('sucesso'))
            <x-ginga::alert variant="success" role="status" dismissible>
                {{ session('sucesso') }}
            </x-ginga::alert>
        @endif
        @endverbatim
    </x-docs.codigo>
    <p>Para mensagens que somem sozinhas no canto da tela, use o <a href="{{ route('docs.pagina', 'toast') }}">flash</a>.</p>

    <x-docs.secao titulo="Acessibilidade" />
    <x-docs.nota tipo="a11y">
        <code>role="alert"</code> (o padrão) faz o leitor de tela interromper o que está lendo para anunciar a mensagem: use para erros e avisos urgentes.
        Para mensagens informativas, como “Salvo com sucesso”, passe <code>role="status"</code>, que espera a leitura atual terminar.
    </x-docs.nota>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['variant', 'string', 'primary', 'Variante do Bootstrap: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`.'],
        ['title', 'string|null', 'null', 'Título em destaque acima da mensagem. Também aceita o slot `title`, com HTML.'],
        ['dismissible', 'bool', 'false', 'Botão de fechar com animação de saída.'],
    ]" />
    <x-docs.props legenda="Slots" :colunas="['Slot', 'Descrição']" :itens="[
        ['(padrão)', 'Mensagem do alerta.'],
        ['title', 'Título com HTML. Substitui a prop `title`.'],
        ['icon', 'Ícone à esquerda, alinhado ao texto.'],
    ]" />
</x-docs.pagina>
