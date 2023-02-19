const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js/app.js').vue().sourceMaps();
mix.sass('resources/sass/app.scss', 'public/css/app.css', {}).sourceMaps();
