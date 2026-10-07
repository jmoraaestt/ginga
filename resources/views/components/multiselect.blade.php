{{--
    Campo que abre um modal para escolher várias opções. A escolha só vale ao clicar em "Inserir";
    fechar o modal descarta. Os itens escolhidos aparecem como etiquetas, cada uma com botão de remover.
    search: null mostra a busca só quando há mais de 8 opções
--}}
@props([
    'name' => null,
    'label' => null,
    'title' => null,
    'options' => [],
    'value' => [],
    'placeholder' => 'Selecione',
    'confirm' => 'Inserir',
    'help' => null,
    'search' => null,
    'required' => false,
])

@use('Ginga\Support\Campo')

@php
    $chave = Campo::chave($name);
    $id = Campo::id($attributes->get('id'), $chave);
    $erro = Campo::erro($errors ?? null, $chave, itens: true);
    $opcoes = Campo::opcoes($options);
    $marcados = Campo::marcados($chave, $value);
    $nome = Campo::nomeLista($name);
    $comBusca = $search ?? count($opcoes) > 8;

    $escolhidos = array_filter($opcoes, fn ($opcao) => in_array((string) $opcao, $marcados, true), ARRAY_FILTER_USE_KEY);
    $contagem = fn (int $total) => match ($total) { 0 => 'Nenhum selecionado', 1 => '1 selecionado', default => "{$total} selecionados" };
@endphp

<div class="mb-3" data-ginga-multiselect data-placeholder="{{ $placeholder }}">
@if (filled($label))
    <label class="form-label" id="{{ $id }}-rotulo" for="{{ $id }}">
        {{ $label }}
        @if ($required)<span class="text-danger" aria-hidden="true">*</span>@endif
    </label>
@endif

    {{-- O leitor de tela ouve o rótulo e a quantidade: "Estados, 3 selecionados" --}}
    <button
        type="button"
        {{ $attributes->except('id')->merge(['class' => 'form-select text-start' . ($erro ? ' is-invalid' : '')]) }}
        id="{{ $id }}"
        data-bs-toggle="modal"
        data-bs-target="#{{ $id }}-modal"
        aria-haspopup="dialog"
        aria-labelledby="{{ filled($label) ? $id . '-rotulo ' : '' }}{{ $id }}-resumo"
        @if ($descricoes = Campo::descricoes($id, $help, $erro)) aria-describedby="{{ $descricoes }}" @endif
        @if ($erro) aria-invalid="true" @endif
    >
        <span id="{{ $id }}-resumo" @class(['d-block text-truncate', 'text-body-secondary' => ! $escolhidos]) data-ginga-multiselect-resumo>
            {{ $escolhidos ? $contagem(count($escolhidos)) : $placeholder }}
        </span>
    </button>
    <div class="invalid-feedback" id="{{ $id }}-erro">{{ $erro }}</div>
@if (filled($help))
    <div class="form-text" id="{{ $id }}-ajuda">{{ $help }}</div>
