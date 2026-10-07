# ginga

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

O componente usa apenas classes do Bootstrap. As cores vêm do tema da sua aplicação ou do tema opcional do Ginga:

```bash
php artisan vendor:publish --tag=ginga-assets
```

Inclua o tema **depois** do CSS do Bootstrap, senão as regras do Bootstrap prevalecem:

```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('vendor/ginga/ginga-theme.css') }}">
```

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

### Exemplo: sucesso e erros de validação

```blade
<x-ginga::toast-container>
    @if (session('sucesso'))
        <x-ginga::toast variant="success">
            <x-slot:icon>
                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            </x-slot:icon>
            {{ session('sucesso') }}
        </x-ginga::toast>
    @endif

    @if ($errors->any())
        <x-ginga::toast variant="danger" title="Não foi possível salvar" :delay="8000">
            <x-slot:icon>
                <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
            </x-slot:icon>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </x-ginga::toast>
    @endif
</x-ginga::toast-container>
```

Coloque o container uma única vez no layout, por exemplo logo depois da abertura do `<body>`, e todas as páginas passam a exibir as mensagens.

> **Erros que somem:** quem lê devagar ou usa leitor de tela pode não conseguir ler a mensagem a tempo. Para erros, use um `delay` maior ou `:autohide="false"`. Para erros de validação, mantenha também a indicação no próprio campo (`is-invalid` e `invalid-feedback`).

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
| `value`  | `mixed`        | `null`   | Valor inicial. O `old()` tem prioridade depois de um envio com erro. |
| `help`   | `string\|null` | `null`   | Texto de ajuda embaixo do campo (`form-text`). |
| `mask`   | `string\|null` | `null`   | Máscara: `cpf`, `cnpj`, `cpf-cnpj`, `cep`, `telefone` ou `dinheiro`. Veja [Campos brasileiros](#campos-brasileiros). |
| `prefix` | `string\|null` | `null`   | Texto antes do campo, em um `input-group` (ex.: `R$`). |
| `suffix` | `string\|null` | `null`   | Texto depois do campo, em um `input-group` (ex.: `kg`). |

### Props do `select`

| Prop          | Tipo           | Padrão | Descrição |
|---------------|----------------|--------|-----------|
| `name`        | `string\|null` | `null` | Nome do campo. |
| `label`       | `string\|null` | `null` | Texto do label. |
| `options`     | `array`        | `[]`   | Opções no formato `valor => texto`. |
| `value`       | `mixed`        | `null` | Valor selecionado. O `old()` tem prioridade. |
| `placeholder` | `string\|null` | `null` | Primeira opção, com valor vazio (ex.: `Selecione`). |
| `help`        | `string\|null` | `null` | Texto de ajuda embaixo do campo. |

```blade
<x-ginga::select name="plano" label="Plano" placeholder="Selecione" :options="['basico' => 'Básico', 'pro' => 'Profissional']" />
```

Qualquer outro atributo (`required`, `autofocus`, `wire:model`...) vai para o `<input>` ou `<select>`. O componente vem com `mb-3` em volta.

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
