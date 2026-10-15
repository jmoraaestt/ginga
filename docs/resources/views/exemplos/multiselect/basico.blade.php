<x-ginga::multiselect
    name="estados"
    label="Estados onde atende"
    title="Escolher estados"
    :options="\Ginga\Support\Brasil::ESTADOS"
    :value="['SP', 'RJ', 'MG']"
/>
