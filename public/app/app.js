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
  'angular-google-analytics', 'ui.bootstrap.datetimepicker', 'textAngular',
  'angular-img-cropper'
]);

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
    }).state('main.viewHapp', {
      url: '/happ/:id',
      views: {
        '@main': {
          templateUrl: 'app/components/happs/view.html',
          controller: 'HappViewController',
          resolve: {
            happ: function($stateParams, $q, cleanData, Happ) {
              return $q(function(resolve) {
                Happ.get({ id: $stateParams.id, include: 'tags,categories,venues'}, function(payload) {
                  payload = cleanData.buildRelationships(payload);
                  resolve(payload.data[0]);
                });
              });
            }
          }
        },
        'menubar@main': {
          // templateUrl: 'app/components/filters/filters.html',
          // controller: 'MainFilterController'
        }
      },
    }).state('main.curate', {
      url: '/curate',
      views: {
        '@main': {
          templateUrl: 'app/components/curate/index.html',
          resolve: {
            happs: function($q, $rootScope, Happ) {
              return $q(function(resolve) {
                Happ.query({user_id: $rootScope.currentUser.id}, function(happs) {
                  resolve(happs.data);
                });
              });
            },
            venues: function($q, $rootScope, Venue) {
              return $q(function(resolve) {
                Venue.query({user_id: $rootScope.currentUser.id}, function(venues) {
                  resolve(venues.data);
                });
              });
            }
          },
          controller: function($scope, happs, venues) {
            $scope.happs = happs;
            $scope.venues = venues;
          }
        },
        'menubar@main': {
        }
      },
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
      css:['assets/css/textangular.min.css']
    }).state('main.addHapp', {
        url: '/admin/event/add',
        views: {
            '@main': {
              templateUrl: 'app/components/happs/edit.html',
              controller: 'adminEventAddController',
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
                }
              }
            },
            'menubar@main': {
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
            }
        },
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
    }).state('main.viewVenue', {
        url: '/venue/:id',
        views: {
          '@main': {
            templateUrl: 'app/components/venues/view.html',
            controller: 'VenueViewController',
            resolve: {
              venue: function($stateParams, $q, cleanData, Venue) {
                return $q(function(resolve) {
                  Venue.get({id: $stateParams.id, include: 'happs,tags'}, function(payload) {
                    resolve(cleanData.buildRelationships(payload).data[0]);
                  });
                });
              }
            }
          },
          'menubar@main': {
            // templateUrl: 'app/components/filters/filters.html',
            // controller: 'MainFilterController'
          }
        },
    }).state('main.addVenue', {
        url: '/admin/venue/add',
        views: {
            '@main': {
              templateUrl: 'app/components/venues/edit.html',
              controller: 'adminVenueController',
              resolve: {
                venue: function() {
                  return {};
                }
              }
            },
            'menubar@main': {
                // templateUrl: 'app/components/filters/filters.html',
                // controller: 'MainFilterController'
            }
        },
    }).state('main.editVenue', {
      url: '/admin/venue/edit/:id',
      views: {
        '@main': {
          templateUrl: 'app/components/venues/edit.html',
          controller: 'adminVenueController',
          resolve: {
            venue: function(venueEditFormData, $stateParams) {
              return venueEditFormData.get($stateParams.id);
            }
          }
        },
        'menubar@main': {
          // templateUrl: 'app/components/filters/filters.html',
          // controller: 'MainFilterController'
        }
      },
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
    }).state('main.listFavorite', {
        url: '/favorites',
        views: {
            '@main': {
                templateUrl: 'app/components/favorites/list.html',
                controller: 'FavoriteController'
            }
        },
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

}).run(function($rootScope, $state, $http, editableOptions, $q, userDecorator) {
  var authRoute = 'api/authenticate/user';
  function canCurate() {
    return $rootScope.authenticated && $rootScope.currentUser.canCreate();
  }

  $rootScope.$on('$stateChangeStart', function(event, toState, toParams) {
    editableOptions.theme = 'bs3';
    $rootScope.title = 'City Happs';
    var routeRequiresAdmin = isAdminRoute(toState.url);

    if (routeRequiresAdmin && $rootScope.authenticated) {
      if (canCurate()) {
        return true;
      } else {
        $state.go('main.home');
      }
    }
    if (!routeRequiresAdmin && $rootScope.authenticated) return true;

    var authPromise = $http.get(authRoute).then(function(res) {
      $rootScope.authenticated = true;
      $rootScope.currentUser = userDecorator.decorate(res.data.user);
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
    return toRoute.match(/^\/?(admin|profile|curate\/?)/);
  }

  function isAdminRoute(toRoute) {
    return toRoute.match(/^\/?(admin|curate)/);
  }

  $rootScope.$on('$stateChangeError', function(event, toState) {
    console.log("State change rejected");
    console.log(event, toState);
  });
});
