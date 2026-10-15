<x-ginga::select
    name="turnos"
    label="Turnos disponíveis"
    multiple
    size="3"
    :options="['Manhã', 'Tarde', 'Noite']"
    :value="['Manhã', 'Noite']"
    help="Segure Ctrl (ou Cmd) para escolher mais de um."
/>
