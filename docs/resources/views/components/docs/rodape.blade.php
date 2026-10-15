<footer class="docs-rodape">
    <div class="container-xxl">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <a class="docs-marca" href="{{ route('docs.inicio') }}"><x-docs.logo :tamanho="24" /> <span>Ginga</span></a>
            <p class="mb-0">
                Feito com os próprios componentes do Ginga.
                Licença <a href="{{ config('docs.repositorio') }}/blob/main/LICENSE">MIT</a>
                · <a href="{{ config('docs.repositorio') }}">GitHub</a>
            </p>
        </div>
    </div>
</footer>
