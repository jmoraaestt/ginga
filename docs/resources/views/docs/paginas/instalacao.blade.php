<x-docs.pagina :pagina="$pagina">
    <x-docs.secao titulo="Requisitos" />

    <x-docs.props :colunas="['Requisito', 'Versão']" legenda="Requisitos" :itens="[
        ['PHP', '8.2 ou mais novo'],
        ['Laravel', '11, 12 ou 13'],
        ['Bootstrap', '5.3, pelo CDN (padrão) ou pela sua aplicação'],
    ]" />

    <x-docs.secao titulo="Instalação" />

    <x-docs.codigo lang="bash">composer require jmoraaestt/ginga</x-docs.codigo>

    <p>O pacote se registra sozinho (auto-discovery). Não há nada para publicar: o tema é servido pelo próprio pacote e se atualiza junto com ele.</p>

    <x-docs.secao titulo="Layout base" />

    <p>Três componentes no layout carregam tudo o que os outros precisam:</p>

    <x-docs.codigo titulo="resources/views/components/layouts/app.blade.php">
        @verbatim
        <!doctype html>
        <html lang="pt-BR">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Minha aplicação</title>

            <x-ginga::styles />   {{-- Bootstrap + tema do Ginga --}}
        </head>
        <body>
            {{ $slot }}

            <x-ginga::flash />    {{-- mensagens de sucesso e erro como toasts --}}
            <x-ginga::scripts />  {{-- JavaScript do Bootstrap --}}
        </body>
        </html>
        @endverbatim
    </x-docs.codigo>

    <x-docs.secao titulo="Primeiro formulário" />

    <p>Use os componentes e valide no controller com as regras do Ginga:</p>

    <x-docs.codigo titulo="resources/views/clientes/create.blade.php">
        @verbatim
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <x-ginga::input name="nome" label="Nome" required />
            <x-ginga::cpf name="cpf" required />
            <x-ginga::telefone name="celular" />
            <x-ginga::cep name="cep" :preencher="['logradouro' => 'endereco', 'localidade' => 'cidade', 'uf' => 'uf']" />
            <x-ginga::button type="submit">Salvar</x-ginga::button>
        </form>
        @endverbatim
    </x-docs.codigo>

    <x-docs.codigo lang="php" titulo="app/Http/Controllers/ClienteController.php">
        $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'required|cpf',
            'celular' => 'nullable|telefone',
            'cep' => 'nullable|cep',
        ]);

        return redirect()->route('clientes.index')->with('sucesso', 'Cliente salvo.');
    </x-docs.codigo>

    <p>Pronto. Os campos ganham máscara, o erro de validação aparece embaixo do campo certo, o valor digitado volta depois de um erro e o <code>flash</code> mostra “Cliente salvo.” no canto da tela.</p>

    <x-docs.secao titulo="Estilos e scripts" />

    @php
        $propsStyles = [
            ['bootstrap', 'bool', 'true', 'Em `<x-ginga::styles>`. Carrega o CSS do Bootstrap pelo CDN. Use `:bootstrap="false"` se a aplicação já carrega o Bootstrap.'],
            ['icons', 'bool', 'false', 'Em `<x-ginga::styles>`. Carrega também o Bootstrap Icons.'],
        ];
    @endphp
    <x-docs.props :itens="$propsStyles" />

    <p><code>&lt;x-ginga::scripts /&gt;</code> não tem props: carrega o JavaScript do Bootstrap pelo CDN, usado por modal, toast, alert com botão de fechar e confirmação de exclusão.</p>

    <x-docs.secao titulo="Com Vite" nivel="3" />

    <p>Se a aplicação já empacota o Bootstrap, carregue só o tema e exponha o Bootstrap para os scripts do Ginga:</p>

    <x-docs.codigo>
        @verbatim
        <x-ginga::styles :bootstrap="false" />
        {{-- e não use <x-ginga::scripts /> --}}
        @endverbatim
    </x-docs.codigo>

    <x-docs.codigo lang="javascript" titulo="resources/js/app.js">
        import * as bootstrap from 'bootstrap';
        window.bootstrap = bootstrap;
    </x-docs.codigo>

    <x-docs.secao titulo="Convenções" />

    <x-docs.nota titulo="Props booleanas">
        Escreva a prop sem valor (<code>&lt;x-ginga::button pill&gt;</code>) ou com uma expressão PHP (<code>:loading="$salvando"</code>).
        Evite <code>loading="false"</code>: sem os dois-pontos, o valor chega como a string <code>"false"</code>, que é verdadeira.
    </x-docs.nota>

    <x-docs.nota titulo="Atributos extras" tipo="dica">
        Qualquer atributo que não é prop (<code>id</code>, <code>wire:model</code>, <code>x-show</code>...) vai para o elemento principal do componente.
        Classes extras são somadas às do componente; os demais atributos substituem os padrões.
    </x-docs.nota>
</x-docs.pagina>
