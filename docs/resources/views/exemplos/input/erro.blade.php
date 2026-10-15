{{-- oculto --}}
@php \App\Docs\Exemplos::erros(['contato' => 'O campo e-mail deve ser um endereço de e-mail válido.'], ['contato' => 'maria@']); @endphp
{{-- /oculto --}}
{{-- Depois de um envio com erro: nada a fazer na view --}}
<x-ginga::input name="contato" type="email" label="E-mail" required />
