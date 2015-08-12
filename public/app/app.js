/**
 * CityHapps AngularJS Bootstrap
 */

angular.module('cityHapps', ['ui.bootstrap', 'ui.router', 'ngRoute',
    'ngResource', 'ui.validate', 'facebook', 'http-auth-interceptor',
    'remoteValidation', 'google-maps'.ns(), 'ui.calendar', 'angular.filter',
    'ngSanitize', 'ngCookies', 'snap', 'ngIdle', 'checklist-model',
    'ngTagsInput', 'cityHapps.controllers', 'cityHapps.services',
    'cityHapps.filters', 'cityHapps.directives']);

angular.module('cityHapps').config(function($routeProvider, $locationProvider, FacebookProvider, $stateProvider, $urlRouterProvider, snapRemoteProvider) {
    $stateProvider.state('default', {
        abstract: true,
        views: {
            '@': {
                templateUrl: 'app/components/happs/home.html',
                controller: 'HappHomeController'
            },
            'sidebar': {
                templateUrl: 'app/components/categories/list.html',
                controller: 'CategorySidebarController'
            },
            'menubar': {
                templateUrl: 'app/components/filters/filters.html',
                controller: 'MainFilterController'
            }
        }
    }).state('home', {
        url: '/',
        parent: 'default'
    }).state('viewHapp', {
        url: '/happ/:id',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/happs/view.html',
                controller: 'HappViewController'
            }
        }
    }).state('addHapp', {
        url: '/admin/event/add',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'templates/event.html',
                controller: 'adminEventController'
            }
        }
    }).state('editHapp', {
        url: '/admin/event/edit/:id',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'templates/event.html',
                controller: 'adminEventController'
            }
        }
    }).state('listHapp', {
        url: '/preview',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/happs/list.html',
                controller: 'adminEventController'
            }
        }
    }).state('listCategoryHapp', {
        url: '/category/:slug',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/categories/happlist.html',
                controller: 'CategoryHappController'
            }
        }
    }).state('viewVenue', {
        url: '/venue/:id',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/venues/view.html',
                controller: 'VenueViewController'
            }
        }
    }).state('addVenue', {
        url: '/admin/venue/add',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/venues/edit.html',
                controller: 'adminVenueController'
            }
        }
    }).state('editVenue', {
        url: '/admin/venue/edit/:id',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/venues/edit.html',
                controller: 'adminVenueController'
            }
        }
    }).state('listVenue', {
        url: '/venues',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/venues/list.html',
                controller: 'VenueListController'
            }
        }
    }).state('listVenuePage', {
        url: '/venues/:page',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/venues/list.html',
                controller: 'VenueListController'
            }
        }
    }).state('adminListVenue', {
        url: '/admin/venue/list',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/venues/admin-list.html',
                controller: 'adminVenueController'
            }
        }
    }).state('adminListVenuePage', {
        url: '/admin/venue/list/:page',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/venues/admin-list.html',
                controller: 'adminVenueController'
            }
        }
    }).state('about', {
        url: '/about',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/static/about.html'
            }
        }
    }).state('requestVenue', {
        url: '/add-venue',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/static/add-venue.html'
            }
        }
    }).state('requestEvent', {
        url: '/add-event',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/static/add-event.html'
            }
        }
    }).state('contact', {
        url: '/contact',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/static/contact.html'
            }
        }
    }).state('userLogin', {
            url: '/login',
            parent: 'default',
            views: {
                '@': {
                    templateUrl: 'app/components/user/login.html',
                    controller: 'registerFormController'
                }
            }
        }).state('userRegister', {
        url: '/register',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/user/register.html',
                controller: 'registerFormController'
            }
        }
    }).state('userRegisterCategories', {
        url: '/register/categories',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/user/register-categories.html',
                controller: 'registerFormController'
            }
        }
    }).state('userReset', {
        url: '/reset',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/user/reset.html',
                controller: 'registerFormController'
            }
        }
    }).state('userProfile', {
        url: '/profile',
        parent: 'default',
        views: {
            '@': {
                templateUrl: 'app/components/user/profile.html',
                controller: 'registerFormController'
            }
        }
    });

    $urlRouterProvider.otherwise('/');

    // use the HTML5 History API
    $locationProvider.html5Mode(true);

    var myAppId = '1149149361767339';
    FacebookProvider.init(myAppId);

    snapRemoteProvider.globalOptions = {
        disable: 'right',
        touchToDrag: false
    };

}).run(function() {
   //
});

/*

cityHapps.config(['$keepaliveProvider', '$idleProvider', function($keepaliveProvider, $idleProvider) {
  $idleProvider.idleDuration(5);

}]);

cityHapps.config(['GoogleMapApiProvider'.ns(), function (GoogleMapApi) {
    GoogleMapApi.configure({
        //    key: 'your api key',
        v: '3.17',
        libraries: 'weather,geometry,visualization'
    });
}]);*/


