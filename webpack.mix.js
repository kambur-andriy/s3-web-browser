let mix = require('laravel-mix');

mix.js('resources/assets/js/browser.js', 'public/js')
    .js('resources/assets/js/account.js', 'public/js')
    .sass('resources/assets/sass/application.scss', 'public/css');
