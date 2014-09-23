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

			if(!data.success) {
				console.log('not working');
			} else {
				console.log('successfully POSTang!');
			}

			console.log(data);

		});
	};

});


// cityHapps.controller('registrationModal', function($scope){
// $scope.open();

// });