/*cityHapps.controller('eventsController', function($scope, $rootScope, $http,
    $filter, $modal, registerDataService, voteService, $cookies, $cookieStore, getEvents,
    getRecommendedEvents, $window, getCategories) {

    $scope.mobile = function() {
        if ($window.innerWidth <= 768) {
            return true;
        } else {
           return false;
        }
    };

    var userID = null;

    var cookie = $cookies.user;
    if (cookie) {
        userID = cookie.id;
    }

    $scope.userID = userID;

    // Will only happen on mobile because desktop version redirects to day view
    $scope.$on('search', function(info, data) {
        console.log(data);
        recommendedEventSuccess(data);
        eventSuccess(data);
    });

    $scope.fadeIn = function(speed) {
        $('.carousel-inner').fadeIn(speed);
    };

    var recommendedEventSuccess = function(data) {

        $rootScope.recEventCount = data.events.length;

        $scope.eventData = data.events;

        console.log($scope.eventData);

        if ($window.innerWidth <= 768) {

                $scope.recSlideGroup = [];

                for (rec = 0; rec < $scope.eventData.length; rec++) {

                    var mobileRecSlides = $scope.eventData[rec];
                    $scope.recSlideGroup.push(mobileRecSlides);
                }

                $scope.voteEvent = function() {
                    $modal.open({
                        templateUrl: "templates/registrationModal.html",
                        controller: 'modalInstanceController',
                    });
                };


                $scope.recEventModalMobile = function(data) {

                    $modal.open({
                        templateUrl: "templates/eventModal.html",
                        controller: 'simpleModalInstanceController',
                        resolve: {
                            data: function() {
                                return data;
                            },
                            vote : function() {
                                return vote;
                            }
                        }
                    });
                };

                $scope.interval = 500000000000;

            }

            if ($scope.eventData != undefined && $window.innerWidth >= 768) {

        		$scope.recSlideGroup = [];

        		var rec;
	            for (rec = 0; rec < $scope.eventData.length; rec += 4) {

					var recSlides = {
						'first' : $scope.eventData[rec],
						'second' : $scope.eventData[rec + 1],
						'third' : $scope.eventData[rec + 2],
						'fourth' : $scope.eventData[rec + 3]
					};

					if (recSlides['first'] != undefined) {
						recSlides['first'].vote = {
							upVote: false,
							downVote: false
						};
						if (recSlides['first'].recommended_votes && recSlides['first'].recommended_votes.length > 0) {
							if (recSlides['first'].recommended_votes[0].vote == '1') {
								recSlides['first'].vote.upVote = true;
							}

							if (recSlides['first'].recommended_votes[0].vote == '0') {
								recSlides['first'].vote.downVote = true;
							}
						}
					};

					if (recSlides['second'] != undefined) {
						recSlides['second'].vote = {
							upVote: false,
							downVote: false
						};
						if (recSlides['second'].recommended_votes && recSlides['second'].recommended_votes.length > 0) {
							if (recSlides['second'].recommended_votes[0].vote == '1') {
								recSlides['second'].vote.upVote = true;
							}

							if (recSlides['second'].recommended_votes[0].vote == '0') {
								recSlides['second'].vote.downVote = true;
							}
						}
					};

					if (recSlides['third'] != undefined) {
						recSlides['third'].vote = {
							upVote: false,
							downVote: false
						};
						if (recSlides['third'].recommended_votes && recSlides['third'].recommended_votes.length > 0) {
							if (recSlides['third'].recommended_votes[0].vote == '1') {
								recSlides['third'].vote.upVote = true;
							}

							if (recSlides['third'].recommended_votes[0].vote == '0') {
								recSlides['third'].vote.downVote = true;
							}
						}
					};

					if (recSlides['fourth'] != undefined) {
						recSlides['fourth'].vote = {
							upVote: false,
							downVote: false
						};
						if (recSlides['fourth'].recommended_votes && recSlides['fourth'].recommended_votes.length > 0) {
							if (recSlides['fourth'].recommended_votes[0].vote == '1') {
								recSlides['fourth'].vote.upVote = true;
							}

							if (recSlides['fourth'].recommended_votes[0].vote == '0') {
								recSlides['fourth'].vote.downVote = true;
							}
						}
					};

					$scope.recSlideGroup.push(recSlides);
				}
			}
        }


		var eventSuccess = function(data) {

			$scope.eventData = data.events;
      for (rec = 0; rec < $scope.eventData.length; rec ++) {
        // if event_image_url isn't there, replace it with /img/logo.png
        if (!$scope.eventData[rec].event_image_url) $scope.eventData[rec].event_image_url = '/img/logo.png';
      }
			console.log($scope.eventData);

            if ($window.innerWidth <= 768 ) {
                $scope.slideGroup = [];

                for (rec = 0; rec < $scope.eventData.length; rec ++) {

                    var mobileRecSlides = $scope.eventData[rec];
                    $scope.slideGroup.push(mobileRecSlides);
                }

                $scope.voteEvent = function() {

                    $modal.open({
                        templateUrl: "templates/registrationModal.html",
                        controller: 'modalInstanceController',
                    });
                };

                $scope.eventModalMobile = function(data, vote) {
                    // debugger;
                    $modal.open({
                        templateUrl: "templates/eventModal.html",
                        controller: 'simpleModalInstanceController',
                        resolve: {
                            data: function() {
                                return data;
                            },
                            vote : function() {
                                return vote;
                            }
                        }
                    });
                };
                $scope.interval = 500000000000;
            }

            $scope.eventCount = data.meta.count;

			if ($window.innerWidth > 768) {

				$scope.slideGroup = [];

				var i;

                for (i = 0; i < $scope.eventData.length; i += 4) {

					          var slides = {
                        'first' : $scope.eventData[i],
                        'second' : $scope.eventData[i + 1],
                        'third' : $scope.eventData[i + 2],
                        'fourth' : $scope.eventData[i + 3]
                    };

                    $scope.slideGroup.push(slides);


                    if (slides['first'] !== undefined) {
                        slides['first'].vote = {
                            upVote: false,
                            downVote: false
                        };
                        if (slides['first'].votes && slides['first'].votes.length > 0) {
                            if (slides['first'].votes[0].vote == '1') {
                                slides['first'].vote.upVote = true;
                            }

                            if (slides['first'].votes[0].vote == '0') {
                                slides['first'].vote.downVote = true;
                            }
                        }
                    };

                    if (slides['second'] !== undefined) {
                        slides['second'].vote = {
                            upVote: false,
                            downVote: false
                        };
                        if (slides['second'].votes && slides['second'].votes.length > 0) {
                            if (slides['second'].votes[0].vote == '1') {
                                slides['second'].vote.upVote = true;
                            }

                            if (slides['second'].votes[0].vote == '0') {
                                slides['second'].vote.downVote = true;
                            }
                        }
                    };

                    if (slides['third'] !== undefined) {
                        slides['third'].vote = {
                            upVote: false,
                            downVote: false
                        };
                        if (slides['third'].votes &&  slides['third'].votes.length > 0) {
                            if (slides['third'].votes[0].vote == '1') {
                                slides['third'].vote.upVote = true;
                            }

                            if (slides['third'].votes[0].vote == '0') {
                                slides['third'].vote.downVote = true;
                            }
                        }
                    };

                    if ( slides['fourth'] !== undefined) {
                        slides['fourth'].vote = {
                            upVote: false,
                            downVote: false
                        };
                        if (slides['fourth'].votes && slides['fourth'].votes.length > 0) {
                            if (slides['fourth'].votes[0].vote == '1') {
                                slides['fourth'].vote.upVote = true;
                            }

                            if (slides['fourth'].votes[0].vote == '0') {
                                slides['fourth'].vote.downVote = true;
                            }
                        }
                    };

			}

		}

		$scope.eventModal = function(data, num) {

			$modal.open({
				templateUrl: "templates/eventModal.html",
				controller: 'eventModalInstanceController',
				resolve: {
					data: function() {
						return data;
					},
					num : function() {
						return num;
					},
					vote : function() {
						return data[num].vote;
					}
				}
			});
		};

		$scope.interval = 500000000000;
	};



    $scope.categoryToggle = function() {
        $(".categoriesDropdown").fadeToggle();

        getCategories.success(function(data){
            $scope.categories = data;
        });
    };

    $scope.clearAll = function() {

        $scope.filterData.categories = {};
        $scope.filterData = {};

        $scope.queryString = null;
        $scope.pageCount = 1;

        $http.get("/events?user_id=" + $scope.userID + "&start_date="+ $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&page_size=10&page_count=1')
            .success(function(data){
                eventSuccess(data);
                recommendedEventSuccess(data);

                console.log(data);
            });
    }

    $scope.filterCategory = function() {

        $scope.nowGet = moment().format();
        $scope.nowDateGet = moment().format('YYYY-MM-DD');

        var queryString = '';

        for (var i in $scope.filterData.categories){
            console.log(i);
            if ($scope.filterData.categories[i] == true) {
                queryString += "&category[]=" + i ;
                //+ "&";
            }
        }

        $http.get("/events?" + "user_id=" + $scope.userID + "&start_date="+ $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&page_count=1" + "&page_size=10" + queryString)
            .success(function(data){
                // $scope.eventData = data;
                eventSuccess(data);

                if ($rootScope.user) {
                    eventSuccess(data);
                    recommendedEventSuccess(data);
                }


                console.log(data);
            });

        console.log($scope.filterData.categories);
    }

    var cookie = $cookies.user;

    $scope.$on('event:loginConfirmed', function(){
        getRecommendedEvents.events().success(recommendedEventSuccess);
    });

    if (cookie) {
        getRecommendedEvents.events().success(recommendedEventSuccess);
        getEvents.events().success(eventSuccess);
    } else {
        getEvents.events().success(eventSuccess);
    }

	$scope.now = moment().format("dddd, MMMM Do");
    $scope.nowMobile = moment().format("ddd, MMMM D, YYYY");

	//$scope.nowPost = moment().format();

		var next = 0;

        $scope.nextDay = function() {
            next += 1;
            $scope.now = moment().add((next),'days').format("dddd, MMMM Do");
            $scope.nowMobile = moment().add(next, 'days').format("ddd, MMMM D, YYYY");

            $scope.nowGet = moment().add(next,'days').format();
        	$scope.nowDateGet = moment().add(next,'days').format('YYYY-MM-DD');

            var end = moment().add(next ,'days').endOf('day').format('YYYY-MM-DD');

            $http.get('/events?user_id=' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&end_date=" + end)
                .success(function(data){
                    $scope.eventData = data;
                    eventSuccess(data);
                });
        };

        $scope.prevDay = function() {
            next -= 1;
            $scope.now = moment().add(next, 'days').format("dddd, MMMM Do");
            $scope.nowMobile = moment().add(next, 'days').format("ddd, MMMM D, YYYY");

            $scope.nowGet = moment().add(next,'days').format();
        	$scope.nowDateGet = moment().add(next,'days').format('YYYY-MM-DD');

            var end = moment().add(next ,'days').endOf('day').format('YYYY-MM-DD');

            $http.get('/events?user_id=' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&end_date=" + end)
                .success(function(data){
                    $scope.eventData = data;
                    eventSuccess(data);
                });
        };
        //end sloppy code re-use
});*/

