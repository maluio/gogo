var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    .addEntry('js/app', './assets/app/js/app.js')
    .addStyleEntry('css/app', './assets/app/scss/app.scss')

    .addStyleEntry('css/learn', './assets/learn-component/learn.scss')
    .addEntry('js/learn', './assets/learn-component/learn.jsx')

    // uncomment if you use Sass/SCSS files
     .enableSassLoader()

    .enableReactPreset()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
