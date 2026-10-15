<x-docs.pagina :pagina="$pagina">
    <p>Informe quais campos preencher com a resposta do <a href="https://viacep.com.br" target="_blank" rel="noopener">ViaCEP</a>. A chave é o campo do ViaCEP e o valor é o <code>name</code> (ou <code>id</code>) do campo do formulário. Digite um CEP abaixo:</p>

    <x-docs.exemplo arquivo="cep.endereco" />

    <x-docs.secao titulo="Como funciona" />
    <ul>
        <li><strong>Quando busca:</strong> só quando o 8º dígito é digitado. O mesmo CEP não é buscado duas vezes.</li>
        <li><strong>Foco:</strong> depois de preencher, o foco vai para o campo de <code>focar</code>, normalmente o número, que o ViaCEP não traz.</li>
        <li><strong>CEP inexistente:</strong> o campo fica marcado como inválido com a mensagem “CEP não encontrado.”.</li>
        <li><strong>Sem conexão:</strong> se o ViaCEP estiver fora do ar, nada acontece e a pessoa preenche o endereço à mão.</li>
        <li><strong>Livewire e Alpine:</strong> os campos preenchidos disparam <code>input</code> e <code>change</code>, então <code>wire:model</code> e <code>x-model</code> percebem a mudança.</li>
    </ul>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['preencher', 'array', '[]', 'Campos para preencher. Sem essa prop, o CEP não é buscado. Campos do ViaCEP: `logradouro`, `complemento`, `bairro`, `localidade`, `uf`, `estado`, `ibge`, `ddd`.'],
        ['focar', 'string|null', 'null', 'Campo que recebe o foco depois de preencher.'],
        ['label', 'string', 'CEP', 'Texto do label.'],
    ]" />
    <p>Os demais props e atributos são os do <a href="{{ route('docs.pagina', 'input') }}">input</a>.</p>
</x-docs.pagina>
