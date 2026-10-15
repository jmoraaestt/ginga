{{--
    Casca de todas as páginas: <head>, barra do topo, busca (Ctrl+K) e os toasts usados pelos exemplos.
    menu: mostra o botão que abre o menu lateral no celular (só nas páginas da documentação)
--}}
@props([
    'titulo' => null,
    'descricao' => 'Componentes Blade com Bootstrap 5 para aplicações Laravel brasileiras: CPF, CNPJ, CEP, telefone, dinheiro, datatable e mais.',
    'menu' => false,
])

@use('App\Docs\Navegacao')

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ? $titulo . ' · Ginga' : 'Ginga · Componentes Blade para aplicações brasileiras' }}</title>
    <meta name="description" content="{{ $descricao }}">
    <meta property="og:title" content="{{ $titulo ?? 'Ginga' }}">
    <meta property="og:description" content="{{ $descricao }}">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#C9306F">
    <link rel="icon" href="{{ asset('docs/favicon.svg') }}" type="image/svg+xml">

    {{-- Antes de qualquer CSS: aplica o tema salvo e evita o "piscar" do claro para o escuro --}}
    <script>
        (() => {
            let tema = null;
            try { tema = localStorage.getItem('docs-tema'); } catch (erro) {}
            document.documentElement.dataset.bsTheme = tema ?? (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap">

    <x-ginga::styles icons />
    <link rel="stylesheet" href="{{ asset('docs/docs.css') }}?v={{ filemtime(public_path('docs/docs.css')) }}">
</head>
<body @if (config('docs.estatico')) data-docs-estatico @endif>
    <a href="#conteudo" class="visually-hidden-focusable docs-pular">Pular para o conteúdo</a>

    <header class="docs-topo">
        <nav class="container-xxl docs-topo-barra" aria-label="Principal">
        @if ($menu)
            <button class="btn docs-icone-botao d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#docs-menu" aria-controls="docs-menu" aria-label="Abrir menu">
                <i class="bi bi-list fs-5" aria-hidden="true"></i>
            </button>
        @endif

            <a class="docs-marca" href="{{ route('docs.inicio') }}">
                <x-docs.logo />
                <span>Ginga</span>
            </a>

            <ul class="docs-topo-links d-none d-md-flex">
                <li><a href="{{ route('docs.pagina', 'instalacao') }}" @class(['active' => $menu])>Documentação</a></li>
                <li><a href="{{ route('docs.inicio') }}#componentes">Componentes</a></li>
                <li><a href="{{ route('docs.pagina', 'exemplo') }}">Exemplo</a></li>
            </ul>

            <button type="button" class="docs-busca-botao ms-auto" data-bs-toggle="modal" data-bs-target="#docs-busca" aria-keyshortcuts="Control+K" aria-label="Buscar na documentação">
                <i class="bi bi-search" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">Buscar</span>
                <kbd class="d-none d-sm-inline" aria-hidden="true">Ctrl K</kbd>
            </button>

            <button type="button" class="btn docs-icone-botao" data-docs-tema aria-label="Usar tema escuro">
                <i class="bi bi-moon-stars" aria-hidden="true"></i>
            </button>

            <a class="btn docs-icone-botao" href="{{ config('docs.repositorio') }}" target="_blank" rel="noopener" aria-label="Ginga no GitHub (abre em nova aba)">
                <i class="bi bi-github fs-5" aria-hidden="true"></i>
            </a>
        </nav>
    </header>

    {{ $slot }}

    {{-- Busca: o índice vai embutido para funcionar também no site estático --}}
    <x-ginga::modal id="docs-busca" size="lg" scrollable class="docs-busca" aria-label="Buscar na documentação">
        <div class="docs-busca-campo">
            <i class="bi bi-search" aria-hidden="true"></i>
            <input type="search" class="form-control" placeholder="Buscar componente ou página..." aria-label="Buscar componente ou página"
                   role="combobox" aria-expanded="true" aria-controls="docs-busca-resultados" aria-autocomplete="list" autocomplete="off" data-docs-busca-campo>
            <kbd aria-hidden="true">Esc</kbd>
        </div>
        <ul class="docs-busca-resultados" id="docs-busca-resultados" role="listbox" aria-label="Resultados"></ul>
        <p class="docs-busca-vazio" hidden data-docs-busca-vazio>Nada encontrado. Tente “cpf”, “tabela” ou “modal”.</p>

        <x-slot:footer>
            <span><kbd>↑</kbd> <kbd>↓</kbd> navegar</span>
            <span><kbd>Enter</kbd> abrir</span>
            <span><kbd>Esc</kbd> fechar</span>
        </x-slot:footer>
    </x-ginga::modal>
    <script type="application/json" id="docs-indice-busca">@json(Navegacao::indiceBusca())</script>

    {{-- Modelos dos toasts disparados pelos exemplos (envio de formulário, exclusão, botões de demonstração) --}}
    @foreach (['success' => 'bi-check-circle', 'danger' => 'bi-x-circle', 'warning' => 'bi-exclamation-triangle', 'primary' => 'bi-info-circle'] as $variante => $icone)
        <template data-docs-toast="{{ $variante }}">
            <x-ginga::toast :variant="$variante">
                <x-slot:icon><i class="bi {{ $icone }}" aria-hidden="true"></i></x-slot:icon>
                <span data-docs-toast-texto></span>
            </x-ginga::toast>
        </template>
    @endforeach
    <x-ginga::toast-container id="docs-toasts" />

    <x-ginga::scripts />
    <script src="{{ asset('docs/docs.js') }}?v={{ filemtime(public_path('docs/docs.js')) }}"></script>
</body>
</html>
