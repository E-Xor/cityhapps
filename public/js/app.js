var cityHapps = angular.module('cityHapps', []);


cityHapps.controller("EventsCtrl", function($scope, $http) {

	var events = $http.get('/events');

	events.success(function(data) {
		console.log(data);
	});

});
