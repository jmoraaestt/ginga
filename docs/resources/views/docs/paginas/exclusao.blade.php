<x-docs.pagina :pagina="$pagina">
    <x-slot:etiquetas>
        <x-ginga::badge variant="success" subtle pill><i class="bi bi-check2 me-1" aria-hidden="true"></i>Funciona sem JavaScript</x-ginga::badge>
    </x-slot:etiquetas>

    <p>Um botão em cada linha é tudo o que precisa. Ao clicar, um modal pergunta “Tem certeza que deseja excluir <strong>Maria Silva</strong>?”. Ao confirmar, o componente envia um <code>DELETE</code> com o token CSRF para a URL de <code>action</code>.</p>

    <x-docs.exemplo arquivo="exclusao.basico" />

    <x-docs.codigo>
        @verbatim
        <x-ginga::delete-button :action="route('clientes.destroy', $cliente)" :item="$cliente->nome" />
        @endverbatim
    </x-docs.codigo>

    <ul>
        <li><strong>Sem clique duplo:</strong> o botão de confirmar é desabilitado no envio, para não excluir duas vezes.</li>
        <li><strong>Foco:</strong> ao fechar o modal, o foco volta para o botão que o abriu.</li>
        <li><strong>Sem o JavaScript do Bootstrap:</strong> o botão usa a confirmação nativa do navegador e continua funcionando.</li>
        <li><strong>Dentro da datatable:</strong> continua funcionando depois de buscar ou trocar de página.</li>
    </ul>

    <x-docs.secao titulo="Textos personalizados" />
    <p>Coloque um <code>confirm-delete</code> na página para trocar o título, a pergunta e os botões. Com mais de um na página, aponte o botão para o modal certo com <code>modal</code>.</p>
    <x-docs.exemplo arquivo="exclusao.personalizado" centro />

    <x-docs.secao titulo="Acessibilidade" />
    <x-docs.nota tipo="a11y">
        Numa tabela há vários botões “Excluir”. O <code>item</code> também é lido pelo leitor de tela (“Excluir Maria Silva”), para cada botão dizer o que exclui.
    </x-docs.nota>

    <x-docs.secao titulo="Props" />
    <x-docs.secao titulo="delete-button" id="props-delete-button" nivel="3" />
    <x-docs.props :itens="[
        ['action', 'string', '—', 'URL que recebe o `DELETE`.'],
        ['item', 'string|null', 'null', 'Nome mostrado na confirmação e lido pelo leitor de tela.'],
        ['variant', 'string', 'outline-danger', 'Variante do botão.'],
        ['size', 'string|null', 'sm', 'Tamanho do botão.'],
        ['modal', 'string', 'ginga-confirmar-exclusao', '`id` de um `confirm-delete` personalizado.'],
    ]" />
    <x-docs.secao titulo="confirm-delete" id="props-confirm-delete" nivel="3" />
    <x-docs.props :itens="[
        ['id', 'string', 'ginga-confirmar-exclusao', 'Mesmo id passado no `modal` do botão.'],
        ['title', 'string', 'Excluir registro?', 'Título do modal.'],
        ['confirm', 'string', 'Excluir', 'Texto do botão que confirma.'],
        ['cancel', 'string', 'Cancelar', 'Texto do botão que cancela.'],
    ]" />
    <p>Para trocar a pergunta, passe o texto no slot. O elemento com <code>data-ginga-exclusao-item</code> recebe o nome do item.</p>
</x-docs.pagina>
