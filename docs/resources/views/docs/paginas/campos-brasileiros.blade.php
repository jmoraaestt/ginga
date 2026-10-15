<x-docs.pagina :pagina="$pagina">
    <p>Cada campo tem máscara enquanto a pessoa digita, abre o teclado numérico no celular e aceita as mesmas props do <a href="{{ route('docs.pagina', 'input') }}">input</a> (ou do <a href="{{ route('docs.pagina', 'select') }}">select</a>, no caso de <code>uf</code>), inclusive <code>label</code> para trocar o texto. Digite nos exemplos abaixo.</p>

    <x-docs.secao titulo="CPF e CNPJ" />
    <x-docs.exemplo arquivo="campos-brasileiros.documentos" />

    <x-docs.nota tipo="aviso" titulo="CNPJ alfanumérico">
        Desde julho de 2026 o CNPJ pode ter letras nas 12 primeiras posições. O campo aceita e converte para maiúsculas, a regra <code>cnpj</code> valida e a busca da datatable encontra.
        Guarde em uma coluna de texto (<code>string('cnpj', 14)</code>), não numérica.
    </x-docs.nota>

    <x-docs.secao titulo="Telefone" />
    <p>A máscara muda de fixo para celular no 11º dígito. O campo usa <code>type="tel"</code>.</p>
    <x-docs.exemplo arquivo="campos-brasileiros.contato" />

    <x-docs.secao titulo="Dinheiro" />
    <p>Os dígitos entram pela direita, como numa maquininha: digitar <code>5</code> mostra <code>0,05</code>. O <code>R$</code> vem antes do campo; troque com <code>prefix</code>.</p>
    <x-docs.exemplo arquivo="campos-brasileiros.dinheiro" />

    <x-docs.secao titulo="Estado (UF)" />
    <p>Select com os 27 estados. O valor enviado é sempre a sigla; com <code>formato="sigla"</code>, a lista também mostra só a sigla.</p>
    <x-docs.exemplo arquivo="campos-brasileiros.uf" />

    <x-docs.secao titulo="Valor vindo do banco" />
    <p>Passe o valor sem máscara e o componente formata. No <code>dinheiro</code>, passe o decimal com ponto, como vem de uma coluna <code>decimal</code>.</p>
    <x-docs.exemplo arquivo="campos-brasileiros.banco" />

    <x-docs.secao titulo="Resumo" />
    <x-docs.props :colunas="['Componente', 'Label padrão', 'Máscara', 'Observações']" legenda="Campos brasileiros" :itens="[
        ['cpf', 'CPF', '000.000.000-00', 'Teclado numérico.'],
        ['cnpj', 'CNPJ', '00.000.000/0000-00', 'Aceita letras nas 12 primeiras posições.'],
        ['cpf-cnpj', 'CPF ou CNPJ', 'muda sozinha', 'CPF até 11 dígitos; CNPJ a partir do 12º caractere ou da primeira letra.'],
        ['telefone', 'Telefone', '(00) 0000-0000', 'Vira `(00) 00000-0000` no 11º dígito.'],
        ['dinheiro', 'Valor', '1.234,56', 'Com `R$` antes do campo.'],
        ['cep', 'CEP', '00000-000', 'Pode buscar o endereço no ViaCEP.'],
        ['uf', 'Estado', '—', 'Select com os 27 estados.'],
    ]" />

    <x-docs.nota tipo="js" titulo="E sem JavaScript?">
        As máscaras são um script pequeno, sem dependências, incluído uma única vez pelo primeiro campo com máscara. Ele também funciona com campos adicionados depois do carregamento (Livewire, Alpine).
        Sem JavaScript, o campo continua funcionando sem máscara, e a validação no servidor continua valendo.
    </x-docs.nota>
</x-docs.pagina>
