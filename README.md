# Installation

Install the package with composer and publish the assets with the senna-ui:install command:

```
composer require senna/ui
```
```
php artisan senna-ui:install
```

```
<head>
    ..
<x-senna.ui.head />
</head>

Add the head component to your layouts page head-tag. This will include the styles and the theme. Also add the footer component before your layouts closing body tag. This will include the js dependencies when needed by a component.

<body>
    ..
<x-senna.ui.footer />
</body>
```