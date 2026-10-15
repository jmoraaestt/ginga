{{-- oculto --}}
@php \App\Docs\Exemplos::erros(['aceite' => 'Você precisa aceitar a política de privacidade.']); @endphp
{{-- /oculto --}}
<x-ginga::checkbox name="aceite" label="Aceito a política de privacidade" required />
