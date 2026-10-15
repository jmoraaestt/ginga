<x-docs.pagina :pagina="$pagina">
    <x-slot:etiquetas>
        <x-ginga::badge variant="secondary" subtle pill><i class="bi bi-filetype-js me-1" aria-hidden="true"></i>JavaScript do Bootstrap</x-ginga::badge>
    </x-slot:etiquetas>

    <x-docs.secao titulo="Flash" />
    <p>O jeito mais comum de usar toasts: uma linha no layout mostra as mensagens da sessão depois de um redirect.</p>

    <x-docs.codigo titulo="layout">
        @verbatim
        <x-ginga::flash />
        @endverbatim
    </x-docs.codigo>

    <div class="docs-exemplo">
        <div class="docs-exemplo-preview docs-exemplo-centro">
            <x-ginga::button variant="success" data-docs-toast="success" data-docs-toast-mensagem="Cliente salvo.">sucesso</x-ginga::button>
            <x-ginga::button variant="danger" data-docs-toast="danger" data-docs-toast-mensagem="Não foi possível emitir a nota fiscal.">erro</x-ginga::button>
            <x-ginga::button variant="warning" data-docs-toast="warning" data-docs-toast-mensagem="Cliente Maria Silva excluído.">aviso</x-ginga::button>
            <x-ginga::button data-docs-toast="primary" data-docs-toast-mensagem="Importação agendada para 22h.">info</x-ginga::button>
        </div>
        <x-docs.codigo lang="php" class="docs-exemplo-codigo">
            return redirect()->route('clientes.index')->with('sucesso', 'Cliente salvo.');
            return back()->with('erro', 'Não foi possível emitir a nota fiscal.');
            return back()->with('aviso', 'Cliente Maria Silva excluído.');
            return back()->with('info', 'Importação agendada para 22h.');
        </x-docs.codigo>
    </div>

    <x-docs.props :colunas="['Chave da sessão', 'Cor']" legenda="Chaves da sessão" :itens="[
        ['sucesso ou success', 'Verde.'],
        ['erro ou error', 'Vermelho. Fica 8 segundos na tela.'],
        ['aviso ou warning', 'Âmbar.'],
        ['info', 'Rosa.'],
    ]" />

    <p>Depois de um erro de validação, o <code>flash</code> também mostra “Corrija os 3 campos destacados.”, e cada mensagem aparece embaixo do seu campo.</p>

    <x-docs.props legenda="Props do flash" :itens="[
        ['position', 'string', 'bottom-end', 'Canto da tela. Veja as posições do `toast-container`.'],
        ['validation', 'bool', 'true', 'Mostra o toast de erro de validação.'],
    ]" />

    <x-docs.secao titulo="Toast" />
    <p>Para mensagens personalizadas, use o <code>toast</code> dentro de um <code>toast-container</code>. Ele usa as mesmas cores do alert e pausa o tempo enquanto o mouse ou o foco do teclado estão sobre ele.</p>
    <x-docs.exemplo arquivo="toast.variantes" />

    <x-docs.codigo>
        @verbatim
        <x-ginga::toast-container position="top-end">
            <x-ginga::toast variant="success">Cliente cadastrado com sucesso.</x-ginga::toast>
        </x-ginga::toast-container>
        @endverbatim
    </x-docs.codigo>

    <x-docs.secao titulo="Acessibilidade" />
    <x-docs.nota tipo="a11y">
        O <code>danger</code> recebe <code>role="alert"</code> e <code>aria-live="assertive"</code>: o leitor de tela anuncia o erro na hora.
        As demais variantes recebem <code>role="status"</code> e <code>aria-live="polite"</code>, que esperam a leitura atual terminar.
    </x-docs.nota>
    <x-docs.nota tipo="aviso" titulo="Erros que somem">
        Quem lê devagar ou usa leitor de tela pode não conseguir ler a mensagem a tempo. Para erros, use um <code>delay</code> maior ou <code>:autohide="false"</code>,
        e mantenha a indicação no próprio campo.
    </x-docs.nota>

    <x-docs.secao titulo="Props" />
    <x-docs.secao titulo="toast" id="props-toast" nivel="3" />
    <x-docs.props :itens="[
        ['variant', 'string', 'primary', 'Variante do Bootstrap. Gera fundo, texto e borda suaves (`bg-{variant}-subtle`).'],
        ['title', 'string|null', 'null', 'Título em destaque. Também aceita o slot `title`.'],
        ['delay', 'int', '5000', 'Tempo em milissegundos até o toast sumir.'],
        ['autohide', 'bool', 'true', 'Com `false`, só fecha pelo botão.'],
    ]" />
    <x-docs.secao titulo="toast-container" id="props-toast-container" nivel="3" />
    <x-docs.props :itens="[
        ['position', 'string', 'bottom-end', '`top-start`, `top-center`, `top-end`, `bottom-start`, `bottom-center` ou `bottom-end`.'],
    ]" />
</x-docs.pagina>
