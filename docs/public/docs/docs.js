/*
 * Site de documentação do Ginga: tema, copiar código, busca (Ctrl+K), "Nesta página" e os exemplos.
 * Sem dependências além do Bootstrap, que o <x-ginga::scripts> já carrega.
 */
(() => {
    const raiz = document.documentElement;
    const estatico = document.body.hasAttribute('data-docs-estatico');

    // ------------------------------------------------------------ Toasts

    const containerToasts = document.getElementById('docs-toasts');

    /** Mostra um <x-ginga::toast> renderizado no servidor, guardado em um <template> */
    const mostrarToast = (modelo, texto) => {
        const toast = modelo.content.firstElementChild.cloneNode(true);
        if (texto !== undefined) toast.querySelector('[data-docs-toast-texto]').textContent = texto;

        containerToasts.append(toast);
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
        bootstrap.Toast.getOrCreateInstance(toast).show();
    };

    const toast = (variante, texto) => mostrarToast(document.querySelector(`template[data-docs-toast="${variante}"]`), texto);
    window.docsToast = toast;

    // Botões de demonstração: data-docs-toast="success" data-docs-toast-mensagem="Cliente salvo."
    document.addEventListener('click', (evento) => {
        const botao = evento.target.closest('button[data-docs-toast]');
        if (botao) toast(botao.dataset.docsToast, botao.dataset.docsToastMensagem);
    });

    // ------------------------------------------------------------ Tema claro / escuro

    const botaoTema = document.querySelector('[data-docs-tema]');

    const aplicarTema = (tema) => {
        raiz.dataset.bsTheme = tema;
        const escuro = tema === 'dark';
        botaoTema.setAttribute('aria-label', escuro ? 'Usar tema claro' : 'Usar tema escuro');
        botaoTema.querySelector('.bi').className = `bi ${escuro ? 'bi-sun' : 'bi-moon-stars'}`;
    };

    aplicarTema(raiz.dataset.bsTheme);
    botaoTema.addEventListener('click', () => {
        const tema = raiz.dataset.bsTheme === 'dark' ? 'light' : 'dark';
        aplicarTema(tema);
        try { localStorage.setItem('docs-tema', tema); } catch (erro) { /* navegação privada */ }
    });

    // ------------------------------------------------------------ Copiar código

    document.addEventListener('click', async (evento) => {
        const botao = evento.target.closest('[data-docs-copiar]');
        if (!botao) return;

        const alvo = botao.dataset.docsCopiar
            ? document.querySelector(botao.dataset.docsCopiar)
            : botao.closest('.docs-codigo').querySelector('code');

        try {
            await navigator.clipboard.writeText(alvo.textContent.trim());
        } catch (erro) {
            toast('danger', 'Não foi possível copiar. Selecione o texto e use Ctrl+C.');
            return;
        }

        const rotulo = botao.querySelector('span');
        const icone = botao.querySelector('.bi');
        botao.classList.add('copiado');
        icone.className = 'bi bi-check2';
        if (rotulo) rotulo.textContent = 'Copiado';
        // O leitor de tela não percebe a troca de texto do botão: avisa pelo toast, que é role="status"
        if (!rotulo) toast('success', 'Copiado para a área de transferência.');

        clearTimeout(botao.docsEspera);
        botao.docsEspera = setTimeout(() => {
            botao.classList.remove('copiado');
            icone.className = 'bi bi-clipboard';
            if (rotulo) rotulo.textContent = 'Copiar';
        }, 1800);
    });

    // Código longo: mostrar tudo / recolher
    document.addEventListener('click', (evento) => {
        const botao = evento.target.closest('[data-docs-expandir]');
        if (!botao) return;

        const aberto = botao.closest('.docs-codigo').classList.toggle('aberto');
        botao.setAttribute('aria-expanded', String(aberto));
        botao.querySelector('span').textContent = aberto ? 'Recolher código' : 'Mostrar código completo';
    });

    // ------------------------------------------------------------ Busca (Ctrl+K)

    const modalBusca = document.getElementById('docs-busca');
    const campoBusca = modalBusca.querySelector('[data-docs-busca-campo]');
    const listaBusca = modalBusca.querySelector('#docs-busca-resultados');
    const vazioBusca = modalBusca.querySelector('[data-docs-busca-vazio]');
    const indice = JSON.parse(document.getElementById('docs-indice-busca').textContent);

    // "sao" encontra "São": compara sem acentos e sem maiúsculas
    const normalizar = (texto) => texto.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
    for (const item of indice) {
        item.alvo = normalizar(`${item.titulo} ${item.grupo} ${item.termos} ${item.descricao}`);
        item.tituloNormal = normalizar(`${item.titulo} ${item.termos}`);
    }

    let selecionado = 0;

    const opcoes = () => [...listaBusca.querySelectorAll('[role="option"]')];

    const selecionar = (posicao) => {
        const lista = opcoes();
        if (!lista.length) return;

        selecionado = (posicao + lista.length) % lista.length;
        lista.forEach((opcao, i) => opcao.setAttribute('aria-selected', i === selecionado ? 'true' : 'false'));
        campoBusca.setAttribute('aria-activedescendant', lista[selecionado].id);
        lista[selecionado].scrollIntoView({ block: 'nearest' });
    };

    const buscar = () => {
        const termos = normalizar(campoBusca.value.trim()).split(/\s+/).filter(Boolean);

        // Título que combina vem antes de descrição que combina
        const resultados = indice
            .filter((item) => termos.every((termo) => item.alvo.includes(termo)))
            .sort((a, b) => Number(termos.some((t) => b.tituloNormal.includes(t))) - Number(termos.some((t) => a.tituloNormal.includes(t))));

        listaBusca.replaceChildren();
        let grupoAtual = null;

        resultados.forEach((item, i) => {
            // Sem busca: agrupa como no menu. Com busca: lista corrida, do mais relevante ao menos
            if (!termos.length && item.grupo !== grupoAtual) {
                grupoAtual = item.grupo;
                const grupo = document.createElement('li');
                grupo.className = 'docs-busca-grupo';
                grupo.setAttribute('role', 'presentation');
                grupo.textContent = item.grupo;
                listaBusca.append(grupo);
            }

            const opcao = document.createElement('li');
            opcao.id = `docs-busca-${i}`;
            opcao.setAttribute('role', 'option');
            opcao.innerHTML = `<a href="${item.url}" tabindex="-1"><i class="bi ${item.icone}" aria-hidden="true"></i><div><strong></strong><small></small></div></a>`;
            opcao.querySelector('strong').textContent = item.titulo;
            opcao.querySelector('small').textContent = termos.length ? `${item.grupo} · ${item.descricao}` : item.descricao;
            opcao.addEventListener('mousemove', () => selecionado !== i && selecionar(i));
            listaBusca.append(opcao);
        });

        vazioBusca.hidden = resultados.length > 0;
        selecionar(0);
    };

    campoBusca.addEventListener('input', buscar);
    campoBusca.addEventListener('keydown', (evento) => {
        if (evento.key === 'ArrowDown' || evento.key === 'ArrowUp') {
            evento.preventDefault();
            selecionar(selecionado + (evento.key === 'ArrowDown' ? 1 : -1));
        } else if (evento.key === 'Enter') {
            evento.preventDefault();
            opcoes()[selecionado]?.querySelector('a').click();
        }
    });

    modalBusca.addEventListener('show.bs.modal', buscar);
    modalBusca.addEventListener('shown.bs.modal', () => campoBusca.focus());
    modalBusca.addEventListener('hidden.bs.modal', () => { campoBusca.value = ''; });

    document.addEventListener('keydown', (evento) => {
        const digitando = evento.target.closest('input, textarea, select, [contenteditable]');
        const atalho = (evento.key === 'k' && (evento.ctrlKey || evento.metaKey)) || (evento.key === '/' && !digitando);
        if (!atalho) return;

        evento.preventDefault();
        bootstrap.Modal.getOrCreateInstance(modalBusca).show();
    });

    // ------------------------------------------------------------ "Nesta página": marca a seção visível

    const linksIndice = new Map(
        [...document.querySelectorAll('[data-docs-indice-link]')].map((link) => [link.hash.slice(1), link]),
    );

    if (linksIndice.size && 'IntersectionObserver' in window) {
        const visiveis = new Set();
        const titulos = [...linksIndice.keys()].map((id) => document.getElementById(id)).filter(Boolean);

        const observador = new IntersectionObserver((entradas) => {
            for (const entrada of entradas) {
                entrada.isIntersecting ? visiveis.add(entrada.target) : visiveis.delete(entrada.target);
            }

            // A primeira seção visível; se nenhuma estiver, a última que já passou pelo topo
            const atual = titulos.find((titulo) => visiveis.has(titulo))
                ?? [...titulos].reverse().find((titulo) => titulo.getBoundingClientRect().top < 0);

            for (const [id, link] of linksIndice) {
                link.classList.toggle('active', id === atual?.id);
            }
        }, { rootMargin: '-64px 0px -55% 0px' });

        titulos.forEach((titulo) => observador.observe(titulo));
    }

    // ------------------------------------------------------------ Exemplos: nada sai do navegador

    const dentroDoExemplo = (elemento) => elemento.closest('.docs-exemplo-preview, [data-docs-demo]');
    const naDatatable = (elemento) => elemento.closest('[data-ginga-datatable]');

    let avisouDatatable = false;
    const avisarDatatable = () => {
        if (avisouDatatable) return;
        avisouDatatable = true;
        toast('primary', 'Busca, ordenação e paginação rodam no servidor. Rode o site de docs localmente para testar.');
    };

    // Fase de captura no window: roda antes dos scripts do Ginga, que escutam no document
    window.addEventListener('submit', (evento) => {
        const formulario = evento.target;

        if (formulario.matches('[data-ginga-exclusao-form]')) {
            evento.preventDefault();
            evento.stopImmediatePropagation();
            const item = formulario.closest('.modal').querySelector('[data-ginga-exclusao-item]')?.textContent || 'O registro';
            bootstrap.Modal.getInstance(formulario.closest('.modal'))?.hide();
            toast('warning', `Exemplo: ${item} não foi excluído de verdade.`);
            return;
        }

        if (naDatatable(formulario)) {
            if (estatico) {
                evento.preventDefault();
                evento.stopImmediatePropagation();
                avisarDatatable();
            }
            return;
        }

        if (dentroDoExemplo(formulario)) {
            evento.preventDefault();
            bootstrap.Modal.getInstance(formulario.closest('.modal'))?.hide();
            toast('success', 'Formulário de exemplo enviado. Nada saiu do navegador.');
        }
    }, true);

    window.addEventListener('click', (evento) => {
        const link = evento.target.closest('a[href]');
        if (!link || !dentroDoExemplo(link) || link.matches('[data-bs-toggle], [data-docs-livre]')) return;

        if (naDatatable(link) && !estatico) return;

        evento.preventDefault();
        if (naDatatable(link)) {
            evento.stopImmediatePropagation();
            avisarDatatable();
        }
    }, true);

    // Site estático: a datatable buscaria uma página que não muda. Mostra o aviso no lugar
    if (estatico) {
        for (const tipo of ['input', 'change']) {
            window.addEventListener(tipo, (evento) => {
                if (!naDatatable(evento.target)) return;
                evento.stopImmediatePropagation();
                avisarDatatable();
            }, true);
        }
    }

    // ------------------------------------------------------------ Página de tema: testar outra cor

    const campoCor = document.querySelector('[data-docs-cor]');
    if (campoCor) {
        const variaveis = ['--ginga-rosa', '--ginga-rosa-rgb', '--ginga-rosa-hover', '--ginga-rosa-active', '--ginga-rosa-claro'];
        const valorCor = document.querySelector('[data-docs-cor-valor]');

        const aplicarCor = (hex) => {
            const rgb = hex.match(/\w\w/g).map((par) => parseInt(par, 16)).join(', ');
            raiz.style.setProperty('--ginga-rosa', hex);
            raiz.style.setProperty('--ginga-rosa-rgb', rgb);
            raiz.style.setProperty('--ginga-rosa-hover', `color-mix(in srgb, ${hex} 85%, black)`);
            raiz.style.setProperty('--ginga-rosa-active', `color-mix(in srgb, ${hex} 75%, black)`);
            raiz.style.setProperty('--ginga-rosa-claro', `color-mix(in srgb, ${hex} 18%, var(--ginga-superficie-alta))`);
            valorCor.textContent = `--ginga-rosa: ${hex.toUpperCase()};\n--ginga-rosa-rgb: ${rgb};`;
        };

        campoCor.addEventListener('input', () => aplicarCor(campoCor.value));
        document.querySelector('[data-docs-cor-restaurar]').addEventListener('click', () => {
            variaveis.forEach((variavel) => raiz.style.removeProperty(variavel));
            campoCor.value = campoCor.defaultValue;
            valorCor.textContent = '--ginga-rosa: #C9306F;\n--ginga-rosa-rgb: 201, 48, 111;';
        });
    }
})();
