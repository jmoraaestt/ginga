{{--
    Tabela de props: cada item é [nome, tipo, padrão, descrição]. A descrição aceita `código` e **negrito**.
--}}
@props([
    'itens' => [],
    'colunas' => ['Prop', 'Tipo', 'Padrão', 'Descrição'],
    'legenda' => 'Props',
])

@use('Illuminate\Support\Str')

<div class="docs-tabela">
    <x-ginga::table :columns="$colunas" :caption="$legenda" :hover="false">
    @foreach ($itens as $item)
        <tr>
            <td><code class="docs-prop">{{ $item[0] }}</code></td>
        @if (count($colunas) === 4)
            <td><code class="docs-tipo">{{ $item[1] }}</code></td>
            <td>@if ($item[2] === '—')<span class="text-body-tertiary">—</span>@else<code>{{ $item[2] }}</code>@endif</td>
            <td>{!! Str::inlineMarkdown($item[3], ['html_input' => 'escape']) !!}</td>
        @else
            <td>{!! Str::inlineMarkdown($item[1], ['html_input' => 'escape']) !!}</td>
        @endif
        </tr>
    @endforeach
    </x-ginga::table>
</div>
