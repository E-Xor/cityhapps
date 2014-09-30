<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>City Happs</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

	</style>
	<link rel="stylesheet" href="/css/style.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link href="/css/jquery.bxslider.css" rel="stylesheet" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.js"></script> <!-- load angular -->
	<script src="/js/angular-route.min.js"></script>
	<script type="text/javascript" src="/js/ui-utils.min.js"></script>
	<script type="text/javascript" src="/js/ui-bootstrap-tpls-0.11.0.min.js"></script>
	<script src="/js/jquery.bxslider.min.js"></script>
	<script src="/js/app.js"></script>
	<script src="/js/dom.js"></script>
	
	

	<base href="/">

</head>

<body ng-app="cityHapps">
	
	<div class="bgContainer">	
		<div class="container main">
		
		<div ng-include src="'/templates/registrationModal.html'"></div>
		<div ng-include src="'/templates/loginModal.html'"></div>
		
			<div class="wide">
				<div class='row wide-search'>
					<input type="text" placeholder="Search" class="search-large">
				</div>
				<div class="row header-search">

					<div class="row-sm-12">
						<div class="col-sm-4 center">

							<a href="/"><img src="/img/logo.png"></a>

						</div>
						<div class="col-sm-4 center">
							
							<input type="text" placholder="Find Events &amp; Activities" class="main-search" />

						</div>
						<div class="col-sm-3 col-sm-offset-1 left auth">
							
							<h4 class="uppercase registerModal" >Create An Account</h4>
							<h4 class="uppercase loginModal" >Sign in</h4>
		
							<div ng-controller="logoutController">
								<h4 class="uppercase logout" ng-click="logoutUser()">Logout</h4>
							</div>
							<div ng-show="currentUserSignedIn">
								Welcome, {{currentUser.email}} !	

							</div>

						</div>
					</div>
				</div>
			</div>

			<div class="row day-slider">
				<div class="row-sm-12 center">
					<div class="col-sm-1 left">
						<h1>&lsaquo;</h1>
					</div>
					<div class="col-sm-10">
						<h3 class="uppercase bold">Wednesday, September 10, 2014</h3>
					</div>
					<div class="col-sm-1 left">
						<h1>&rsaquo;</h1>
					</div>
					
				</div>
			</div><!-- Get the header partial -->
	<!-- <<<<<<< HEAD
	======= -->
	<!-- <div class="row view-select">
		
		<div class="col-sm-12">
			<div class="col-sm-2">
				All Categories
					
			</div>	
			<div class="col-sm-2">
				Most Recent

			</div>	
			<div class="col-sm-2 col-sm-offset-6">
				View Icons will go here

			</div>	

		</div>	

	</div>

	<div class="row event-slider" ng-controller="EventsCtrl">
		<div class="col-sm-12">
			<h3 class="uppercase">Events</h3>
		<div class="bxslider">
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
	 		<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	<div class="row event-slider">
		<div class="col-sm-12">
			<h3 class="uppercase">Activities</h3>
			<div class="bxslider">
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
	 		<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 event">
				<div class="event-inner">
					<img src="http://placehold.it/255x150">
					<div class="event-details">
						<h3>Event Header</h3>
						<p>Jul 11 11:00 am</p>
						<p>Fabulous Fox Thetre</p>
						<p>in Atlanta</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div> -->
	<!-- >>>>>>> 1fa72743f3ac54e70dd1606b848ce1a37a80a32d -->



		<div ng-view></div>
<!-- Get the home body partial -->


	</div>
	</div>
</body>
</html>
