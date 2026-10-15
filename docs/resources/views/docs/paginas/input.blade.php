<x-docs.pagina :pagina="$pagina">
    <x-docs.exemplo arquivo="input.basico" />

    <p>O componente cuida de tudo o que todo campo repete:</p>
    <ul>
        <li><strong>Valor depois de erro:</strong> quando a validação falha, o campo volta com o que foi digitado (<code>old()</code>). Senhas nunca voltam preenchidas.</li>
        <li><strong>Erro de validação:</strong> com erro no <code>$errors</code>, o campo recebe <code>is-invalid</code> e <code>aria-invalid="true"</code>, e a mensagem aparece embaixo, ligada por <code>aria-describedby</code>.</li>
        <li><strong>id automático:</strong> gerado a partir do <code>name</code> (<code>campo-nome</code>), ligando o label ao campo. Passe <code>id</code> para usar outro.</li>
        <li><strong>Obrigatório:</strong> com <code>required</code>, o label ganha um asterisco escondido do leitor de tela, que já anuncia o campo como obrigatório.</li>
    </ul>

    <x-docs.secao titulo="Depois de um erro" />
    <p>Assim fica o campo quando o controller rejeita o envio. A view não muda nada:</p>
    <x-docs.exemplo arquivo="input.erro" />

    <x-docs.secao titulo="Prefixo e sufixo" />
    <x-docs.exemplo arquivo="input.grupo" />

    <x-docs.secao titulo="Nomes com colchetes" />
    <p><code>endereco[cidade]</code> funciona: o erro e o <code>old()</code> são procurados em <code>endereco.cidade</code>.</p>
    <x-docs.exemplo arquivo="input.colchetes" />

    <x-docs.secao titulo="HTML gerado" />
    <x-docs.codigo lang="html">
        <div class="mb-3">
            <label class="form-label" for="campo-nome">
                Nome
                <span class="text-danger" aria-hidden="true">*</span>
            </label>
            <input class="form-control" type="text" id="campo-nome" name="nome" required="required">
            <div class="invalid-feedback" id="campo-nome-erro"></div>
        </div>
    </x-docs.codigo>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['name', 'string|null', 'null', 'Nome do campo. Também é usado para achar o erro e o `old()`.'],
        ['label', 'string|null', 'null', 'Texto do label.'],
        ['type', 'string|null', 'text', 'Tipo do input: `email`, `password`, `number`, `date`...'],
        ['value', 'mixed', 'null', 'Valor inicial. O `old()` tem prioridade depois de um envio com erro. Datas do Eloquent funcionam direto com `type=date`.'],
        ['help', 'string|null', 'null', 'Texto de ajuda embaixo do campo, ligado por `aria-describedby`.'],
        ['mask', 'string|null', 'null', '`cpf`, `cnpj`, `cpf-cnpj`, `cep`, `telefone` ou `dinheiro`. Veja os campos brasileiros.'],
        ['prefix', 'string|null', 'null', 'Texto antes do campo, em um `input-group`.'],
        ['suffix', 'string|null', 'null', 'Texto depois do campo, em um `input-group`.'],
    ]" />
    <p>Os demais atributos (<code>required</code>, <code>autofocus</code>, <code>wire:model</code>...) vão para o <code>&lt;input&gt;</code>.</p>
</x-docs.pagina>
