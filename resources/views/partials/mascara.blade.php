{{-- Incluído pelos campos com máscara. O id do @once garante um único script por página, mesmo com vários campos --}}
@once('ginga-mascara')
<script>
(() => {
    const CPF = '000.000.000-00';
    const CNPJ = '00.000.000/0000-00';

    // Encaixa os caracteres no molde: cada "0" recebe um caractere, o resto é separador
    const moldar = (valor, molde) => {
        let resultado = '';
        let i = 0;

        for (const caractere of molde) {
            if (i >= valor.length) break;
            resultado += caractere === '0' ? valor[i++] : caractere;
        }

        return resultado;
    };

    const digitos = (valor) => valor.replace(/\D/g, '');
    // CNPJ pode ter letras desde julho de 2026
    const alfanumericos = (valor) => valor.toUpperCase().replace(/[^A-Z0-9]/g, '');

    const mascaras = {
        cpf: {
            limpar: (v) => digitos(v).slice(0, 11),
            formatar: (v) => moldar(v, CPF),
        },
        cnpj: {
            limpar: (v) => alfanumericos(v).slice(0, 14),
            formatar: (v) => moldar(v, CNPJ),
        },
        'cpf-cnpj': {
            limpar: (v) => alfanumericos(v).slice(0, 14),
            // Com letra ou mais de 11 caracteres só pode ser CNPJ
            formatar: (v) => moldar(v, /[A-Z]/.test(v) || v.length > 11 ? CNPJ : CPF),
        },
        cep: {
            limpar: (v) => digitos(v).slice(0, 8),
            formatar: (v) => moldar(v, '00000-000'),
        },
        telefone: {
            limpar: (v) => digitos(v).slice(0, 11),
            formatar: (v) => moldar(v, v.length > 10 ? '(00) 00000-0000' : '(00) 0000-0000'),
        },
        dinheiro: {
            limpar: (v) => digitos(v).replace(/^0+/, '').slice(0, 15),
            // Os dois últimos dígitos são os centavos: "123456" -> "1.234,56"
            formatar: (v) => {
                if (v === '') return '';
                const numero = v.padStart(3, '0');
                const inteiro = numero.slice(0, -2).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                return `${inteiro},${numero.slice(-2)}`;
            },
        },
    };

    const significativo = /[A-Za-z0-9]/;

    const aplicarMascara = (campo, mascara) => {
        const valor = campo.value;
        const cursor = campo.selectionStart ?? valor.length;
        const formatado = mascara.formatar(mascara.limpar(valor));

        if (formatado === valor) return;
        campo.value = formatado;
        if (document.activeElement !== campo) return;

        // Mantém o cursor no mesmo caractere digitado, mesmo com os separadores mudando de lugar
        let posicao;

        if (campo.dataset.gingaMascara === 'dinheiro') {
            // Dinheiro cresce da direita para a esquerda: conta os dígitos depois do cursor
            let restantes = digitos(valor.slice(cursor)).length;
            posicao = formatado.length;
            while (posicao > 0 && restantes > 0) {
                if (/\d/.test(formatado[--posicao])) restantes--;
            }
            // Fica colado no dígito digitado, antes de vírgula ou ponto
            while (posicao > 0 && !/\d/.test(formatado[posicao - 1])) posicao--;
        } else {
            let antes = mascara.limpar(valor.slice(0, cursor)).length;
            posicao = 0;
            while (posicao < formatado.length && antes > 0) {
                if (significativo.test(formatado[posicao++])) antes--;
            }
        }

        campo.setSelectionRange(posicao, posicao);
    };

    // Busca de CEP no ViaCEP

    const acharCampo = (origem, nome) =>
        origem.form?.elements.namedItem(nome) ?? document.getElementById(nome);

    const mostrarErro = (campo, mensagem) => {
        const feedback = document.getElementById(`${campo.id}-erro`);
        campo.classList.add('is-invalid');
        campo.setAttribute('aria-invalid', 'true');
        campo.dataset.gingaErroCep = '';

        if (feedback) {
            feedback.textContent = mensagem;
            const descricoes = (campo.getAttribute('aria-describedby') ?? '').split(' ').filter(Boolean);
            if (!descricoes.includes(feedback.id)) {
                campo.setAttribute('aria-describedby', [...descricoes, feedback.id].join(' '));
            }
        }
    };

    // Remove só o erro criado pela busca; erros do servidor ficam até o próximo envio
    const limparErro = (campo) => {
        if (campo.dataset.gingaErroCep === undefined) return;

        const feedback = document.getElementById(`${campo.id}-erro`);
        campo.classList.remove('is-invalid');
        campo.removeAttribute('aria-invalid');
        delete campo.dataset.gingaErroCep;
        if (feedback) feedback.textContent = '';
    };

    const buscarCep = async (campo) => {
        const cep = digitos(campo.value);

        if (cep.length !== 8) {
            delete campo.dataset.gingaCepBuscado;
            limparErro(campo);
            return;
        }

        // Evita buscar de novo o mesmo CEP
        if (campo.dataset.gingaCepBuscado === cep) return;
        campo.dataset.gingaCepBuscado = cep;
        campo.setAttribute('aria-busy', 'true');

        try {
            const resposta = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const endereco = await resposta.json();

            // O usuário trocou o CEP enquanto a busca acontecia
            if (digitos(campo.value) !== cep) return;

            if (endereco.erro) {
                mostrarErro(campo, 'CEP não encontrado.');
                return;
            }

            limparErro(campo);
            const preencher = JSON.parse(campo.dataset.gingaCep);

            for (const [chave, nome] of Object.entries(preencher)) {
                const alvo = acharCampo(campo, nome);
                if (!alvo || endereco[chave] === undefined) continue;

                alvo.value = endereco[chave];
                // Avisa Livewire, Alpine e outros scripts que o valor mudou
                alvo.dispatchEvent(new Event('input', { bubbles: true }));
                alvo.dispatchEvent(new Event('change', { bubbles: true }));
            }

            const foco = campo.dataset.gingaCepFocar && acharCampo(campo, campo.dataset.gingaCepFocar);
            foco?.focus?.();
        } catch {
            // Sem conexão ou ViaCEP fora do ar: o usuário preenche o endereço à mão
            delete campo.dataset.gingaCepBuscado;
        } finally {
            campo.removeAttribute('aria-busy');
        }
    };

    // Um único ouvinte no documento também atende campos adicionados depois (Livewire, Alpine...)
    document.addEventListener('input', (evento) => {
        const campo = evento.target;
        const mascara = mascaras[campo.dataset?.gingaMascara];
        if (!mascara) return;

        aplicarMascara(campo, mascara);

        if (campo.dataset.gingaCep !== undefined) {
            buscarCep(campo);
        }
    });
})();
</script>
@endonce
