var cityHapps = angular.module('cityHapps', ['ui.bootstrap', 'ngRoute', 'ui.validate',
	'facebook', 'http-auth-interceptor', 'remoteValidation', 'google-maps'.ns(), 'ngTouch',
    'ui.calendar', 'angular.filter', 'ngSanitize']);

cityHapps.controller("eventsController", function($scope, $http, $filter, $modal, registerDataService, voteService, getEvents, getRecommendedEvents, $window, getCategories) {

	$scope.formatAMPM = function(date) {
		var hours = date.getHours();
		var minutes = date.getMinutes();
		var ampm = hours >= 12 ? 'pm' : 'am';
		hours = hours % 12;
		hours = hours ? hours : 12; // the hour '0' should be '12'
		minutes = minutes < 10 ? '0'+minutes : minutes;
		var strTime = hours + ':' + minutes + ' ' + ampm;
		return strTime;		
	};
		$scope.mobile = function() {
			if ($window.innerWidth <= 768 ) {
				return true;
			} else {
				return false;
			}
		};

        $scope.fadeIn = function(speed) {
            $('.carousel-inner').fadeIn(speed);
        }

        var recommendedEventSuccess = function(data) {
        	$scope.recommendedEventData = data.events;
        	console.log($scope.recommendedEventData);

        	if ($scope.recommendedEventData != undefined) {

        		$scope.recSlideGroup = [];

        		var rec;
	            for (rec = 0; rec < $scope.recommendedEventData.length; rec += 4) {
				
					var recSlides = {
						'first' : $scope.recommendedEventData[rec],
						'second' : $scope.recommendedEventData[rec + 1],
						'third' : $scope.recommendedEventData[rec + 2],
						'fourth' : $scope.recommendedEventData[rec + 3]
					};

					var mobileRecSlides = $scope.recommendedEventData[rec];
			
					if ($window.innerWidth <= 768 ) {
						$scope.recSlideGroup.push(mobileRecSlides);

		                /*
		                $scope.recEventModalMobile = function(data) {

							$modal.open({
								templateUrl: "templates/eventModal.html",
								controller: 'simpleModalInstanceController', 
								resolve: {
									data: function() {
										return data;
									},
								}
							});
						};
						*/

					} else {
						$scope.recSlideGroup.push(recSlides);
					}

					$scope.recommendedEventData[rec].upvoted = "";
				}
			}

        }
		
		var eventSuccess = function(data) {

			$scope.eventData = data.events;
			console.log($scope.eventData);

			if ($scope.eventData != undefined) {
				
				$scope.eventCount = data.meta.count;

				$scope.slideGroup = [];
				//console.log($scope.slideGroup);

				var i;
	            for (i = 0; i < $scope.eventData.length; i += 4) {
				
					var slides = {
					'first' : $scope.eventData[i],
					'second' : $scope.eventData[i + 1],
					'third' : $scope.eventData[i + 2],
					'fourth' : $scope.eventData[i + 3]
				};

				var mobileSlides = $scope.eventData[i];
			
				if ($window.innerWidth <= 768 ) {
					$scope.slideGroup.push(mobileSlides);

	                $scope.eventModalMobile = function(data) {

						$modal.open({
							templateUrl: "templates/eventModal.html",
							controller: 'simpleModalInstanceController', 
							resolve: {
								data: function() {
									return data;
								},
							}
						});
					};

				} else {
					$scope.slideGroup.push(slides);
				}

				$scope.eventData[i].upvoted = "";
			}
			
		}

		$scope.eventModal = function(data, num, vote) {

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
						return vote;
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
            eventSuccess(data);
        });
    }

    $scope.filterData = {};
    $scope.filterData.categories = {};

    $scope.filterCategory = function() {

        var queryString = '';

        for (var i in $scope.filterData.categories){
            console.log(i);
            if ($scope.filterData.categories[i] == true) {
                queryString += "category=[]" + i;
                //+ "&";
            }
        }

        $http.get("/events?" + queryString)
            .success(function(data){
                eventSuccess(data);
                console.log(data);
            });

        console.log($scope.filterData.categories);
    }


	getEvents.events().success(eventSuccess);
	getRecommendedEvents.events().success(recommendedEventSuccess);

	$scope.now = moment().format("dddd, MMMM Do");
	//$scope.nowPost = moment().format();

		var next = 0;

        var next = 0;
        $scope.nextDay = function() {
            // debugger;
            next += 1;
            $scope.now = moment().add((next),'days').format("dddd, MMMM Do");
            $scope.nowGet = moment().add(next,'days').format();
        	$scope.nowDateGet = moment().add(next,'days').format('YYYY-MM-DD');

            $http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
                .success(function(data){
                    $scope.eventData = data;
                    eventSuccess(data);
                });
        };

        $scope.prevDay = function() {
            next -= 1;
            $scope.now = moment().add(next, 'days').format("dddd, MMMM Do");
            $scope.nowGet = moment().add(next,'days').format();
        	$scope.nowDateGet = moment().add(next,'days').format('YYYY-MM-DD');

            $http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
                .success(function(data){
                    $scope.eventData = data;
                    eventSuccess(data);
                });
        };
        //end sloppy code re-use
});

