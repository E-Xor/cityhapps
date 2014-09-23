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
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.js"></script> <!-- load angular -->
	<script type="text/javascript" src="/js/ui-bootstrap-tpls-0.11.0.min.js"></script>
	<script src="/js/app.js"></script>
	<script src="/js/dom.js"></script>

</head>

<body ng-app="cityHapps">

	<div ng-include src="'/templates/registrationModal.html'"></div>

	<div class="container main">
		<div class="wide">
			<div class="row header-search">

				<div class="row-sm-12">
					<div class="col-sm-4 center">

						<h3>Logo Goes here</h3>

					</div>
					<div class="col-sm-4 center">
						
						<input type="text" placholder="Find Events &amp; Activities" class="main-search" />

					</div>
					<div class="col-sm-3 col-sm-offset-1 left">
						
						<h4 class="uppercase registerModal" >Create An Account</h4>
						<h4 class="uppercase" >Sign in</h4>

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
					<h3 class="">Wednesday, September 10, 2014</h3>
				</div>
				<div class="col-sm-1 left">
					<h1>&rsaquo;</h1>
				</div>
				
			</div>
		</div><!-- Get the header partial -->
		<div class="row view-select">
	
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
		<div class="col-sm-3 event">
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
		<div class="col-sm-3 event">
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
		<div class="col-sm-3 event">
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
		<div class="col-sm-3 event">
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

<div class="row event-slider">
	<div class="col-sm-12">
		<h3 class="uppercase">Activities</h3>
		<div class="col-sm-3 event">
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
		<div class="col-sm-3 event">
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
		<div class="col-sm-3 event">
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
		<div class="col-sm-3 event">
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



<!-- Get the home body partial -->



	</div>
</body>
</html>
