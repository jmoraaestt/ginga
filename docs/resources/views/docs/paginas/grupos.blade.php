<x-docs.pagina :pagina="$pagina">
    <p>Grupos de opções dentro de um <code>&lt;fieldset&gt;</code>, com o label no <code>&lt;legend&gt;</code>: o leitor de tela anuncia “Plano, grupo” ao entrar nas opções.</p>

    <x-docs.secao titulo="Radio-group" />
    <x-docs.exemplo arquivo="grupos.radio" />

    <x-docs.secao titulo="Checkbox-group" />
    <p>O <code>[]</code> é adicionado ao <code>name</code> sozinho e o servidor recebe um array. Com <code>switch</code>, as opções viram interruptores.</p>
    <x-docs.exemplo arquivo="grupos.checkbox" />

    <x-docs.secao titulo="Depois de um erro" />
    <p>O erro aparece uma vez, embaixo do grupo. No <code>checkbox-group</code>, erros dos itens (<code>dias.*</code>) também aparecem.</p>
    <x-docs.exemplo arquivo="grupos.erro" />
    <x-docs.codigo lang="php">
        'dias' => 'required|array|min:1',
        'dias.*' => 'in:seg,ter,qua,qui,sex',
    </x-docs.codigo>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['name', 'string|null', 'null', 'Nome do campo. No `checkbox-group`, o `[]` é adicionado sozinho.'],
        ['label', 'string|null', 'null', 'Texto do `<legend>`.'],
        ['options', 'array', '[]', 'Opções no formato `valor => texto`, ou nos outros formatos do select.'],
        ['value', 'mixed', '—', 'Opção marcada (`radio-group`) ou array de opções marcadas (`checkbox-group`).'],
        ['inline', 'bool', 'false', 'Opções lado a lado.'],
        ['required', 'bool', 'false', 'Mostra o asterisco. No `radio-group`, também adiciona `required` em cada opção.'],
        ['switch', 'bool', 'false', 'Só no `checkbox-group`: opções com visual de interruptor.'],
        ['help', 'string|null', 'null', 'Texto de ajuda.'],
    ]" />
    <p>A classe extra vai para o <code>&lt;fieldset&gt;</code>. Os demais atributos (<code>wire:model</code>, <code>disabled</code>...) vão para cada opção.</p>
</x-docs.pagina>
