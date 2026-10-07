@props([
    // Ginga\Support\Tabela
    'tabela',
    // chave => título, ou chave => ['label' => ..., 'class' => ..., 'hidden' => true]
    'columns' => [],
    'search' => true,
    'placeholder' => 'Buscar...',
    'empty' => 'Nenhum registro encontrado.',
    'caption' => null,
])

@use('Ginga\Support\Tabela')

@php
    if (! $tabela->linhas) {
        $tabela->paginar();
    }

    // O id precisa ser o mesmo a cada carregamento: o JavaScript acha a tabela nova na resposta por ele
    $id = $attributes->get('id', 'ginga-datatable');

    $colunas = collect($columns)->map(fn ($coluna) => is_array($coluna) ? $coluna : ['label' => $coluna]);
@endphp

<div {{ $attributes->merge(['id' => $id, 'data-ginga-datatable' => true]) }}>
    <form method="GET" action="{{ request()->url() }}" class="row g-2 align-items-center mb-3" data-ginga-datatable-form>
    @if ($search)
        <div class="col-12 col-sm">
            <label class="visually-hidden" for="{{ $id }}-busca">Buscar</label>
            <div class="input-group">
                <span class="input-group-text" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg>
                </span>
                <input
                    type="search"
                    class="form-control"
                    id="{{ $id }}-busca"
                    name="{{ Tabela::BUSCA }}"
                    value="{{ $tabela->busca }}"
                    placeholder="{{ $placeholder }}"
                    autocomplete="off"
                    data-ginga-datatable-campo
                >
            </div>
        </div>
    @endif
        <div class="col-auto d-flex align-items-center gap-2 ms-auto">
            <label class="small text-body-secondary text-nowrap" for="{{ $id }}-por-pagina">Por página</label>
            <select class="form-select w-auto" id="{{ $id }}-por-pagina" name="{{ Tabela::POR_PAGINA }}" data-ginga-datatable-campo>
            @foreach ($tabela->opcoesPorPagina as $opcao)
                <option value="{{ $opcao }}" @selected($opcao === $tabela->porPagina)>{{ $opcao }}</option>
            @endforeach
            </select>
        </div>
        {{-- Mantém a ordenação ao buscar. O JavaScript troca este bloco a cada carregamento --}}
        <div class="d-none" data-ginga-datatable-estado>
        @if ($tabela->ordem)
            <input type="hidden" name="{{ Tabela::ORDENAR }}" value="{{ $tabela->ordem }}">
            <input type="hidden" name="{{ Tabela::DIRECAO }}" value="{{ $tabela->direcao }}">
        @endif
        </div>
        <noscript>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </noscript>
    </form>

    {{-- Anuncia o resultado para leitores de tela sem reler a tabela inteira --}}
    <div class="visually-hidden" role="status" data-ginga-datatable-anuncio></div>

    <div data-ginga-datatable-resultado>
        {{-- columns só informa o número de colunas para a linha de "nenhum registro"; o cabeçalho vem do slot head --}}
        <x-ginga::table :caption="$caption" :columns="$colunas->pluck('label')->all()">
            <x-slot:head>
                <tr>
                @foreach ($colunas as $chave => $coluna)
                    @php
                        $ativa = $tabela->ordem === $chave;
                        $direcao = $tabela->direcao === 'asc' ? 'ascending' : 'descending';
                    @endphp
                    <th scope="col" @class(['text-nowrap', $coluna['class'] ?? '' => isset($coluna['class'])]) @if ($ativa) aria-sort="{{ $direcao }}" @endif>
                    @if ($coluna['hidden'] ?? false)
                        <span class="visually-hidden">{{ $coluna['label'] }}</span>
                    @elseif (is_string($chave) && $tabela->ordenavel($chave))
                        <a href="{{ $tabela->urlOrdenar($chave) }}" class="link-body-emphasis text-decoration-none" data-ginga-datatable-link>
                            {{ $coluna['label'] }}
                            @if ($ativa)
                                <span aria-hidden="true">{{ $tabela->direcao === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-body-tertiary" aria-hidden="true">↕</span>
                            @endif
                        </a>
                    @else
                        {{ $coluna['label'] }}
                    @endif
                    </th>
                @endforeach
                </tr>
            </x-slot:head>

            <x-slot:empty>
            @if ($tabela->busca !== '')
                Nenhum resultado para “{{ $tabela->busca }}”.
                <a href="{{ $tabela->urlSemBusca() }}" data-ginga-datatable-link>Limpar busca</a>
            @else
                {{ $empty }}
            @endif
            </x-slot:empty>

            {{ $slot }}
        </x-ginga::table>

        <x-ginga::pagination :paginator="$tabela->linhas" class="mt-3" />
    </div>
</div>

@once
<script>
(() => {
    if (window.gingaDatatable) return;
    window.gingaDatatable = true;

    const controles = new WeakMap();

    // innerHTML não executa <script>: recria cada um para os componentes da tabela nova funcionarem.
    // Os scripts do Ginga verificam se já rodaram, então não duplicam nada
    const executarScripts = (elemento) => {
        for (const antigo of elemento.querySelectorAll('script')) {
            const novo = document.createElement('script');
            novo.textContent = antigo.textContent;
            antigo.replaceWith(novo);
        }
    };

    const parte = (raiz, nome) => raiz.querySelector(`[data-ginga-datatable-${nome}]`);

    // Busca a página inteira e troca só a tabela: funciona com qualquer controller, sem rota extra
    const carregar = async (raiz, url, { historico = true } = {}) => {
        controles.get(raiz)?.abort();
        const controle = new AbortController();
        controles.set(raiz, controle);

        const resultado = parte(raiz, 'resultado');
        raiz.setAttribute('aria-busy', 'true');
        resultado.classList.add('opacity-50');

        try {
            const resposta = await fetch(url, { headers: { Accept: 'text/html' }, signal: controle.signal });
            if (!resposta.ok) throw new Error(resposta.statusText);

            const pagina = new DOMParser().parseFromString(await resposta.text(), 'text/html');
            const nova = pagina.getElementById(raiz.id);
            if (!nova) throw new Error('Tabela não encontrada na resposta');

            for (const nome of ['resultado', 'estado']) {
                parte(raiz, nome).innerHTML = parte(nova, nome).innerHTML;
            }
            executarScripts(parte(raiz, 'resultado'));

            // Voltar/avançar no navegador: atualiza os campos, menos o que está sendo digitado
            for (const campo of raiz.querySelectorAll('[data-ginga-datatable-campo]')) {
                const novo = nova.querySelector(`[name="${campo.name}"]`);
                if (novo && campo !== document.activeElement) campo.value = novo.value;
            }

            const resumo = parte(raiz, 'resultado').querySelector('[data-ginga-paginacao-resumo]');
            parte(raiz, 'anuncio').textContent = resumo
                ? resumo.textContent.trim()
                : parte(raiz, 'resultado').querySelector('tbody').textContent.trim();

            if (historico) history.pushState({ gingaDatatable: raiz.id }, '', url);
        } catch (erro) {
            if (erro.name === 'AbortError') return;
            // Qualquer falha: navegação normal, que sempre funciona
            location.href = url;
        } finally {
            if (controles.get(raiz) === controle) {
                raiz.removeAttribute('aria-busy');
                resultado.classList.remove('opacity-50');
            }
        }
    };

    const enviar = (formulario) => {
        const url = new URL(formulario.action);
        for (const [nome, valor] of new FormData(formulario)) {
            if (valor !== '') url.searchParams.set(nome, valor);
        }
        carregar(formulario.closest('[data-ginga-datatable]'), url.toString());
    };

    document.addEventListener('submit', (evento) => {
        const formulario = evento.target.closest('[data-ginga-datatable-form]');
        if (!formulario) return;

        evento.preventDefault();
        enviar(formulario);
    });

    // Busca enquanto digita, esperando uma pausa para não consultar a cada tecla
    let espera;
    document.addEventListener('input', (evento) => {
        const formulario = evento.target.closest('[data-ginga-datatable-form]');
        if (!formulario || evento.target.type !== 'search') return;

        clearTimeout(espera);
        espera = setTimeout(() => enviar(formulario), 350);
    });

    document.addEventListener('change', (evento) => {
        const formulario = evento.target.closest('[data-ginga-datatable-form]');
        if (formulario && evento.target.tagName === 'SELECT') enviar(formulario);
    });

    // Ordenação, paginação e "Limpar busca"
    document.addEventListener('click', (evento) => {
        const link = evento.target.closest('[data-ginga-datatable-resultado] :is([data-ginga-datatable-link], .pagination a)');
        // Ctrl/Cmd + clique abre em outra aba normalmente
        if (!link || evento.ctrlKey || evento.metaKey || evento.shiftKey || evento.button !== 0) return;

        evento.preventDefault();
        carregar(link.closest('[data-ginga-datatable]'), link.href);
    });

    window.addEventListener('popstate', () => {
        for (const raiz of document.querySelectorAll('[data-ginga-datatable]')) {
            carregar(raiz, location.href, { historico: false });
        }
    });
})();
</script>
@endonce
