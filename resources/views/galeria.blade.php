@php
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\MessageBag;
    use Illuminate\Support\ViewErrorBag;

    $erros = fn (array $mensagens) => (new ViewErrorBag)->put('default', new MessageBag($mensagens));

    $paginador = new LengthAwarePaginator(array_fill(0, 10, 'x'), 57, 10, 2, ['path' => url()->current()]);

    $cores = [
        'rosa', 'rosa-hover', 'rosa-active', 'rosa-vivo', 'rosa-claro',
        'verde-agua', 'verde-agua-claro', 'ambar', 'ambar-claro', 'perigo', 'perigo-claro',
        'tinta', 'tinta-suave', 'borda', 'superficie',
    ];

    // Cada exemplo é mostrado de verdade (renderizado agora, pelo próprio Blade) e com o código que o gera.
    // 'erros' simula o retorno de um formulário com erro de validação.
    $secoes = [
        [
            'id' => 'botoes', 'titulo' => 'Botões',
            'texto' => 'Variantes, tamanhos, estado de carregamento e link com aparência de botão.',
            'exemplos' => [
                ['codigo' => <<<'BLADE'
<x-ginga::button>Salvar</x-ginga::button>
<x-ginga::button variant="secondary">Secundário</x-ginga::button>
<x-ginga::button variant="success">Sucesso</x-ginga::button>
<x-ginga::button variant="danger">Excluir</x-ginga::button>
<x-ginga::button variant="outline-primary">Contorno</x-ginga::button>
<x-ginga::button variant="link">Link</x-ginga::button>
BLADE],
                ['codigo' => <<<'BLADE'
<x-ginga::button size="sm">Pequeno</x-ginga::button>
<x-ginga::button pill>Pílula</x-ginga::button>
<x-ginga::button size="lg">Grande</x-ginga::button>
<x-ginga::button loading>Salvando</x-ginga::button>
<x-ginga::button disabled>Desabilitado</x-ginga::button>
<x-ginga::button href="#botoes">Link</x-ginga::button>
BLADE],
                ['codigo' => '<x-ginga::button block>Largura total</x-ginga::button>'],
            ],
        ],
        [
            'id' => 'mensagens', 'titulo' => 'Alert e Badge',
            'texto' => 'Mensagens fixas na página e etiquetas de status.',
            'exemplos' => [
                ['codigo' => <<<'BLADE'
<x-ginga::alert variant="success" title="Tudo certo">Cliente cadastrado com sucesso.</x-ginga::alert>
<x-ginga::alert variant="warning">Sua assinatura vence em 3 dias.</x-ginga::alert>
<x-ginga::alert variant="danger" dismissible>Não foi possível salvar.</x-ginga::alert>
<x-ginga::alert variant="info">Novidades na versão 2.</x-ginga::alert>
BLADE],
                ['codigo' => <<<'BLADE'
<x-ginga::badge variant="success" pill>Ativo</x-ginga::badge>
<x-ginga::badge variant="danger" subtle>Inativo</x-ginga::badge>
<x-ginga::badge variant="warning" subtle pill>Pendente</x-ginga::badge>
<x-ginga::badge>Novo</x-ginga::badge>
BLADE],
            ],
        ],
        [
            'id' => 'formulario', 'titulo' => 'Campos de formulário',
            'texto' => 'Label, ajuda, asterisco de obrigatório, erro e valor antigo automáticos.',
            'exemplos' => [
                ['codigo' => <<<'BLADE'
<x-ginga::input name="nome" label="Nome" help="Como aparece no cadastro." required />
<x-ginga::input name="email" type="email" label="E-mail" />
<x-ginga::input name="peso" label="Peso" type="number" suffix="kg" />
<x-ginga::select name="plano" label="Plano" placeholder="Selecione" :options="['basico' => 'Básico', 'pro' => 'Profissional']" />
<x-ginga::textarea name="observacoes" label="Observações" rows="3" />
BLADE],
                ['titulo' => 'Com erro de validação', 'erros' => ['nome' => 'O campo nome é obrigatório.', 'plano' => 'Escolha um plano.'], 'codigo' => <<<'BLADE'
<x-ginga::input name="nome" label="Nome" required />
<x-ginga::select name="plano" label="Plano" placeholder="Selecione" :options="['basico' => 'Básico', 'pro' => 'Profissional']" />
BLADE],
            ],
        ],
        [
            'id' => 'escolhas', 'titulo' => 'Escolhas',
            'texto' => 'Checkbox, switch, grupos de opções e o multiselect com busca.',
            'exemplos' => [
                ['codigo' => <<<'BLADE'
<x-ginga::checkbox name="termos">Li e aceito os <a href="#escolhas">termos de uso</a></x-ginga::checkbox>
<x-ginga::switch name="newsletter" label="Receber novidades por e-mail" checked />
BLADE],
                ['codigo' => <<<'BLADE'
<x-ginga::radio-group name="plano" label="Plano" required :options="['basico' => 'Básico', 'pro' => 'Profissional', 'empresa' => 'Empresa']" value="pro" />
<x-ginga::checkbox-group name="interesses" label="Interesses" inline :options="['danca' => 'Dança', 'musica' => 'Música', 'capoeira' => 'Capoeira']" :value="['danca']" />
BLADE],
                ['codigo' => <<<'BLADE'
<x-ginga::multiselect name="estados" label="Estados onde atende" title="Escolher estados" :options="\Ginga\Support\Brasil::ESTADOS" :value="['SP', 'RJ']" />
BLADE],
            ],
        ],
        [
            'id' => 'brasil', 'titulo' => 'Campos brasileiros',
            'texto' => 'Máscara, teclado certo no celular e validação no servidor. Digite para testar. O CEP 01310-100 preenche o endereço.',
            'exemplos' => [
                ['codigo' => <<<'BLADE'
<div class="row">
    <div class="col-md-6"><x-ginga::cpf name="cpf" required /></div>
    <div class="col-md-6"><x-ginga::cnpj name="cnpj" /></div>
    <div class="col-md-6"><x-ginga::cpf-cnpj name="documento" /></div>
    <div class="col-md-6"><x-ginga::telefone name="telefone" /></div>
    <div class="col-md-6"><x-ginga::dinheiro name="valor" /></div>
    <div class="col-md-6"><x-ginga::uf name="uf" /></div>
</div>
BLADE],
                ['titulo' => 'CEP com busca de endereço', 'codigo' => <<<'BLADE'
<div class="row">
    <div class="col-md-4"><x-ginga::cep name="cep" :preencher="['logradouro' => 'endereco', 'bairro' => 'bairro', 'localidade' => 'cidade', 'uf' => 'uf_cep']" focar="numero" /></div>
    <div class="col-md-8"><x-ginga::input name="endereco" label="Endereço" /></div>
    <div class="col-md-3"><x-ginga::input name="numero" label="Número" /></div>
    <div class="col-md-5"><x-ginga::input name="bairro" label="Bairro" /></div>
    <div class="col-md-4"><x-ginga::input name="cidade" label="Cidade" /></div>
    <div class="col-md-4"><x-ginga::uf name="uf_cep" formato="sigla" /></div>
</div>
BLADE],
                ['titulo' => 'Valores salvos sem máscara, exibidos com diretivas', 'codigo' => <<<'BLADE'
<ul class="list-unstyled mb-0">
    <li>@cpf('52998224725')</li>
    <li>@cnpj('12ABC34501DE35')</li>
    <li>@telefone('11987654321')</li>
    <li>@cep('01310100')</li>
    <li>@dinheiro(1234.5)</li>
</ul>
BLADE],
                ['titulo' => 'Com erro de validação', 'erros' => ['cpf' => 'O campo CPF deve ser um CPF válido.'], 'codigo' => '<x-ginga::cpf name="cpf" value="11111111111" />'],
            ],
        ],
        [
            'id' => 'listagens', 'titulo' => 'Tabela e paginação',
            'texto' => 'O datatable precisa de uma consulta no banco, então aqui aparecem a table e a pagination.',
            'exemplos' => [
                ['codigo' => <<<'BLADE'
<x-ginga::card title="Clientes" flush>
    <x-slot:actions><x-ginga::button size="sm">Novo cliente</x-ginga::button></x-slot:actions>
    <x-ginga::table :columns="['Nome', 'CPF', 'Situação', '']" caption="Clientes">
        <tr>
            <td>Maria Silva</td>
            <td>@cpf('52998224725')</td>
            <td><x-ginga::badge variant="success" subtle>Ativo</x-ginga::badge></td>
            <td class="text-end"><x-ginga::delete-button action="#" item="Maria Silva" /></td>
        </tr>
        <tr>
            <td>João Souza</td>
            <td>@cpf('11144477735')</td>
            <td><x-ginga::badge variant="danger" subtle>Inativo</x-ginga::badge></td>
            <td class="text-end"><x-ginga::delete-button action="#" item="João Souza" /></td>
        </tr>
    </x-ginga::table>
</x-ginga::card>
BLADE],
                ['codigo' => '<x-ginga::table :columns="[\'Nome\', \'E-mail\']" empty="Nenhum cliente cadastrado." />'],
                ['codigo' => '<x-ginga::pagination :paginator="$paginador" />', 'dados' => ['paginador' => $paginador]],
            ],
        ],
        [
            'id' => 'estrutura', 'titulo' => 'Card, breadcrumb e modal',
            'texto' => 'Estrutura da página.',
            'exemplos' => [
                ['codigo' => <<<'BLADE'
<x-ginga::breadcrumb :items="['Início' => '#', 'Clientes' => '#', 'Maria Silva']" />
<x-ginga::card title="Maria Silva">
    <x-slot:actions><x-ginga::button size="sm" variant="outline-primary">Editar</x-ginga::button></x-slot:actions>
    Conteúdo do card.
    <x-slot:footer>Atualizado hoje</x-slot:footer>
</x-ginga::card>
BLADE],
                ['codigo' => <<<'BLADE'
<x-ginga::button data-bs-toggle="modal" data-bs-target="#modal-exemplo">Abrir modal</x-ginga::button>
<x-ginga::modal id="modal-exemplo" title="Novo contato" centered>
    <x-ginga::input name="contato" label="Nome do contato" />
    <x-slot:footer>
        <x-ginga::button variant="link" data-bs-dismiss="modal">Cancelar</x-ginga::button>
        <x-ginga::button>Salvar</x-ginga::button>
    </x-slot:footer>
</x-ginga::modal>
BLADE],
            ],
        ],
    ];
