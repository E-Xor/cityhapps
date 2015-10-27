/**
 * CityHapps AngularJS Bootstrap
 */

angular.module('cityHapps', ['xeditable', 'ui.bootstrap', 'ui.router', 'ngRoute',
    'ngResource', 'ui.validate', 'facebook', 'http-auth-interceptor',
    'remoteValidation', 'google-maps'.ns(), 'ui.calendar', 'angular.filter',
    'ngSanitize', 'ngCookies', 'snap', 'ngIdle', 'checklist-model',
    'ngTagsInput', 'cityHapps.controllers', 'cityHapps.services',
    'cityHapps.filters', 'cityHapps.directives', 'satellizer', 'door3.css', 'angular-google-analytics', 'ui.bootstrap.datetimepicker']);

angular.module('cityHapps').config(function($routeProvider, $locationProvider, FacebookProvider, AnalyticsProvider, $stateProvider, $urlRouterProvider, snapRemoteProvider, $authProvider) {
    $stateProvider.state('main', {
        abstract: true,
        templateUrl: 'app/shared/templates/main-layout.tpl.html',
        controller: 'CategorySidebarController'
    }).state('main.home', {
        url: '/',
        views: {
            '@main': {
                templateUrl: 'app/components/happs/home.html',
                controller: 'HappHomeController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.viewHapp', {
        url: '/happ/:id',
        views: {
            '@main': {
                templateUrl: 'app/components/happs/view.html',
                controller: 'HappViewController'
            },
            'menubar@main': {
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.addHapp', {
        url: '/admin/event/add',
        views: {
            '@main': {
                templateUrl: 'app/components/happs/edit.html',
                controller: 'adminEventController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.editHapp', {
        url: '/admin/event/edit/:id',
        views: {
            '@main': {
                templateUrl: 'app/components/happs/edit.html',
                controller: 'adminEventController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.listHapp', {
        url: '/preview',
        views: {
            '@main': {
                templateUrl: 'app/components/happs/list.html',
                controller: 'adminEventController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.listCategoryHapp', {
        url: '/category/:slug',
        views: {
            '@main': {
                templateUrl: 'app/components/categories/happlist.html',
                controller: 'CategoryHappController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.viewVenue', {
        url: '/venue/:id',
        views: {
            '@main': {
                templateUrl: 'app/components/venues/view.html',
                controller: 'VenueViewController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.addVenue', {
        url: '/admin/venue/add',
        views: {
            '@main': {
                templateUrl: 'app/components/venues/edit.html',
                controller: 'adminVenueController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.editVenue', {
        url: '/admin/venue/edit/:id',
        views: {
            '@main': {
                templateUrl: 'app/components/venues/edit.html',
                controller: 'adminVenueController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.listVenue', {
        url: '/venues',
        views: {
            '@main': {
                templateUrl: 'app/components/venues/list.html',
                controller: 'VenueListController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.listFavorite', {
        url: '/favorites',
        views: {
            '@main': {
                templateUrl: 'app/components/favorites/list.html',
                controller: 'FavoriteController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.listVenuePage', {
        url: '/venues/:page',
        views: {
            '@main': {
                templateUrl: 'app/components/venues/list.html',
                controller: 'VenueListController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.adminListVenue', {
        url: '/admin/venue/list',
        views: {
            '@main': {
                templateUrl: 'app/components/venues/admin-list.html',
                controller: 'adminVenueController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.adminListVenuePage', {
        url: '/admin/venue/list/:page',
        views: {
            '@main': {
                templateUrl: 'app/components/venues/admin-list.html',
                controller: 'adminVenueController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.about', {
        url: '/about',
        views: {
            '@main': {
                templateUrl: 'app/components/static/about.html'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.termsConditions', {
        url: '/terms-conditions',
        views: {
            '@main': {
                templateUrl: 'app/components/static/terms-conditions.html'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.privacyPolicy', {
        url: '/privacy-policy',
        views: {
            '@main': {
                templateUrl: 'app/components/static/privacy-policy.html'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.requestVenue', {
        url: '/add-venue',
        views: {
            '@main': {
                templateUrl: 'app/components/static/add-venue.html'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.requestEvent', {
        url: '/add-event',
        views: {
            '@main': {
                templateUrl: 'app/components/static/add-event.html'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.contact', {
        url: '/contact',
        views: {
            '@main': {
                templateUrl: 'app/components/static/contact.html'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('userLogin', {
        url: '/login',
        templateUrl: 'app/components/user/login.html',
        controller: 'AuthController as auth'
    }).state('userRegister', {
        url: '/register',
        templateUrl: 'app/components/user/register.html',
        controller: 'registerFormController'
    }).state('userRegisterCategories', {
        url: '/register/categories',
        templateUrl: 'app/components/user/register-categories.html',
        controller: 'registerFormController'
    }).state('userReset', {
        url: '/reset',
        templateUrl: 'app/components/user/reset.html',
        controller: 'registerFormController'
    }).state('userLogout', {
        url: '/logout',
        controller: 'UserLogoutController'
    }).state('main.userProfileEdit', {
        url: '/profile/edit',
        views: {
            '@main': {
                templateUrl: 'app/components/user/profile-edit.html',
                controller: 'registerFormController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.userProfile', {
        url: '/profile',
        views: {
            '@main': {
                templateUrl: 'app/components/user/profile.html',
                controller: 'UserProfileController'
            },
            'menubar@main': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    });

    $urlRouterProvider.otherwise('/');

    // use the HTML5 History API
    $locationProvider.html5Mode(true);

    // Satellizer configuration that specifies which API
    // route the JWT should be retrieved from
    $authProvider.loginUrl = '/api/authenticate';
    $authProvider.facebook({
        clientId: '1678520879058865'
    });

    var myAppId = '1678520879058865';
    FacebookProvider.init(myAppId);

    snapRemoteProvider.globalOptions = {
        disable: 'right',
        touchToDrag: false
    };

    // google analytics
    AnalyticsProvider.setAccount('UA-65216452-1');

    // Track all routes (or not)
    AnalyticsProvider.trackPages(true);

    // Track all URL query params (default is false)
    AnalyticsProvider.trackUrlParams(true);

}).run(function($rootScope, $state, $http, editableOptions) {
    $rootScope.$on('$stateChangeStart', function(event, toState) {
        editableOptions.theme = 'bs3';
        if($rootScope.authenticated !== true){
          $http.get('api/authenticate/user')
          .then(function(response) {
              $rootScope.authenticated = true;
              $rootScope.currentUser = response.data.user;
          })
          .catch(function(payload, status) {
              console.log('Not authenticated');
          });
        }
    });
    $rootScope.title = 'City Happs';
});
