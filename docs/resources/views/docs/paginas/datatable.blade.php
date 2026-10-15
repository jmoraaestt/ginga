<x-docs.pagina :pagina="$pagina">
    <x-slot:etiquetas>
        <x-ginga::badge variant="success" subtle pill><i class="bi bi-check2 me-1" aria-hidden="true"></i>Funciona sem JavaScript</x-ginga::badge>
    </x-slot:etiquetas>

    <p>Busca, ordenação e paginação feitas na consulta ao banco. Funciona com qualquer quantidade de registros, porque só a página atual é carregada.</p>

    @if (config('docs.estatico'))
        <x-docs.nota tipo="info" titulo="Este site é estático">
            A tabela abaixo mostra o primeiro carregamento. Para buscar, ordenar e paginar de verdade, rode o site de documentação localmente:
            <code>cd docs &amp;&amp; composer install &amp;&amp; php artisan serve</code>.
        </x-docs.nota>
    @endif

    <x-docs.exemplo arquivo="datatable.clientes" />

    <x-docs.secao titulo="No controller" />
    <p>A classe <code>Tabela</code> lê a query string e monta a consulta:</p>
    <x-docs.codigo lang="php">
        use Ginga\Support\Tabela;

        public function index()
        {
            $clientes = Tabela::de(Cliente::query())
                ->buscarEm(['nome', 'cpf', 'cidade', 'empresa.nome'])
                ->ordenarPor(['nome', 'cidade', 'mensalidade'], padrao: 'nome')
                ->paginar();

            return view('clientes.index', compact('clientes'));
        }
    </x-docs.codigo>

    <x-docs.secao titulo="O que a tela ganha" />
    <ul>
        <li><strong>Busca:</strong> procura nas colunas de <code>buscarEm()</code> enquanto a pessoa digita, com uma pausa de 350 ms.</li>
        <li><strong>Ordenação:</strong> cabeçalhos clicáveis nas colunas de <code>ordenarPor()</code>, com seta e <code>aria-sort</code>.</li>
        <li><strong>Itens por página</strong> e <strong>paginação em português</strong>, com “Mostrando 1 a 10 de 138 resultados”.</li>
        <li><strong>Lista vazia:</strong> “Nenhum resultado para ‘xyz’.” com um link para limpar a busca.</li>
        <li><strong>URL sempre atualizada:</strong> voltar e avançar funcionam, e o endereço pode ser salvo ou compartilhado.</li>
        <li><strong>Leitor de tela:</strong> ouve “Mostrando 1 a 10 de 57 resultados” a cada carregamento.</li>
    </ul>

    <x-docs.secao titulo="Busca em documentos" nivel="3" />
    <p>Quando a busca tem números, a <code>Tabela</code> também procura a versão sem pontuação: <code>529.982.247</code> encontra o CPF salvo como <code>52998224725</code>. Vale para CNPJ, CEP e telefone. No PostgreSQL, a busca usa <code>ILIKE</code>.</p>

    <x-docs.secao titulo="Segurança" nivel="3" />
    <p>Só as colunas de <code>ordenarPor()</code> podem ser ordenadas, só as opções de <code>itensPorPagina()</code> são aceitas e valores inválidos voltam ao padrão. A busca usa bindings do banco, nunca texto concatenado no SQL.</p>

    <x-docs.secao titulo="Com e sem JavaScript" nivel="3" />
    <p>Sem JavaScript, tudo funciona com links e um formulário GET, e aparece um botão “Buscar”. Com JavaScript, o componente pede a página inteira ao servidor e troca só a tabela: não precisa de rota extra nem de mudança no controller. Se algo der errado, ele cai para a navegação normal.</p>

    <x-docs.secao titulo="A classe Tabela" />
    <x-docs.props :colunas="['Método', 'Descrição']" legenda="Métodos da classe Tabela" :itens="[
        ['Tabela::de($query)', 'Recebe um query builder do Eloquent, uma relação (`$empresa->clientes()`) ou um `DB::table()`.'],
        ['buscarEm(array $colunas)', 'Colunas pesquisadas. Use ponto para relacionamentos: `empresa.nome`.'],
        ['ordenarPor(array $colunas, ?string $padrao, string $direcao)', 'Colunas que podem ser ordenadas e a ordem inicial.'],
        ['itensPorPagina(int $padrao, ?array $opcoes)', 'Padrão `10`, opções `[10, 25, 50, 100]`.'],
        ['paginar()', 'Executa a consulta. O resultado fica em `$tabela->linhas`, um paginator do Laravel.'],
    ]" />
    <p>Parâmetros da URL: <code>?busca=maria&amp;ordenar=nome&amp;direcao=desc&amp;por_pagina=25&amp;pagina=2</code>.</p>

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['tabela', 'Tabela', '—', 'O resultado de `Tabela::de()`.'],
        ['columns', 'array', '[]', '`chave => título`. A chave é a coluna do banco. Aceita também `label`, `class` e `hidden` (esconde o título só visualmente, útil na coluna de ações).'],
        ['search', 'bool', 'true', 'Mostra o campo de busca.'],
        ['placeholder', 'string', 'Buscar...', 'Texto de exemplo do campo de busca.'],
        ['empty', 'string', 'Nenhum registro encontrado.', 'Mensagem da tabela vazia, quando não há busca.'],
        ['caption', 'string|null', 'null', 'Nome da tabela para leitores de tela.'],
        ['id', 'string', 'ginga-datatable', 'Precisa ser fixo. Com duas tabelas na mesma página, dê um id para cada uma.'],
    ]" />
</x-docs.pagina>
