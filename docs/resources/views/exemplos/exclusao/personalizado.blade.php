<x-ginga::delete-button action="/arquivos/7" item="relatorio-2026.pdf" modal="excluir-arquivo" variant="danger" :size="null">
    <i class="bi bi-trash3 me-1" aria-hidden="true"></i> Excluir arquivo
</x-ginga::delete-button>

<x-ginga::confirm-delete id="excluir-arquivo" title="Excluir arquivo?" confirm="Sim, excluir">
    O arquivo <strong data-ginga-exclusao-item>este arquivo</strong> some para todos da equipe.
</x-ginga::confirm-delete>