/*cityHapps.filter('dateToISO', function() {
    return function(input) {
        input = moment(input).toISOString();
        return input;
    };
});

cityHapps.filter('uniqueHour', function() {
    var prevVal = null;
    return function(input) {
        if (prevVal !== input) {
            prevVal = input;
            return prevVal;
        }
    };
});


cityHapps.filter('nonUniqueHour', function() {
    var prevVal = null;
    return function(input) {
        if (input == null) {
            return false;
        }
    };
});

cityHapps.filter('ellipsis', function () {
    return function (input, num) {
        if (input && input.length >= num) {
            return input.substr(0, num) + "...";
        } else {
            return input;
        }
    };
});



cityHapps.factory('voteService', function(){

	var vote = {};

	return {
		getData: function() {
			return vote;
		}
	};

});


cityHapps.factory('getEvents', function($http, $cookies, $cookieStore) {
    return {
        events: function() {
            var userID = null;

            var cookie = $cookies.user;
            if (cookie) {
                userID = cookie.id;
            }

            var startDate = moment().format('YYYY-MM-DD');
            var startTime = moment().format();

            // '?start_date=' + startDate
            return $http.get('/events?user_id=' + userID + '&start_date=' + startDate + '&start_time=' + startTime).success(function(data) {});
        },
        eventsDay: function(time) {
            var startDate = moment().format('YYYY-MM-DD');
            var startTime = moment().format();

            var end = moment(time).endOf('day').format('YYYY-MM-DD');

            var cookie = $cookies.user;
            if (cookie) {
                var userID = cookie.id;
            }

            return $http.get('/events?user_id=' + userID +'&start_date=' + time + "&end_date=" + end).success(function(data) {
                console.log(data);
                //if (data.events.votes.length > 0) {
                //    if (data.events.vote == '1') {
                //        data.events.vote.upVote = true;
                //    }
                //
                //    if (data.events.vote == '0') {
                //        data.events.vote.downVote = true;
                //    }
                //}
            });
        }//,
 //       allEvents: function() {
 //           var userID = null;
//
 //           var cookie = $cookies.user;
 //           if (cookie) {
 //               userID = cookie.id;
 //           }
//
 //           return $http.get('/events?user_id=' + userID).success(function(data) {});
 //       }
    };
});

cityHapps.factory('getRecommendedEvents', function($http, $cookies, $cookieStore) {
	return {
		events : function() {
			var userID = null;

			var cookie = $cookies.user;
			if (cookie) {
                userID = cookie.id;
            }

			var startDate = moment().format('YYYY-MM-DD');
            var startTime = moment().format();

			return $http.get('/recommendedEvents?user_id=' + userID + '&start_date=' + startDate + '&end_date=' + startDate + '&start_time=' + startTime).success(function(data) {

			});
		}
	}
});

var firstOfMonth = moment().startOf('month').format('YYYY-MM-DD');
var endOfMonth = moment().endOf('month').format('YYYY-MM-DD');
var maxPerDay = 5;

cityHapps.factory('getEventsMonthStart', function($http, $cookies, $cookieStore){
   return  {
       events : function(){
           var userID = null;

            var cookie = $cookies.user;
            if (cookie) {
                userID = cookie.id;
            }

           return $http.get('/events?page_size=all&user_id=' + userID + '&start_date='+ firstOfMonth + '&end_date=' + endOfMonth + '&max_per_day=' + maxPerDay).success(function(data){

           });
       }
   }

});
cityHapps.directive('dateTimePicker', function($parse) {
    return {
        // Restrict it to be an attribute in this case
        restrict: 'A',
        // responsible for registering DOM listeners as well as updating the DOM
        link: function(scope, element, attrs) {
            $(element).datetimepicker({
              formatTime: 'g:i A',
              format: 'm/d/Y h:i A',
              onClose: function(ct,$i) {
                ngModel = $parse($i.attr('ng-model'));
                var date = $i.val();
                scope.$apply(function(scope){
                   // Change binded variable
                   ngModel.assign(scope, date);
                });
              }
           });
        }
    };
});
cityHapps.directive('ngModelOnblur', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        priority: 1, // needed for angular 1.2.x
        link: function(scope, elm, attr, ngModelCtrl) {
            if (attr.type === 'radio' || attr.type === 'checkbox') return;

            elm.unbind('input').unbind('keydown').unbind('change');
            elm.bind('blur', function() {
                scope.$apply(function() {
                    ngModelCtrl.$setViewValue(elm.val());
                });
            });
        }
    };
});


cityHapps.factory('search', function($http, $cookies, $cookieStore){
    var now = moment().format('YYYY-MM-DD');

    return {
        searchData : function(query){
            var userID = null;

            var cookie = $cookies.user;
            if (cookie) {
                userID = cookie.id;
            }

            return $http.get("/events?user_id=" + userID + "&search=" + query + "&start_date=" + now )
                .success(function(data, scope){
                  console.log(data);

            });
        }
    };
});

cityHapps.factory('calDayClick', function($http, $cookies, $cookieStore){
    var now = moment().format('YYYY-MM-d');

    return {
        dateClicked : function(day){
            var userID = null;

            var cookie = $cookies.user;
            if (cookie) {
                userID = cookie.id;
            }

            return $http.get('/events?user_id=' + userID + '&start_date=' + day + "&end_date=" + day)
                .success(function(data){
                    console.log(data);
            });
        }
    };
});*/

//cityHapps.formData = {};

/*cityHapps.controller('registerFormController', [ "$scope", "$http", "$modal", "registerDataService", "$timeout", "authFactory", "Facebook",
  function($scope, $http, $modal, registerDataService, $timeout, authFactory, Facebook) {


    // Facebook Auth

      // Define user empty data :/
      $scope.user = {};

    // Watch for Facebook to be ready.
    // There's also the event that could be used
       
      $scope.$watch(
        function() {
          return Facebook.isReady();
        },
        function(newVal) {
          if (newVal)
            $scope.facebookReady = true;
        }
      );

      var userIsConnected = false;

      Facebook.getLoginStatus(function(response) {
        if (response.status == 'connected') {
          userIsConnected = true;

        }
      });

      $scope.IntentLogin = function() {
        if(!userIsConnected) {
          $scope.login();
        }
      };


	$scope.login = function() {
		Facebook.login(function(response) {
			if (response.status == 'connected') {
				$scope.logged = true;
				$scope.user.token = response;

				console.log($scope.user.token);

				Facebook.api('/me', function(response) {
					$scope.$apply(function() {

						console.log(response);
						$scope.user.info = response;

						$scope.fbInfo = {
							"categories" : {},
							"email" : $scope.user.info.email,
							"password" : $scope.user.token.authResponse.accessToken,
							"name" : $scope.user.info.name
						};

						authFactory.userCheck($scope.user.info.email, function(response){

							console.log(response);

							$scope.fbUser = {
								"email" : $scope.fbInfo.email,
								"password" : $scope.fbInfo.password
							};

							if (response.isValid == true ) {
								registerDataService.data = $scope.fbInfo;

								$modal.open({
									templateUrl: "templates/categoriesModal.html",
									controller: 'modalInstanceController'
								});

							} else {
								console.log(response.id);

								$http({
									method: 'PATCH',
									url: '/user/' + response.id,
									data: {"password" : $scope.fbUser.password},
									headers: {"Content-Type": "application/json"}
								}).success(function(data){
									authFactory.loginUser($scope.fbUser);
								});

							}

						});
					});
				});
			}
		}, {scope: 'email'});
	};


      $scope.logout = function() {

      };

      $scope.remove = function() {
		Facebook.api(
			"/me/permissions/user_profile",
			"DELETE",
			function (response) {
			  if (response && !response.error) {
			    alert('access revoked');
			  }
			}
		);
      };

        $scope.registerOpen = function(size) {

            var modalInstance = $modal.open({
                templateUrl: "templates/registrationModal.html",
                controller: 'modalInstanceController',
                size: size
            });
        };

        $scope.resetPasswordOpen = function(size) {

            var modalInstance = $modal.open({
                templateUrl: "templates/resetPasswordModal.html",
                controller: 'modalInstanceController',
                size: size
            });
        };


      // $scope.$on('Facebook:statusChange', function(ev, data) {
      //   console.log('Status: ', data);
      //   if (data.status == 'connected') {
      //     $scope.$apply(function() {
      //       $scope.salutation = true;
      //       $scope.byebye     = false;
      //     });
      //   } else {
      //     $scope.$apply(function() {
      //       $scope.salutation = false;
      //       $scope.byebye     = true;

      //       // Dismiss byebye message after two seconds
      //       $timeout(function() {
      //         $scope.byebye = false;
      //       }, 2000);
      //     });
      //   }


      // });

	$scope.formData = registerDataService.data;

	// $scope.categoryService = categoryService.getCategories();

	var credentials = {
		"email" :  $scope.formData.email,
		"password" : $scope.formData.password,
		"username" : $scope.formData.username
	};

	$scope.processForm = function(formData) {
		$http({
			method: 'POST',
			url: '/user',
			data: formData,
			headers: {"Content-Type": "application/json"}
		}).success(function(data){
			if(!data) {
				console.log('not working');
			} else if (data) {
				var fbInfo = {
					"email" : data.email,
					"password" : data.fb_token
				};
				$scope.id = data.id;

				authFactory.loginUser({"email":formData.email, "password":formData.password});
			}
			console.log(data);
		});
	};


	}
]);*/


