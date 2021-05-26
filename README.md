# Laravel blade components

This is a package of blade components that I often use in my projects. It's called senna/ui on packagist. 
Livewire users: all of the components are wire-modelable via wire:model.

## Installation

Install the package with composer and publish the assets with the senna-ui:install command:

```
composer require senna/ui
```
```
php artisan senna-ui:install
```

Add the head component to your layouts page head-tag. This will include the styles and the theme. Also add the footer component before your layouts closing body tag. This will include the js dependencies when needed by a component.

```
<head>
    ..
<x-senna.ui.head />
</head>

<body>
    ..
<x-senna.ui.footer />
</body>
```

## Documentation

You can preview the components and see the full documenation on: https://getsenna.com/senna-ui/v1