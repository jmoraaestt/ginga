@use('App\Docs\Navegacao')
@use('App\Docs\Exemplos')

@php
    // Estado de erro da vitrine "Do Blade ao HTML": mostra o que o componente faz sozinho depois de um envio inválido
    Exemplos::erros(
        ['vitrine_cpf' => 'O campo CPF deve ser um CPF válido.'],
        ['vitrine_cpf' => '123.456.789-00', 'vitrine_nome' => 'Maria Silva'],
    );
@endphp

<x-docs.layout>
    <main id="conteudo" tabindex="-1">
        {{-- Herói --}}
        <section class="docs-heroi">
            <div class="container-xxl">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <h1>Formulários brasileiros, <em>prontos no Blade.</em></h1>

                        <p class="docs-heroi-lead">
                            CPF, CNPJ, CEP, telefone e dinheiro com máscara e validação. Datatable com busca no banco.
                            Mensagens, modais e confirmações acessíveis.
                        </p>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <x-ginga::button :href="route('docs.pagina', 'instalacao')" size="lg">
                                Começar
                                <x-slot:iconRight><i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></x-slot:iconRight>
                            </x-ginga::button>
                            <x-ginga::button href="#componentes" variant="outline-secondary" size="lg">Ver componentes</x-ginga::button>
                        </div>

                        <div class="docs-instalar">
                            <code id="docs-comando-instalar"><span>$ </span>composer require {{ config('docs.pacote') }}</code>
                            <button type="button" class="docs-copiar" data-docs-copiar="#docs-comando-instalar" aria-label="Copiar comando de instalação">
                                <i class="bi bi-clipboard" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="docs-vitrine" data-docs-demo>
                            <div class="docs-vitrine-barra" aria-hidden="true">
                                <i></i><i></i><i></i>
                                <span>clientes/create.blade.php</span>
                            </div>
                            <form class="docs-vitrine-corpo" novalidate>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm"><x-ginga::cpf name="heroi_cpf" id="heroi-cpf" required /></div>
                                    <div class="col-sm"><x-ginga::telefone name="heroi_celular" id="heroi-celular" label="Celular" /></div>
                                </div>
                                <div class="row g-0 column-gap-3">
                                    <div class="col-sm-5">
                                        <x-ginga::cep name="heroi_cep" id="heroi-cep" help="Tente 01310-100"
                                            :preencher="['localidade' => 'heroi-cidade', 'uf' => 'heroi-uf']" />
                                    </div>
                                    <div class="col-sm"><x-ginga::input name="heroi_cidade" id="heroi-cidade" label="Cidade" /></div>
                                    <div class="col-sm-3"><x-ginga::uf name="heroi_uf" id="heroi-uf" label="UF" formato="sigla" placeholder="—" /></div>
                                </div>
                                <x-ginga::dinheiro name="heroi_mensalidade" id="heroi-mensalidade" label="Mensalidade" />
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <x-ginga::switch name="heroi_ativo" id="heroi-ativo" checked class="mb-0">Cliente ativo</x-ginga::switch>
                                    <x-ginga::button type="submit">
                                        <x-slot:iconLeft><i class="bi bi-check2 me-1" aria-hidden="true"></i></x-slot:iconLeft>
                                        Salvar cliente
                                    </x-ginga::button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Recursos --}}
        <section class="docs-secao" aria-labelledby="recursos-titulo">
            <div class="container-xxl">
                <div class="docs-secao-cabecalho">
                    <p class="docs-sobretitulo">Por que Ginga</p>
                    <h2 id="recursos-titulo">O trabalho repetitivo de todo formulário, já resolvido.</h2>
                    <p>Label, erro de validação, valor antigo, máscara, teclado do celular e atributos de acessibilidade: cada componente cuida disso para você escrever só o que importa.</p>
                </div>

                <div class="row g-3 g-lg-4">
                @foreach ([
                    ['bi-person-vcard', 'primary', 'Campos brasileiros', 'CPF, CNPJ (inclusive o alfanumérico), CEP com busca no ViaCEP, telefone fixo e celular, dinheiro e UF.'],
                    ['bi-shield-check', 'success', 'Validação no servidor', 'Regras cpf, cnpj, cpf_cnpj, cep, telefone e uf, com mensagens em português e valores com ou sem máscara.'],
                    ['bi-ui-checks-grid', 'warning', 'Formulários sem repetição', 'O erro aparece embaixo do campo certo, o valor digitado volta depois do erro e o id é gerado sozinho.'],
                    ['bi-table', 'primary', 'Datatable no banco', 'Busca, ordenação e paginação feitas na consulta, sem recarregar a página e com a URL sempre atualizada.'],
                    ['bi-universal-access', 'success', 'Acessível por padrão', 'aria-invalid, aria-describedby, fieldset com legend, foco devolvido ao fechar modais e anúncios para leitores de tela.'],
                    ['bi-lightning-charge', 'warning', 'Sem build', 'Funciona só com Blade e o CDN do Bootstrap. Com Vite também. Os scripts são pequenos e sem dependências.'],
                ] as [$icone, $cor, $titulo, $texto])
                    <div class="col-sm-6 col-lg-4">
                        <div class="docs-recurso">
                            <div class="docs-recurso-icone bg-{{ $cor }}-subtle text-{{ $cor }}-emphasis"><i class="bi {{ $icone }}" aria-hidden="true"></i></div>
                            <h3>{{ $titulo }}</h3>
                            <p>{{ $texto }}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </section>

        {{-- Do Blade ao HTML --}}
        <section class="docs-secao" aria-labelledby="blade-titulo">
            <div class="container-xxl">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-5">
                        <div class="docs-secao-cabecalho mb-4">
                            <p class="docs-sobretitulo">Menos código</p>
                            <h2 id="blade-titulo">Uma linha por campo. O resto é com o Ginga.</h2>
                            <p>Ao lado, o formulário depois de um envio com CPF inválido. Nenhuma linha de <code>@@error</code>, <code>old()</code> ou <code>is-invalid</code> foi escrita: o componente lê o <code>$errors</code> e o valor antigo da sessão.</p>
                        </div>
                        <x-docs.codigo lang="blade" titulo="clientes/create.blade.php">
                            @verbatim
                            <x-ginga::input name="nome" label="Nome" required />
                            <x-ginga::cpf name="cpf" required />
                            <x-ginga::button type="submit">Salvar</x-ginga::button>
                            @endverbatim
                        </x-docs.codigo>
                        <x-docs.codigo lang="php" titulo="ClienteController.php">
                            $request->validate(['nome' => 'required', 'cpf' => 'required|cpf']);
                        </x-docs.codigo>
                    </div>
                    <div class="col-lg-7">
                        <x-ginga::card title="Novo cliente" class="docs-vitrine" data-docs-demo>
                            <x-slot:actions>
                                <x-ginga::badge variant="danger" subtle pill>1 erro</x-ginga::badge>
                            </x-slot:actions>
                            <form novalidate>
                                <x-ginga::input name="vitrine_nome" label="Nome" required />
                                <x-ginga::cpf name="vitrine_cpf" required />
                                <x-ginga::button type="submit">Salvar</x-ginga::button>
                            </form>
                        </x-ginga::card>
                    </div>
                </div>
            </div>
        </section>

        {{-- Catálogo --}}
        <section class="docs-secao" id="componentes" aria-labelledby="componentes-titulo">
            <div class="container-xxl">
                <div class="docs-secao-cabecalho">
                    <p class="docs-sobretitulo">Componentes</p>
                    <h2 id="componentes-titulo">Tudo o que uma tela de cadastro precisa.</h2>
                    <p>Cada página traz exemplos rodando de verdade, o código que gerou cada um e a tabela de props.</p>
                </div>

            @foreach (Navegacao::componentes() as $grupo => $paginas)
                <h3 class="docs-catalogo-grupo">{{ $grupo }}</h3>
                <div class="row g-3">
                @foreach ($paginas as $pagina)
                    <div class="col-sm-6 col-lg-4">
                        <a class="docs-catalogo-item" href="{{ $pagina->url() }}">
                            <i class="bi {{ $pagina->icone }}" aria-hidden="true"></i>
                            <div>
                                <strong>{{ $pagina->titulo }}</strong>
                                <span>{{ $pagina->descricao }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
                </div>
            @endforeach
            </div>
        </section>

        {{-- Chamada final --}}
        <section class="docs-secao pt-0">
            <div class="container-xxl">
                <div class="docs-cta">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-7">
                            <h2>Comece em dois minutos.</h2>
                            <p class="mb-0">Instale pelo Composer, coloque três componentes no layout e pronto: sem publicar arquivos, sem configurar build.</p>
                        </div>
                        <div class="col-lg-5 d-flex flex-wrap gap-2 justify-content-lg-end">
                            <x-ginga::button :href="route('docs.pagina', 'instalacao')" size="lg">Ler a documentação</x-ginga::button>
                            <x-ginga::button :href="route('docs.pagina', 'exemplo')" variant="light" size="lg">Ver exemplo completo</x-ginga::button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-docs.rodape />
</x-docs.layout>