/*cityHapps.factory("registerDataService", function(){

	var registerDataService = {};
	registerDataService.data = {};
	registerDataService.data.categories = {};

    registerDataService.recEventCount = {};

	registerDataService.vote = {};

	return registerDataService;


});*/


/*cityHapps.factory('authFactory', function($http, authService, $rootScope, $modal, $cookies, $cookieStore, $modalStack){

	var auth = {};

	auth.loginUser = function(formData) {

			$http.post('/auth/login', formData).success(function(res) {
				console.log(res);

				delete res.password;
				localStorage.setItem('user', JSON.stringify(res));

                $cookieStore.put('user', res);
                //ipCookie('user', res, {expires: 2, expirationUnit: 'hours'});

                var userString = localStorage.getItem('user');
                var user = angular.fromJson(userString);

				authService.loginConfirmed();

                $modalStack.dismissAll('submit');

				document.location.reload(true);

			}).
            error(function(data){

                $rootScope.loginError = "There was a problem with your username or password";

            });
		};

	auth.logoutUser = function() {
		$http({
			method: "GET",
			url: '/auth/logout',
			headers : {"Content-Type": "application/json"}
		}).success(function(data){
			$cookieStore.remove('user');
            //ipCookie.remove("user");

			document.location.reload(true);
			if (!data) {
				console.log("There was an error logging you out");
			} else if(data) {
				console.log("You have logged out");
			}
		});
	};

    //for checking emails on registration
	auth.userCheck = function(email, callback ) {
		$http({
			method: "POST",
			url: '/user/check',
			data: { "email" : email },
			headers : {"Content-Type": "application/json"}
		}).success(function(data){

			console.log(data);
			if(typeof callback  === 'function') {
				callback(data);
			}

		});

	};

    auth.userExist = function(email, callback ) {
        $http({
            method: "POST",
            url: '/user/exist',
            data: { "email" : email },
            headers : {"Content-Type": "application/json"}
        }).success(function(data){

            console.log(data);
            if(typeof callback  === 'function') {
                callback(data);
            }

        });

    };

    auth.resetPassword = function (email) {

        $http
            .post('/user/reset-password', email)
            .success(function (res) {
                console.log(res);
            })
            .error(function (res) {
                console.log(res);
            });
    };

    auth.editUserData = function (data) {

        $http
            .post('/user/edit', data)
            .success(function (res) {
                console.log(res);
            })
            .error(function (res) {
                console.log(res);
            });
    };

	return auth;


});*/


/*cityHapps.controller("modalController", function($scope, $modal, $http, authFactory, registerDataService){

	$scope.formData = registerDataService.data;

	$scope.registerOpen = function(size) {

		var modalInstance = $modal.open({
			templateUrl: "templates/registrationModal.html",
			controller: 'modalInstanceController',
			size: size
		});
	};

	$scope.loginOpen = function(size) {

		var modalInstance = $modal.open({
			templateUrl: "templates/loginModal.html",
			controller: 'modalInstanceController',
			size: size
		});
	};

    $scope.editOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/editModal.html",
            controller: 'modalInstanceController',
            size: size
        });
    };

    $scope.getUserData = function() {
        $http
            .post('/user/getData')
            .success(function (res) {
                console.log(res);
                $scope.formData.username = res.username;
                $scope.formData.email = res.email;
            })
            .error(function (res) {
                console.log('Errors');
                console.log(res);
            });
    };

    $scope.resetPasswordOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/resetPasswordModal.html",
            controller: 'modalInstanceController',
            size: size
        });
    };

	$scope.categoriesOpen = function(size) {

		var modalInstance = $modal.open({
			templateUrl: "templates/categoriesModal.html",
			controller: 'modalInstanceController',
			size: size
		});
	};

	$scope.confirmationOpen = function(size) {

		var modalInstance = $modal.open({
			templateUrl: "templates/confirmationModal.html",
			controller: 'modalInstanceController',
			size: size
		});
	};

	$scope.logoutUser = function() {
		authFactory.logoutUser();
	};

    $scope.userExist = function() {
        authFactory.userExist();
    };


    $scope.resetPassword = function (data) {
        authFactory.resetPassword($scope.formData);
    };

    $scope.editUserData = function () {
        authFactory.editUserData($scope.formData);
    };

});*/

//
//Handles homepage event modals
//
/*cityHapps.controller("eventModalInstanceController", ["$scope", "registerDataService", '$rootScope', 'voteService', "$http", "$modalInstance", 'data', 'num', 'vote', '$cookies', '$cookieStore', 'Facebook',
		function($scope, registerDataService, $rootScope, voteService, $http, $modalInstance, data, num, vote, $cookies, $cookieStore, Facebook){

		if (num === null || num === undefined) {
			$scope.data = data;
			// $scope.data.status = voteService.vote;
		} else if (num) {
			$scope.data = data[num];
			// $scope.data.vote = voteService.vote;
		} else {
			$scope.data = data;
		}

		$scope.vote = {};

        $scope.user = $cookies.user;

            if ($rootScope.user) {

                $scope.simpleVoteEvent = function(event, action) {

                    var eventID = event.id;

                    console.log(event.vote);

                    var userID = $scope.user.id;
                    var upVote = event.vote.upVote;
                    var downVote = event.vote.downVote;

                    var eventVote = -1;

                    if (action == 'up') {
                        event.vote.downVote = false;
                        if (upVote == true) {
                            eventVote = 1;
                        }
                    } else { // Downvote changed
                        event.vote.upVote = false;
                        if (downVote == true) {
                            eventVote = 0;
                        }
                    }
                    $http({
                        method: "POST",
                        url: '/userEvent',
                        data: {
                            'user_id' : userID,
                            'event_id' : eventID,
                            'vote' : eventVote
                        },
                        headers : {"Content-Type": "application/json"}
                    }).success(function(data){
                        if (!data) {
                            console.log("no vote, man");
                        } else if(data) {
                            console.log(data);
                        }
                    });

                };

            } else {

                $scope.voteEvent = function(event) {
                    $modal.open({
                        templateUrl: "templates/registrationModal.html",
                        controller: 'modalInstanceController',
                    });
                }
            }


            $scope.shareReveal =  function() {
            $('.share-overlay').fadeToggle();
        };

        $scope.currentURL = "http://" + window.location.host + "/share/";

        $scope.fbShare = function(url, title) {

            Facebook.ui({
                method: 'feed',
                link: url,
                caption: title
            }, function(response){
                if (response && !response.error_code) {
                    //alert(response);
                } else {
                    //alert('Error while posting.');
                }
            });
        };

            $scope.socialShare = function(url, name, size) {
                window.open(url, name, size);
            }

            $scope.sharedPush =  function(event_id, target) {

                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                for( var i=0; i < 5; i++ )
                    text += possible.charAt(Math.floor(Math.random() * possible.length));

                var sharedEvent;

                $scope.user = $cookies.user;

                if ($scope.user) {
                    var sharedEvent = {
                        'user_id' : $scope.user.id,
                        'event_id' : event_id,
                        'share_link_key' : text,
                        'share_target_platform' : target
                    }
                }

                $http.post("/sharedEvent", sharedEvent).success(function(data){

                });
            };


           //$scope.left = (screen.width/2)-(w/2);
           // $scope.top = (screen.height/2)-(h/2);

		//THIS IS WORKING AND REFLECTING VOTE IN MODAL, NEED TO DO THE OPPOSITE
		//The 'vote' service being registered in the controller is what is being resolved by firing the modal,
		//thus giving the new template access to it

		$scope.vote = vote;

		$scope.ok = function () {
			$modalInstance.close($scope.selected.item);
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	}
]);*/

