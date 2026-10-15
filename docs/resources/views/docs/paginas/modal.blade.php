<x-docs.pagina :pagina="$pagina">
    <x-slot:etiquetas>
        <x-ginga::badge variant="secondary" subtle pill><i class="bi bi-filetype-js me-1" aria-hidden="true"></i>JavaScript do Bootstrap</x-ginga::badge>
    </x-slot:etiquetas>

    <p>Abra com qualquer botão que tenha <code>data-bs-toggle="modal"</code> e <code>data-bs-target</code> apontando para o <code>id</code> do modal.</p>

    <x-docs.exemplo arquivo="modal.basico" centro />

    <p>O botão de salvar fica no rodapé, fora do <code>&lt;form&gt;</code>: o atributo <code>form="form-contato"</code> liga os dois.</p>

    <x-docs.secao titulo="Sem fechar por engano" />
    <p>Com <code>static</code>, clicar fora do modal ou apertar Esc não fecha. Use em formulários longos, que não podem ser perdidos por um clique errado.</p>
    <x-docs.exemplo arquivo="modal.estatico" centro />

    <x-docs.secao titulo="Acessibilidade" />
    <x-docs.nota tipo="a11y">
        Com <code>title</code>, o modal fica ligado ao título por <code>aria-labelledby</code>: o leitor de tela anuncia “Novo contato, diálogo” ao abrir.
        O título é um <code>&lt;h2&gt;</code> e o botão de fechar tem o texto “Fechar”.
    </x-docs.nota>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['id', 'string', '—', 'Obrigatório. Usado no `data-bs-target` do botão que abre o modal.'],
        ['title', 'string|null', 'null', 'Título com o botão de fechar, ligado ao modal por `aria-labelledby`.'],
        ['size', 'string|null', 'null', '`sm`, `lg` ou `xl`.'],
        ['centered', 'bool', 'false', 'Centraliza na vertical.'],
        ['scrollable', 'bool', 'false', 'Rola só o corpo quando o conteúdo é grande.'],
        ['static', 'bool', 'false', 'Não fecha ao clicar fora nem com Esc.'],
    ]" />
    <x-docs.props legenda="Slots" :colunas="['Slot', 'Descrição']" :itens="[
        ['(padrão)', 'Corpo do modal.'],
        ['footer', 'Rodapé, normalmente com os botões de cancelar e confirmar.'],
    ]" />
</x-docs.pagina>
