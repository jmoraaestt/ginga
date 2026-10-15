<x-docs.pagina :pagina="$pagina">
    <x-docs.secao titulo="Regras de validação" />
    <p>O Ginga registra as regras <code>cpf</code>, <code>cnpj</code>, <code>cpf_cnpj</code>, <code>cep</code>, <code>telefone</code> e <code>uf</code>. Elas aceitam o valor com ou sem máscara.</p>

    <x-docs.codigo lang="php">
        $request->validate([
            'cpf' => 'required|cpf',
            'cnpj' => 'nullable|cnpj',          // aceita o CNPJ alfanumérico
            'documento' => 'required|cpf_cnpj',
            'telefone' => 'required|telefone',  // fixo (2-5) ou celular (9), com DDD válido
            'cep' => 'required|cep',
            'uf' => 'required|uf',
        ]);
    </x-docs.codigo>

    <p>As mensagens já vêm em português (“O campo :attribute deve ser um CPF válido.”). Para trocar, defina <code>validation.cpf</code>, <code>validation.cnpj</code> etc. no arquivo de tradução da aplicação.</p>

    <x-docs.secao titulo="Exibindo valores formatados" />
    <p>Em tabelas e páginas, as diretivas mostram o valor salvo sem máscara já formatado. O texto sai escapado, como no <code>@{{ }}</code>, e um valor vazio não mostra nada.</p>
    <x-docs.exemplo arquivo="validacao.diretivas" />
    <p>No PHP, o equivalente é <code>Mascara::exibir('cpf', $valor)</code>.</p>

    <x-docs.secao titulo="Salvando sem máscara" />
    <p>Use <code>Mascara::limpar()</code> para guardar só o valor no banco:</p>
    <x-docs.codigo lang="php">
        use Ginga\Support\Mascara;

        Mascara::limpar('cpf', '529.982.247-25');          // "52998224725"
        Mascara::limpar('cnpj', '12.abc.345/01de-35');     // "12ABC34501DE35"
        Mascara::limpar('telefone', '(11) 98765-4321');    // "11987654321"
        Mascara::limpar('cep', '01310-100');               // "01310100"
        Mascara::limpar('dinheiro', '1.234,56');           // "1234.56"
    </x-docs.codigo>

    <p>Em um Form Request, dá para limpar antes de validar:</p>
    <x-docs.codigo lang="php">
        protected function prepareForValidation(): void
        {
            $this->merge([
                'cpf' => Mascara::limpar('cpf', $this->cpf),
                'valor' => Mascara::limpar('dinheiro', $this->valor),
            ]);
        }
    </x-docs.codigo>
</x-docs.pagina>
