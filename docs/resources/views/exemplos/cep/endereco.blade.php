<div class="row">
    <div class="col-sm-4">
        <x-ginga::cep
            name="cep"
            :preencher="['logradouro' => 'endereco', 'bairro' => 'bairro', 'localidade' => 'cidade', 'uf' => 'uf']"
            focar="numero"
            help="Tente 01310-100"
        />
    </div>
    <div class="col-sm-8"><x-ginga::input name="endereco" label="Endereço" /></div>
    <div class="col-sm-3"><x-ginga::input name="numero" label="Número" /></div>
    <div class="col-sm-4"><x-ginga::input name="bairro" label="Bairro" /></div>
    <div class="col-sm-5"><x-ginga::input name="cidade" label="Cidade" /></div>
    <div class="col-sm-6"><x-ginga::uf name="uf" /></div>
</div>
