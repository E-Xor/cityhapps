<!doctype html>
<html lang="en" ng-app="cityHapps">
<head>
	<meta charset="UTF-8">
	<title>City Happs</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

	</style>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link href="/css/jquery.bxslider.css" rel="stylesheet" />
	<link rel="stylesheet" href="/css/style.css" />

	<script src="https://maps.googleapis.com/maps/api/js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.js"></script> <!-- load angular -->
	<script src="/js/angular-route.min.js"></script>
	<script src="/js/angular-facebook.js"></script>
	<script src="/js/http-auth-interceptor.js"></script>
	<script src="/js/ngRemoteValidate.0.4.1.min.js"></script>

	


	<script type="text/javascript" src="/js/ui-utils.min.js"></script>
	<script type="text/javascript" src="/js/ui-bootstrap-tpls-0.11.0.min.js"></script>
	<script src="/js/jquery.bxslider.min.js"></script>
	<script src="/js/app.js"></script>
	<script src="/js/dom.js"></script>
	
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '895139070496415',
	      xfbml      : true,
	      version    : 'v2.1'
	    });
	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>
	

	<base href="/">

</head>

<?php 

	
?>

<body ng-controller="harnessController">
	<p>CityHapps Test Harness.</p>
	
	<button ng-click="showEvents('Eventful')">Eventful</button>
			
	<div ng-repeat="event in eventData">
		<div>
			{{event.title}}
		</div>
	</div>

</body>
</html>
