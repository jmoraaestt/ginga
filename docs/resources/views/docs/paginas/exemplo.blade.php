<x-docs.pagina :pagina="$pagina">
    <p>Uma tela real de cadastro, do jeito que ela ficaria na sua aplicação: trilha de navegação, alerta, cartões de resumo, datatable com busca no banco, exclusão com confirmação e um modal com o formulário. Experimente buscar, ordenar, excluir e salvar um cliente.</p>

    <x-docs.secao titulo="A tela" />

    <x-docs.exemplo arquivo="exemplo.tela" />

    <x-docs.secao titulo="O controller" />

    <p>Do lado do servidor, só a consulta, a validação e as mensagens. Busca, ordenação, paginação, erros nos campos e toasts são dos componentes.</p>

    <x-docs.codigo lang="php" titulo="app/Http/Controllers/ClienteController.php">
        use Ginga\Support\Mascara;
        use Ginga\Support\Tabela;

        class ClienteController extends Controller
        {
            public function index()
            {
                $clientes = Tabela::de(Cliente::query())
                    ->buscarEm(['nome', 'cpf', 'cidade'])
                    ->ordenarPor(['nome', 'mensalidade'], padrao: 'nome')
                    ->paginar();

                return view('clientes.index', compact('clientes'));
            }

            public function store(Request $request)
            {
                $dados = $request->validate([
                    'nome' => 'required|string|max:100',
                    'cpf' => 'required|cpf|unique:clientes',
                    'celular' => 'nullable|telefone',
                    'mensalidade' => 'required',
                    'cep' => 'nullable|cep',
                    'uf' => 'nullable|uf',
                    'ativo' => 'boolean',
                ]);

                Cliente::create([
                    ...$dados,
                    'cpf' => Mascara::limpar('cpf', $dados['cpf']),
                    'mensalidade' => Mascara::limpar('dinheiro', $dados['mensalidade']),
                ]);

                return back()->with('sucesso', 'Cliente salvo.');
            }

            public function destroy(Cliente $cliente)
            {
                $cliente->delete();

                return back()->with('aviso', "Cliente {$cliente->nome} excluído.");
            }
        }
    </x-docs.codigo>

    <x-docs.nota tipo="dica" titulo="E o modal depois de um erro?">
        Se a validação falhar, o formulário volta com os erros, mas o modal começa fechado. Abra-o de novo quando houver erros:
        <code>@@if ($errors->any()) &lt;script&gt;addEventListener('load', () => new bootstrap.Modal('#novo-cliente').show())&lt;/script&gt; @@endif</code>
    </x-docs.nota>
</x-docs.pagina>
