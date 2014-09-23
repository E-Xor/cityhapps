var cityHapps = angular.module('cityHapps', ['ui.bootstrap']);


cityHapps.controller("EventsCtrl", function($scope, $http) {

	var events = $http.get('/events');

	events.success(function(data) {
		// console.log(data);
	});

});


cityHapps.controller('registerFormController', function($scope, $http){

	$scope.formData = {};

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

			console.log(data);

		});
	};

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


// cityHapps.controller('registrationModal', function($scope){
// $scope.open();

// });
