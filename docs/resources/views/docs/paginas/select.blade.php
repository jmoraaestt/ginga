<x-docs.pagina :pagina="$pagina">
    <x-docs.exemplo arquivo="select.basico" />

    <x-docs.secao titulo="Múltipla escolha" />
    <p>Com <code>multiple</code>, o <code>[]</code> é adicionado ao <code>name</code> sozinho e o servidor recebe um array.</p>
    <x-docs.exemplo arquivo="select.multiplo" />
    <x-docs.nota tipo="dica" titulo="Escolher vários">
        No select múltiplo é preciso segurar Ctrl (ou Cmd), o que muita gente não sabe. Com poucas opções, prefira o
        <a href="{{ route('docs.pagina', 'grupos') }}">checkbox-group</a>. Com muitas, como os 27 estados, prefira o
        <a href="{{ route('docs.pagina', 'multiselect') }}">multiselect</a>.
    </x-docs.nota>

    <x-docs.secao titulo="Depois de um erro" />
    <x-docs.exemplo arquivo="select.erro" />

    <x-docs.secao titulo="Formatos de opções" />
    <p><code>select</code>, <code>multiselect</code>, <code>radio-group</code> e <code>checkbox-group</code> aceitam as opções de várias formas:</p>
    <x-docs.codigo>
        @verbatim
        {{-- valor => texto --}}
        :options="['sp' => 'São Paulo', 'rj' => 'Rio de Janeiro']"

        {{-- lista simples: o texto também é o valor enviado --}}
        :options="['Manhã', 'Tarde', 'Noite']"

        {{-- Collection do Eloquent --}}
        :options="Empresa::orderBy('nome')->pluck('nome', 'id')"

        {{-- enum: usa o método label() do enum, se existir, ou o nome do caso --}}
        :options="Plano::class"
        @endverbatim
    </x-docs.codigo>
    <p>O <code>value</code> também aceita o próprio enum (<code>:value="$assinatura->plano"</code>), uma Collection ou um array.</p>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['name', 'string|null', 'null', 'Nome do campo.'],
        ['label', 'string|null', 'null', 'Texto do label.'],
        ['options', 'mixed', '[]', 'Opções: array, lista simples, Collection ou classe de enum.'],
        ['value', 'mixed', 'null', 'Valor selecionado, ou array no múltiplo. Aceita enum. O `old()` tem prioridade.'],
        ['placeholder', 'string|null', 'null', 'Primeira opção, com valor vazio. Ex.: `Selecione`.'],
        ['help', 'string|null', 'null', 'Texto de ajuda embaixo do campo.'],
    ]" />
</x-docs.pagina>
