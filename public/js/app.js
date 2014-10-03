var cityHapps = angular.module('cityHapps', ['ui.bootstrap', 'ngRoute', 'ui.validate']);

cityHapps.controller("eventsController", function($scope, $http) {

	var events = $http.get('/events');
	
	events.success(function(data) {
		
		$scope.eventData = data.events["event"];
		console.log(typeof($scope.eventData));

	});

});


cityHapps.formData = {};

cityHapps.controller('registerFormController', function($scope, $http, registerDataService){


	$scope.formData = registerDataService.data;

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

	$scope.getCategories = function() {
		$http({
			method: "GET", 
			url: "/category", 
			headers: {"Content-Type": "application/json"}
		}).success(function(data){
			if(!data) {
			console.log('Unable to Get Categories');
			} else if (data) {
				console.log('successfully Getting Categories');
				console.log(data);
				$scope.categories = data;
			}
		});
	}

});


cityHapps.controller("modalController", function($scope, $modal){

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


cityHapps.controller("modalInstanceController", function($scope, $modalInstance, registerDataService){

	$scope.formData = registerDataService.data;
	
	$scope.ok = function () {
		$modalInstance.close($scope.selected.item);
	};

	$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	};

});


cityHapps.factory("registerDataService", function(){

	var registerDataService = {};
	registerDataService.data = {};

	return registerDataService;


});


cityHapps.controller('loginController', function($scope, $http) {

	$scope.formData = {};
	$scope.currentUser = {};
	$scope.currentUserSignedIn = false;

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


			
				$scope.currentUserSignedIn = true;

				console.log($scope.currentUserSignedIn);
				$scope.currentUser.email = data.email;
			}
		});
	};


});


cityHapps.controller('logoutController', function($scope, $http) {

	$scope.logoutUser = function() {
		$http({
			method: "GET",
			url: '/auth/logout',
			// data: $scope.formData,
			headers : {"Content-Type": "application/json"}
		}).success(function(data){

			if (!data) {
				console.log("There was an error logging you out");
			} else if(data) {
				console.log("You have logged out");
			}

		});

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




// cityHapps.controller('registrationModal', function($scope){
// $scope.open();

// });