// cityHapps.controller("shareController", function($scope, $modal, $http, $rootScope, $location, $routeParams){


// });


//
//Handles All event modals not on homepage
//
/*cityHapps.controller("simpleModalInstanceController", ["$scope", "$modalInstance", 'data', '$http', '$rootScope', '$cookies', '$cookieStore', 'voteService', 'Facebook', '$modal', '$window',
		function($scope, $modalInstance, data, $http, $rootScope, $cookies, $cookieStore, voteService, Facebook, $modal, $window){

		$scope.data = data;
        if ($window.innerWidth > 768) {
            $scope.description = data.description;
        }

        //$scope.vote = data.vote;
        //    console(data);

        //for (var i = 0; i < $scope.data.length; i++) {
        //    data.vote = {
        //        upVote: false,
        //        downVote: false
        //    };
        //    if (data.votes.length > 0) {
        //        if ($scope.data.votes.vote == '1') {
        //            $scope.data.votes.vote.upVote = true;
        //        }
        //
        //        if ($scope.data.votes.vote == '0') {
        //            $scope.data.votes.downVote = true;
        //        }
        //    }
        ////}



        console.log(data);
            $scope.user = $cookies.user;

            if ($scope.user) {
                $scope.simpleVoteEvent = function(event, action) {

                    var eventID = event.id;

                    console.log(event.vote);

                    var userID = $scope.user.id;
                    var upVote = event.vote.upVote;
                    var downVote = event.vote.downVote;

                    var eventVote = -1;

                    if (action == 'up') {
                        event.vote.downVote = false;
                        if (upVote == true) {
                            eventVote = 1;
                        }
                    } else { // Downvote changed
                        event.vote.upVote = false;
                        if (downVote == true) {
                            eventVote = 0;
                        }
                    }
                    $http({
                        method: "POST",
                        url: '/userEvent',
                        data: {
                            'user_id' : userID,
                            'event_id' : eventID,
                            'vote' : eventVote
                        },
                        headers : {"Content-Type": "application/json"}
                    }).success(function(data){
                        if (!data) {
                            console.log("no vote, man");
                        } else if(data) {
                            console.log(data);
                        }
                    });

                };

            } else {
                $scope.voteEvent = function(event) {
                    $modal.open({
                        templateUrl: "templates/registrationModal.html",
                        controller: 'modalInstanceController',
                    });
                }
            }
            //if (slides['first'] != undefined) {
            //    slides['first'].vote = {
            //        upVote: false,
            //        downVote: false
            //    };
            //    if ($scope.vote.length > 0) {
            //        if ($scope.vote == '1') {
            //            $scope.vote.upVote = true;
            //        }
            //        if ($scope.vote == '0') {
            //            slides['first'].vote.downVote = true;
            //        }
            //    }
            //};

        $scope.shareReveal =  function() {

            $('.share-overlay').fadeToggle();
        }

		$scope.ok = function () {
			$modalInstance.close($scope.selected.item);
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};

            $scope.currentURL = "http://" + window.location.host + "/share/";

            $scope.fbShare = function(url, title) {
                Facebook.ui({
                    method: 'feed',
                    link: url,
                    caption: title
                }, function(response){
                    if (response && !response.error_code) {
                        //alert(response);
                    } else {
                        //alert('Error while posting.');
                    }
                });
            };

            $scope.socialShare = function(url, name, size) {
                window.open(url, name, size);
            };
        }
]);*/

/*cityHapps.factory('getCategories', function($http){
       return $http({
            method: "GET",
            url: "/category",
            headers: {"Content-Type": "application/json"}
        }).success(function (data) {
            if (!data) {
                console.log('Unable to Get Categories');
            } else if (data) {
                console.log('successfully Getting Categories');
                console.log(data);

            }
        });
});*/

/*cityHapps.factory('getUserCategories', function($http){
    return {
        params : function(args) {
            return $http({
                method: "GET",
                url: "/getUserCategories" + args,
                headers: {"Content-Type": "application/json"}
            }).success(function (data) {
                if (!data) {
                    console.log('Unable to Get Categories');
                } else if (data) {
                    console.log('successfully Getting Categories');
                    console.log(data);

                }
            });
        }
    }
});*/

/*cityHapps.factory('updateUserCategories', function($http){
    return {
        params : function(args) {
            return $http({
                method: "GET",
                url: "/getUserCategories" + args,
                headers: {"Content-Type": "application/json"}
            }).success(function (data) {
                if (!data) {
                    console.log('Unable to Get Categories');
                } else if (data) {
                    console.log('successfully Getting Categories');
                    console.log(data);

                }
            });
        }
    }
});*/

//
//Handles Log in and registration modals
//
/*cityHapps.controller("modalInstanceController", ["$scope", "$modalInstance", "$http", "registerDataService", "authFactory", "getCategories",
		function($scope, $modalInstance, $http, registerDataService, authFactory){

		$scope.formData = registerDataService.data;

            $scope.getCategories = function() {
                $http({
                    method: "GET",
                    url: "/category",
                    headers: {"Content-Type": "application/json"}
                }).success(function(data) {
                    if(!data) {
                        console.log('Unable to Get Categories');
                    } else if (data) {
                        console.log('successfully Getting Categories');
                        console.log(data);
                        $scope.categories = data;

                        return $scope.categories;
                    }
                });
            };

		$scope.getCategories();

		$scope.checkCategories = function() {

			// console.log($scope.formData.categories);
			var obj = $scope.formData.categories;

			for (var key in obj) {
				if ( obj[key] === false ) {
					return false;
					console.log(false);
				} else {
					return true;
					console.log(true);
				}
			}

		};


		$scope.emailSetArgs = function( val, el, attrs, ngModel ) {
    		return { email: val };
		};

		$scope.ok = function () {
			$modalInstance.close($scope.selected.item);
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};

		$scope.loginInfo = {
			"email" : $scope.formData.email,
			"password" : $scope.formData.password
		}


		$scope.loginUser = function(data) {
            if (data) {
				authFactory.loginUser(data);
			} else {
				authFactory.loginUser($scope.formData);
			}
		}
	}

]);*/


/*cityHapps.controller('loginController', [ "$rootScope", "$scope", "$controller", "registerDataService", "$http", 'authService', '$modal', function($rootScope, $scope, $http, registerDataService, authService, $controller, $modal) {

		//$controller('appController', {$scope:$scope});

		$scope.formData = registerDataService.data;

	}

]);*/

/*cityHapps.service('Session', function(){

	this.create = function(sessionId, userId) {
		this.id = sessionId;
		this.userId = userId;
	};

	this.destroy = function() {
		this.id = null;
		this.userId = null;
	};

});*/


