var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
  mix.styles([
    'jquery.bxslider.css',
    'fullcalendar.css',
    'style.css',
    'xeditable.css',
    'pnotify.custom.min.css',
    'ng-tags-input.min.css',
    'jquery.datetimepicker.css',
    'datetimepicker.css',
    'angular-snap.min.css',
    'textangular.min.css'
  ], 'public/assets/css/asset-styles.css');

  mix.sass(['admin-form.scss','style.scss'], 'public/assets/css/style.css');
});
