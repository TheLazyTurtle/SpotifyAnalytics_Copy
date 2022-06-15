const mix = require('laravel-mix');
const path = require('path');
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
mix.webpackConfig({
    resolve: {
        alias: {
            //adding react and react-dom may not be necessary for you but it did fix some issues in my setup.
            'react': path.resolve('./node_modules/react'),
            'react-dom': path.resolve('./node_modules/react-dom'),
        },
    },
});
mix.ts('resources/js/src/App.tsx', 'public/js/app.js').react()
// mix.postCss('resources/js/src/App.css', 'public/css/app.css', [])
