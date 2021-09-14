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

// Конфигурация "Webpack"
mix.options({

    // Отключение генерации файла "mix-manifest.json"
    manifest: false,

    // Отключение генерации файла "*.js.LICENSE.txt"
    terser: {
        extractComments: false,
    },

    // Перенос ресурсов из папки "node_modules"
    processCssUrls: true,
    fileLoaderDirs: {
        fonts: 'assets/fonts',
        images: 'assets/media'
    }
});

// Компиляция ресурсов
mix.sass('resources/assets/styles/app.scss', 'public/assets/styles/app.css');
mix.copy('resources/assets/media', 'public/assets/media');
mix.js('resources/assets/scripts/app.js', 'public/assets/scripts/app.js');
