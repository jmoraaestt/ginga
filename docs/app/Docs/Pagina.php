<?php

namespace App\Docs;

class Pagina
{
    public function __construct(
        public readonly string $slug,
        public readonly string $grupo,
        public readonly string $titulo,
        public readonly string $descricao,
        public readonly string $icone,
        /** @var string[] */
        public readonly array $componentes = [],
    ) {
    }

    public function url(): string
    {
        return route('docs.pagina', $this->slug);
    }

    public function view(): string
    {
        return 'docs.paginas.' . $this->slug;
    }

    /**
     * Link para editar a página no GitHub.
     */
    public function urlEdicao(): string
    {
        return config('docs.repositorio') . '/edit/main/docs/resources/views/docs/paginas/' . $this->slug . '.blade.php';
    }
}
