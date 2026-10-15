<div class="row">
    <div class="col-sm-6"><x-ginga::cpf name="cpf" required /></div>
    <div class="col-sm-6"><x-ginga::cnpj name="cnpj" help="Aceita letras: 12.ABC.345/01DE-35" /></div>
</div>
<x-ginga::cpf-cnpj name="documento" help="Muda de CPF para CNPJ sozinho a partir do 12º caractere." />
