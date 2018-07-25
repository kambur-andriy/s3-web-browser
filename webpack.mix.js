let mix = require('laravel-mix');

mix.js('resources/assets/js/application.js', 'public/js')
    .sass('resources/assets/sass/application.scss', 'public/css');
