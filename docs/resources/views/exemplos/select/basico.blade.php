<x-ginga::select
    name="plano"
    label="Plano"
    placeholder="Selecione"
    :options="['basico' => 'Básico', 'pro' => 'Profissional', 'empresa' => 'Empresa']"
    required
/>
