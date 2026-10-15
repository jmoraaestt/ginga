<x-ginga::button data-bs-toggle="modal" data-bs-target="#novo-contato">
    Novo contato
</x-ginga::button>

<x-ginga::modal id="novo-contato" title="Novo contato" centered>
    <form id="form-contato" method="POST" action="#">
        <x-ginga::input name="contato_nome" label="Nome" required />
        <x-ginga::telefone name="contato_telefone" />
    </form>

    <x-slot:footer>
        <x-ginga::button variant="link" data-bs-dismiss="modal">Cancelar</x-ginga::button>
        <x-ginga::button type="submit" form="form-contato">Salvar</x-ginga::button>
    </x-slot:footer>
</x-ginga::modal>
