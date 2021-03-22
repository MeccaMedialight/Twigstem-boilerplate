const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

//require('laravel-mix-purgecss');
mix.postCss('./src/tailwind.css', './main.css')
    .options({
        processCssUrls: true,
        postCss: [
            tailwindcss('./tailwind.config.js')
        ],
    });
//mix.copyDirectory()
mix.js('src/app.js', 'dist').setPublicPath('./public/dist');