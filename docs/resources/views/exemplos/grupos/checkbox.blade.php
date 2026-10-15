<x-ginga::checkbox-group
    name="interesses"
    label="Interesses"
    inline
    :options="['danca' => 'Dança', 'musica' => 'Música', 'capoeira' => 'Capoeira']"
    :value="['musica']"
/>

<x-ginga::checkbox-group
    name="avisos"
    label="Avisar por"
    switch
    :options="['email' => 'E-mail', 'whatsapp' => 'WhatsApp', 'sms' => 'SMS']"
    :value="['email', 'whatsapp']"
/>
