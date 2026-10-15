<?php

namespace App\Console\Commands;

use App\Docs\Navegacao;
use App\Docs\Pagina;
use Ginga\Ginga;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Gera o site estático para o GitHub Pages: cada página vira um index.html,
 * renderizado pelo próprio Laravel, com os componentes reais do Ginga.
 */
#[Signature('docs:build {--url=http://localhost:8000 : Endereço público do site, ex.: https://usuario.github.io/ginga} {--saida=dist : Pasta de saída}')]
#[Description('Gera a documentação como site estático')]
class ConstruirDocs extends Command
{
    public function handle(Filesystem $arquivos, Kernel $kernel): int
    {
        $url = rtrim($this->option('url'), '/');
        $saida = base_path($this->option('saida'));

        // Sem sessão persistente e com o aviso de "site estático" nos exemplos que dependem do servidor
        config(['session.driver' => 'array', 'docs.estatico' => true, 'app.url' => $url]);

        $arquivos->deleteDirectory($saida);
        $arquivos->ensureDirectoryExists($saida);

        $paginas = collect(['/' => 'index.html'])
            ->merge(Navegacao::paginas()->mapWithKeys(fn (Pagina $pagina) => ["/{$pagina->slug}" => "{$pagina->slug}/index.html"]));

        $this->components->info("Gerando {$paginas->count()} páginas para {$url}");

        foreach ($paginas as $caminho => $destino) {
            $this->components->task($caminho, function () use ($kernel, $url, $caminho, $saida, $destino, $arquivos) {
                $arquivos->ensureDirectoryExists(dirname("{$saida}/{$destino}"));
                $arquivos->put("{$saida}/{$destino}", $this->renderizar($kernel, $url, $caminho, 200));
            });
        }

        $this->components->task('404.html', fn () => $arquivos->put("{$saida}/404.html", $this->renderizar($kernel, $url, '/pagina-que-nao-existe', 404)));

        $this->components->task('Arquivos estáticos', function () use ($arquivos, $saida) {
            $arquivos->copyDirectory(public_path('docs'), "{$saida}/docs");
            // Mesmo caminho da rota do tema (/_ginga/tema.css): o HTML não precisa mudar
            $arquivos->ensureDirectoryExists("{$saida}/_ginga");
            $arquivos->copy(Ginga::TEMA, "{$saida}/_ginga/tema.css");
            // Sem Jekyll: ele ignoraria a pasta _ginga
            $arquivos->put("{$saida}/.nojekyll", '');
        });

        $this->newLine();
        $this->components->info('Site gerado em ' . $this->option('saida') . '/. Para ver: php -S localhost:8000 -t ' . $this->option('saida'));

        return self::SUCCESS;
    }

    /**
     * Faz uma requisição interna, como se viesse do endereço público.
     * O SCRIPT_NAME com o subdiretório (ex.: /ginga) faz route() e asset() gerarem os links certos.
     */
    private function renderizar(Kernel $kernel, string $url, string $caminho, int $esperado): string
    {
        $base = parse_url($url, PHP_URL_PATH) ?? '';

        $requisicao = Request::create($url . $caminho, server: [
            'SCRIPT_NAME' => "{$base}/index.php",
            'SCRIPT_FILENAME' => public_path('index.php'),
            'PHP_SELF' => "{$base}/index.php",
        ]);

        $resposta = $kernel->handle($requisicao);
        $kernel->terminate($requisicao, $resposta);

        if ($resposta->getStatusCode() !== $esperado) {
            throw new RuntimeException("{$caminho} respondeu {$resposta->getStatusCode()}, esperado {$esperado}.");
        }

        return $resposta->getContent();
    }
}
