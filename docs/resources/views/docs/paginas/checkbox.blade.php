<x-docs.pagina :pagina="$pagina">
    <p>Um checkbox sozinho, para “sim ou não”. Para usar HTML no texto (um link, por exemplo), passe pelo slot.</p>
    <x-docs.exemplo arquivo="checkbox.basico" />

    <x-docs.secao titulo="Switch" />
    <p><code>&lt;x-ginga::switch&gt;</code> é o mesmo checkbox com visual de interruptor e <code>role="switch"</code>.</p>
    <x-docs.exemplo arquivo="checkbox.switch" />

    <x-docs.secao titulo="Por que o hidden com “0”?" />
    <p>O navegador não envia checkbox desmarcado. Sem o hidden, desmarcar a newsletter numa edição não chegaria ao servidor, e o valor salvo continuaria <code>true</code>. Com ele, <code>$request->boolean('newsletter')</code> retorna <code>false</code>.</p>
    <x-docs.codigo lang="html">
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="newsletter" value="0">
            <input class="form-check-input" type="checkbox" role="switch" id="campo-newsletter" name="newsletter" value="1" checked="checked">
            <label class="form-check-label" for="campo-newsletter">Receber novidades por e-mail</label>
            <div class="invalid-feedback" id="campo-newsletter-erro"></div>
        </div>
    </x-docs.codigo>
    <p>O hidden não é criado em campos <code>disabled</code>, para não sobrescrever o valor salvo, nem em listas (<code>name="itens[]"</code>).</p>

    <x-docs.secao titulo="Depois de um erro" />
    <p>Para exigir o aceite, use a regra <code>accepted</code>: ela recusa o <code>"0"</code>.</p>
    <x-docs.exemplo arquivo="checkbox.erro" />

    <x-docs.secao titulo="Props" />
    <x-docs.props :itens="[
        ['name', 'string|null', 'null', 'Nome do campo.'],
        ['label', 'string|null', 'null', 'Texto do label. Para HTML, use o slot.'],
        ['value', 'string', '1', 'Valor enviado quando marcado.'],
        ['checked', 'bool', 'false', 'Marcado ao abrir a página. Depois de um envio com erro, vale o que foi enviado.'],
        ['uncheckedValue', 'string|null', '0', 'Valor enviado quando desmarcado. `null` desliga o hidden.'],
        ['switch', 'bool', 'false', 'Visual de interruptor. O componente `switch` já vem com ele ligado.'],
        ['help', 'string|null', 'null', 'Texto de ajuda.'],
    ]" />
</x-docs.pagina>
