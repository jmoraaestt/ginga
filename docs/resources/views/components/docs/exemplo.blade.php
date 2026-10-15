{{--
    Exemplo vivo: renderiza resources/views/exemplos/{arquivo}.blade.php e mostra o código do mesmo arquivo.
    O código exibido é sempre o que roda: os dois nunca ficam diferentes.
    centro: centraliza o conteúdo do preview (botões, badges)
--}}
@props([
    'arquivo',
    'centro' => false,
])

@use('App\Docs\Codigo')

@php
    $view = 'exemplos.' . $arquivo;

    // Na prévia, links para rotas da aplicação de exemplo (/clientes/create...) viram "#":
    // essas rotas não existem aqui, e o exportador estático seguiria o link até um 404.
    // Só href que começa com uma barra: "#ancora", "http(s)://" e "//cdn..." ficam como estão.
    // action de <form> e data-* (delete-button) não são href e não mudam. O código mostrado vem do arquivo, intacto
    $previa = preg_replace('/(\shref=)(?:"\/(?!\/)[^"]*"|\'\/(?!\/)[^\']*\')/i', '$1"#"', view($view)->render());
@endphp

<figure {{ $attributes->merge(['class' => 'docs-exemplo']) }}>
    <div @class(['docs-exemplo-preview', 'docs-exemplo-centro' => $centro])>
        {!! $previa !!}
    </div>
    <x-docs.codigo :codigo="Codigo::deArquivo(view()->getFinder()->find($view))" class="docs-exemplo-codigo" />
</figure>
