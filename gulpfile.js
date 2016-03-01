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

  mix.scripts([
    'vendor/angular/angular-route.min.js',
    'vendor/angular/angular-cookies.min.js',
    'vendor/ui-bootstrap-tpls-0.13.3.min.js',
    'vendor/angular/angular-sanitize.min.js',
    'vendor/textangular.min.js',
    'vendor/angular-img-cropper.min.js',
    'vendor/angular-idle.min.js',
    'vendor/angular-google-analytics.min.js',
    'vendor/ngRemoteValidate.0.4.1.min.js',
    'vendor/ui-utils.min.js',
    'vendor/ui-bootstrap-tpls-0.13.1.js',
    'vendor/snap.min.js',
    'vendor/angular-snap.min.js',
    'vendor/angular-filter.js',
    'vendor/angular-facebook.js',
    'vendor/angular-google-maps.min.js',
    'vendor/http-auth-interceptor.js',
    'vendor/satellizer.min.js',
    'vendor/ng-tags-input.min.js',
    'vendor/checklist-model.js',
    'vendor/datetimepicker.js',
    'vendor/xeditable.js'
  ], 'public/assets/js/angular-libs.js')
    .scripts([
      'vendor/md5.min.js',
      'vendor/jquery.datetimepicker.js',
      'vendor/moment.min.js',
      'vendor/fullcalendar.js',
      'vendor/calendar.js',
      'vendor/pnotify.custom.min.js',
    ], 'public/assets/js/libs.js')
    .scripts([
      'app/app.js',
      'app/controllers.js',
      'app/services.js',
      'app/filters.js',
      'app/directives.js',
      'app/dom.js'
    ], 'public/assets/js/app.js');
});
