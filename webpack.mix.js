const mix = require('laravel-mix');

mix.sass('assets/scss/style.scss', 'assets/css/style.css')
.sass('assets/scss/style-admin.scss', 'assets/css/style-admin.css');