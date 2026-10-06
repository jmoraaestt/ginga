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
