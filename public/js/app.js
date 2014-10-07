var cityHapps = angular.module('cityHapps', ['ui.bootstrap', 'ngRoute', 'ui.validate']);

cityHapps.controller("eventsController", function($scope, $http) {

	var events = $http.get('/events');
	
	events.success(function(data) {
		
		$scope.eventData = data.events["event"];
		console.log($scope.eventData);

	});

	// $scope.groupOfFour = function() {
	// 	for (var i = 0; i < $scope.eventData.length; i++ ) {

	// 	var four += 4;
	// 	var fourMore += four;
		
	// 	return $scope.eventData.slice(four, fourMore);
		
	// 	}
	// }
});



cityHapps.controller("sliderController", function($scope){

	$scope.interval = 5000;
	
	$scope.getTimes=function(n){
     return new Array(n);
};


});


cityHapps.controller('appController', function($scope, Auth){

	Auth.state();

});



cityHapps.formData = {};

cityHapps.controller('registerFormController', function($scope, $http, registerDataService){


	$scope.formData = registerDataService.data;

	// $scope.categoryService = categoryService.getCategories();

	// console.log($scope.formData);

	$scope.processForm = function() {
		$http({
			method: 'POST',
			url: '/user',
			data: $scope.formData,
			headers: {"Content-Type": "application/json"}
		}).success(function(data){

			if(!data) {
				console.log('not working');
			} else if (data) {
				console.log('successfully POSTang!');
			}
	
		// $scope.checkEmail = function() {
		// 	$http({
		// 		method: "POST", 
		// 		url: "/user", 
		// 		data: $scope.formData.email, 
		// 		headers: {"Content-Type": "application/json"}
		// 	}).success(function(data) {

		// 	});
		// }
			console.log(data);

		});
	};

		$scope.alert = function(){

			alert("firing");
		}

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

		}

});


cityHapps.factory("registerDataService", function(){

	var registerDataService = {};
	registerDataService.data = {};

	return registerDataService;


});


cityHapps.controller("modalController", function($scope, $modal, $http){

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

});


cityHapps.controller("modalInstanceController", function($scope, $modalInstance, $http, registerDataService){

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

				// return $scope.categories;
			}
		});
	};

	$scope.getCategories();
	
	$scope.ok = function () {
		$modalInstance.close($scope.selected.item);
	};

	$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	};

});


cityHapps.controller('loginController', function($scope, $http, Auth) {

	$scope.formData = {};
	$scope.currentUser = {};
	$scope.loggedOut = true;

	$scope.loginUser =  function() {
		$http({
			method: "POST",
			url: '/auth/login',
			data: $scope.formData,
			headers : {"Content-Type": "application/json"}
		}).success(function(data){

			if (!data) {
				console.log("There was an error logging you in");
			} else if(data) {
				console.log("You are Logged in!!");
				console.log(data);
			
				$scope.loggedOut = false;
				$scope.currentUser = data.email;
			}
		});

		Auth.loggedIn(data);
	};

	$scope.logoutUser = function() {
		$http({
			method: "GET",
			url: '/auth/logout',
			// data: $scope.formData,
			headers : {"Content-Type": "application/json"}
		}).success(function(data){

			if (!data) {
				console.log("There was an error logging you out");
				$scope.loggedOut = false;
			} else if(data) {
				console.log("You have logged out");
				$scope.loggedOut = true;

				categoryService.addCategory(data);
				
			}

		});

	};

});


cityHapps.factory('Auth', function(){
	var user, loggedIn;

	return {
		loggedIn : function(user) {
			if (user) {
				var loggedIn = true;
			}
		},

		state : function() {
			return loggedIn;
		}
	}

	
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