@endphp
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Ginga · Galeria</title>

    <x-ginga::styles icons />

    <style>
        .galeria-nav a { display: block; padding: .3rem .75rem; border-radius: var(--bs-border-radius); color: var(--bs-body-color); text-decoration: none; }
        .galeria-nav a:hover { background: var(--ginga-rosa-claro); }
        @media (min-width: 992px) { .galeria-nav { position: sticky; top: 1.5rem; } }
        .swatch { height: 3rem; border-radius: var(--bs-border-radius); border: 1px solid var(--bs-border-color); }
        .exemplo-codigo { max-height: 18rem; overflow: auto; font-size: .8125rem; }
        section { scroll-margin-top: 1rem; }
    </style>
</head>
<body>
<div class="container py-4">

    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Ginga · Galeria</h1>
            <p class="mb-0 text-body-secondary">Os componentes renderizados de verdade, com o código de cada um. Só aparece em ambiente local.</p>
        </div>
        <button class="btn btn-outline-primary" type="button" id="alternar-tema">
            <i class="bi bi-moon-stars" aria-hidden="true"></i>
            <span>Tema escuro</span>
        </button>
    </header>

    <div class="row g-4">
        <nav class="col-lg-3" aria-label="Seções">
            <div class="galeria-nav">
                <a href="#paleta">Paleta</a>
                @foreach ($secoes as $secao)
                    <a href="#{{ $secao['id'] }}">{{ $secao['titulo'] }}</a>
                @endforeach
                <a href="#avisos">Toasts</a>
            </div>
        </nav>

        <main class="col-lg-9">
            <section id="paleta" class="mb-5">
                <h2 class="h4">Paleta</h2>
                <div class="row row-cols-3 row-cols-md-5 g-3">
                    @foreach ($cores as $cor)
                        <div class="col">
                            <div class="swatch" style="background: var(--ginga-{{ $cor }})"></div>
                            <small>{{ $cor }}</small>
                        </div>
                    @endforeach
                </div>
            </section>

            @foreach ($secoes as $secao)
                <section id="{{ $secao['id'] }}" class="mb-5">
                    <h2 class="h4">{{ $secao['titulo'] }}</h2>
                    <p class="text-body-secondary">{{ $secao['texto'] }}</p>

                    @foreach ($secao['exemplos'] as $exemplo)
                        @if (isset($exemplo['titulo']))
                            <h3 class="h6 mt-4">{{ $exemplo['titulo'] }}</h3>
                        @endif
                        <div class="card mb-3">
                            <div class="card-body">
                                {!! Blade::render($exemplo['codigo'], ($exemplo['dados'] ?? []) + ['errors' => $erros($exemplo['erros'] ?? [])]) !!}
                            </div>
                            <details class="border-top">
                                <summary class="px-3 py-2 small text-body-secondary">Ver código</summary>
                                <pre class="exemplo-codigo m-0 px-3 pb-3"><code>{{ $exemplo['codigo'] }}</code></pre>
                            </details>
                        </div>
                    @endforeach
                </section>
            @endforeach

            <section id="avisos" class="mb-5">
                <h2 class="h4">Toasts</h2>
                <p class="text-body-secondary">No projeto, use <code>redirect()->with('sucesso', '...')</code> com o <code>&lt;x-ginga::flash /&gt;</code>.</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach (['success' => 'Sucesso', 'danger' => 'Erro', 'warning' => 'Aviso', 'info' => 'Informação'] as $variante => $nome)
                        <x-ginga::button :variant="'outline-' . ($variante === 'info' ? 'primary' : $variante)" data-toast="{{ $variante }}">{{ $nome }}</x-ginga::button>
                    @endforeach
                </div>
                <div class="toast-container position-fixed p-3 bottom-0 end-0" id="toasts-galeria"></div>
            </section>
        </main>
    </div>
