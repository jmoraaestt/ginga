<x-ginga::multiselect
    name="modalidades"
    label="Modalidades"
    :options="['capoeira' => 'Capoeira', 'samba' => 'Samba', 'forro' => 'Forró', 'frevo' => 'Frevo']"
    placeholder="Escolha as modalidades"
    help="Sem busca: aparece sozinha quando há mais de 8 opções."
    required
/>
