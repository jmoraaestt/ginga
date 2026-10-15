<x-docs.layout titulo="Página não encontrada">
    <main id="conteudo" class="docs-heroi text-center" tabindex="-1">
        <div class="container-xxl py-5">
            <x-ginga::badge variant="primary" subtle pill class="mb-3">Erro 404</x-ginga::badge>
            <h1>Essa página <em>saiu dançando.</em></h1>
            <p class="docs-heroi-lead mx-auto">O endereço pode ter mudado. Use a busca ou volte para o começo.</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <x-ginga::button :href="route('docs.inicio')" size="lg">Ir para o início</x-ginga::button>
                <x-ginga::button variant="outline-secondary" size="lg" data-bs-toggle="modal" data-bs-target="#docs-busca">
                    <x-slot:iconLeft><i class="bi bi-search me-1" aria-hidden="true"></i></x-slot:iconLeft>
                    Buscar
                </x-ginga::button>
            </div>
        </div>
    </main>
</x-docs.layout>
