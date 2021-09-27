# Laravel blade components

This is a package of blade components that I often use in my projects. It's called senna/ui on packagist. 
Livewire users: all of the components are wire-modelable via wire:model.

## Installation

Install the package with composer and publish the assets with the senna-ui:install command:

```
composer require senna/ui
```
```bash
php artisan senna-ui:install     # Copies the config file, and creates a symlinkt assets in public dir
php artisan senna-ui:link        # (Optional) Makes a link to resources/components/senna so that vscode "goto view" plugin can navigate to the component
```

Add the head component to your layouts page head-tag. This will include the styles and the theme. Also add the footer component before your layouts closing body tag. This will include the js dependencies when needed by a component.

```blade
<head>
    ..
@include("senna.ui::theme")
@include("senna.ui::styles")
</head>

<body>
    ..
@include("senna.ui::scripts")
</body>
```

If not allready done so, add tailwind to your laravel project:
https://tailwindcss.com/docs/guides/laravel

Change your tailwind.config.js like this:

```js
    ..
    mode: 'jit',
    purge: [
        // The purge paths of your laravel project:
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',

        // ADD THIS LINE The senna/ui purge path:
        ...require('./vendor/senna/ui/tailwind.purge'),
    ],
    theme: {
        extend: {
            colors: {
                // ADD THIS LINE The senna/ui colors path:
                ...require('./vendor/senna/ui/tailwind.colors'),
            }
        },
    },
    ..
```

Run ``yarn dev`` or ``yarn watch``

## Documentation

You can preview the components and see the full documenation on: https://getsenna.com/senna-ui/v1
