var cityHapps = angular.module('cityHapps', ['ui.bootstrap', 'ngRoute', 'ui.validate',
	'facebook', 'http-auth-interceptor', 'remoteValidation']);


// angular.module('customFilters', []).filter('modulo', function(){
// 	return function(arr, div, val) {
// 		return arr.filter(function(item, index){
// 			return index % div === (val || 0);
// 		});
// 	};
// });


cityHapps.controller("eventsController", function($scope, $http, $filter) {

	var events = $http.get('/events');
	var i,
	first, second, third, fourth, slides;

	events.success(function(data) {
		
		$scope.eventData = data.events["event"];

		$scope.slideGroup = [];

		for (i = 0; i < $scope.eventData.length; i += 4) {

			slides = {
				'first' : $scope.eventData[i],
				'second' : $scope.eventData[i + 1],
				'third' : $scope.eventData[i + 2],
				'fourth' : $scope.eventData[i + 3]
			};
			console.log($scope.slideGroup);
			$scope.slideGroup.push(slides);
		}

	});


	


});

cityHapps.controller("sliderController", function($scope, $filter){

	$scope.interval = 5000;

	$scope.getTimes=function(n){
    return new Array(n);
};


});

cityHapps.config([
    'FacebookProvider',
    function(FacebookProvider) {
     var myAppId = '895139070496415';
     
     FacebookProvider.init(myAppId);
     
    }
 ]);

cityHapps.controller('appController', ['$scope', 'authService', 'userData', '$rootScope', 'authFactory', '$http',
	function($scope, $rootScope, authService, userData, authFactory, $http){
			
		$scope.userString = localStorage.getItem('user');
		$scope.user = angular.fromJson($scope.userString);

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

		$scope.$on('event:auth-loginConfirmed', function(){
			alert("youre logged in");

			//YESSSSS
			// This is where we can fire events based on user, 
			// GET requests for user based on category.

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
								"password" : $scope.fbInfo.fb_token
							};

							if (response.isValid == true ) {
								registerDataService.data = $scope.fbInfo;

								$modal.open({
									templateUrl: "templates/categoriesModal.html",
									controller: 'modalInstanceController'
								});	


							} else {
								authFactory.loginUser($scope.fbUser);
								// $modal.open({
								// 	templateUrl: "templates/categoriesModal.html",
								// 	controller: 'modalInstanceController'
								// });	
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

	return registerDataService;


});


cityHapps.factory('authFactory', function($http, authService){

	var auth = {};


	auth.loginUser =  function(formData) {

			$http.post('/auth/login', formData).then(function(res) {
				console.log(res);

				authService.loginConfirmed();
				
				// alert(authService.loginConfirmed());
				localStorage.setItem('user', JSON.stringify(res));
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


// This factory will do most of the data passing, getting, and setting
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
			controller: 'eventsController',
			templateUrl: 'templates/homeView.html'
		})
		.when("/map", {
			controller: 'mapViewController',
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


cityHapps.controller("tabSetController", function($scope){

$scope.tabs = [
    { title:'Events', content:'Dynamic content 1' },
    { title:'Activities', content:'Dynamic content 2' }
  ];

 //  var tabEvents = $http.get('/events');

	// tabEvents.success(function(data) {
		
	// });


});


cityHapps.controller('mapViewController', function($scope){

});

cityHapps.controller('calViewController', function($scope){

});


//Map Placeholder



  function initialize() {
    var mapCanvas = document.getElementById('map_canvas');
    var mapOptions = {
      center: new google.maps.LatLng( 33.7550, -84.3900),
      zoom: 8,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(mapCanvas, mapOptions)
  }
  google.maps.event.addDomListener(window, 'load', initialize);




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

	$scope.showEvents = function(eventSource) {
		
		$scope.eventData = null;

		$scope.showEventful = false;
		$scope.showActive = false;

		var eventRoute = '';

		switch(eventSource) {
			case "Eventful":
				eventRoute = "eventfulEvents";
				break;
			case "Active":
				eventRoute = "activeEvents";
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
