{{--
    Página da documentação: menu lateral, cabeçalho, conteúdo, "Nesta página" e anterior/próxima.
    O slot "etiquetas" vai ao lado dos nomes dos componentes (ex.: "Usa o JavaScript do Bootstrap")
--}}
@props(['pagina'])

@use('App\Docs\Indice')
@use('App\Docs\Navegacao')

@php
    // O conteúdo ($slot) já foi renderizado aqui: as seções registradas pelo <x-docs.secao> estão prontas
    $secoes = Indice::consumir();
    [$anterior, $proxima] = Navegacao::vizinhas($pagina->slug);
    $primeiraDoGrupo = Navegacao::grupos()[$pagina->grupo]->first();
@endphp

<x-docs.layout :titulo="$pagina->titulo" :descricao="$pagina->descricao" menu>
    <div class="container-xxl docs-grade">
        <aside class="docs-lateral offcanvas-lg offcanvas-start" id="docs-menu" tabindex="-1" aria-labelledby="docs-menu-titulo">
            <div class="offcanvas-header">
                <a class="docs-marca" href="{{ route('docs.inicio') }}" id="docs-menu-titulo"><x-docs.logo /> <span>Ginga</span></a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#docs-menu" aria-label="Fechar menu"></button>
            </div>
            <nav class="offcanvas-body" aria-label="Documentação">
                <div class="docs-lateral-conteudo">
                @foreach (Navegacao::grupos() as $grupo => $paginas)
                    <h2 class="docs-lateral-grupo">{{ $grupo }}</h2>
                    <ul>
                    @foreach ($paginas as $item)
                        <li>
                            <a href="{{ $item->url() }}" @if ($item->slug === $pagina->slug) class="active" aria-current="page" @endif>
                                <i class="bi {{ $item->icone }}" aria-hidden="true"></i>{{ $item->titulo }}
                            </a>
                        </li>
                    @endforeach
                    </ul>
                @endforeach
                </div>
            </nav>
        </aside>

        <main id="conteudo" class="docs-conteudo" tabindex="-1">
            <x-ginga::breadcrumb class="docs-trilha" :items="[
                'Início' => route('docs.inicio'),
                $pagina->grupo => $primeiraDoGrupo->url(),
                $pagina->titulo,
            ]" />

            <header class="docs-cabecalho">
                <h1>{{ $pagina->titulo }}</h1>
                <p class="docs-lead">{{ $pagina->descricao }}</p>
            @if ($pagina->componentes || isset($etiquetas))
                <div class="docs-etiquetas">
                @foreach ($pagina->componentes as $componente)
                    @if (preg_match('/^[a-z-]+$/', $componente))
                        <code>&lt;x-ginga::{{ $componente }}&gt;</code>
                    @endif
                @endforeach
                    {{ $etiquetas ?? '' }}
                </div>
            @endif
            </header>

            <div class="docs-prosa">
                {{ $slot }}
            </div>

            <footer class="docs-rodape-pagina">
                <a class="docs-editar" href="{{ $pagina->urlEdicao() }}" target="_blank" rel="noopener">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i> Editar esta página no GitHub
                </a>

                <nav class="docs-vizinhas" aria-label="Páginas vizinhas">
                @if ($anterior)
                    <a href="{{ $anterior->url() }}" rel="prev">
                        <span>Anterior</span>
                        <strong><i class="bi bi-arrow-left" aria-hidden="true"></i> {{ $anterior->titulo }}</strong>
                    </a>
                @endif
                @if ($proxima)
                    <a href="{{ $proxima->url() }}" rel="next" class="docs-vizinhas-proxima">
                        <span>Próxima</span>
                        <strong>{{ $proxima->titulo }} <i class="bi bi-arrow-right" aria-hidden="true"></i></strong>
                    </a>
                @endif
                </nav>
            </footer>
        </main>

        @if ($secoes)
            <aside class="docs-indice" aria-labelledby="docs-indice-titulo">
                <h2 id="docs-indice-titulo">Nesta página</h2>
                <ul>
                @foreach ($secoes as $secao)
                    <li @class(['docs-indice-sub' => $secao['nivel'] > 2])>
                        <a href="#{{ $secao['id'] }}" data-docs-indice-link>{{ $secao['titulo'] }}</a>
                    </li>
                @endforeach
                </ul>
                <a href="#conteudo" class="docs-topo-link"><i class="bi bi-arrow-up-short" aria-hidden="true"></i> Voltar ao topo</a>
            </aside>
        @endif
    </div>

    <x-docs.rodape />
</x-docs.layout>