/*cityHapps.controller('mapController',['$scope', 'GoogleMapApi'.ns(), 'getEvents', '$modal', '$log', '$http', 'getCategories', '$cookies', '$cookieStore',
	function($scope, GoogleMapApi, getEvents, $modal, $log, $http, getCategories, $cookies, $cookieStore) {

	//handle tabs inside mapController
    $scope.ifLimit = function(length) {
        return length >= 20;
    }

	$scope.tabs = [
		{ title:'Events', content:'Dynamic content 1' },

		// Activities tab if we need it
		// { title:'Activities', content:'Dynamic content 2' }
	];

    var userID = null;

    var cookie = $cookies.user;
    if (cookie) {
        userID = cookie.id;
    }

    $scope.userID = userID;

	$scope.scrollTop = function() {
		document.body.scrollTop = 0;
	};

		$scope.showEvents = function() {
			console.log($scope.tabEvents);
		}

		
		//$scope.mapEventModal = function(data) {
//			$modal.open({
//				templateUrl: "templates/eventModal.html",
//				controller: 'simpleModalInstanceController',
//				resolve: {
//					data: function() {
//						alert('Fire 1');
//						return data;
//					},
//					vote: function() {
//						alert($scope.vote);
//						return $scope.vote;
//					}
//				}
//			});
//		};
		

	//sloppy code re-use, but im doing it for demo
	$scope.now = moment().format("dddd, MMMM Do");


	var next = 0;
	$scope.nextDay = function() {
			next += 1;
			$scope.now = moment().add((next),'days').format("dddd, MMMM Do");
			$scope.nowGet = moment().add(next,'days').format();
			$scope.nowDateGet = moment().add(next, 'days').format('YYYY-MM-DD');

            var end = moment().add(next ,'days').endOf('day').format('YYYY-MM-DD');

			$http.get('/events?user_id = ' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&end_date=" + end)
				.success(function(data){
					$scope.eventData = data;
					drawEvents(data);
			});
		};

		$scope.prevDay = function() {
			next -= 1;
			$scope.now = moment().add(next, 'days').format("dddd, MMMM Do");
			$scope.nowGet = moment().add(next,'days').format();
			$scope.nowDateGet = moment().add(next, 'days').format('YYYY-MM-DD');

            var end = moment().add(next ,'days').endOf('day').format('YYYY-MM-DD');

			$http.get('/events?user_id = ' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&end_date=" + end )
				.success(function(data){
					$scope.eventData = data;
					drawEvents(data);
			});
		};
	//end sloppy code re-use

		var drawEvents = function(data) {

			$scope.mapMarkerModal = function(data) {

				$scope.mapMarkerEvents = data;
				$modal.open({
					templateUrl: "templates/eventModal.html",
					controller: 'simpleModalInstanceController',
					resolve: {
						data: function() {
							return data;
						}
					}
				});
			}

			$scope.markers = [];
			$scope.markers.id = [];

            //$('.map-event').each(function(i) {
            //$(this).delay((i++) * 500).fadeTo(1000, 1); });

			$scope.tabEvents = data.events;
			console.table($scope.markers);

			for (var tab = 0; tab < $scope.tabEvents.length; tab++) {
				$scope.tabEvents[tab].vote = {
					upVote: false,
					downVote: false
				};
				if ($scope.tabEvents[tab].votes.length > 0) {
					if ($scope.tabEvents[tab].votes[0].vote == '1') {
						$scope.tabEvents[tab].vote.upVote = true;
					}

					if ($scope.tabEvents[tab].votes[0].vote == '0') {
						$scope.tabEvents[tab].vote.downVote = true;
					}
				}
			}

			// $scope.tabEvents.length
			// need to limit to 10 from server

			for (var i = 0; i < 10 ; i++ ) {
				$scope.markers.push({
					'latitude' : $scope.tabEvents[i].latitude,
					'longitude' : $scope.tabEvents[i].longitude,
				});
			}

			$scope.returnMarkers = function() {
				return $scope.markers;
			}

			$scope.mapEventModal = function(data) {

				$modal.open({
					templateUrl: "templates/eventModal.html",
					controller: 'simpleModalInstanceController',
					resolve: {
						data: function() {
							return data;
						}
					}
				});
			};
		};

		$scope.pageCount = 1;

        if ($scope.pageCount <= 1) {
            $scope.disablePrev = true;
        }

        $scope.nowGet = moment().add(next,'days').format();
        $scope.nowDateGet = moment().add(next, 'days').format('YYYY-MM-DD');

		$scope.getNextEvents = function() {

			$scope.pageCount++;
			$scope.tabEvents = {};

            if ($scope.queryString !== null) {
                var string = "&" + $scope.queryString;
            } else if ($scope.queryString == null) {
                string = "";
            }

            $scope.nowDateGet = moment().add(next, 'days').format('YYYY-MM-DD');

			$http.get('/events?user_id=' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&page_size=10&page_count='+ $scope.pageCount + string ).success(function(pagedEvents){
				console.log(pagedEvents.events);
				$scope.tabEvents = pagedEvents.events;
			});
		}

		$scope.getPrevEvents = function() {
			$scope.pageCount--;

            $scope.disablePrev = function() {
                if($scope.pageCount <= 1) {
                    return true;
                }
            }

            if ($scope.queryString !== null) {
                var string = "&" + $scope.queryString;
            } else if ($scope.queryString == null) {
                var string = "";
            }

			$http.get('/events?user_id=' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&page_size=10&page_count='+ $scope.pageCount + "&" + string).success(function(pagedEvents){
				$log.info(pagedEvents.events);
				$scope.tabEvents = pagedEvents.events;
			});
		}

		getEvents.events().success(drawEvents);


		$scope.iconPath = function() {
			return "/img/marker.png";
		}

		$scope.idKey = function() {
			for (var i = 0; i < $scope.tabEvents.length; i++) {
				return i;
			}
		}

        $scope.categoryToggle = function() {
            $(".categoriesDropdown").fadeToggle();
            $(".categoriesDropdownMap").fadeToggle();

            getCategories.success(function(data){
                $scope.categories = data;
            });
        }

        $scope.clearAll = function() {
            $scope.filterData.categories = {};

            $scope.queryString = null;
            $scope.pageCount = 1;

            $http.get("/events?user_id=" + $scope.userID + "&start_date="+ $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&page_size=10&page_count=1')
                .success(function(data){
                    $scope.tabEvents = data.events;
                    //drawEvents(data.events);

                    console.log(data);
                });
        }

        $scope.filterData = {};
        $scope.filterData.categories = {};

        $scope.filterCategory = function() {

            $scope.pageCount = 1;

            $scope.queryString = '';

            for (var i in $scope.filterData.categories){
                console.log(i);
                if ($scope.filterData.categories[i] == true) {
                    $scope.queryString += "&category[]=" + i;
                }
            }

            $http.get("/events?" + "user_id=" + $scope.userID + "&start_date="+ $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&page_count=" + $scope.pageCount + "&page_size=10" + $scope.queryString)
                .success(function(data){
                    $scope.tabEvents = data.events;
                    //drawEvents(data.events);

                    console.log(data);
                });

            console.log($scope.filterData.categories);
        }


		$scope.map = {
			center: {
				latitude: 33.7550,
				longitude: -84.3900
			},
			zoom: 14,
            scrollwheel: false,
			events: {
				tilesloaded: function (map) {
					$scope.$apply(function () {
						$log.info('this is the map instance', map);
						$scope.mapInstance = map;
						console.log($scope.mapInstance);
					});
				},
				dragend: function(){

					$scope.markers = [];

					$log.info($scope.map.center);

					$http.post('/geoEvents', $scope.map.center)
						.success(function(newData){
							$log.info(newData);
							$scope.tabEvents = newData;

								for(var i=0; i < newData.length; i++) {
									$scope.markers.push(newData[i]);
								}

					});
				}
			}
		};

}]);*/

