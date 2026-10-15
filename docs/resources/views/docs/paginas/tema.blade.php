@php
    $cores = [
        'Principal' => [
            ['--ginga-rosa', 'Rosa', 'Botões, links e foco'],
            ['--ginga-rosa-hover', 'Rosa hover', 'Botão sob o mouse'],
            ['--ginga-rosa-vivo', 'Rosa vivo', 'Identidade: logo e capa'],
            ['--ginga-rosa-claro', 'Rosa claro', 'Fundo suave'],
        ],
        'Estados' => [
            ['--ginga-verde-agua', 'Verde-água', 'Sucesso'],
            ['--ginga-ambar', 'Âmbar', 'Aviso'],
            ['--ginga-perigo', 'Perigo', 'Erro e exclusão'],
            ['--ginga-verde-agua-claro', 'Verde-água claro', 'Fundo de sucesso'],
            ['--ginga-ambar-claro', 'Âmbar claro', 'Fundo de aviso'],
            ['--ginga-perigo-claro', 'Perigo claro', 'Fundo de erro'],
        ],
        'Neutros' => [
            ['--ginga-tinta', 'Tinta', 'Texto'],
            ['--ginga-tinta-suave', 'Tinta suave', 'Texto secundário'],
            ['--ginga-borda', 'Borda', 'Bordas e divisórias'],
            ['--ginga-superficie', 'Superfície', 'Fundo da página'],
            ['--ginga-superficie-alta', 'Superfície alta', 'Cards e modais'],
        ],
    ];
@endphp

<x-docs.pagina :pagina="$pagina">
    <p>O tema é um CSS pequeno, carregado depois do Bootstrap pelo <code>&lt;x-ginga::styles /&gt;</code>. Ele troca as cores do Bootstrap pelas do Ginga e define variáveis que você pode usar nas suas próprias telas. Use o botão de lua no topo para ver a paleta no modo escuro.</p>

    <x-docs.secao titulo="Paleta" />

    @foreach ($cores as $grupo => $lista)
        <x-docs.secao :titulo="$grupo" :id="'paleta-' . Str::slug($grupo)" nivel="3" />
        <div class="docs-paleta">
        @foreach ($lista as [$variavel, $nome, $uso])
            <div class="docs-cor">
                <div class="docs-cor-amostra" style="background: var({{ $variavel }})"></div>
                <div class="docs-cor-texto">
                    <strong>{{ $nome }}</strong>
                    <span class="text-body-secondary">{{ $uso }}</span><br>
                    <code>{{ $variavel }}</code>
                </div>
            </div>
        @endforeach
        </div>
    @endforeach

    <x-docs.secao titulo="Variáveis" />

    <x-docs.props :colunas="['Variável', 'Uso']" legenda="Variáveis do tema" :itens="[
        ['--ginga-rosa, -hover, -active', 'Cor principal (`btn-primary`, links, foco). Os três estados do botão.'],
        ['--ginga-rosa-vivo', 'Identidade visual: logo, capa. Não use atrás de texto pequeno.'],
        ['--ginga-rosa-claro', 'Fundo suave da cor principal.'],
        ['--ginga-verde-agua, -claro, -borda, -texto', 'Sucesso.'],
        ['--ginga-ambar, -claro, -borda, -texto', 'Aviso.'],
        ['--ginga-perigo, -claro, -borda, -texto', 'Erro e exclusão.'],
        ['--ginga-tinta, --ginga-tinta-suave', 'Texto e texto secundário.'],
        ['--ginga-borda, --ginga-superficie, --ginga-superficie-alta', 'Bordas e fundos.'],
    ]" />

    <p>Cada cor tem uma versão <code>-rgb</code> (<code>--ginga-rosa-rgb: 201, 48, 111</code>) para usar com transparência: <code>rgba(var(--ginga-rosa-rgb), .2)</code>.</p>

    <x-docs.secao titulo="Modo escuro" />

    <p>Coloque <code>data-bs-theme="dark"</code> no <code>&lt;html&gt;</code> e todas as variáveis mudam sozinhas, com o contraste ajustado para o fundo escuro.</p>

    <x-docs.codigo lang="html">
        <html lang="pt-BR" data-bs-theme="dark">
    </x-docs.codigo>

    <x-docs.secao titulo="Trocar a cor principal" />

    <p>Sobrescreva as variáveis em um CSS seu, carregado depois do tema. Escolha uma cor abaixo para ver a documentação inteira com ela:</p>

    <div class="docs-exemplo">
        <div class="docs-exemplo-preview">
            <div class="row g-4 align-items-center">
                <div class="col-sm-auto">
                    <label class="form-label" for="docs-cor">Cor principal</label>
                    <div class="d-flex gap-2">
                        <input type="color" class="form-control form-control-color" id="docs-cor" value="#C9306F" data-docs-cor>
                        <x-ginga::button variant="outline-secondary" data-docs-cor-restaurar>Restaurar</x-ginga::button>
                    </div>
                </div>
                <div class="col-sm d-flex flex-wrap gap-2 align-items-center">
                    <x-ginga::button>Primário</x-ginga::button>
                    <x-ginga::button variant="outline-primary">Contorno</x-ginga::button>
                    <x-ginga::badge pill>Badge</x-ginga::badge>
                    <x-ginga::badge subtle pill>Suave</x-ginga::badge>
                    <x-ginga::switch name="docs_cor_switch" id="docs-cor-switch" checked class="mb-0">Switch</x-ginga::switch>
                </div>
            </div>
        </div>
        <div class="docs-codigo docs-exemplo-codigo">
            <div class="docs-codigo-barra">
                <span>resources/css/app.css</span>
                <button type="button" class="docs-copiar" data-docs-copiar><i class="bi bi-clipboard" aria-hidden="true"></i><span>Copiar</span></button>
            </div>
            <pre tabindex="0"><code><span class="hl-keyword">:root</span> {
<span data-docs-cor-valor>--ginga-rosa: #C9306F;
--ginga-rosa-rgb: 201, 48, 111;</span>
}</code></pre>
        </div>
    </div>

    <x-docs.nota tipo="aviso">
        Troque também <code>--ginga-rosa-hover</code> e <code>--ginga-rosa-active</code> por tons mais escuros da sua cor, e confira o contraste do texto branco sobre ela (mínimo de 4,5:1).
    </x-docs.nota>

    <x-docs.secao titulo="Servir como arquivo estático" />

    <p>Por padrão o tema vem da rota <code>/_ginga/tema.css</code>, com a data do arquivo na URL para o navegador baixar de novo a cada atualização. Para servir do <code>public/</code> (num CDN, por exemplo):</p>

    <x-docs.codigo lang="bash">php artisan vendor:publish --tag=ginga-assets</x-docs.codigo>

    <x-docs.codigo>
        @verbatim
        <link rel="stylesheet" href="{{ asset('vendor/ginga/ginga-theme.css') }}">
        @endverbatim
    </x-docs.codigo>

    <p>Depois de publicar, o arquivo não se atualiza mais com o pacote: rode o comando de novo com <code>--force</code> a cada atualização.</p>
</x-docs.pagina>
