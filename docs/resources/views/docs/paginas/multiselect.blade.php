<x-docs.pagina :pagina="$pagina">
    <x-slot:etiquetas>
        <x-ginga::badge variant="secondary" subtle pill><i class="bi bi-filetype-js me-1" aria-hidden="true"></i>JavaScript do Bootstrap</x-ginga::badge>
    </x-slot:etiquetas>

    <p>O campo abre um modal com busca, “Selecionar todos” e “Limpar”. A escolha só vale ao clicar em <strong>Inserir</strong>: fechar ou cancelar descarta. Os itens escolhidos aparecem como etiquetas, cada uma com um botão para remover.</p>

    <x-docs.exemplo arquivo="multiselect.basico" />

    <p>A busca ignora acentos: “sao” encontra “São Paulo”. O servidor recebe um array, como no <code>checkbox-group</code>:</p>

    <x-docs.codigo lang="php">
        'estados' => 'required|array|min:1',
        'estados.*' => 'uf',
    </x-docs.codigo>

    <x-docs.secao titulo="Poucas opções" />
    <x-docs.exemplo arquivo="multiselect.poucas" />

    <x-docs.secao titulo="Acessibilidade" />
    <ul>
        <li><strong>Campo:</strong> o leitor de tela anuncia o rótulo e a quantidade (“Estados onde atende, 3 selecionados”).</li>
        <li><strong>Abrir o modal:</strong> o foco vai para a busca.</li>
        <li><strong>Remover uma etiqueta:</strong> o foco passa para a etiqueta seguinte.</li>
    </ul>

    <x-docs.secao titulo="Evento" />
    <p>Ao inserir ou remover, o componente dispara o evento <code>ginga:multiselect</code> com os valores escolhidos:</p>
    <x-docs.codigo lang="javascript">
        document.addEventListener('ginga:multiselect', (evento) => {
            console.log(evento.detail.valores); // ['SP', 'RJ', 'MG']
        });
    </x-docs.codigo>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['name', 'string|null', 'null', 'Nome do campo. O `[]` é adicionado sozinho.'],
        ['label', 'string|null', 'null', 'Texto do label do campo e do campo de busca.'],
        ['title', 'string|null', 'o label', 'Título do modal.'],
        ['options', 'mixed', '[]', 'Opções, nos mesmos formatos do select.'],
        ['value', 'mixed', '[]', 'Opções já escolhidas. O `old()` tem prioridade depois de um erro.'],
        ['placeholder', 'string', 'Selecione', 'Texto do campo quando nada foi escolhido.'],
        ['confirm', 'string', 'Inserir', 'Texto do botão que confirma a escolha.'],
        ['search', 'bool|null', 'null', 'Mostra a busca. Com `null`, só quando há mais de 8 opções.'],
        ['required', 'bool', 'false', 'Mostra o asterisco. Exigir pelo menos uma opção é papel da validação.'],
        ['help', 'string|null', 'null', 'Texto de ajuda.'],
    ]" />
</x-docs.pagina>
