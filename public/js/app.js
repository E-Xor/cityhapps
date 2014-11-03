var cityHapps = angular.module('cityHapps', ['ui.bootstrap', 'ngRoute', 'ui.validate',
	'facebook', 'http-auth-interceptor', 'remoteValidation', 'google-maps'.ns()]);

cityHapps.controller("eventsController", function($scope, $http, $filter, $modal, registerDataService, voteService, getEvents, $window) {

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

		// getEvents.events();

		alert('eventsController');

		var eventSuccess = function(data) {

		$scope.eventData = data.data;
		console.log($scope.eventData);

		$scope.eventCount = data.length;

		$scope.slideGroup = [];
		//console.log($scope.slideGroup);

		var i;

		var slideCount = '';

		$scope.isMobile = '';

		$scope.mobile = function() {
			if ($window.innerWidth <= 768 ) {

				return true;
			} else {
				return false;
			}
		};

		
		if ($window.innerWidth <= 768 ) {
			slideCount = 1;
			console.log(slideCount);
		} else {
			slideCount = 4;
		}

		// console.log(slideCount);

		for (i = 0; i < $scope.eventData.length; i += slideCount) {

			var slides = {
				'first' : $scope.eventData[i],
				'second' : $scope.eventData[i + 1],
				'third' : $scope.eventData[i + 2],
				'fourth' : $scope.eventData[i + 3]
			};

			$scope.eventData[i].upvoted = "";

			//console.log($scope.slideGroup);
			$scope.slideGroup.push(slides);
		}

		$scope.mapEventModal = function(data) {

				$modal.open({
					templateUrl: "templates/eventModal.html",
					controller: 'mapEventModalInstanceController', 
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

		$scope.vote = voteService.vote;

		console.log(voteService.vote);

		$scope.eventModal = function(data, num) {

			alert(voteService.vote);

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
						return voteService.vote;
					} 	
				}
			});
		};
		
		$scope.interval = 500000000000;
		};


	getEvents.events().success(eventSuccess);

	$scope.now = moment().format("dddd, MMMM Do");

		var next = 0;
		$scope.nextDay = function() {
			next += 1;
			$scope.now = moment().add(next,'days').format("dddd, MMMM Do");
		};
		
		$scope.prevDay = function() {
			next -= 1;
			$scope.now = moment().add(next, 'days').format("dddd, MMMM Do");
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
			return $http.get('/events?page=1').success(function(data) {
				//console.log(data);
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

cityHapps.controller('appController', ['$scope', 'authService', 'registerDataService', 'voteService', 'userData', '$rootScope', 'authFactory', '$http', '$modal',
	function($scope, $rootScope, authService, registerDataService, voteService, userData, authFactory, $http, $modal){
			
		
		$scope.userString = localStorage.getItem('user');
		$scope.user = angular.fromJson($scope.userString);

		if ($scope.userString) {

			$scope.upVote = function(event, num, vote) {
				alert("This" + JSON.stringify(event[num])  + "has been upvoted");

				voteService.vote = vote;
				alert(voteService.vote);
				
			};

			$scope.downVote = function(event, num, vote) {
				alert("This" + JSON.stringify(event[num])  + "has been downvoted");

				voteService.vote = vote;
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
      
      // Defining user logged status
      $scope.logged = false;
      
      // And some fancy flags to display messages upon user status change
      $scope.byebye = false;
      $scope.salutation = false;
      
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

			console.log(data);

			if(!data) {
				console.log('not working');
			} else if (data) {
				var fbInfo = {
					"email" : data.email,
					"password" : data.fb_token
				};

				$modal.open({
					templateUrl: "templates/confirmationModal.html",
					controller: 'modalInstanceController',
				});

				$scope.id = data.id;					

			}

			console.log(data);

		});
	};

		$scope.fbLogin = function() {
			$http({
				method: "POST",
				url: '/auth/login-fb',
				// data: $scope.formData,
				headers : {"Content-Type": "application/json"}
			}).success(function(data){

				if (!data) {
					console.log("There was an error logging you into Facebook");
					// $scope.loggedOut = false;
				} else if(data) {
					console.log("You are logged in with Facebook");
					// $scope.loggedOut = true;
				}
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
	}

	// $scope.loginUser = function() {
	// 	authFactory.loginUser($scope.formData);
	// }
});

cityHapps.controller("eventModalInstanceController", ["$scope", "registerDataService", 'voteService', "$modalInstance", 'data', 'num', 'vote', 
		function($scope, registerDataService, voteService, $modalInstance, data, num, vote){

		if (num === null) {
			$scope.data = data;
			// $scope.data.vote = voteService.vote;
			
		} else {
			$scope.data = data[num];	
			// $scope.data.vote = voteService.vote;
		}	
		
		alert('eventModalInstanceController');
		console.log(JSON.stringify(vote));

		$scope.vote = voteService.getData();

		$scope.ok = function () {
			$modalInstance.close($scope.selected.item);
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	}
]);

cityHapps.controller("mapEventModalInstanceController", ["$scope", "$modalInstance", 'data', 'voteService', 
		function($scope, $modalInstance, data, voteService){
			
		$scope.data = data;	

		$scope.vote = voteService.vote;
		
		console.log(data);

		$scope.ok = function () {
			$modalInstance.close($scope.selected.item);
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	}
]);


cityHapps.controller("modalInstanceController", ["$scope", "$modalInstance", "$http", "registerDataService", "authFactory", 
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
			authFactory.loginUser(data);
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


cityHapps.controller('loginController', [ "$rootScope", "$scope", "$controller", "registerDataService", "$http", 'userData', 'authService',
	function($rootScope, $scope, $http, userData, registerDataService, authService, $controller ) {

		$controller('appController', {$scope:$scope});

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
			controller: "calViewController",
			templateUrl: "templates/calView.html"
		})
		.otherwise({redirectTo: "/"});

		// use the HTML5 History API
		$locationProvider.html5Mode(true);
});


cityHapps.controller('mapController',['$scope', 'GoogleMapApi'.ns(), 'getEvents', '$modal',
	function ($scope, GoogleMapApi, getEvents, $modal) {
	
	//handle tabs inside mapController

	$scope.tabs = [
		{ title:'Events', content:'Dynamic content 1' },
		
		// Activities tab if we need it
		// { title:'Activities', content:'Dynamic content 2' }
	];


	//sloppy code re-use, but im doing it for demo
	$scope.now = moment().format("dddd, MMMM Do");

	var next = 0;
	$scope.nextDay = function() {
		next += 1;
		$scope.now = moment().add(next,'days').format("dddd, MMMM Do");
	};
	
	$scope.prevDay = function() {
		next -= 1;
		$scope.now = moment().add(next, 'days').format("dddd, MMMM Do");
	};
	//end sloppy code re-use

		var drawEvents = function(data){

			
			$scope.mapMarkerModal = function(data) {

				$modal.open({
					templateUrl: "templates/eventModal.html",
					controller: 'mapEventModalInstanceController',



					resolve: {
						data: function() {
							// alert('this is firing');
							return data;
						}		
					}
				});
			} 

			$scope.markers = [];
			$scope.markers.id = [];


			$scope.tabEvents = data;
			console.log($scope.tabEvents);

			// $scope.tabEvents.length
			// need to limit to 10 from server 

			for (var i = 0; i < 10 ; i++ ) {
				$scope.markers.push({
					'latitude' : $scope.tabEvents[i].latitude,
					'longitude' : $scope.tabEvents[i].longitude,
					'id' : i
				});
			}

			$scope.returnMarkers = function() {
				return $scope.markers;
			}

			$scope.mapEventModal = function(data) {

				$modal.open({
					templateUrl: "templates/eventModal.html",
					controller: 'mapEventModalInstanceController', 
					resolve: {
						data: function() {
							// alert('this is firing');
							return data;
						}		
					}
				});
			};
		};

		getEvents.events().success(drawEvents);



		$scope.iconPath = function() {
			return "/img/marker.png";
		}

		$scope.idKey = function() {
			for (var i = 0; i < $scope.tabEvents.length; i++) {
				return i;
			}
		}


	$scope.map = {
		center: {
			latitude: 33.7550,
			longitude: -84.3900
		},
		zoom: 8
	};

}]);

cityHapps.controller('calViewController', function($scope){

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