cityHapps.filter('dateToISO', function() {
    return function(input) {
        input = new Date(input).toISOString();
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

cityHapps.factory('voteService', function(){

	var vote = {};

	return {
		getData: function() {
			return vote;
		}
	};

});


cityHapps.factory('getEvents', function($http){
	return {
		events : function() {
			var startDate = moment().format('YYYY-MM-DD');
            var startTime = moment().format();

			// '?start_date=' + startDate
			return $http.get('/events?start_date=' + startDate + '&start_time=' + startTime).success(function(data) {
			
			});
		}
	}
});

cityHapps.factory('getRecommendedEvents', function($http) {
	return {
		events : function() {
			var userString = localStorage.getItem('user');
			var user = angular.fromJson(userString);
			var userID = user.data.id;

			var startDate = moment().format('YYYY-MM-DD');
            var startTime = moment().format();

			return $http.get('/recommendedEvents?user_id=' + userID + '&start_date=' + startDate + '&start_time=' + startTime).success(function(data) {
			
			});
		}
	}
});

var firstOfMonth = moment().startOf('month').format('YYYY-MM-DD');
var endOfMonth = moment().endOf('month').format('YYYY-MM-DD');
var maxPerDay = 5;

cityHapps.factory('getEventsMonthStart', function($http){
   return  {
       events : function(){
           return $http.get('/events?start_date='+ firstOfMonth + '&end_date=' + endOfMonth + '&max_per_day=' + maxPerDay).success(function(data){

           });
       }
   }

});

cityHapps.config([
    'FacebookProvider',
    function(FacebookProvider) {
     var myAppId = '895139070496415';

     FacebookProvider.init(myAppId);

    }
]);


	cityHapps.config(['GoogleMapApiProvider'.ns(), function (GoogleMapApi) {
	    GoogleMapApi.configure({
	        //    key: 'your api key',
	        v: '3.17',
	        libraries: 'weather,geometry,visualization'
	    });
	}]);

cityHapps.factory('search', function($http){
    return {
        searchData : function(query){
            return $http.get("/events?search="+ query)
                .success(function(data, scope){
                  console.log(data);

            });
        }
    };

});

cityHapps.controller('appController', ['$scope', '$window', 'authService', 'registerDataService', 'voteService', 'userData', '$rootScope', 'authFactory', '$http', '$modal', '$location', 'search',
	function($scope, $window, $rootScope, authService, registerDataService, voteService, userData, authFactory, $http, $modal, $location, search){


		$scope.mobile = function() {
			if ($window.innerWidth <= 768 ) {
				return true;
			} else {
				return false;
			}
		};

        $scope.search = function(query) {
            $location.path('/day');
            search.searchData(query).success(function(data){
                $scope.$broadcast('search', data);
            });
        };

		$scope.userString = localStorage.getItem('user');
		$scope.user = angular.fromJson($scope.userString);

		if ($scope.userString) {

			$scope.voteEvent = function(event, num, vote) {
				
				alert(vote);

				alert("This" + JSON.stringify(event[num])  + "has been upvoted by user " + $scope.user.data.id);

				//needs to be broken into a factory/ service soon
				$http({
					method: "POST",
					url: '/user_event',
					data: {
						'user_id' : $scope.user.data.id,
						'event_id' : event[num].id, 
						'vote' : vote[upVote]
					},

					headers : {"Content-Type": "application/json"}
				}).success(function(data){

					if (!data) {
						console.log("no vote, man");
						// $scope.loggedOut = false;;
					} else if(data) {
						console.log(data);
						// $scope.loggedOut = true;
					}
				});
				
			};

			$scope.downVote = function(event, num, vote) {
				alert("This" + JSON.stringify(event[num])  + "has been downvoted");

				$http({
					method: "POST",
					url: '/user_event',
					data: {
						'user_id' : $scope.user.data.id,
						'event_id' : event[num].id,
						'vote' : vote
					},

					headers : {"Content-Type": "application/json"}
				}).success(function(data){

					if (!data) {
						console.log("no vote, man");
						// $scope.loggedOut = false;
					} else if(data) {
						console.log(data);
						// $scope.loggedOut = true;
					}
				});
				// voteService.vote = vote;
			};

		} else {

			$scope.upVote = function(event) {
				$modal.open({
					templateUrl: "templates/registrationModal.html",
					controller: 'modalInstanceController', 
				});
			}

			$scope.downVote = function(event) {
				$modal.open({
					templateUrl: "templates/registrationModal.html",
					controller: 'modalInstanceController', 
				});
			}
		}

		console.log(registerDataService.vote);

		if ($scope.user) {
			console.log($scope.user.data.email);
		}

		$scope.formData = {
			email : '',
			password: ''
		};

		$scope.loginUser = function(formData) {
			authFactory.loginUser(formData);
		};


		$scope.vote = registerDataService.vote;

		$scope.$on('event:loginConfirmed', function(){
			// alert("youre logged in");

			$scope.upvoted = '';
			
		});

		console.log($rootScope.userData);
	}
]);



cityHapps.formData = {};

cityHapps.controller('registerFormController', [ "$scope", "$http", "$modal", "registerDataService", "$timeout", "authFactory", "Facebook", 
	function($scope, $http, $modal, registerDataService, $timeout, authFactory, Facebook ){

	//Facebook Auth 

      // Define user empty data :/
      $scope.user = {};

      /**
       * Watch for Facebook to be ready.
       * There's also the event that could be used
       */
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

      /**
       * Logout
       */
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
      
      /**
       * Taking approach of Events :D
       */
      $scope.$on('Facebook:statusChange', function(ev, data) {
        console.log('Status: ', data);
        if (data.status == 'connected') {
          $scope.$apply(function() {
            $scope.salutation = true;
            $scope.byebye     = false;   
          });
        } else {
          $scope.$apply(function() {
            $scope.salutation = false;
            $scope.byebye     = true;
            
            // Dismiss byebye message after two seconds
            $timeout(function() {
              $scope.byebye = false;
            }, 2000);
          });
        }
        
        
      });

	$scope.formData = registerDataService.data;

	// $scope.categoryService = categoryService.getCategories();

	var credentials = {
		"email" :  $scope.formData.email,
		"password" : $scope.formData.password
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
]);


cityHapps.factory("registerDataService", function(){

	var registerDataService = {};
	registerDataService.data = {};
	registerDataService.data.categories = {};

	registerDataService.vote = {};

	return registerDataService;


});


cityHapps.factory('authFactory', function($http, authService, $rootScope){

	var auth = {};

	auth.loginUser = function(formData) {

			$http.post('/auth/login', formData).then(function(res) {
				console.log(res);

				delete res.password;
				localStorage.setItem('user', JSON.stringify(res));


				authService.loginConfirmed();
				$rootScope.$broadcast('event:loginConfirmed');
			
				document.location.reload(true);

			});
		};

	auth.logoutUser = function() {
		$http({
			method: "GET",
			url: '/auth/logout',
			headers : {"Content-Type": "application/json"}
		}).success(function(data){
			localStorage.removeItem('user');
			document.location.reload(true);
			if (!data) {
				console.log("There was an error logging you out");
			} else if(data) {
				console.log("You have logged out");
			}
		});
	};

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

	return auth;


});

cityHapps.factory("FBLogin", function($http){


})


cityHapps.controller("modalController", function($scope, $modal, $http, authFactory, registerDataService){

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

});

cityHapps.controller("eventModalInstanceController", ["$scope", "registerDataService", 'voteService', "$modalInstance", 'data', 'num', 'vote', 'Facebook',
		function($scope, registerDataService, voteService, $modalInstance, data, num, vote, Facebook){

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

        $scope.shareReveal =  function() {

            $('.share-overlay').fadeToggle();
        };

            //var path = window.location.href.split("/");
            //var protocol = path[0];
            //var host = path[2];

        $scope.currentURL = "http://" + window.location.host + "/share/";

        $scope.fbShare = function(url, title) {
            alert(url);
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

           //$scope.left = (screen.width/2)-(w/2);
           // $scope.top = (screen.height/2)-(h/2);

		//THIS IS WORKING AND REFLECTING VOTE IN MODAL, NEED TO DO THE OPPOSITE
		//The 'vote' service being registered in the controller is what is being resolved by firing the modal, 
		//thus giving the new template access to it


		$scope.vote.status = vote;
		
		$scope.ok = function () {
			$modalInstance.close($scope.selected.item);
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	}
]);

cityHapps.directive('twitter', [
    function() {
        return {
            link: function(scope, element, attr) {
                setTimeout(function() {
                    twttr.widgets.createShareButton(
                        attr.url,
                        element[0],
                        function(el) {}, {
                            count: 'none',
                            text: attr.text
                        }
                    );
                });
            }
        }
    }
]);


cityHapps.controller("simpleModalInstanceController", ["$scope", "$modalInstance", 'data', 'voteService', 'Facebook',
		function($scope, $modalInstance, data, voteService, Facebook){
			
		$scope.data = data;
        $scope.description = data.description;

		console.log(data);

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
                alert(url);
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
]);

cityHapps.factory('getCategories', function($http){
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
});


cityHapps.controller("modalInstanceController", ["$scope", "$modalInstance", "$http", "registerDataService", "authFactory", "getCategories",
		function($scope, $modalInstance, $http, registerDataService, authFactory){

		$scope.formData = registerDataService.data;

        //
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

]);


// To be removed
cityHapps.factory('userData', function($rootScope, authService){

	var user = {};
	
	user.get = function() {
		alert("dafuq?");
	};

	user.setFB = function(fbData) {
		$rootScope.facebookData = fbData;
	};

	user.setUser = function(userData) {
		$rootScope.userData = userData;
	};

	return user;
});


cityHapps.controller('loginController', [ "$rootScope", "$scope", "$controller", "registerDataService", "$http", 'userData', 'authService', '$modal', function($rootScope, $scope, $http, userData, registerDataService, authService, $controller, $modal) {

		//$controller('appController', {$scope:$scope});

        $scope.registerOpen = function(size) {

            var modalInstance = $modal.open({
                templateUrl: "templates/registrationModal.html",
                controller: 'modalInstanceController',
                size: size
            });
        };

		$scope.formData = registerDataService.data;

	}

]);

cityHapps.service('Session', function(){

	this.create = function(sessionId, userId) {
		this.id = sessionId;
		this.userId = userId;
	};

	this.destroy = function() {
		this.id = null;
		this.userId = null;
	};

});

 
//handle all routing via anuglar templates

cityHapps.config(function($routeProvider, $locationProvider){

	$routeProvider
		.when("/", {
			// controller: 'eventsController',
			templateUrl: 'templates/homeView.html'
		})
		.when("/map", {
			// controller: 'mapViewController',
			templateUrl: 'templates/mapView.html'
		})
		.when("/calendar", {
			// controller: "calController",
			templateUrl: "templates/calView.html"
		})
        .when("/day", {
            // controller: "calController",
            templateUrl: "templates/dayView.html"
        })
        .when('/share/:id', {
            templateUrl: "templates/dayView.html"
        })
		.otherwise({redirectTo: "/"});

		// use the HTML5 History API
		$locationProvider.html5Mode(true);
});


cityHapps.controller('mapController',['$scope', 'GoogleMapApi'.ns(), 'getEvents', '$modal', '$log', '$http', 'getCategories',
	function($scope, GoogleMapApi, getEvents, $modal, $log, $http, getCategories) {
	
	//handle tabs inside mapController

	$scope.tabs = [
		{ title:'Events', content:'Dynamic content 1' },
		
		// Activities tab if we need it
		// { title:'Activities', content:'Dynamic content 2' }
	];


	$scope.scrollTop = function() {
		document.body.scrollTop = 0;
	};

		$scope.showEvents = function() {
			console.log($scope.tabEvents);
		}

		$scope.mapEventModal = function(data) {

			$modal.open({
				templateUrl: "templates/eventModal.html",
				controller: 'simpleModalInstanceController', 
				resolve: {
					data: function() {
						// alert('this is firing');
						return data;
					},
					vote: function() {
						alert($scope.vote);
						return $scope.vote;
					} 		
				}
			});
		};

	//sloppy code re-use, but im doing it for demo
	$scope.now = moment().format("dddd, MMMM Do");

	var next = 0;
	$scope.nextDay = function() {
			// debugger;
			next += 1;
			$scope.now = moment().add((next),'days').format("dddd, MMMM Do");
			$scope.nowGet = moment().add(next,'days').format();
			$scope.nowDateGet = moment().add(next, 'days').format('YYYY-MM-DD');

			$http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
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
			
			$http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
				.success(function(data){
					$scope.eventData = data;
					drawEvents(data);
			});
		};
	//end sloppy code re-use

        //$scope.marker = {
        //    options: {
        //        labelContent: ($index + 1) + (10 * (pageCount - 1)),
        //        labelAnchor: (25, 0),
        //        labelClass: 'markerLabel'
        //    }
        //}

		var drawEvents = function(data){
			
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

			$scope.tabEvents = data.events;
			console.table($scope.tabEvents);

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
							// alert('this is firing');
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

		$scope.getNextEvents = function() {
			$scope.pageCount++;
			$scope.tabEvents = {};

			$scope.now = moment().add(next,'days').format("dddd, MMMM Do");
			$scope.nowGet = moment().add(next,'days').format();
			$scope.nowDateGet = moment().add(next, 'days').format('YYYY-MM-DD');

			$http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&page_size=10&page_count='+ $scope.pageCount).success(function(pagedEvents){
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

			$scope.now = moment().add(next,'days').format("dddd, MMMM Do");
			$scope.nowGet = moment().add(next,'days').format();
			$scope.nowDateGet = moment().add(next, 'days').format('YYYY-MM-DD');

			// $scope.tabEvents = {};

			$http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&page_size=10&page_count='+ $scope.pageCount).success(function(pagedEvents){
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

            getCategories.success(function(data){
                $scope.categories = data;
            });
        }

        $scope.filterData = {};
        $scope.filterData.categories = {};

        $scope.filterCategory = function(cat) {

            var queryString = '';

            for (var i in $scope.filterData.categories){
                console.log(i);
                if ($scope.filterData.categories[i] == true) {
                    queryString += "category[]=" + i + "&";
                }
            }

            $http.get("/events?" + queryString)
                .success(function(data){
                    $scope.tabEvents = data;
                    drawEvents(data);

                    console.log(data);
                });

            console.log($scope.filterData.categories);
        }


		$scope.map = {
			center: {
				latitude: 33.7550,
				longitude: -84.3900
			},
			zoom: 15, 
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
	
}]);

cityHapps.controller('calController', function($scope, getEvents, uiCalendarConfig, $modal, $http, getCategories, getEventsMonthStart){

	$scope.alertTest = function() {
		//alert('firing on click');
	}

    //Needs to be broken out into a factory
    $scope.now = moment().format("dddd, MMMM Do");

    var next = 0;

    //How will this functinon in Cal View? Is is necessary?
    $scope.nextDay = function() {
        // debugger;
        next += 1;
        $scope.now = moment().add((next),'days').format("dddd, MMMM Do");
        $scope.nowGet = moment().add(next,'days').format();
        $scope.nowDateGet = moment().add(next, 'days').format("YYYY-MM-DD");

        $http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
            .success(function(data){
                $scope.eventData = data;
                calEvents(data);
            });
    };

    $scope.prevDay = function() {
        next -= 1;
        $scope.now = moment().add(next, 'days').format("dddd, MMMM Do");
        $scope.nowGet = moment().add(next,'days').format();
        $scope.nowDateGet = moment().add(next, 'days').format("YYYY-MM-DD");

        $http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
            .success(function(data){
                $scope.eventData = data;
                calEvents(data);
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
    var firstOfMonth = moment().startOf('month').format();

    $scope.filterCategory = function(cat) {

        var queryString = '';

        for (var i in $scope.filterData.categories){
            console.log(i);
            if ($scope.filterData.categories[i] == true) {
                queryString += "category[]=" + i;
                //+ "&";
            }
        }

        $http.get("/events?start_time="+firstOfMonth+"&"+ queryString)
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
               left: "",
               center: "",
               right: ""
            },
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
                $('td.fc-day-number').each(function () {
                    var day = $(this)[0].className.substr(17, 20)
                        .split(" ");

                    var dayShort = day[0].charAt(0).toUpperCase() + day[0].slice(1);
                    console.log(dayShort);

                    //$(dayShort + " ").before($(this));
                    $(this).one().prepend(dayShort + " ");
                    //

                });

                $(".fc-day").each(function () {
                    console.log($(this));
                    var eventCount = $(this).children()
                                    .find('.fc-event-container').length;

                    $(this).one().prepend("<div class='event-count'>" +  eventCount + "</div>");

                });
            },
            lazyFetching : true,
            dayClick : function(date, jsEVent, view) {

                alert('Taking you to day view ' + date);
                //window.location.href("/")

            }
        }
    }

    //var start = moment.format()

    getEventsMonthStart.events().success(calEvents);

    $scope.eventSource =[$scope.events];

	//console.table($scope.events);

});

cityHapps.controller("dayController", function($scope, getEvents, $modal, $http, getCategories, $rootScope) {

    //Needs to be broken out into a factory
    $scope.now = moment().format("dddd, MMMM Do");

    var next = 0;
    $scope.nextDay = function() {
        // debugger;
        next += 1;
        $scope.now = moment().add((next),'days').format("dddd, MMMM Do");
        $scope.nowGet = moment().add(next,'days').format();
        $scope.nowDateGet = moment().add(next,'days').format('YYYY-MM-DD');

        $http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
            .success(function(data){
                $scope.eventData = data;
                dayEvents(data);
            });
    };

    $scope.prevDay = function() {
        next -= 1;
        $scope.now = moment().add(next, 'days').format("dddd, MMMM Do");
        $scope.nowGet = moment().add(next,'days').format();
        $scope.nowDateGet = moment().add(next,'days').format('YYYY-MM-DD');

        $http.get('/events?start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet)
            .success(function(data){
                $scope.eventData = data;
                dayEvents(data);
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

        var queryString = '';

        for (var i in $scope.filterData.categories){
            console.log(i);
            if ($scope.filterData.categories[i] == true) {
                queryString += "category=[]" + i + "&";
            }
        }

        $http.get("/events?" + queryString)
            .success(function(data){
               dayEvents(data);
                console.log(data);
            });

        console.log($scope.filterData.categories);
    }

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
        console.log(data);
        $scope.dayEvents = data.events;
    })


    var dayEvents = function(data) {
        $scope.dayEvents = data.events;
        $scope.eventGroup =  [];
    };


    getEvents.events().success(dayEvents);

});




// cityHapps.controller('registrationModal', function($scope){
// $scope.open();

// });

/* ***************************** */
/* TEST HARNESS                  */
/* -- Start --                   */
/* ***************************** */
cityHapps.controller('harnessController', ['$scope', '$http', function($scope, $http) {
		
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

}]);
/* ***************************** */
/* TEST HARNESS                  */
/* -- Stop --                   */
/* ***************************** */
