<?php

use Ginga\Support\Brasil;
use Ginga\Tests\Fixtures\Nivel;
use Illuminate\Support\Facades\Blade;

describe('compilação', function () {
    // Pega erros como "@{{" na URL ou uma tag <x-...> escrita dentro de um comentário JavaScript
    it('compila todos os componentes e partials para PHP válido', function (string $arquivo) {
        $php = Blade::compileString(file_get_contents($arquivo));
        $temporario = tempnam(sys_get_temp_dir(), 'ginga') . '.php';
        file_put_contents($temporario, $php);

        exec('php -l ' . escapeshellarg($temporario) . ' 2>&1', $saida, $codigo);
        unlink($temporario);

        expect($codigo)->toBe(0, implode("\n", $saida));
    })->with(fn () => glob(__DIR__ . '/../../resources/views/{components,partials}/*.blade.php', GLOB_BRACE));
});

describe('input', function () {
    it('liga label, campo e id pelo name', function () {
        $this->blade('<x-ginga::input name="nome" label="Nome" required />')
            ->assertSee('for="campo-nome"', false)
            ->assertSee('id="campo-nome" name="nome"', false)
            ->assertSee('<span class="text-danger" aria-hidden="true">*</span>', false);
    });

    it('mostra o erro e o valor digitado depois de um envio com erro', function () {
        $this->comEnvio(['nome' => 'Ma'], ['nome' => 'Nome muito curto.'])
            ->blade('<x-ginga::input name="nome" />')
            ->assertSee('class="form-control is-invalid"', false)
            ->assertSee('value="Ma"', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('aria-describedby="campo-nome-erro"', false)
            ->assertSee('Nome muito curto.');
    });

    it('nunca devolve a senha', function () {
        $this->comEnvio(['senha' => 'segredo'])
            ->blade('<x-ginga::input type="password" name="senha" />')
            ->assertDontSee('segredo');
    });

    it('aceita datas do Eloquent', function () {
        $this->blade('<x-ginga::input type="date" name="d" :value="$data" />', ['data' => now()->setDate(2026, 10, 7)])
            ->assertSee('value="2026-10-07"', false);
    });
});

describe('campos brasileiros', function () {
    it('formata o valor do banco e liga a máscara', function () {
        $this->blade('<x-ginga::cpf name="cpf" value="52998224725" />')
            ->assertSee('value="529.982.247-25"', false)
            ->assertSee('data-ginga-mascara="cpf"', false)
            ->assertSee('inputmode="numeric"', false)
            ->assertSeeText('CPF');
    });

    it('mostra R$ no dinheiro', function () {
        $this->blade('<x-ginga::dinheiro name="valor" value="1234.5" />')
            ->assertSee('<span class="input-group-text">R$</span>', false)
            ->assertSee('value="1.234,50"', false);
    });

    it('configura a busca de CEP', function () {
        $this->blade('<x-ginga::cep name="cep" :preencher="[\'uf\' => \'estado\']" focar="numero" />')
            ->assertSee('data-ginga-cep="{&quot;uf&quot;:&quot;estado&quot;}"', false)
            ->assertSee('data-ginga-cep-focar="numero"', false);
    });

    it('inclui o script das máscaras uma única vez', function () {
        $html = (string) $this->blade('<x-ginga::cpf name="a" /><x-ginga::cep name="b" /><x-ginga::telefone name="c" />');

        expect(substr_count($html, 'window.gingaMascara = true'))->toBe(1);
    });

    it('lista os 27 estados', function () {
        $html = (string) $this->blade('<x-ginga::uf name="uf" value="SP" />');

        expect(substr_count($html, '<option value="'))->toBe(28) // 27 + "Selecione"
            ->and($html)->toContain('<option value="SP" selected>São Paulo</option>');
    });
});

describe('select', function () {
    it('aceita enum como opções e como valor', function () {
        $this->blade('<x-ginga::select name="nivel" :options="$opcoes" :value="$valor" />', ['opcoes' => Nivel::class, 'valor' => Nivel::Avancado])
            ->assertSee('<option value="avancado" selected>Avançado</option>', false);
    });

    it('funciona como múltiplo', function () {
        $html = (string) $this->blade('<x-ginga::select name="tags" multiple :options="[\'php\', \'js\']" :value="[\'js\']" />');

        expect($html)->toContain('name="tags[]"')
            ->toContain('<option value="js" selected>')
            ->toContain('<option value="php" >');
    });
});

describe('checkbox e grupos', function () {
    it('envia "0" quando o checkbox está desmarcado', function () {
        $this->blade('<x-ginga::checkbox name="aceite" label="Aceito" />')
            ->assertSee('<input type="hidden" name="aceite" value="0">', false);
    });

    it('não cria o hidden em campos desabilitados', function () {
        $this->blade('<x-ginga::switch name="beta" label="Beta" disabled />')
            ->assertDontSee('type="hidden"', false)
            ->assertSee('role="switch"', false);
    });

    it('volta desmarcado depois de um envio com erro', function () {
        $this->comEnvio(['aceite' => '0'])
            ->blade('<x-ginga::checkbox name="aceite" label="Aceito" checked />')
            ->assertDontSee('checked="checked"', false);
    });

    it('usa o id no fieldset, sem repetir nas opções', function () {
        $html = (string) $this->blade('<x-ginga::checkbox-group id="grupo" name="a" :options="[\'A\', \'B\']" />');

        expect($html)->toContain('<fieldset class="mb-3" id="grupo"')
            ->toContain('id="grupo-0"')
            ->toContain('id="grupo-1"')
            ->toContain('name="a[]"');
    });

    it('mostra erros dos itens da lista', function () {
        $this->comEnvio([], ['interesses.0' => 'Opção inválida.'])
            ->blade('<x-ginga::checkbox-group name="interesses" :options="[\'A\']" />')
            ->assertSee('Opção inválida.');
    });
});

describe('multiselect', function () {
    it('mostra os escolhidos como etiquetas e campos enviados', function () {
        $html = (string) $this->blade('<x-ginga::multiselect name="estados" label="Estados" :options="$estados" :value="[\'SP\', \'RJ\']" />', ['estados' => Brasil::ESTADOS]);

        expect($html)->toContain('<input type="hidden" name="estados[]" value="RJ">')
            ->toContain('<input type="hidden" name="estados[]" value="SP">')
            ->toContain('aria-label="Remover São Paulo"')
            ->toContain('2 selecionados')
            ->toContain('data-ginga-multiselect-busca'); // mais de 8 opções: busca automática

        // As caixas do modal não têm name: só valem depois de "Inserir"
        expect($html)->not->toMatch('/type="checkbox"[^>]*name=/');
    });

    it('volta com o que foi enviado depois de um erro', function () {
        $this->comEnvio(['estados' => ['BA']])
            ->blade('<x-ginga::multiselect name="estados" :options="$estados" :value="[\'SP\']" />', ['estados' => Brasil::ESTADOS])
            ->assertSee('value="BA"', false)
            ->assertDontSee('name="estados[]" value="SP"', false);
    });
});

describe('mensagens', function () {
    it('o flash mostra as mensagens da sessão', function () {
        $this->app['request']->setLaravelSession($this->app['session.store']);
        session()->put('sucesso', 'Cliente salvo.');
        session()->put('error', 'Falhou.');

        $html = (string) $this->blade('<x-ginga::flash />');

        expect($html)->toContain('Cliente salvo.')
            ->toContain('bg-success-subtle')
            ->toContain('Falhou.')
            ->toContain('data-bs-delay="8000"');
    });

    it('o flash conta os campos com erro', function () {
        $this->comEnvio([], ['nome' => ['Obrigatório.', 'Curto.'], 'cpf' => 'Inválido.'])
            ->blade('<x-ginga::flash />')
            ->assertSee('Corrija os 2 campos destacados.');
    });

    it('o flash não renderiza nada sem mensagens', function () {
        expect(trim((string) $this->blade('<x-ginga::flash />')))->toBe('');
    });

    it('o toast de erro interrompe o leitor de tela', function () {
        $this->blade('<x-ginga::toast variant="danger">Erro</x-ginga::toast>')
            ->assertSee('role="alert" aria-live="assertive"', false);
    });
});

describe('exclusão', function () {
    it('o botão traz o modal padrão e o script uma única vez', function () {
        $html = (string) $this->blade('<x-ginga::delete-button action="/clientes/1" item="Maria" /><x-ginga::delete-button action="/clientes/2" item="João" />');

        expect(substr_count($html, '<template data-ginga-exclusao-modelo'))->toBe(1)
            ->and(substr_count($html, 'window.gingaExclusao = true'))->toBe(1)
            ->and($html)->toContain('data-ginga-excluir="/clientes/1"')
            ->toContain('<span class="visually-hidden">Maria</span>');

        // O script vem antes do modelo: dentro do <template> ele não seria executado
        expect(strpos($html, 'window.gingaExclusao'))->toBeLessThan(strpos($html, '<template'));
    });
});

describe('layout', function () {
    it('o styles carrega o tema com versão na URL', function () {
        $this->blade('<x-ginga::styles icons />')
            ->assertSee('bootstrap@5.3.3/dist/css/bootstrap.min.css', false)
            ->assertSee('bootstrap-icons@', false)
            ->assertSee('/_ginga/tema.css?v=', false);
    });

    it('a rota do tema entrega o CSS com cache longo', function () {
        $this->get('/_ginga/tema.css')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/css; charset=UTF-8')
            ->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');
    });

    it('as diretivas exibem valores formatados', function () {
        expect(compactar(Blade::render('@cpf($a) | @dinheiro($b) | @telefone($c) | [@cep(null)]', ['a' => '52998224725', 'b' => 49.9, 'c' => '1134567890'])))
            ->toBe("529.982.247-25 | R$\u{00A0}49,90 | (11) 3456-7890 | []");
    });
});
