<x-docs.pagina :pagina="$pagina">
    <x-docs.exemplo arquivo="textarea.basico" />

    <x-docs.secao titulo="Depois de um erro" />
    <x-docs.exemplo arquivo="textarea.erro" />

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['name', 'string|null', 'null', 'Nome do campo.'],
        ['label', 'string|null', 'null', 'Texto do label.'],
        ['value', 'mixed', 'null', 'Texto inicial. O `old()` tem prioridade.'],
        ['help', 'string|null', 'null', 'Texto de ajuda.'],
        ['rows', 'int', '3', 'Altura em linhas.'],
    ]" />
    <p>Os demais atributos (<code>maxlength</code>, <code>placeholder</code>, <code>wire:model</code>...) vão para o <code>&lt;textarea&gt;</code>.</p>
</x-docs.pagina>
