<?php

namespace App\Docs;

use Illuminate\Support\Collection;

/**
 * Mapa do site: grupos e páginas, na ordem do menu.
 * Também alimenta a busca, o "anterior / próximo" e o build estático.
 */
class Navegacao
{
    /**
     * slug => [título, descrição, ícone, componentes]. A view fica em docs/paginas/{slug}.
     * Componentes listados aqui também são encontrados pela busca.
     */
    private const GRUPOS = [
        'Começando' => [
            'instalacao' => ['Instalação', 'Requisitos, instalação pelo Composer e o layout base com estilos, scripts e mensagens.', 'bi-box-seam', ['styles', 'scripts']],
            'tema' => ['Tema e cores', 'Paleta do Ginga, modo escuro e como trocar as cores com variáveis CSS.', 'bi-palette', []],
            'exemplo' => ['Exemplo completo', 'Uma tela de cadastro de clientes montada só com componentes do Ginga.', 'bi-window-sidebar', []],
        ],
        'Mensagens' => [
            'alert' => ['Alert', 'Alertas com título, ícone e botão de fechar, nas cores do tema.', 'bi-exclamation-circle', ['alert']],
            'toast' => ['Toast e Flash', 'Mensagens flutuantes que somem sozinhas e o flash que mostra as mensagens da sessão.', 'bi-chat-square-dots', ['toast', 'toast-container', 'flash']],
            'modal' => ['Modal', 'Janela de diálogo com título acessível, rodapé e opção de não fechar por engano.', 'bi-window-stack', ['modal']],
        ],
        'Ações' => [
            'button' => ['Button', 'Botões e links com variantes, ícones e estado de carregamento.', 'bi-hand-index', ['button']],
            'exclusao' => ['Confirmação de exclusão', 'Botão de excluir com modal de confirmação e envio do DELETE com CSRF.', 'bi-trash3', ['delete-button', 'confirm-delete']],
        ],
        'Formulários' => [
            'input' => ['Input', 'Campo de texto com label, erro de validação, old() e acessibilidade automáticos.', 'bi-input-cursor-text', ['input']],
            'select' => ['Select', 'Lista de opções com placeholder, múltipla escolha e opções vindas de arrays, Collections ou enums.', 'bi-menu-button-wide', ['select']],
            'multiselect' => ['Multiselect', 'Escolha de várias opções em um modal com busca, etiquetas e confirmação.', 'bi-ui-checks', ['multiselect']],
            'textarea' => ['Textarea', 'Texto longo com o mesmo tratamento de erro e old() do input.', 'bi-textarea-t', ['textarea']],
            'checkbox' => ['Checkbox e Switch', 'Caixa de seleção e interruptor que enviam "0" quando desmarcados.', 'bi-toggle-on', ['checkbox', 'switch']],
            'grupos' => ['Checkbox e radio em grupo', 'Grupos de opções em fieldset, com legend lida pelo leitor de tela.', 'bi-list-check', ['checkbox-group', 'radio-group']],
        ],
        'Campos brasileiros' => [
            'campos-brasileiros' => ['CPF, CNPJ, telefone e dinheiro', 'Campos com máscara, teclado numérico no celular e valor do banco formatado.', 'bi-person-vcard', ['cpf', 'cnpj', 'cpf-cnpj', 'telefone', 'dinheiro', 'uf']],
            'cep' => ['CEP e endereço', 'Campo de CEP que busca o endereço no ViaCEP e preenche o formulário.', 'bi-geo-alt', ['cep']],
            'validacao' => ['Validação e máscaras', 'Regras de validação, diretivas Blade de exibição e a classe Mascara.', 'bi-shield-check', ['@cpf', '@dinheiro', 'Mascara']],
        ],
        'Listagens' => [
            'datatable' => ['Datatable', 'Tabela com busca, ordenação e paginação feitas no banco, sem recarregar a página.', 'bi-table', ['datatable', 'Tabela']],
            'table' => ['Table', 'Tabela simples, responsiva, com mensagem automática quando não há registros.', 'bi-grid-3x3', ['table']],
            'pagination' => ['Pagination', 'Paginação em português para qualquer paginator do Laravel.', 'bi-three-dots', ['pagination']],
            'badge' => ['Badge', 'Etiquetas de status sólidas ou suaves.', 'bi-tag', ['badge']],
        ],
        'Estrutura' => [
            'card' => ['Card', 'Cartão com título, ações no cabeçalho, rodapé e modo sem margem para tabelas.', 'bi-card-heading', ['card']],
            'breadcrumb' => ['Breadcrumb', 'Trilha de navegação com a página atual marcada para leitores de tela.', 'bi-signpost-split', ['breadcrumb']],
        ],
    ];

    /**
     * @return Collection<string, Collection<int, Pagina>>
     */
    public static function grupos(): Collection
    {
        return collect(self::GRUPOS)->map(fn (array $paginas, string $grupo) => collect($paginas)
            ->map(fn (array $dados, string $slug) => new Pagina($slug, $grupo, ...$dados))
            ->values());
    }

    /**
     * @return Collection<int, Pagina>
     */
    public static function paginas(): Collection
    {
        return self::grupos()->flatten(1)->values();
    }

    public static function pagina(string $slug): ?Pagina
    {
        return self::paginas()->firstWhere('slug', $slug);
    }

    /**
     * Páginas de componentes, sem as do grupo "Começando": usadas no catálogo da página inicial.
     */
    public static function componentes(): Collection
    {
        return self::grupos()->except('Começando');
    }

    /**
     * @return array{0: ?Pagina, 1: ?Pagina}
     */
    public static function vizinhas(string $slug): array
    {
        $paginas = self::paginas();
        $indice = $paginas->search(fn (Pagina $pagina) => $pagina->slug === $slug);

        return [$paginas->get($indice - 1), $paginas->get($indice + 1)];
    }

    /**
     * Índice da busca (Ctrl+K), embutido no HTML para funcionar também no site estático.
     */
    public static function indiceBusca(): array
    {
        return self::paginas()->map(fn (Pagina $pagina) => [
            'titulo' => $pagina->titulo,
            'grupo' => $pagina->grupo,
            'descricao' => $pagina->descricao,
            'icone' => $pagina->icone,
            'url' => $pagina->url(),
            'termos' => implode(' ', $pagina->componentes),
        ])->all();
    }
}
