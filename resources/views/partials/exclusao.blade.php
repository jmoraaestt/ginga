{{-- Incluído pelo delete-button e pelo confirm-delete. O id do @once garante um único script por página --}}
@once('ginga-exclusao')
<script>
(() => {
    // A datatable pode executar este script de novo ao trocar a tabela
    if (window.gingaExclusao) return;
    window.gingaExclusao = true;

    // Sem modal: envia o DELETE com um formulário criado na hora
    const excluirSemModal = (url, token) => {
        const formulario = document.createElement('form');
        formulario.method = 'POST';
        formulario.action = url;
        formulario.hidden = true;

        for (const [nome, valor] of [['_method', 'DELETE'], ['_token', token]]) {
            const campo = document.createElement('input');
            campo.type = 'hidden';
            campo.name = nome;
            campo.value = valor;
            formulario.append(campo);
        }

        document.body.append(formulario);
        formulario.submit();
    };

    const acharModal = (id) => {
        const modal = document.getElementById(id);
        if (modal) return modal;

        // Sem o componente confirm-delete na página: usa o modal padrão guardado pelo delete-button
        const modelo = document.querySelector(`template[data-ginga-exclusao-modelo="${id}"]`);
        if (!modelo) return null;

        document.body.append(modelo.content.cloneNode(true));
        return document.getElementById(id);
    };

    document.addEventListener('click', (evento) => {
        const botao = evento.target.closest('[data-ginga-excluir]');
        if (!botao) return;

        evento.preventDefault();
        const item = botao.dataset.gingaItem || 'este registro';
        const modal = acharModal(botao.dataset.gingaModal);

        // Sem o JavaScript do Bootstrap em window.bootstrap: confirmação nativa do navegador
        if (!modal || !window.bootstrap?.Modal) {
            if (confirm(`Tem certeza que deseja excluir ${item}? Esta ação não pode ser desfeita.`)) {
                excluirSemModal(botao.dataset.gingaExcluir, botao.dataset.gingaToken);
            }
            return;
        }

        modal.querySelector('[data-ginga-exclusao-form]').action = botao.dataset.gingaExcluir;
        const nome = modal.querySelector('[data-ginga-exclusao-item]');
        if (nome) nome.textContent = item;

        // Dentro de tabelas e cards o modal pode ficar atrás do fundo escuro: o lugar certo é no <body>
        if (modal.parentElement !== document.body) document.body.append(modal);

        // Ao fechar, o foco volta para o botão que abriu
        modal.addEventListener('hidden.bs.modal', () => botao.isConnected && botao.focus(), { once: true });
        bootstrap.Modal.getOrCreateInstance(modal).show(botao);
    });

    // Evita excluir duas vezes com clique duplo
    document.addEventListener('submit', (evento) => {
        if (!evento.target.matches('[data-ginga-exclusao-form]')) return;

        const botao = evento.target.querySelector('[type="submit"]');
        botao.disabled = true;
        botao.setAttribute('aria-busy', 'true');
        botao.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ');
    });
})();
</script>
@endonce
