# Publish resources
php artisan senna-ui:install


# Add to head of a layout
<link href="{{ asset("senna-ui/css/ui.css") }}" rel="stylesheet">
<x-senna.ui.theme></x-senna.ui.theme>

Be sure to use the layout as a component or extend it properly, this way the push/stack mechanism works.
