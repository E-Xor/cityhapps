/**
 * CityHapps AngularJS Bootstrap
 */

angular.module('cityHapps', [
  'xeditable', 'ui.bootstrap', 'ui.router', 'ngRoute',
  'ngResource', 'ui.validate', 'facebook', 'http-auth-interceptor',
  'remoteValidation', 'google-maps'.ns(), 'ui.calendar', 'angular.filter',
  'ngSanitize', 'ngCookies', 'snap', 'ngIdle', 'checklist-model',
  'ngTagsInput', 'cityHapps.controllers', 'cityHapps.services',
  'cityHapps.filters', 'cityHapps.directives', 'satellizer', 'door3.css',
  'angular-google-analytics', 'ui.bootstrap.datetimepicker', 'textAngular']);

angular.module('cityHapps').config(function($routeProvider, $locationProvider, FacebookProvider, AnalyticsProvider, $stateProvider, $urlRouterProvider, snapRemoteProvider, $authProvider) {
  var sortingComparator = function(key) {
    return function(a, b) {
      return (a[key]> b[key]) ? 1 : ((b[key] > a[key]) ? -1 : 0);
    };
  };
    $stateProvider.state('main', {
        abstract: true,
        templateUrl: 'app/shared/templates/main-layout.tpl.html',
        controller: 'CategorySidebarController'
    }).state('main.home', {
        url: '/',
        views: {
          '@main': {
            templateUrl: 'app/components/happs/home.html',
            controller: 'HappHomePageController',
            resolve: {
              welcomeMessage: function(siteSettings) {
                return siteSettings.get('welcome_message');
              }
              }
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
    }).state('main.curate', {
      url: '/curate',
      views: {
        '@main': {
          templateUrl: 'app/components/curate/index.html'
        },
        'menubar@main': {
        }
      },
      css: 'assets/css/angular-snap.min.css'
    }).state('main.curateGreeting', {
      url: '/curate/greeting',
      views: {
        '@main': {
          templateUrl: 'app/components/curate/greeting.html',
          controller: 'EditWelcomeMessageController',
          resolve: {
            welcomeMessage: function($http) {
              return $http.get('/admin/welcome-message').then(function(res) {
                return res.data.welcome_message;
              }, function(res) {
                throw new Error("FailedWelcomeMessage");
              });
            }
          }
        },
        'menubar@main': {
        }
      },
      css:['assets/css/textangular.min.css', 'assets/css/angular-snap.min.css']
    }).state('main.addHapp', {
        url: '/admin/event/add',
        views: {
            '@main': {
                templateUrl: 'app/components/happs/edit.html',
                controller: 'adminEventController'
            },
            'menubar@main': {
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.editHapp', {
      url: '/admin/event/edit/:id',
      views: {
        '@main': {
          templateUrl: 'app/components/happs/edit.html',
          controller: 'adminEventEditController',
          resolve: {
            ageLevels: function($q, AgeLevel) {
              return $q(function(resolve, reject) {
                AgeLevel.query(function(payload) {
                  resolve(payload.data.sort(sortingComparator('id')));
                });
              });
            },
            categories: function($q, Category) {
              return $q(function(resolve, reject) {
                Category.query(function(payload) {
                  resolve(payload.data.sort(sortingComparator('name')));
                });
              });
            },
            happ: function($stateParams, happEditFormData) {
              return happEditFormData.get($stateParams.id);
            }
          },
          'menubar@main': {
            // templateUrl: 'app/components/filters/filters.html',
            // controller: 'MainFilterController'
          }
        },
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
            }
        },
        css: 'assets/css/angular-snap.min.css'
    }).state('main.requestConfirm', {
        url: '/form-submitted',
        views: {
            '@main': {
                templateUrl: 'app/components/static/form-submitted.html'
            },
            'menubar@main': {
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
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

}).run(function($rootScope, $state, $http, editableOptions, $q) {
  var authRoute = 'api/authenticate/user';
  function isAdmin() {
    return $rootScope.authenticated &&
      $rootScope.currentUser &&
      $rootScope.currentUser.role === 'ROLE_ADMIN';
  }

  $rootScope.$on('$stateChangeStart', function(event, toState, toParams) {
    editableOptions.theme = 'bs3';
    $rootScope.title = 'City Happs';
    var routeRequiresAdmin = isAdminRoute(toState.url);

    if (routeRequiresAdmin && $rootScope.authenticated) {
      if(isAdmin()) {
        return true;
      } else {
        $state.go('main.home');
      }
    }
    if (!routeRequiresAdmin && $rootScope.authenticated) return true;

    var authPromise = $http.get(authRoute).then(function(res) {
      $rootScope.authenticated = true;
      $rootScope.currentUser = res.data.user;
    });

    if (routeRequiresAuth(toState.url)){
      event.preventDefault();
      authPromise.then(function(res) {
        $state.go(toState.name, toParams);
      }, function(err) {
        $state.go('userLogin');
      });
    } else {
      authPromise.then(null, function(payload, status) {
        console.log('Not authenticated');
      });
    }
  });

  function routeRequiresAuth(toRoute) {
    return toRoute.match(/^\/?(admin|profile\/?)/);
  }

  function isAdminRoute(toRoute) {
    return toRoute.match(/^\/?(admin)/);
  }

  $rootScope.$on('$stateChangeError', function(event, toState) {
    console.log("State change rejected");
    console.log(event, toState);
  });
});
