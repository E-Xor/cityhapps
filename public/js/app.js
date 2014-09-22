var cityHapps = angular.module('cityHapps', ['ui.bootstrap.modal']);


cityHapps.controller("EventsCtrl", function($scope, $http) {

	var events = $http.get('/events');

	events.success(function(data) {
		console.log(data);
	});

});

cityHapps.controller("createAccount", function($scope){
	$scope.open =  function() {
		$scope.showModal =  true;
	};

	$scope.ok =  function() {
		$scope.showModal =  false;
	};

	$scope.cancel =  function() {
		$scope.showModal =  false;
	};

});