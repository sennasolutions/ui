const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/alpine.js', './dist/js')
    .js('resources/js/codemirror.js', './dist/js')
    .js('resources/js/helpers.js', './dist/js')
    .postCss('resources/css/codemirror.css', './dist/css')
    .postCss('resources/css/tailwind.css', './dist/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .postCss('resources/css/ui.css', './dist/css', [
        require('postcss-import'),
        require('tailwindcss'),
        // nesting
        require('postcss-nested'),
    ]);
