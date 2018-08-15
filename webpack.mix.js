let mix = require('laravel-mix');

mix.js('resources/assets/js/browser.js', 'public/js')
    .js('resources/assets/js/tags.js', 'public/js')
    .js('resources/assets/js/images.js', 'public/js')
    .sass('resources/assets/sass/application.scss', 'public/css');
