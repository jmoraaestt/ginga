# Ginga

Componentes Blade com Bootstrap 5 para aplicações Laravel brasileiras: formulários com CPF, CNPJ (inclusive o alfanumérico), CEP, telefone e dinheiro, mensagens, tabelas com busca no servidor e confirmação de exclusão. Tudo em português e acessível.

- **Campos brasileiros** com máscara, teclado certo no celular e regras de validação (`cpf`, `cnpj`, `cep`...).
- **Formulários prontos:** label, erro de validação, `old()` e atributos `aria-*` automáticos.
- **Datatable** com busca, ordenação e paginação feitas no banco.
- **Tema** em CSS, com modo escuro e cores trocáveis por variáveis.
- **Sem build:** funciona só com Blade e o CDN do Bootstrap. Com Vite também.

## Sumário

- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Início rápido](#início-rápido)
- [Configuração](#configuração)
- [Tema e cores](#tema-e-cores)
- [Componentes](#componentes)
- [Desenvolvimento](#desenvolvimento)
- [Licença](#licença)

## Requisitos

| Requisito | Versão |
|-----------|--------|
| PHP | 8.2 ou mais novo |
| Laravel | 11, 12 ou 13 |
| Bootstrap | 5.3, pelo CDN ou pela sua aplicação |

## Instalação

```bash
composer require jmoraaestt/ginga
```

O pacote se registra sozinho (auto-discovery). Não há nada para publicar: o tema é servido pelo próprio pacote.

## Início rápido

**1. Monte o layout** com três componentes:

```blade
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minha aplicação</title>

    <x-ginga::styles />   {{-- Bootstrap + tema do Ginga --}}
</head>
<body>
    {{ $slot }}

    <x-ginga::flash />    {{-- mensagens de sucesso e erro como toasts --}}
    <x-ginga::scripts />  {{-- JavaScript do Bootstrap --}}
</body>
</html>
```

**2. Use os componentes e valide:**

```blade
<form method="POST" action="{{ route('clientes.store') }}">
    @csrf
    <x-ginga::input name="nome" label="Nome" required />
    <x-ginga::cpf name="cpf" required />
    <x-ginga::telefone name="celular" />
    <x-ginga::cep name="cep" :preencher="['logradouro' => 'endereco', 'localidade' => 'cidade', 'uf' => 'uf']" />
    <x-ginga::button type="submit">Salvar</x-ginga::button>
</form>
```

```php
$request->validate(['cpf' => 'required|cpf', 'celular' => 'nullable|telefone', 'cep' => 'cep']);

return redirect()->route('clientes.index')->with('sucesso', 'Cliente salvo.');
```

Pronto: os campos ganham máscara, o erro de validação aparece embaixo do campo certo, o valor digitado volta depois de um erro e o `flash` mostra "Cliente salvo." no canto da tela.

## Configuração

Os componentes `styles` e `scripts` carregam o Bootstrap e o tema. Ajuste-os conforme a sua aplicação:

| Componente | Prop | Padrão | Descrição |
|------------|------|--------|-----------|
| `<x-ginga::styles />` | `bootstrap` | `true` | Carrega o CSS do Bootstrap pelo CDN. Use `:bootstrap="false"` se a aplicação já carrega o Bootstrap. |
| | `icons` | `false` | Carrega também o [Bootstrap Icons](https://icons.getbootstrap.com). |
| `<x-ginga::scripts />` | — | — | Carrega o JavaScript do Bootstrap pelo CDN. |

O tema do Ginga é servido pelo próprio pacote (`/_ginga/tema.css`), então não é preciso rodar `vendor:publish`, e ele se atualiza junto com o pacote.

**Com Vite:** use `<x-ginga::styles :bootstrap="false" />`, não use `<x-ginga::scripts />` e exponha o Bootstrap para os componentes do Ginga:

```js
// resources/js/app.js
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
```

## Tema e cores

O tema é um CSS pequeno (`ginga-theme.css`) carregado depois do Bootstrap. Ele troca as cores do Bootstrap pelas do Ginga e define variáveis que você pode usar nas suas próprias telas:

| Variável | Uso |
|----------|-----|
| `--ginga-rosa`, `-hover`, `-active` | Cor principal (`btn-primary`, links, foco). Os três estados do botão. |
| `--ginga-rosa-vivo` | Identidade visual: logo, capa. Não use atrás de texto pequeno. |
| `--ginga-rosa-claro` | Fundo suave da cor principal. |
| `--ginga-verde-agua`, `-claro`, `-borda`, `-texto` | Sucesso. |
| `--ginga-ambar`, `-claro`, `-borda`, `-texto` | Aviso. |
| `--ginga-perigo`, `-claro`, `-borda`, `-texto` | Erro e exclusão. |
| `--ginga-tinta`, `--ginga-tinta-suave` | Texto e texto secundário. |
| `--ginga-borda`, `--ginga-superficie`, `--ginga-superficie-alta` | Bordas e fundos. |

Cada cor tem uma versão `-rgb` (`--ginga-rosa-rgb: 201, 48, 111`) para usar em `rgba(var(--ginga-rosa-rgb), .2)`.

**Tema escuro:** coloque `data-bs-theme="dark"` no `<html>` e todas as variáveis mudam sozinhas, com contraste ajustado.

**Trocar uma cor:** sobrescreva a variável em um CSS seu, carregado depois do tema:

```css
:root {
    --ginga-rosa: #7B2CBF;
    --ginga-rosa-rgb: 123, 44, 191;
}
```

**Servir como arquivo estático:** por padrão o tema vem da rota `/_ginga/tema.css`. Para copiá-lo para `public/vendor/ginga/ginga-theme.css`:

```bash
php artisan vendor:publish --tag=ginga-assets
```

Depois de publicar, ele não se atualiza mais com o pacote: rode o comando de novo ao atualizar.

## Componentes

Todos os componentes usam o prefixo `x-ginga::`. Cada seção abaixo traz um exemplo, as props e as particularidades de acessibilidade.

| Grupo | Componentes |
|-------|-------------|
| Mensagens | [`flash`](#flash), [`alert`](#alert), [`toast`, `toast-container`](#toast), [`modal`](#modal) |
| Ações | [`button`](#button), [`delete-button`, `confirm-delete`](#confirmação-de-exclusão) |
| Formulários | [`input`, `select`](#input-e-select), [`multiselect`](#multiselect), [`textarea`](#textarea), [`checkbox`, `switch`](#checkbox-e-switch), [`checkbox-group`, `radio-group`](#checkbox-group-e-radio-group) |
| Campos brasileiros | [`cpf`, `cnpj`, `cpf-cnpj`, `telefone`, `dinheiro`, `cep`, `uf`, validação e diretivas `@cpf`, `@dinheiro`](#campos-brasileiros) |
| Listagens | [`datatable`, classe `Tabela`](#datatable), [`table`](#table), [`pagination`](#pagination), [`badge`](#badge) |
| Estrutura | [`card`](#card), [`breadcrumb`](#breadcrumb) |

> **Props booleanas:** escreva a prop sem valor (`<x-ginga::button pill>`) ou com uma expressão PHP (`:loading="$salvando"`). Evite `loading="false"`: sem os dois-pontos, o valor chega como a string `"false"`, que é verdadeira.

> **Atributos extras:** qualquer atributo não listado nas props (`id`, `wire:model`, `x-show`...) é repassado ao elemento principal. Classes extras são somadas às do componente.

## Flash

Mostra as mensagens da sessão como toasts. Coloque uma vez no layout:

```blade
<x-ginga::flash />
```

E use `with()` no redirect:

```php
return redirect()->route('clientes.index')->with('sucesso', 'Cliente salvo.');
```

| Chave da sessão | Cor |
|-----------------|-----|
| `sucesso` ou `success` | verde |
| `erro` ou `error` | vermelho (fica 8 segundos na tela) |
| `aviso` ou `warning` | amarelo |
| `info` | rosa |

Depois de um erro de validação, o `flash` também mostra "Corrija os 3 campos destacados.", e cada mensagem aparece embaixo do seu campo.

| Prop         | Tipo     | Padrão         | Descrição |
|--------------|----------|----------------|-----------|
| `position`   | `string` | `'bottom-end'` | Canto da tela. Veja [`toast-container`](#toast-container). |
| `validation` | `bool`   | `true`         | Mostra o toast de erro de validação. |

## Alert

Alerta do Bootstrap 5.3 com título, ícone e botão de fechar.

```blade
<x-ginga::alert>Seus dados foram atualizados.</x-ginga::alert>
```

Gera:

```html
<div class="alert alert-primary" role="alert">
    Seus dados foram atualizados.
</div>
```

### Props

| Prop          | Tipo           | Padrão      | Descrição |
|---------------|----------------|-------------|-----------|
| `variant`     | `string`       | `'primary'` | Variante do Bootstrap: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`. Gera a classe `alert-{variant}`. |
| `title`       | `string\|null` | `null`      | Título em destaque acima da mensagem (`alert-heading`). Também pode ser passado como slot. |
| `dismissible` | `bool`         | `false`     | Adiciona o botão de fechar e a animação de saída (`alert-dismissible fade show`). |

Qualquer outro atributo (`id`, `wire:key`, `x-show`...) é repassado para a `<div>`. Classes extras são **somadas** às do componente, e os demais atributos **substituem** os padrões. Por exemplo, `role="status"` substitui o `role="alert"`.

> **Leitores de tela:** `role="alert"` faz o leitor de tela interromper o que está lendo para anunciar a mensagem. Use para erros e avisos urgentes. Para mensagens informativas, como "Salvo com sucesso", prefira `role="status"`.

### Slots

| Slot      | Descrição |
|-----------|-----------|
| (padrão)  | Mensagem do alerta. |
| `title`   | Título com HTML. Substitui a prop `title`. |
| `icon`    | Ícone à esquerda. O alerta passa a usar `d-flex` para alinhar o ícone ao texto. |

### Exemplos

**Variante e título**

```blade
<x-ginga::alert variant="danger" title="Não foi possível salvar">
    Verifique os campos destacados e tente novamente.
</x-ginga::alert>
```

```html
<div class="alert alert-danger" role="alert">
    <div class="alert-heading fw-semibold mb-1">Não foi possível salvar</div>
    Verifique os campos destacados e tente novamente.
</div>
```

**Com botão de fechar**

```blade
<x-ginga::alert variant="success" role="status" dismissible>
    Cliente cadastrado com sucesso.
</x-ginga::alert>
```

```html
<div class="alert alert-success alert-dismissible fade show" role="status">
    Cliente cadastrado com sucesso.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
</div>
```

O botão de fechar depende do JavaScript do Bootstrap (`bootstrap.bundle.min.js`).

**Ícone**

```blade
<x-ginga::alert variant="warning">
    <x-slot:icon>
        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
    </x-slot:icon>
    Sua assinatura vence em 3 dias.
</x-ginga::alert>
```

```html
<div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
    <div class="flex-grow-1">
        Sua assinatura vence em 3 dias.
    </div>
</div>
```

**Links e mensagem de sessão**

Use a classe `alert-link` para que o link acompanhe a cor do alerta:

```blade
@if (session('sucesso'))
    <x-ginga::alert variant="success" role="status" dismissible>
        {{ session('sucesso') }} <a href="{{ route('clientes.index') }}" class="alert-link">Ver clientes</a>
    </x-ginga::alert>
@endif
```

### Cores

O tema do Ginga define as versões suaves (fundo, borda e texto) de `primary`, `success`, `warning` e `danger`, usadas pelo alerta nos temas claro e escuro. As demais variantes usam as cores padrão do Bootstrap.

## Toast

Mensagem flutuante no canto da tela que some sozinha. Ideal para avisos de sucesso ou erro depois de salvar um formulário. Usa o Toast do Bootstrap 5.3 com as mesmas cores do [Alert](#alert).

```blade
<x-ginga::toast-container>
    @if (session('sucesso'))
        <x-ginga::toast variant="success">{{ session('sucesso') }}</x-ginga::toast>
    @endif
</x-ginga::toast-container>
```

Gera:

```html
<div class="toast-container position-fixed p-3 bottom-0 end-0">
    <div class="toast bg-success-subtle text-success-emphasis border-success-subtle" role="status" aria-live="polite" aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true" data-ginga-toast="data-ginga-toast">
        <div class="d-flex align-items-start">
            <div class="toast-body flex-grow-1">
                Cliente cadastrado com sucesso.
            </div>
            <button type="button" class="btn-close flex-shrink-0 me-2 mt-2" data-bs-dismiss="toast" aria-label="Fechar"></button>
        </div>
    </div>
</div>
```

> **JavaScript obrigatório:** o toast depende do JavaScript do Bootstrap (`bootstrap.bundle.min.js`). O componente inclui um script, uma única vez por página, que exibe todos os toasts quando a página termina de carregar. Sem o Bootstrap, os toasts ficam ocultos.

### `toast-container`

Posiciona os toasts fixos na tela e empilha um embaixo do outro.

| Prop       | Tipo     | Padrão      | Descrição |
|------------|----------|-------------|-----------|
| `position` | `string` | `'bottom-end'` | Canto da tela: `top-start`, `top-center`, `top-end`, `bottom-start`, `bottom-center` ou `bottom-end`. O padrão é o canto inferior direito. |

### `toast`

| Prop       | Tipo           | Padrão      | Descrição |
|------------|----------------|-------------|-----------|
| `variant`  | `string`       | `'primary'` | Variante do Bootstrap: `primary`, `success`, `warning`, `danger` etc. Gera as classes `bg-{variant}-subtle`, `text-{variant}-emphasis` e `border-{variant}-subtle`. |
| `title`    | `string\|null` | `null`      | Título em destaque acima da mensagem. Também pode ser passado como slot. |
| `delay`    | `int`          | `5000`      | Tempo em milissegundos até o toast sumir. |
| `autohide` | `bool`         | `true`      | Quando `false`, o toast só fecha pelo botão. |

O tempo pausa enquanto o mouse ou o foco do teclado estão sobre o toast.

O `danger` recebe `role="alert"` e `aria-live="assertive"`, que fazem o leitor de tela anunciar o erro na hora. As demais variantes recebem `role="status"` e `aria-live="polite"`, que esperam a leitura atual terminar. Os dois podem ser substituídos por atributos.

Slots: o padrão (a mensagem), `title` e `icon`, iguais aos do Alert.

### Mensagens de sucesso e erro

Para as mensagens da sessão, use o [`<x-ginga::flash />`](#flash), que monta os toasts sozinho. Use `toast` diretamente só para mensagens personalizadas:

```blade
<x-ginga::toast-container>
    <x-ginga::toast variant="warning" title="Assinatura vencendo" :autohide="false">
        Renove até sexta para não perder o acesso.
    </x-ginga::toast>
</x-ginga::toast-container>
```

> **Erros que somem:** quem lê devagar ou usa leitor de tela pode não conseguir ler a mensagem a tempo. Para erros, use um `delay` maior ou `:autohide="false"`. Para erros de validação, mantenha também a indicação no próprio campo (`is-invalid` e `invalid-feedback`).

## Modal

```blade
<x-ginga::button data-bs-toggle="modal" data-bs-target="#novo-contato">Novo contato</x-ginga::button>

<x-ginga::modal id="novo-contato" title="Novo contato" centered>
    <p>Conteúdo do modal.</p>

    <x-slot:footer>
        <x-ginga::button variant="link" data-bs-dismiss="modal">Cancelar</x-ginga::button>
        <x-ginga::button type="submit" form="form-contato">Salvar</x-ginga::button>
    </x-slot:footer>
</x-ginga::modal>
```

| Prop         | Tipo           | Padrão  | Descrição |
|--------------|----------------|---------|-----------|
| `id`         | `string`       | —       | Obrigatório. Usado no `data-bs-target` do botão que abre o modal. |
| `title`      | `string\|null` | `null`  | Título, ligado ao modal por `aria-labelledby`, com o botão de fechar. |
| `size`       | `string\|null` | `null`  | `sm`, `lg` ou `xl`. |
| `centered`   | `bool`         | `false` | Centraliza na vertical. |
| `scrollable` | `bool`         | `false` | Rola só o corpo quando o conteúdo é grande. |
| `static`     | `bool`         | `false` | Não fecha ao clicar fora nem com Esc. Útil para formulários que não podem ser perdidos por engano. |

Depende do JavaScript do Bootstrap.

## Button

Botão do Bootstrap 5.3 com suporte a links, estado de carregamento e ícones.

```blade
<x-ginga::button>Salvar</x-ginga::button>
```

Gera:

```html
<button class="btn btn-primary" type="button">
    Salvar
</button>
```

### Props

| Prop       | Tipo           | Padrão      | Descrição |
|------------|----------------|-------------|-----------|
| `variant`  | `string`       | `'primary'` | Variante do Bootstrap: `primary`, `secondary`, `success`, `danger`, `outline-primary`, `link` etc. Gera a classe `btn-{variant}`. |
| `size`     | `string\|null` | `null`      | Tamanho: `sm` ou `lg`. Gera a classe `btn-{size}`. |
| `href`     | `string\|null` | `null`      | Quando informado, renderiza um `<a>` com `role="button"` no lugar do `<button>`. |
| `pill`     | `bool`         | `false`     | Cantos totalmente arredondados (`rounded-pill`). |
| `block`    | `bool`         | `false`     | Largura total (`w-100`). |
| `loading`  | `bool`         | `false`     | Mostra um spinner antes do texto, desabilita o botão e adiciona `aria-busy="true"`. |
| `disabled` | `bool`         | `false`     | Desabilita o botão. Também funciona em links (veja abaixo). |

Qualquer outro atributo (`id`, `wire:click`, `form`, `target`...) é repassado para o elemento. Classes extras são **somadas** às do componente, e os demais atributos **substituem** os padrões. Por exemplo, `type="submit"` substitui o `type="button"`.

> **Booleanos:** escreva a prop sem valor (`<x-ginga::button pill>`) ou passe uma expressão PHP com dois-pontos (`:loading="$salvando"`). Evite `loading="false"`: sem os dois-pontos, o valor chega como a string `"false"`, que é verdadeira.

### Slots

| Slot        | Descrição |
|-------------|-----------|
| (padrão)    | Texto do botão. |
| `iconLeft`  | Ícone antes do texto. Enquanto `loading` estiver ativo, o spinner ocupa o lugar dele. |
| `iconRight` | Ícone depois do texto. |

### Exemplos

**Variante e tamanho**

```blade
<x-ginga::button variant="danger" size="lg">Excluir</x-ginga::button>
<x-ginga::button variant="outline-secondary" type="submit">Enviar</x-ginga::button>
```

**Link com aparência de botão**

```blade
<x-ginga::button href="{{ route('clientes.create') }}">Novo cliente</x-ginga::button>
```

```html
<a class="btn btn-primary" href="..." role="button">
    Novo cliente
</a>
```

O `<a>` não recebe o atributo `type`.

**Pill e largura total**

```blade
<x-ginga::button pill>Assinar</x-ginga::button>
<x-ginga::button block>Continuar</x-ginga::button>
```

**Carregando**

```blade
<x-ginga::button type="submit" :loading="$salvando">Salvar</x-ginga::button>
```

```html
<button class="btn btn-primary" type="submit" disabled="disabled" aria-busy="true">
    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
    <span class="visually-hidden" role="status">Carregando...</span>
    Salvar
</button>
```

O texto "Carregando..." fica visível apenas para leitores de tela. Em um link, `loading` aplica o mesmo tratamento de `disabled`.

**Desabilitado**

```blade
<x-ginga::button disabled>Salvar</x-ginga::button>
<x-ginga::button href="/relatorio" disabled>Baixar relatório</x-ginga::button>
```

Links não aceitam o atributo `disabled`. No `<a>`, o componente adiciona a classe `disabled` (que bloqueia o clique com `pointer-events: none`), `aria-disabled="true"` para leitores de tela e `tabindex="-1"` para tirar o link da navegação por teclado:

```html
<a class="btn btn-primary disabled" href="/relatorio" role="button" aria-disabled="true" tabindex="-1">
    Baixar relatório
</a>
```

**Ícones**

```blade
<x-ginga::button>
    <x-slot:iconLeft>
        <i class="bi bi-plus-lg" aria-hidden="true"></i>
    </x-slot:iconLeft>
    Adicionar
</x-ginga::button>

<x-ginga::button href="/passo-2" variant="outline-primary">
    Próximo
    <x-slot:iconRight>
        <i class="bi bi-arrow-right" aria-hidden="true"></i>
    </x-slot:iconRight>
</x-ginga::button>
```

Você pode usar qualquer biblioteca de ícones ou SVG inline. Para ícones decorativos, use `aria-hidden="true"`. Em SVG, `fill="currentColor"` faz o ícone acompanhar a cor do botão.

### Cores

O componente usa apenas classes do Bootstrap. As cores vêm do tema do Ginga, carregado pelo [`<x-ginga::styles />`](#styles-e-scripts).

Para servir o tema como arquivo estático (por exemplo, num CDN), copie para `public/` e inclua **depois** do CSS do Bootstrap:

```bash
php artisan vendor:publish --tag=ginga-assets
```

```html
<link rel="stylesheet" href="{{ asset('vendor/ginga/ginga-theme.css') }}">
```

A cópia não se atualiza sozinha: rode o comando de novo com `--force` a cada atualização do pacote.

## Confirmação de exclusão

Um botão em cada linha é tudo o que precisa:

```blade
<x-ginga::delete-button :action="route('clientes.destroy', $cliente)" :item="$cliente->nome" />
```

Ao clicar, um modal pergunta "Tem certeza que deseja excluir **Maria Silva**? Esta ação não pode ser desfeita." Ao confirmar, ele envia um `DELETE` (com o token CSRF) para a URL de `action`. O botão de confirmar é desabilitado no envio, para não excluir duas vezes, e ao fechar o modal o foco volta para o botão que o abriu.

Sem o JavaScript do Bootstrap, o botão usa a confirmação nativa do navegador e continua funcionando.

Para trocar os textos do modal, coloque um `confirm-delete` na página. Ele substitui o modal padrão:

```blade
<x-ginga::confirm-delete title="Excluir cliente?" confirm="Sim, excluir" />
```

**`delete-button`:**

| Prop      | Tipo           | Padrão                       | Descrição |
|-----------|----------------|------------------------------|-----------|
| `action`  | `string`       | —                            | URL que recebe o `DELETE`. |
| `item`    | `string\|null` | `null`                       | Nome mostrado na confirmação. Também é lido pelo leitor de tela ("Excluir Maria Silva"), já que uma tabela tem vários botões "Excluir". |
| `variant` | `string`       | `'outline-danger'`           | Variante do botão. |
| `size`    | `string\|null` | `'sm'`                       | Tamanho do botão. |
| `modal`   | `string`       | `'ginga-confirmar-exclusao'` | `id` de um `confirm-delete` personalizado, quando há mais de um na página. |

O texto do botão é "Excluir". Para trocar ou usar um ícone, passe o conteúdo no slot.

**`confirm-delete`:** aceita `title`, `confirm` (texto do botão, padrão `Excluir`), `cancel` (padrão `Cancelar`) e `id`. Para trocar a pergunta, passe o texto no slot.

## Input e Select

Campos de formulário com label, mensagem de erro e valor antigo automáticos.

```blade
<x-ginga::input name="nome" label="Nome" required />
```

Gera:

```html
<div class="mb-3">
    <label class="form-label" for="campo-nome">
        Nome
        <span class="text-danger" aria-hidden="true">*</span>
    </label>
    <input class="form-control" type="text" id="campo-nome" name="nome" required="required">
    <div class="invalid-feedback" id="campo-nome-erro"></div>
</div>
```

O componente cuida de:

- **Valor depois de erro:** quando a validação falha, o campo volta preenchido com o que foi digitado (`old()`). Campos `type="password"` nunca voltam preenchidos.
- **Erro de validação:** quando `$errors` tem erro para o campo, ele recebe `is-invalid` e `aria-invalid="true"`, e a mensagem aparece embaixo, ligada ao campo por `aria-describedby`.
- **`name` com colchetes:** `endereco[cidade]` funciona, com a chave `endereco.cidade` para os erros e o `old()`.
- **`id` automático:** é gerado a partir do `name` (`campo-nome`). Passe `id` para usar outro.
- **Obrigatório:** com `required`, o label ganha um asterisco escondido do leitor de tela, que já anuncia o campo como obrigatório.

### Props do `input`

| Prop     | Tipo           | Padrão   | Descrição |
|----------|----------------|----------|-----------|
| `name`   | `string\|null` | `null`   | Nome do campo. Também é usado para achar o erro e o `old()`. |
| `label`  | `string\|null` | `null`   | Texto do label. |
| `type`   | `string\|null` | `'text'` | Tipo do input (`email`, `password`, `number`...). |
| `value`  | `mixed`        | `null`   | Valor inicial. O `old()` tem prioridade depois de um envio com erro. Datas do Eloquent funcionam direto: `type="date" :value="$cliente->nascimento"`. |
| `help`   | `string\|null` | `null`   | Texto de ajuda embaixo do campo (`form-text`). |
| `mask`   | `string\|null` | `null`   | Máscara: `cpf`, `cnpj`, `cpf-cnpj`, `cep`, `telefone` ou `dinheiro`. Veja [Campos brasileiros](#campos-brasileiros). |
| `prefix` | `string\|null` | `null`   | Texto antes do campo, em um `input-group` (ex.: `R$`). |
| `suffix` | `string\|null` | `null`   | Texto depois do campo, em um `input-group` (ex.: `kg`). |

### Props do `select`

| Prop          | Tipo           | Padrão | Descrição |
|---------------|----------------|--------|-----------|
| `name`        | `string\|null` | `null` | Nome do campo. |
| `label`       | `string\|null` | `null` | Texto do label. |
| `options`     | `mixed`        | `[]`   | Opções. Veja [formatos de opções](#formatos-de-opções). |
| `value`       | `mixed`        | `null` | Valor selecionado (ou array, no múltiplo). Aceita enum. O `old()` tem prioridade. |
| `placeholder` | `string\|null` | `null` | Primeira opção, com valor vazio (ex.: `Selecione`). |
| `help`        | `string\|null` | `null` | Texto de ajuda embaixo do campo. |

```blade
<x-ginga::select name="plano" label="Plano" placeholder="Selecione" :options="['basico' => 'Básico', 'pro' => 'Profissional']" />

{{-- Múltiplo: o [] do name é adicionado sozinho e o servidor recebe um array --}}
<x-ginga::select name="estados" label="Estados" multiple :options="$estados" :value="['SP', 'RJ']" />
```

> **Escolher vários:** no select múltiplo é preciso segurar Ctrl (ou Cmd), o que muita gente não sabe. Com poucas opções, prefira o [`checkbox-group`](#checkbox-group-e-radio-group). Com muitas, como os 27 estados, prefira o [`multiselect`](#multiselect).

## Multiselect

Campo que abre um modal para escolher várias opções, com busca, "Selecionar todos" e "Limpar". A escolha só vale ao clicar em **Inserir**. Fechar ou cancelar descarta. Os itens escolhidos aparecem embaixo do campo como etiquetas, cada uma com um botão para remover.

```blade
<x-ginga::multiselect
    name="estados"
    label="Estados onde atende"
    title="Escolher estados"
    :options="Brasil::ESTADOS"
    :value="$profissional->estados"
/>
```

O servidor recebe um array (`estados[]`), como no `checkbox-group`:

```php
'estados' => 'required|array|min:1',
'estados.*' => 'uf',
```

| Prop          | Tipo           | Padrão        | Descrição |
|---------------|----------------|---------------|-----------|
| `name`        | `string\|null` | `null`        | Nome do campo. O `[]` é adicionado sozinho. |
| `label`       | `string\|null` | `null`        | Texto do label do campo e do campo de busca. |
| `title`       | `string\|null` | o `label`     | Título do modal. |
| `options`     | `mixed`        | `[]`          | Opções. Veja [formatos de opções](#formatos-de-opções). |
| `value`       | `mixed`        | `[]`          | Opções já escolhidas. O `old()` tem prioridade depois de um erro. |
| `placeholder` | `string`       | `'Selecione'` | Texto do campo quando nada foi escolhido. |
| `confirm`     | `string`       | `'Inserir'`   | Texto do botão que confirma a escolha. |
| `search`      | `bool\|null`   | `null`        | Mostra a busca. Com `null`, aparece só quando há mais de 8 opções. A busca ignora acentos: "sao" encontra "São Paulo". |
| `required`    | `bool`         | `false`       | Mostra o asterisco. Exigir pelo menos uma opção é papel da validação no servidor. |
| `help`        | `string\|null` | `null`        | Texto de ajuda. |

**Acessibilidade:**
- **Campo:** o leitor de tela anuncia o rótulo e a quantidade ("Estados onde atende, 3 selecionados").
- **Abrir o modal:** o foco vai para a busca.
- **Remover uma etiqueta:** o foco passa para a etiqueta seguinte.

Ao inserir ou remover, o componente dispara o evento `ginga:multiselect` com os valores escolhidos (`evento.detail.valores`).

Depende do JavaScript do Bootstrap.

Qualquer outro atributo (`required`, `autofocus`, `wire:model`...) vai para o `<input>` ou `<select>`. O componente vem com `mb-3` em volta.

### Formatos de opções

`select`, `radio-group` e `checkbox-group` aceitam as opções de várias formas:

```blade
{{-- valor => texto --}}
:options="['sp' => 'São Paulo', 'rj' => 'Rio de Janeiro']"

{{-- lista simples: o texto também é o valor enviado --}}
:options="['Manhã', 'Tarde', 'Noite']"

{{-- Collection do Eloquent --}}
:options="Empresa::orderBy('nome')->pluck('nome', 'id')"

{{-- enum: usa o método label() do enum, se existir, ou o nome do caso --}}
:options="Plano::class"
```

O `value` também aceita o próprio enum (`:value="$assinatura->plano"`), uma Collection ou um array.

## Textarea

```blade
<x-ginga::textarea name="observacoes" label="Observações" rows="4" maxlength="500" />
```

Aceita `name`, `label`, `value`, `help` e `rows` (padrão `3`), com o mesmo tratamento de erro e `old()` do `input`.

## Checkbox e Switch

Um checkbox sozinho, para "sim ou não":

```blade
<x-ginga::checkbox name="termos" required>
    Li e aceito os <a href="/termos">termos de uso</a>
</x-ginga::checkbox>

<x-ginga::switch name="newsletter" label="Receber novidades por e-mail" :checked="$usuario->newsletter" />
```

Gera:

```html
<div class="form-check form-switch mb-3">
    <input type="hidden" name="newsletter" value="0">
    <input class="form-check-input" type="checkbox" role="switch" id="campo-newsletter" name="newsletter" value="1" checked="checked">
    <label class="form-check-label" for="campo-newsletter">Receber novidades por e-mail</label>
    <div class="invalid-feedback" id="campo-newsletter-erro"></div>
</div>
```

| Prop             | Tipo           | Padrão  | Descrição |
|------------------|----------------|---------|-----------|
| `name`           | `string\|null` | `null`  | Nome do campo. |
| `label`          | `string\|null` | `null`  | Texto do label. Para usar HTML (um link, por exemplo), passe o texto no slot. |
| `value`          | `string`       | `'1'`   | Valor enviado quando marcado. |
| `checked`        | `bool`         | `false` | Marcado ao abrir a página. Depois de um envio com erro, vale o que foi enviado. |
| `uncheckedValue` | `string\|null` | `'0'`   | Valor enviado quando desmarcado. `null` desliga. |
| `switch`         | `bool`         | `false` | Visual de interruptor (`form-switch`, `role="switch"`). O `<x-ginga::switch>` já vem com ele ligado. |
| `help`           | `string\|null` | `null`  | Texto de ajuda. |

**Por que o hidden com "0"?** O navegador não envia checkbox desmarcado. Sem o hidden, desmarcar a newsletter numa edição não chegaria ao servidor, e o valor salvo continuaria `true`. Com ele, `$request->boolean('newsletter')` retorna `false`. O hidden não é criado em campos `disabled`, para não sobrescrever o valor salvo, nem em listas (`name="itens[]"`).

Para validar o aceite dos termos, use a regra `accepted`: ela recusa o `"0"`.

## Checkbox-group e Radio-group

Grupos de opções dentro de um `<fieldset>`, com o label no `<legend>`, que o leitor de tela anuncia ao entrar no grupo:

```blade
<x-ginga::radio-group
    name="plano"
    label="Plano"
    required
    :options="['basico' => 'Básico', 'pro' => 'Profissional', 'empresa' => 'Empresa']"
    :value="$assinatura->plano"
/>

<x-ginga::checkbox-group
    name="interesses"
    label="Interesses"
    inline
    :options="['danca' => 'Dança', 'musica' => 'Música', 'capoeira' => 'Capoeira']"
    :value="$aluno->interesses"
/>
```

| Prop       | Tipo           | Padrão  | Descrição |
|------------|----------------|---------|-----------|
| `name`     | `string\|null` | `null`  | Nome do campo. No `checkbox-group`, o `[]` é adicionado sozinho e o servidor recebe um array. |
| `label`    | `string\|null` | `null`  | Texto do `<legend>`. |
| `options`  | `array`        | `[]`    | Opções no formato `valor => texto`. |
| `value`    | `mixed`        | —       | Opção marcada (`radio-group`) ou array de opções marcadas (`checkbox-group`). |
| `inline`   | `bool`         | `false` | Opções lado a lado (`form-check-inline`). |
| `required` | `bool`         | `false` | Mostra o asterisco. No `radio-group`, também adiciona `required` em cada opção. |
| `switch`   | `bool`         | `false` | Só no `checkbox-group`: opções com visual de interruptor. |
| `help`     | `string\|null` | `null`  | Texto de ajuda. |

A classe extra vai para o `<fieldset>`. Os demais atributos (`wire:model`, `disabled`...) vão para cada opção.

O erro aparece uma vez, embaixo do grupo. No `checkbox-group`, erros dos itens (`interesses.*`) também aparecem. Para exigir pelo menos uma opção marcada, valide no servidor:

```php
'interesses' => 'required|array|min:1',
'interesses.*' => 'in:danca,musica,capoeira',
```

## Campos brasileiros

Campos prontos com máscara, teclado certo no celular e validação no servidor.

```blade
<x-ginga::cpf name="cpf" required />
<x-ginga::cnpj name="cnpj" />
<x-ginga::cpf-cnpj name="documento" />
<x-ginga::telefone name="telefone" />
<x-ginga::dinheiro name="valor" />
<x-ginga::cep name="cep" />
<x-ginga::uf name="uf" />
```

| Componente | Label padrão  | Máscara | Observações |
|------------|---------------|---------|-------------|
| `cpf`      | `CPF`         | `000.000.000-00` | Teclado numérico. |
| `cnpj`     | `CNPJ`        | `00.000.000/0000-00` | Aceita o CNPJ alfanumérico: letras nas 12 primeiras posições, convertidas para maiúsculas. |
| `cpf-cnpj` | `CPF ou CNPJ` | muda sozinha | Usa a máscara de CPF até 11 dígitos e a de CNPJ a partir do 12º caractere ou da primeira letra. |
| `telefone` | `Telefone`    | `(00) 0000-0000` ou `(00) 00000-0000` | `type="tel"`. Muda para celular no 11º dígito. |
| `dinheiro` | `Valor`       | `1.234,56` | Mostra `R$` antes do campo (troque com `prefix`). Os dígitos entram pela direita, como numa maquininha: `5` vira `0,05`. |
| `cep`      | `CEP`         | `00000-000` | Pode buscar o endereço no ViaCEP (veja abaixo). |
| `uf`       | `Estado`      | não tem | Select com os 27 estados. O valor enviado é a sigla. Use `formato="sigla"` para mostrar `SP` em vez de `São Paulo`. |

Todos aceitam as mesmas props e atributos do [`input`](#input-e-select) (ou do `select`, no caso de `uf`), inclusive `label` para trocar o texto.

**Valor vindo do banco:** passe o valor sem máscara e o componente formata: `value="52998224725"` aparece como `529.982.247-25`. No `dinheiro`, passe o decimal com ponto (`1234.5`), como vem de uma coluna `decimal`, e ele aparece como `1.234,50`.

**JavaScript:** as máscaras são um script pequeno, sem dependências, incluído uma única vez na página pelo primeiro campo com máscara. Ele também funciona com campos adicionados depois do carregamento (Livewire, Alpine). Sem JavaScript, o campo continua funcionando, só que sem máscara, e a validação no servidor continua valendo.

### Busca de CEP

Informe quais campos preencher com a resposta do [ViaCEP](https://viacep.com.br). A chave é o campo do ViaCEP e o valor é o `name` (ou `id`) do campo do formulário:

```blade
<x-ginga::cep
    name="cep"
    :preencher="['logradouro' => 'endereco', 'bairro' => 'bairro', 'localidade' => 'cidade', 'uf' => 'uf']"
    focar="numero"
/>
<x-ginga::input name="endereco" label="Endereço" />
<x-ginga::input name="numero" label="Número" />
<x-ginga::input name="bairro" label="Bairro" />
<x-ginga::input name="cidade" label="Cidade" />
<x-ginga::uf name="uf" />
```

| Prop        | Tipo           | Padrão | Descrição |
|-------------|----------------|--------|-----------|
| `preencher` | `array`        | `[]`   | Campos para preencher. Sem essa prop, o CEP não é buscado. Campos do ViaCEP: `logradouro`, `complemento`, `bairro`, `localidade`, `uf`, `estado`, `ibge`, `ddd`. |
| `focar`     | `string\|null` | `null` | Campo que recebe o foco depois de preencher. Normalmente o número, que o ViaCEP não traz. |

Como funciona:

- **Quando busca:** só quando o 8º dígito é digitado. O mesmo CEP não é buscado duas vezes.
- **CEP inexistente:** o campo fica marcado como inválido com a mensagem "CEP não encontrado.".
- **Sem conexão:** se o ViaCEP estiver fora do ar, nada acontece e o usuário preenche o endereço à mão.
- **Avisos para outros scripts:** os campos preenchidos disparam os eventos `input` e `change`, então `wire:model` e `x-model` percebem a mudança.

### Validação

O Ginga registra as regras `cpf`, `cnpj`, `cpf_cnpj`, `cep`, `telefone` e `uf`. Elas aceitam o valor com ou sem máscara:

```php
$request->validate([
    'cpf' => 'required|cpf',
    'cnpj' => 'nullable|cnpj',          // aceita o CNPJ alfanumérico
    'documento' => 'required|cpf_cnpj',
    'telefone' => 'required|telefone',  // fixo (2-5) ou celular (9), com DDD válido
    'cep' => 'required|cep',
    'uf' => 'required|uf',
]);
```

As mensagens já vêm em português ("O campo :attribute deve ser um CPF válido."). Para trocar, defina `validation.cpf`, `validation.cnpj` etc. no arquivo de tradução da aplicação.

### Exibindo valores formatados

Em tabelas e páginas, use as diretivas para mostrar o valor salvo sem máscara já formatado:

```blade
@cpf($cliente->cpf)            {{-- 529.982.247-25 --}}
@cnpj($empresa->cnpj)          {{-- 12.ABC.345/01DE-35 --}}
@cpfCnpj($cliente->documento)  {{-- CPF ou CNPJ, conforme o tamanho --}}
@cep($endereco->cep)           {{-- 01310-100 --}}
@telefone($cliente->celular)   {{-- (11) 98765-4321 --}}
@dinheiro($pedido->total)      {{-- R$ 1.234,50 --}}
```

O texto sai escapado, como no `{{ }}`. Um valor vazio (`null`) não mostra nada. No PHP, o equivalente é `Mascara::exibir('cpf', $valor)`.

### Salvando sem máscara

Use `Ginga\Support\Mascara::limpar()` para guardar só o valor no banco:

```php
use Ginga\Support\Mascara;

Mascara::limpar('cpf', '529.982.247-25');          // "52998224725"
Mascara::limpar('cnpj', '12.abc.345/01de-35');     // "12ABC34501DE35"
Mascara::limpar('telefone', '(11) 98765-4321');    // "11987654321"
Mascara::limpar('cep', '01310-100');               // "01310100"
Mascara::limpar('dinheiro', '1.234,56');           // "1234.56"
```

Em um Form Request, dá para limpar antes de validar:

```php
protected function prepareForValidation(): void
{
    $this->merge([
        'cpf' => Mascara::limpar('cpf', $this->cpf),
        'valor' => Mascara::limpar('dinheiro', $this->valor),
    ]);
}
```

> **CNPJ no banco:** desde julho de 2026 o CNPJ pode ter letras. Guarde em uma coluna de texto (`string('cnpj', 14)`), não em uma coluna numérica.

## Datatable

Tabela com busca, ordenação e paginação feitas no servidor, direto no banco. Funciona com qualquer quantidade de registros, porque só a página atual é carregada.

**No controller**, a classe `Tabela` lê a query string e monta a consulta:

```php
use Ginga\Support\Tabela;

public function index()
{
    $clientes = Tabela::de(Cliente::query())
        ->buscarEm(['nome', 'email', 'cpf', 'empresa.nome'])
        ->ordenarPor(['nome', 'created_at'], padrao: 'nome')
        ->paginar();

    return view('clientes.index', compact('clientes'));
}
```

**Na view**, você escreve as linhas e o componente cuida do resto:

```blade
<x-ginga::datatable
    :tabela="$clientes"
    placeholder="Buscar por nome, e-mail ou CPF"
    :columns="[
        'nome' => 'Nome',
        'cpf' => 'CPF',
        'mensalidade' => ['label' => 'Mensalidade', 'class' => 'text-end'],
        'created_at' => 'Cliente desde',
        'acoes' => ['label' => 'Ações', 'hidden' => true],
    ]"
>
    @foreach ($clientes as $cliente)
        <tr>
            <td>{{ $cliente->nome }}</td>
            <td>@cpf($cliente->cpf)</td>
            <td class="text-end">@dinheiro($cliente->mensalidade)</td>
            <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
            <td class="text-end">
                <x-ginga::delete-button :action="route('clientes.destroy', $cliente)" :item="$cliente->nome" />
            </td>
        </tr>
    @endforeach
</x-ginga::datatable>
```

A tela fica com:

- **Busca:** um campo que procura nas colunas de `buscarEm()`.
- **Itens por página:** um seletor com 10, 25, 50 ou 100.
- **Ordenação:** cabeçalhos clicáveis nas colunas de `ordenarPor()`, com seta e `aria-sort`.
- **Paginação em português:** com o resumo "Mostrando 1 a 10 de 138 resultados".
- **Lista vazia:** "Nenhum registro encontrado." Quando há busca, "Nenhum resultado para “xyz”." com um link "Limpar busca".

### A classe `Tabela`

| Método | Descrição |
|--------|-----------|
| `Tabela::de($query)` | Recebe um query builder do Eloquent, uma relação (`$empresa->clientes()`) ou um `DB::table()`. |
| `buscarEm(array $colunas)` | Colunas pesquisadas. Use ponto para relacionamentos: `empresa.nome`. |
| `ordenarPor(array $colunas, ?string $padrao, string $direcao = 'asc')` | Colunas que podem ser ordenadas e a ordem inicial. |
| `itensPorPagina(int $padrao, ?array $opcoes)` | Padrão `10`, opções `[10, 25, 50, 100]`. |
| `paginar()` | Executa a consulta. O resultado fica em `$tabela->linhas` (um paginator do Laravel). |

A `Tabela` pode ser percorrida com `@foreach` diretamente. Depois de `paginar()`, o estado lido da URL fica em `$tabela->busca`, `$tabela->ordem`, `$tabela->direcao` e `$tabela->porPagina`.

**Parâmetros da URL:** `?busca=maria&ordenar=nome&direcao=desc&por_pagina=25&pagina=2`. Por isso o endereço pode ser salvo nos favoritos ou compartilhado e abre do mesmo jeito.

**Segurança:** só as colunas de `ordenarPor()` podem ser ordenadas, só as opções de `itensPorPagina()` são aceitas e valores inválidos voltam ao padrão. A busca usa parâmetros do banco (bindings), nunca texto concatenado no SQL.

**Busca em documentos:** quando a busca tem números, a `Tabela` também procura a versão sem pontuação. Assim `529.982.247` encontra o CPF salvo como `52998224725`, e o mesmo vale para CNPJ, CEP e telefone.

**Maiúsculas e minúsculas:** a busca usa `LIKE`, e no PostgreSQL usa `ILIKE`. Nos dois casos, maiúsculas e minúsculas são tratadas como iguais.

### Props do componente

| Prop          | Tipo           | Padrão                         | Descrição |
|---------------|----------------|--------------------------------|-----------|
| `tabela`      | `Tabela`       | —                              | O resultado de `Tabela::de()`. |
| `columns`     | `array`        | `[]`                           | `chave => título`. A chave é a coluna do banco, usada na ordenação. Também aceita `['label' => ..., 'class' => ..., 'hidden' => true]`. `hidden` esconde o título visualmente, mas o leitor de tela continua lendo, o que é útil para a coluna de ações. |
| `search`      | `bool`         | `true`                         | Mostra o campo de busca. |
| `placeholder` | `string`       | `'Buscar...'`                  | Texto de exemplo do campo de busca. |
| `empty`       | `string`       | `'Nenhum registro encontrado.'`| Mensagem da tabela vazia, quando não há busca. |
| `caption`     | `string\|null` | `null`                         | Nome da tabela para leitores de tela (`<caption>` escondido). |
| `id`          | `string`       | `'ginga-datatable'`            | Precisa ser fixo. Com duas tabelas na mesma página, dê um `id` diferente para cada uma. |

### Com e sem JavaScript

Sem JavaScript, tudo funciona com links e um formulário GET comum, e aparece um botão "Buscar".

Com JavaScript, o componente:

- **Busca enquanto a pessoa digita:** com uma pausa de 350 ms, para não consultar a cada tecla.
- **Atualiza sem recarregar a página:** busca, troca de itens por página, ordenação e paginação trocam só a tabela. O campo de busca não perde o foco.
- **Mantém a URL atualizada:** os botões voltar e avançar do navegador funcionam, e Ctrl+clique abre o link em outra aba normalmente.
- **Anuncia o resultado:** o leitor de tela ouve "Mostrando 1 a 10 de 57 resultados" a cada carregamento.

O JavaScript pede a página inteira ao servidor e troca só a tabela. Não precisa de rota extra nem de mudança no controller. Se algo der errado, ele cai para a navegação normal.

## Table

Tabela simples, sem busca, com mensagem automática quando não há registros:

```blade
<x-ginga::table :columns="['Nome', 'E-mail']" empty="Nenhum cliente cadastrado.">
    @foreach ($clientes as $cliente)
        <tr>
            <td>{{ $cliente->nome }}</td>
            <td>{{ $cliente->email }}</td>
        </tr>
    @endforeach
</x-ginga::table>
```

Quando o `@foreach` não gera nenhuma linha, a tabela mostra a mensagem de `empty`. Para usar HTML na mensagem, passe pelo slot `empty`. Para montar um cabeçalho mais elaborado, use o slot `head` no lugar de `columns`.

| Prop      | Tipo           | Padrão                          | Descrição |
|-----------|----------------|---------------------------------|-----------|
| `columns` | `array`        | `[]`                            | Títulos das colunas. |
| `empty`   | `string`       | `'Nenhum registro encontrado.'` | Mensagem da tabela vazia. |
| `caption` | `string\|null` | `null`                          | Nome da tabela para leitores de tela. |
| `striped` | `bool`         | `false`                         | Linhas zebradas. |
| `hover`   | `bool`         | `true`                          | Destaca a linha sob o mouse. |
| `small`   | `bool`         | `false`                         | Linhas mais baixas (`table-sm`). |

## Pagination

Paginação em português para qualquer paginator do Laravel:

```blade
<x-ginga::pagination :paginator="$clientes" />
```

Mostra "Mostrando 1 a 10 de 57 resultados" (desligue com `:summary="false"`) e os links "Anterior", "1 2 3 … 6" e "Próxima". A página atual tem `aria-current="page"`, e os números são lidos como "Página 2". Funciona com `paginate()` e `simplePaginate()`. Com o `simplePaginate()`, aparecem só "Anterior" e "Próxima".

## Badge

```blade
<x-ginga::badge variant="success" pill>Ativo</x-ginga::badge>
<x-ginga::badge variant="danger" subtle>Inativo</x-ginga::badge>
```

| Prop      | Tipo     | Padrão      | Descrição |
|-----------|----------|-------------|-----------|
| `variant` | `string` | `'primary'` | Variante do Bootstrap. |
| `subtle`  | `bool`   | `false`     | Versão suave, com fundo claro e borda, nas mesmas cores do alert. |
| `pill`    | `bool`   | `false`     | Cantos arredondados. |

Não use só a cor para passar a informação: o texto do badge ("Ativo", "Inativo") deve fazer sentido sozinho.

## Card

```blade
<x-ginga::card title="Clientes">
    <x-slot:actions>
        <x-ginga::button size="sm" href="{{ route('clientes.create') }}">Novo cliente</x-ginga::button>
    </x-slot:actions>

    Conteúdo do card.

    <x-slot:footer>Atualizado hoje</x-slot:footer>
</x-ginga::card>
```

| Prop    | Tipo           | Padrão  | Descrição |
|---------|----------------|---------|-----------|
| `title` | `string\|null` | `null`  | Título no cabeçalho. |
| `level` | `int`          | `2`     | Nível do título (`h2`, `h3`...), para manter a hierarquia da página. O tamanho visual não muda. |
| `flush` | `bool`         | `false` | Conteúdo sem o `card-body`, encostado nas bordas. Use com `table` e `list-group`. |

Slots: `actions` (botões à direita do título), `header` (substitui o cabeçalho inteiro) e `footer`.

## Breadcrumb

```blade
<x-ginga::breadcrumb :items="[
    'Início' => route('home'),
    'Clientes' => route('clientes.index'),
    $cliente->nome,
]" />
```

Os itens são `texto => link`. O último é a página atual: não vira link e recebe `aria-current="page"`.

## Desenvolvimento

Os testes usam [Pest](https://pestphp.com) e [Orchestra Testbench](https://packages.tools/testbench), que sobe um Laravel mínimo para testar o pacote:

```bash
composer install
composer test
```

O que os testes cobrem:

- **`tests/Unit`:** validação de CPF, CNPJ (inclusive o alfanumérico), CEP, telefone e UF; máscaras; opções e valores dos campos.
- **`tests/Feature`:** o HTML de cada componente, o `old()` e os erros depois de um envio, a `Tabela` com um banco SQLite em memória, a rota do tema e as diretivas.
- **Compilação:** todo componente e partial precisa virar PHP válido. Esse teste pega erros que só aparecem ao abrir a página, como uma tag `<x-...>` escrita dentro de um comentário JavaScript.

O JavaScript dos componentes (máscaras, datatable, multiselect, exclusão) ainda não tem testes automatizados.

## Licença

[MIT](LICENSE).
