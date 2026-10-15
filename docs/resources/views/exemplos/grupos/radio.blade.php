<x-ginga::radio-group
    name="assinatura"
    label="Plano"
    required
    :options="['basico' => 'Básico', 'pro' => 'Profissional', 'empresa' => 'Empresa']"
    value="pro"
/>