/*cityHapps.controller('calController', function($scope, getEvents, uiCalendarConfig, $modal, $rootScope, $http, getCategories, getEventsMonthStart, $location, $cookies, $cookieStore) {

	$scope.alertTest = function() {
		//alert('firing on click');
	}

    //Needs to be broken out into a factory
    var userID = null;

    var cookie = $cookies.user;
    if (cookie) {
        userID = cookie.id;
    }

    $scope.userID = userID;

    $scope.categoryToggle = function() {
        $(".categoriesDropdown").fadeToggle();

        getCategories.success(function(data){
            $scope.categories = data;
        });
    }

    $scope.filterData = {};
    $scope.filterData.categories = {};
    var firstOfMonth = moment().startOf('month').format();
    var endOfMonth = moment().endOf('month').format();

    $scope.filterCategory = function() {

        var queryString = '';

        for (var i in $scope.filterData.categories){
            console.log(i);
            if ($scope.filterData.categories[i] == true) {
                queryString += "&category[]=" + i;
                //+ "&";
            }
        }

        $http.get("/events?user_id=" + $scope.userID + "&start_date="+firstOfMonth + "&end_date=" + endOfMonth + queryString + "&max_per_day=5")
            .success(function(data){
                $scope.eventData = data;
                calEvents(data);
                console.log(data);
            });

        console.log($scope.filterData.categories);
    }

    $scope.uiConfig = {
		eventClick: $scope.alertTest()
	}

    $scope.events = [];



    var calEvents = function(data) {


        for (var i = 0; i < data.events.length; i++) {
            $scope.events.push({
                title : data.events[i].event_name,
                start: data.events[i].start_time,
                end : data.events[i].end_time,
                allData : data.events[i]
            });
        }
    }

    $scope.uiConfig = {
        calendar: {
            //height: 850
            eventLimit: {
                month : 5,
                default: true
            },
            header : {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            eventTextColor: '#2C2625',
            eventBackgroundColor: 'transparent',
            eventClick: function(calEvent, jsEvent, view) {
               //alert(JSON.stringify(calEvent.allData));
                $modal.open({
                    templateUrl: "templates/eventModal.html",
                    controller: 'simpleModalInstanceController',
                    resolve: {
                        data: function() {
                            return calEvent.allData;
                        },
                    }
                });
            },
            viewRender : function(view) {
                //ugly but works by getting the date abbreviation from the classname and adding it to each cal day cell.
                $('td.fc-day-number').each(function () {
                    var day = $(this)[0].className.substr(17, 20)
                        .split(" ");

                    var dayShort = day[0].charAt(0).toUpperCase() + day[0].slice(1);

                    $(this).one().prepend(dayShort + " ");

                });

                $(".fc-day").each(function () {
                    // console.log($(this));
                    var eventCount = $(this).children()
                                    .find('.fc-event-container').length;

                    $(this).one().prepend("<div class='event-count'>" +  eventCount + "</div>");

                });


                $scope.now = moment().format("MMMM");

                var next = 0;

                //Month Paginator
                $('.fc-right').on('click', function(){

                    next += 1;
                    $scope.now = moment().add((next),'months').format("MMMM");
                    $scope.nowGet = moment().add(next,'months').date(1).format();
                    $scope.nowDateGet = moment().add(next, 'months').date(1).format("YYYY-MM-DD");

                    $http.get('/events?user_id=' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&end_date=' + moment().add((next + 1),'months').date(1).format("YYYY-MM-DD") + '&max_per_day=5')
                        .success(function(data){
                            // $scope.eventSource = [data];
                            calEvents(data);
                        });
                });

                $('.fc-left').on('click', function(){

                    next -= 1;
                    $scope.now = moment().add(next, 'months').format("MMMM");
                    $scope.nowGet = moment().add(next,'months').date(1).format();
                    $scope.nowDateGet = moment().add(next, 'months').date(1).format("YYYY-MM-DD");

                    $http.get('/events?user_id=' + $scope.userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&end_date=' + moment().add((next + 1), 'months').date(1).format("YYYY-MM-DD") + '&max_per_day=5')
                        .success(function(data){
                            // $scope.eventSource = [data];
                            calEvents(data);
                        });
                });
            },
            lazyFetching : true,
            dayClick : function(date, jsEVent, view) {

                var formatDate = date.format();

                $location.path('/day/' + formatDate);

            }
        }
    }

    //var start = moment.format()

    getEventsMonthStart.events().success(calEvents);

    $scope.eventSource =[$scope.events];

	//console.table($scope.events);

});*/





/*cityHapps.controller("dayController", function($scope, getEvents, $modal, $http, getCategories, daySliderFactory, $rootScope, $location, calDayClick, $routeParams, $cookies, $cookieStore) {

    var userID = null;

    var cookie = $cookies.user;
    if (cookie) {
        userID = cookie.id;
    }

    $scope.userID = userID;

        $rootScope.now = moment().format("dddd, MMMM, Do");


        if ($location.path().indexOf('share') > -1) {

            $http.get("/events?user_id=" + $scope.userID + "&id=" + $routeParams.id).success(function(data){

                $modal.open({
                    templateUrl: "templates/eventModal.html",
                    controller: 'simpleModalInstanceController',
                    resolve: {
                        data: function() {
                            return data.events[0];
                        }
                    }
                });

                $rootScope.now = moment(data.events[0].event_date).format("dddd, MMMM, Do");
                $rootScope.nowShareDate = moment(data.events[0].event_date).format("YYYY-MM-DD");

                    var dayEvents = function(data) {

                        $scope.dayEvents = data.events;
                        $scope.eventGroup =  [];

                        for (i = 0; i < $scope.dayEvents.length; i++) {

                            $scope.dayEvents[i].vote = {
                                upVote: false,
                                downVote: false
                            };
                            if ($scope.dayEvents[i].votes && $scope.dayEvents[i].votes.length > 0) {
                                if ($scope.dayEvents[i].votes[0].vote == '1') {
                                    $scope.dayEvents[i].vote.upVote = true;

                                }

                                if ($scope.dayEvents[i].votes[0].vote == '0') {
                                    $scope.dayEvents[i].vote.downVote = true;
                                }
                            }
                        };
                    };

                getEvents.eventsDay(data.events[0].event_date).success(dayEvents);
            });
        } else {
            $scope.nowGet = moment().format();
        }


        // $scope.now = moment().format("dddd, MMMM Do");
        //Needs to be broken out into a factory

        $rootScope.nowDate = moment().format('YYYY-MM-DD');


        $scope.queryString = null;

        var next = 0;

        $scope.nextDay = function() {

            next += 1;

            if ($scope.queryString !== null) {
                var string = "&" + $scope.queryString;
            } else if ($scope.queryString == null) {
                string = "";
            }

             if ($location.path().indexOf('share') > -1) {

                daySliderFactory.nextDay($rootScope.nowShareDate, next, string).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            } else {
                daySliderFactory.nextDay($rootScope.nowDate, next, string).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            }

        };

        $scope.prevDay = function() {

            next -= 1;

            if ($scope.queryString !== null) {
                var string = "&" + $scope.queryString;
            } else if ($scope.queryString == null) {
                string = "";
            }

            if ($location.path().indexOf('share') > -1) {

                daySliderFactory.nextDay($rootScope.nowShareDate, next, string).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            } else {
                daySliderFactory.nextDay($rootScope.nowDate, next, string).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            }


        };

        $scope.categoryToggle = function() {
            $(".categoriesDropdown").fadeToggle();

            getCategories.success(function(data){
                $scope.categories = data;
            });
        }

        $scope.filterData = {};
        $scope.filterData.categories = {};

        //should also be a factory instead of being repeated everywhere
        $scope.filterCategory = function() {

            $scope.queryString = '';

            for (var i in $scope.filterData.categories){
                console.log(i);
                if ($scope.filterData.categories[i] == true) {
                    $scope.queryString += "&category[]=" + i;
                }
            }

            if ($location.path().indexOf('share') > -1) {

                daySliderFactory.nextDay($rootScope.nowShareDate, next, $scope.queryString).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            } else {
                daySliderFactory.nextDay($rootScope.nowDate, next, $scope.queryString).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            }
        };

        $scope.clearAll = function() {
            $scope.filterData.categories = {};
            $scope.queryString = null;
            $scope.pageCount = 1;

            //+ '&page_size=10&page_count=1'

            if ($location.path().indexOf('share') > -1) {

                daySliderFactory.nextDay($rootScope.nowShareDate, next, $scope.queryString).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            } else {
                daySliderFactory.nextDay($rootScope.nowDate, next, $scope.queryString).success(function(data, time){
                    $scope.dayEvents = data.events;
                });

            }
        };


        $scope.eventModal = function(data, vote) {

            $modal.open({
                templateUrl: "templates/eventModal.html",
                controller: 'simpleModalInstanceController',
                resolve: {
                    data: function() {
                        return data;
                    },
                    vote : function() {
                        return vote;
                    }
                }
            });
        };

        $scope.$on('search', function(info, data){
            $scope.dayEvents = data.events;

            for (i = 0; i < $scope.dayEvents.length; i++) {

                $scope.dayEvents[i].vote = {
                    upVote: false,
                    downVote: false
                };
                if ($scope.dayEvents[i].votes && $scope.dayEvents[i].votes.length > 0) {
                    if ($scope.dayEvents[i].votes[0].vote == '1') {
                        $scope.dayEvents[i].vote.upVote = true;

                    }

                    if ($scope.dayEvents[i].votes[0].vote == '0') {
                        $scope.dayEvents[i].vote.downVote = true;
                    }
                }
            };

        });

        var dayEvents = function(data) {

            // if

            $scope.dayEvents = data.events;
            $scope.eventGroup =  [];

            for (i = 0; i < $scope.dayEvents.length; i++) {

                $scope.dayEvents[i].vote = {
                    upVote: false,
                    downVote: false
                };
                if ($scope.dayEvents[i].votes && $scope.dayEvents[i].votes.length > 0) {
                    if ($scope.dayEvents[i].votes[0].vote == '1') {
                        $scope.dayEvents[i].vote.upVote = true;

                    }

                    if ($scope.dayEvents[i].votes[0].vote == '0') {
                        $scope.dayEvents[i].vote.downVote = true;
                    }
                }
            };

        };

    // $rootScope.$on('calDay', function(info, formatDate){

    //     calDayClick.dateClicked(formatDate).success(dayEvents);

    // });


    getEvents.eventsDay($rootScope.nowDate).success(dayEvents);

});*/



