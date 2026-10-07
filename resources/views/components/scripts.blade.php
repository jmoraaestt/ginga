{{--
    Antes do </body>: JavaScript do Bootstrap, usado por modal, toast, alert com botão de fechar e confirmação de exclusão.
    Com Vite, não use este componente: importe o Bootstrap e exponha em window.bootstrap
--}}
@use('Ginga\Ginga')

<script src="{{ Ginga::bootstrapJs() }}"></script>
