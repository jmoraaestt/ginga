{{-- oculto --}}
@php
    $clientes = \App\Docs\Exemplos::tabelaClientes();
    $total = \App\Docs\Exemplos::clientes()->count();
    $ativos = \App\Docs\Exemplos::clientes()->where('ativo', true)->count();
    $receita = \App\Docs\Exemplos::clientes()->where('ativo', true)->sum('mensalidade');
@endphp
{{-- /oculto --}}
<x-ginga::breadcrumb :items="['Início' => '/', 'Clientes']" />

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="h4 fw-bold mb-1">Clientes</h3>
        <p class="text-body-secondary mb-0">Cadastre, consulte e exclua clientes.</p>
    </div>
    <x-ginga::button data-bs-toggle="modal" data-bs-target="#novo-cliente">
        <x-slot:iconLeft><i class="bi bi-plus-lg me-1" aria-hidden="true"></i></x-slot:iconLeft>
        Novo cliente
    </x-ginga::button>
</div>

<x-ginga::alert variant="warning" role="status" dismissible>
    <x-slot:icon><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></x-slot:icon>
    3 clientes estão com a mensalidade atrasada. <a href="/clientes?situacao=atrasados" class="alert-link">Ver quem são</a>
</x-ginga::alert>

<div class="row g-3 mb-4">
    @foreach ([
        ['Clientes', $total, 'bi-people', 'primary'],
        ['Ativos', $ativos, 'bi-check-circle', 'success'],
        ['Receita mensal', \Ginga\Support\Mascara::exibir('dinheiro', $receita), 'bi-cash-stack', 'warning'],
    ] as [$rotulo, $valor, $icone, $cor])
        <div class="col-sm-4">
            <x-ginga::card class="h-100">
                <div class="d-flex align-items-center gap-3">
                    <span class="rounded-3 p-2 lh-1 bg-{{ $cor }}-subtle text-{{ $cor }}-emphasis">
                        <i class="bi {{ $icone }} fs-5" aria-hidden="true"></i>
                    </span>
                    <div>
                        <div class="small text-body-secondary">{{ $rotulo }}</div>
                        <div class="fs-5 fw-bold text-nowrap">{{ $valor }}</div>
                    </div>
                </div>
            </x-ginga::card>
        </div>
    @endforeach
</div>

<x-ginga::card title="Lista de clientes" :level="3">
    <x-ginga::datatable
        :tabela="$clientes"
        placeholder="Buscar por nome, CPF ou cidade"
        caption="Clientes"
        :columns="[
            'nome' => 'Nome',
            'cpf' => 'CPF',
            'mensalidade' => ['label' => 'Mensalidade', 'class' => 'text-end'],
            'acoes' => ['label' => 'Ações', 'hidden' => true],
        ]"
    >
        @foreach ($clientes as $cliente)
            <tr>
                <td>
                    {{ $cliente->nome }}
                    <x-ginga::badge :variant="$cliente->ativo ? 'success' : 'secondary'" subtle pill>
                        {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                    </x-ginga::badge>
                </td>
                <td class="text-nowrap">@cpf($cliente->cpf)</td>
                <td class="text-end text-nowrap">@dinheiro($cliente->mensalidade)</td>
                <td class="text-end">
                    <x-ginga::delete-button action="/clientes/{{ $cliente->id }}" :item="$cliente->nome" />
                </td>
            </tr>
        @endforeach
    </x-ginga::datatable>
</x-ginga::card>

<x-ginga::modal id="novo-cliente" title="Novo cliente" size="lg" static>
    <form id="form-novo-cliente" method="POST" action="/clientes">
        <div class="row">
            <div class="col-md-8"><x-ginga::input name="nome" label="Nome" required /></div>
            <div class="col-md-4"><x-ginga::cpf name="cpf" required /></div>
            <div class="col-md-6"><x-ginga::telefone name="celular" label="Celular" /></div>
            <div class="col-md-6"><x-ginga::dinheiro name="mensalidade" label="Mensalidade" /></div>
            <div class="col-md-4">
                <x-ginga::cep name="cep" :preencher="['localidade' => 'cidade', 'uf' => 'uf']" />
            </div>
            <div class="col-md-5"><x-ginga::input name="cidade" label="Cidade" /></div>
            <div class="col-md-3"><x-ginga::uf name="uf" label="UF" formato="sigla" /></div>
        </div>
        <x-ginga::switch name="ativo" label="Cliente ativo" checked />
    </form>

    <x-slot:footer>
        <x-ginga::button variant="link" data-bs-dismiss="modal">Cancelar</x-ginga::button>
        <x-ginga::button type="submit" form="form-novo-cliente">Salvar cliente</x-ginga::button>
    </x-slot:footer>
</x-ginga::modal>
