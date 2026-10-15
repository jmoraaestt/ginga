# Documentação do Ginga

Site de documentação do pacote, feito em Laravel com os próprios componentes do Ginga. Cada exemplo é renderizado pelo componente real e o código mostrado é lido do mesmo arquivo, então os dois nunca ficam diferentes.

## Rodar localmente

```bash
cd docs
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

O pacote é instalado a partir da pasta acima (`"url": "../"` no `composer.json`): mudanças nos componentes aparecem na hora.

Rodando com o servidor, a datatable busca, ordena e pagina de verdade, num SQLite em memória com dados fictícios. No site estático ela mostra só o primeiro carregamento.

## Gerar o site estático

```bash
php artisan docs:build --url=https://jmoraaestt.github.io/ginga
php -S localhost:8000 -t dist   # para conferir; use --url=http://localhost:8000
```

O GitHub Actions (`.github/workflows/docs.yml`) faz isso a cada push na `main` e publica no GitHub Pages. Na primeira vez, ative em **Settings > Pages > Source: GitHub Actions**.

## Onde fica cada coisa

| Caminho | O que é |
|---------|---------|
| `app/Docs/Navegacao.php` | Menu, ordem das páginas, busca e anterior/próxima. Página nova começa aqui. |
| `resources/views/docs/paginas/` | Uma view por página. |
| `resources/views/exemplos/` | Exemplos vivos, usados com `<x-docs.exemplo arquivo="pasta.nome" />`. |
| `resources/views/components/docs/` | Peças do site: layout, página, exemplo, código, props, nota, seção. |
| `app/Docs/Exemplos.php` | Dados fictícios: clientes da datatable, paginator e erros de validação simulados. |
| `app/Console/Commands/ConstruirDocs.php` | O `docs:build`. |
| `public/docs/` | CSS e JavaScript do site. |

### Escrevendo um exemplo

```blade
{{-- resources/views/exemplos/button/novo.blade.php --}}
<x-ginga::button variant="success">Salvar</x-ginga::button>
```

```blade
{{-- na página --}}
<x-docs.exemplo arquivo="button.novo" centro />
```

Trechos entre `{{-- oculto --}}` e `{{-- /oculto --}}` rodam, mas não aparecem no código mostrado. Use para preparar dados:

```blade
{{-- oculto --}}
@php \App\Docs\Exemplos::erros(['email' => 'O campo e-mail é obrigatório.']); @endphp
{{-- /oculto --}}
<x-ginga::input name="email" label="E-mail" />
```

Os nomes dos campos precisam ser únicos na página: o erro simulado vale para todo campo com aquele `name`.

Para mostrar código sem executar, use `<x-docs.codigo>` com `@verbatim` em volta do Blade.