</div>

<x-ginga::scripts />
<script>
    const raiz = document.documentElement;
    const botaoTema = document.getElementById('alternar-tema');
    botaoTema.addEventListener('click', () => {
        const escuro = raiz.getAttribute('data-bs-theme') !== 'dark';
        raiz.setAttribute('data-bs-theme', escuro ? 'dark' : 'light');
        botaoTema.querySelector('span').textContent = escuro ? 'Tema claro' : 'Tema escuro';
        botaoTema.querySelector('i').className = escuro ? 'bi bi-sun' : 'bi bi-moon-stars';
    });

    const mensagens = { success: 'Cliente salvo.', danger: 'Não foi possível salvar.', warning: 'Assinatura vencendo.', info: 'Há novidades.' };
    document.querySelectorAll('[data-toast]').forEach((botao) => botao.addEventListener('click', () => {
        const variante = botao.dataset.toast;
        const toast = document.createElement('div');
        toast.className = `toast bg-${variante}-subtle text-${variante}-emphasis border-${variante}-subtle`;
        toast.setAttribute('role', variante === 'danger' ? 'alert' : 'status');
        toast.innerHTML = '<div class="d-flex align-items-start"><div class="toast-body flex-grow-1"></div>'
            + '<button type="button" class="btn-close flex-shrink-0 me-2 mt-2" data-bs-dismiss="toast" aria-label="Fechar"></button></div>';
        toast.querySelector('.toast-body').textContent = mensagens[variante];
        document.getElementById('toasts-galeria').append(toast);
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
        new bootstrap.Toast(toast, { delay: 5000 }).show();
    }));
</script>
</body>
</html>