/*cityHapps.factory("daySliderFactory", function($http, $rootScope, $cookies, $cookieStore){

    var userID = null;

    var cookie = $cookies.user;
    if (cookie) {
        userID = cookie.id;
    }

    return {
        nextDay : function(time, next, queryString) {
            $rootScope.now = moment(time).add(next, 'days').format("dddd, MMMM Do");

            var startTime = moment(time).add(next,'days').format();
            var startDate = moment(time).add(next,'days').format('YYYY-MM-DD');

            var end = moment(time).add(next ,'days').endOf('day').format('YYYY-MM-DD');

            return $http.get('/events?user_id=' + userID + '&start_date=' + startDate + '&start_time=' + startTime + "&end_date=" + end + queryString)
                .success(function(data){


                    console.log(data);
                });
        },

        prevDay : function(time, next, queryString) {

            $rootScope.now = moment(time).add(next, 'days').format("dddd, MMMM Do");

            var startTime = moment(time).add(next,'days').format();
            var startDate = moment(time).add(next,'days').format('YYYY-MM-DD');

            var end = moment(time).add(next ,'days').endOf('day').format('YYYY-MM-DD');

            return $http.get('/events?user_id=' + userID + '&start_date=' + startDate + '&start_time=' + startTime + "&end_date=" + end + queryString)
                .success(function(data){

                    console.log(data);
                });
        },

    }
});*/


/*cityHapps.controller("calDayController", function($scope, getEvents, $modal, $http, getCategories, $rootScope, $location, $location,
    calDayClick, $routeParams, $window, daySliderFactory, $cookies, $cookieStore) {

    var userID = null;

    var cookie = $cookies.user;
    if (cookie) {
        userID = cookie.id;
    }

    $scope.userID = userID;

    var dayEvents = function(data) {

        $scope.dayEvents = data.events;
        $scope.eventGroup =  [];
        $rootScope.now = moment($routeParams.date).format("dddd, MMMM Do");

        //Needs to be broken out into a factory


        $scope.queryString = null;

        var next = 0;

        $scope.nextDay = function() {

            next += 1;

            if ($scope.queryString !== null) {
                var string = "&" + $scope.queryString;
            } else if ($scope.queryString == null) {
                string = "";
            }

            daySliderFactory.nextDay($routeParams.date, next, string).success(function(data, time){
                $scope.dayEvents = data.events;
            });

        };

        $scope.prevDay = function() {

            next -= 1;

            if ($scope.queryString !== null) {
                var string = "&" + $scope.queryString;
            } else if ($scope.queryString == null) {
                string = "";
            }


            daySliderFactory.prevDay($routeParams.date, next, string).success(function(data, time){
                $scope.dayEvents = data.events;
            });
        };

        $scope.categoryToggle = function() {
            $(".categoriesDropdown").fadeToggle();

            getCategories.success(function(data){
                $scope.categories = data;
            });
        }

        $scope.filterData = {};
        $scope.filterData.categories = {};

        //should also be a factory instead of being repeated everywhere
        $scope.filterCategory = function() {

            $scope.queryString = '';

            for (var i in $scope.filterData.categories){
                console.log(i);
                if ($scope.filterData.categories[i] == true) {
                    $scope.queryString += "category[]=" + i + "&";
                }
            }

            $http.get("/events?user_id=" + $scope.userID + "&start_date="+ $scope.nowDateGet + '&start_time=' + $scope.nowGet +
            "&page_size=10&" + "end_date=" + $scope.endDate + "&" + $scope.queryString)
                .success(function(data){
                    dayEvents(data);
                    console.log(data);
                });

            console.log($scope.filterData.categories);
        };

        $scope.clearAll = function() {
            $scope.filterData.categories = {};
            $scope.queryString = null;
            $scope.pageCount = 1;

            //+ '&page_size=10&page_count=1'

            $http.get("/events?user_id=" + $scope.userID + "&start_date="+ $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&end_date=" + $scope.endDate + "&")
                .success(function(data){
                    //$scope.tabEvents = data.events;
                    dayEvents(data);

                    console.log(data);
                });
        };


        $scope.eventModal = function(data, vote) {

            $modal.open({
                templateUrl: "templates/eventModal.html",
                controller: 'simpleModalInstanceController',
                resolve: {
                    data: function() {
                        return data;
                    },
                    vote : function() {
                        return vote;
                    }
                }
            });
        };

    };


    calDayClick.dateClicked($routeParams.date).success(dayEvents);

});*/




// cityHapps.controller('registrationModal', function($scope){
// $scope.open();

// });

/* ***************************** */
/* TEST HARNESS                  */
/* -- Start --                   */
/* ***************************** */
/*cityHapps.controller('harnessController', ['$scope', '$http', function($scope, $http) {

	$scope.showEventful = false;
	$scope.showActive = false;
	$scope.showMeetup = false;

	$scope.showEvents = function(eventSource) {

		$scope.eventData = null;

		$scope.showEventful = false;
		$scope.showActive = false;
		$scope.showMeetup = false;
		$scope.showEventbrite = false;

		var eventRoute = '';

		switch(eventSource) {
			case "Eventful":
				eventRoute = "eventfulEvents";
				break;
			case "Active":
				eventRoute = "activeEvents";
				break;
			case "Meetup":
				eventRoute = "meetupEvents";
				break;
			case "Eventbrite":
				eventRoute = "eventbriteEvents";
				break;
			default:
				// This should never happen
		}

		if (eventRoute != '') {

			var events = $http.get('/' + eventRoute);

			events.success(function(data) {

				console.log(data);

                switch(eventSource) {
					case "Eventful":
						$scope.eventData = data.events["event"];
                        $scope.showEventful = true;
                        break;
					case "Active":
						$scope.eventData = data.results;
						$scope.showActive = true;
						break;
					case "Meetup":
						$scope.eventData = data.results;
						$scope.showMeetup = true;
						break;
					case "Eventbrite":
						$scope.eventData = data.events;
						$scope.showEventbrite = true;
						break;
					default:
                        // This should never happen
                }
            });
        }
    };

}]);*/
/* ***************************** */
/* TEST HARNESS                  */
/* -- Stop --                   */
/* ***************************** */