@endif

    {{-- Etiquetas dos escolhidos, cada uma com botão de remover --}}
    <ul @class(['list-unstyled d-flex flex-wrap gap-2 mt-2 mb-0', 'd-none' => ! $escolhidos]) aria-label="{{ $label ? $label . ': ' : '' }}selecionados" data-ginga-multiselect-etiquetas>
    @foreach ($escolhidos as $opcao => $texto)
        <li>
            <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle d-inline-flex align-items-center gap-2 fw-normal fs-6">
                {{ $texto }}
                <button type="button" class="btn-close" style="font-size: .55em" aria-label="Remover {{ $texto }}" data-ginga-multiselect-remover="{{ $opcao }}"></button>
            </span>
        </li>
    @endforeach
    </ul>

    {{-- O que é enviado com o formulário. O JavaScript reescreve ao inserir ou remover --}}
    <div data-ginga-multiselect-valores data-name="{{ $nome }}">
    @foreach ($escolhidos as $opcao => $texto)
        <input type="hidden" name="{{ $nome }}" value="{{ $opcao }}">
    @endforeach
    </div>

    <div class="modal fade" id="{{ $id }}-modal" tabindex="-1" aria-labelledby="{{ $id }}-modal-titulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="{{ $id }}-modal-titulo">{{ $title ?? $label ?? 'Selecione' }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                @if ($comBusca)
                    <label class="form-label" for="{{ $id }}-busca">
                        {{ $label ?? 'Buscar' }}
                        @if ($required)<span class="text-danger" aria-hidden="true">*</span>@endif
                    </label>
                    <input type="search" class="form-control" id="{{ $id }}-busca" placeholder="{{ $placeholder }} ou digite para buscar" autocomplete="off" data-ginga-multiselect-busca>
                @endif

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 my-3 small">
                        <span class="text-body-secondary" role="status" data-ginga-multiselect-contagem>{{ $contagem(count($escolhidos)) }}</span>
                        <span class="d-flex gap-3">
                            <button type="button" class="btn btn-link btn-sm p-0" data-ginga-multiselect-todos>Selecionar todos</button>
                            <button type="button" class="btn btn-link btn-sm p-0" data-ginga-multiselect-limpar>Limpar</button>
                        </span>
                    </div>

                    {{-- Sem name: só valem depois de "Inserir" --}}
                    <fieldset class="border rounded px-3 py-2">
                        <legend class="visually-hidden">{{ $label ?? 'Opções' }}</legend>
                    @foreach ($opcoes as $opcao => $texto)
                        <div class="form-check py-1" data-ginga-multiselect-item>
                            <input class="form-check-input" type="checkbox" id="{{ $id }}-{{ $loop->index }}" value="{{ $opcao }}" @checked(isset($escolhidos[$opcao]))>
                            <label class="form-check-label w-100" for="{{ $id }}-{{ $loop->index }}">{{ $texto }}</label>
                        </div>
                    @endforeach
                        <p class="text-body-secondary my-1 d-none" data-ginga-multiselect-vazio>Nenhuma opção encontrada.</p>
                    </fieldset>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-ginga-multiselect-inserir>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M8 2v12M2 8h12"/></svg>
                        {{ $confirm }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@once
<script>
(() => {
    // A datatable pode executar este script de novo ao trocar a tabela
    if (window.gingaMultiselect) return;
    window.gingaMultiselect = true;

    // "São Paulo" e "sao paulo" são iguais na busca
    const normalizar = (texto) => texto.normalize('NFD').replace(/\p{Diacritic}/gu, '').toLowerCase().trim();

    const contagem = (total) => total === 0 ? 'Nenhum selecionado' : total === 1 ? '1 selecionado' : `${total} selecionados`;

    const parte = (raiz, nome) => raiz.querySelector(`[data-ginga-multiselect-${nome}]`);
    const caixas = (raiz) => [...raiz.querySelectorAll('[data-ginga-multiselect-item] input[type="checkbox"]')];
    const texto = (raiz, caixa) => raiz.querySelector(`label[for="${caixa.id}"]`).textContent.trim();
    const valoresSalvos = (raiz) => [...parte(raiz, 'valores').querySelectorAll('input')].map((campo) => campo.value);

    const atualizarContagem = (raiz) => {
        parte(raiz, 'contagem').textContent = contagem(caixas(raiz).filter((caixa) => caixa.checked).length);
    };

    // Grava a escolha: campos enviados com o formulário, etiquetas e resumo do campo
    const salvar = (raiz, valores) => {
        const container = parte(raiz, 'valores');
        const etiquetas = parte(raiz, 'etiquetas');
        container.replaceChildren();
        etiquetas.replaceChildren();

        for (const caixa of caixas(raiz)) {
            if (!valores.includes(caixa.value)) continue;

            const campo = document.createElement('input');
            campo.type = 'hidden';
            campo.name = container.dataset.name;
            campo.value = caixa.value;
            container.append(campo);

            const nome = texto(raiz, caixa);
            const item = document.createElement('li');
            item.innerHTML = '<span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle d-inline-flex align-items-center gap-2 fw-normal fs-6"><span></span><button type="button" class="btn-close" style="font-size: .55em"></button></span>';
            item.querySelector('span span').textContent = nome;
            const remover = item.querySelector('button');
            remover.setAttribute('aria-label', `Remover ${nome}`);
            remover.dataset.gingaMultiselectRemover = caixa.value;
            etiquetas.append(item);
        }

        etiquetas.classList.toggle('d-none', valores.length === 0);

        const resumo = parte(raiz, 'resumo');
        resumo.textContent = valores.length ? contagem(valores.length) : raiz.dataset.placeholder;
        resumo.classList.toggle('text-body-secondary', valores.length === 0);

        // Avisa Livewire, Alpine e outros scripts que a escolha mudou
        raiz.dispatchEvent(new CustomEvent('ginga:multiselect', { bubbles: true, detail: { valores } }));
    };

    const filtrar = (raiz, termo) => {
        const busca = normalizar(termo);
        let visiveis = 0;

        for (const item of raiz.querySelectorAll('[data-ginga-multiselect-item]')) {
            const mostrar = normalizar(item.textContent).includes(busca);
            item.classList.toggle('d-none', !mostrar);
            if (mostrar) visiveis++;
        }

        parte(raiz, 'vazio').classList.toggle('d-none', visiveis > 0);
    };

    document.addEventListener('change', (evento) => {
        const raiz = evento.target.closest('[data-ginga-multiselect]');
        if (raiz && evento.target.type === 'checkbox') atualizarContagem(raiz);
    });

    document.addEventListener('input', (evento) => {
        if (!evento.target.matches('[data-ginga-multiselect-busca]')) return;
        filtrar(evento.target.closest('[data-ginga-multiselect]'), evento.target.value);
    });

    document.addEventListener('click', (evento) => {
        const raiz = evento.target.closest('[data-ginga-multiselect]');
        if (!raiz) return;

        // "Selecionar todos" e "Limpar" valem só para as opções visíveis na busca
        const todosOuLimpar = evento.target.closest('[data-ginga-multiselect-todos], [data-ginga-multiselect-limpar]');
        if (todosOuLimpar) {
            const marcar = todosOuLimpar.hasAttribute('data-ginga-multiselect-todos');
            for (const item of raiz.querySelectorAll('[data-ginga-multiselect-item]:not(.d-none)')) {
                item.querySelector('input').checked = marcar;
            }
            atualizarContagem(raiz);
            return;
        }

        if (evento.target.closest('[data-ginga-multiselect-inserir]')) {
            salvar(raiz, caixas(raiz).filter((caixa) => caixa.checked).map((caixa) => caixa.value));
            return;
        }

        const remover = evento.target.closest('[data-ginga-multiselect-remover]');
        if (remover) {
            const lista = [...parte(raiz, 'etiquetas').querySelectorAll('[data-ginga-multiselect-remover]')];
            const posicao = lista.indexOf(remover);

            salvar(raiz, valoresSalvos(raiz).filter((valor) => valor !== remover.dataset.gingaMultiselectRemover));

            // O foco vai para a etiqueta seguinte (ou anterior); sem etiquetas, volta para o campo
            const restantes = parte(raiz, 'etiquetas').querySelectorAll('[data-ginga-multiselect-remover]');
            (restantes[posicao] ?? restantes[posicao - 1] ?? raiz.querySelector('[data-bs-toggle="modal"]')).focus();
        }
    });

    // Ao abrir, desfaz o que não foi inserido da última vez e limpa a busca
    document.addEventListener('show.bs.modal', (evento) => {
        const raiz = evento.target.closest('[data-ginga-multiselect]');
        if (!raiz) return;

        const salvos = valoresSalvos(raiz);
        for (const caixa of caixas(raiz)) caixa.checked = salvos.includes(caixa.value);

        const busca = parte(raiz, 'busca');
        if (busca) busca.value = '';
        filtrar(raiz, '');
        atualizarContagem(raiz);
    });

    // O foco vai para a busca (ou para a primeira opção)
    document.addEventListener('shown.bs.modal', (evento) => {
        const raiz = evento.target.closest('[data-ginga-multiselect]');
        if (raiz) (parte(raiz, 'busca') ?? caixas(raiz)[0])?.focus();
    });
})();
</script>
@endonce
