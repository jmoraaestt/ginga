<x-ginga::button variant="outline-primary" data-bs-toggle="modal" data-bs-target="#editar-endereco">
    Editar endereço
</x-ginga::button>

<x-ginga::modal id="editar-endereco" title="Editar endereço" size="lg" static>
    <p class="text-body-secondary">Clicar fora ou apertar Esc não fecha este modal: só os botões.</p>
    <x-ginga::textarea name="endereco_observacoes" label="Observações para entrega" rows="4" />

    <x-slot:footer>
        <x-ginga::button variant="link" data-bs-dismiss="modal">Descartar</x-ginga::button>
        <x-ginga::button data-bs-dismiss="modal">Salvar</x-ginga::button>
    </x-slot:footer>
</x-ginga::modal>
