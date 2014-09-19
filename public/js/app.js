var cityHapps = angular.module('cityHapps', []);


cityHapps.controller("EventsCtrl", function($scope, $http) {

	$http.get('/app/controllers/EventfulController.php').success(json);

	alert(json);

});
