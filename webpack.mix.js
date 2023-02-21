const mix = require('laravel-mix');

if (mix.inProduction()) {
    mix.js('resources/js/app.js', 'public/js/app.js').vue().sourceMaps().version();
    mix.sass('resources/sass/app.scss', 'public/css/app.css', {}).sourceMaps().version();
} else {
    mix.js('resources/js/app.js', 'public/js/app.js').vue().sourceMaps().version();
    mix.sass('resources/sass/app.scss', 'public/css/app.css', {}).sourceMaps().version();
}

