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

    <link rel="stylesheet" href="/css/angular-snap.min.css" />
	<link href="/css/jquery.bxslider.css" rel="stylesheet" />

    <link rel="stylesheet" href="/css/fullcalendar.css" />
    <!-- <link rel="stylesheet" href="/css/bootstrap-responsive.min.css" /> -->
	<link rel="stylesheet" href="/css/style.css" />


	<!-- load angular from CDN-->
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.js"></script>
	<script src='//maps.googleapis.com/maps/api/js?sensor=false'></script>
 	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,height=device-height" >

	<script>
    //	document.body.scrollTop = 0;
	<!--local typeKit -->
    </script>

    <script src="//use.typekit.net/pzp3zgb.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>

	<base href="/">

</head>
<div id="fb-root"></div>


   <!-- default is set to 120 minutes, which our angular cookie is synced to -->
    <!-- echo(Config::get('session.lifetime')); -->

    <body ng-controller="adminEventController" ng-cloak>
		<div ng-if="!mobile()">
                <!-- if mobile add snap-content -->
                <header class="header-container proxima">
					<div class="wide">
						<div class='wide-search'>
                            <form ng-submit="search(query)">
                                <input type="text" placeholder="Search" ng-model="query" class="search-large">
                                <div class="search-clear"></div>
                                <input type="submit" style="display: none;">
                            </form>
						</div>
						<div class="header-search">
							<div class="row sm-12">
								<div class="col-sm-4 center">
									<a href="#/"><img src="/img/logo.png"></a>
								</div>
								<div class="col-sm-4 center logo">
                Admin Center
								</div>
									<div class="col-sm-4 auth" ng-controller="modalController">
                                        <div ng-cloak ng-show="!user" class="margin">
                                            <h4 class="proxima-light recs" ng-click="registerOpen('md'); getCategories() ">Get Your Daily Recs</h4>
                                            <h5 class="red italic inline accountCreate" ng-click="registerOpen('md'); getCategories() ">create an account</h5>
                                            <h5 class="red italic inline accountSignIn" ng-click="loginOpen('md')">sign in</h5>
                                        </div>
                                        <div ng-cloak ng-show="user">
                                            <div class="user-info">
                                                <h5 class="red italic inline accountHelp" ng-click="helpFade()">help</h5>
                                                    <div ng-click="categoryToggle()"> {{user.email}} </div>
                                                    <div class="categoriesDropdownUser">
                                                        <div class="toggle-controls">
                                                            <div class="right red uppercase bold pointer" ng-click="categoryToggle()">Close</div>
                                                            <div class="left red uppercase bold pointer">Edit your categories here</div>
                                                        </div>
                                                        <div ng-repeat="category in categories">
                                                            <label class="event-category" ng-model="filterData.userCategories[category.id]" ng-change="filterCategory()" btn-checkbox>{{category.name}}</label>
                                                        </div>
                                                    </div>
                                                <a class="red logout" ng-click="logoutUser()">Sign out</a>
                                            </div>
                                        </div>
                                        <div class="rec-arrow" ng-cloak ng-show="user" ng-click="recToggle()">
                                            {{recEventCount}}
                                        </div>
									</div>

								</div>
							</div>
						</div>
					</header>
			<!-- Get the home body partial -->
      <div class='center white'>
        <h2> Select what you want to do: </h2>
        <p><a href='/admin/event/add'>Add Event</a></p>
        <p><a href='#'>Filter Events</a></p>
        <p><a href='#'>Delete Event</a></p>
      </div>
			<!-- <div ng-view class="main-content" ng-animate="{enter: 'animate', leave: 'animate'}"></div> -->
		</div>
		<!-- if mobile -->
	<!-- </div> -->

	<!-- ng- scripts n thangs -->
    <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
    <script src="http://platform.twitter.com/widgets.js"></script>

    <script src="/js/angular-route.min.js"></script>
	<script src="/js/angular-facebook.js"></script>

	<script src="/js/http-auth-interceptor.js"></script>
	<script src="/js/ngRemoteValidate.0.4.1.min.js"></script>
    <script src="/js/angular-cookie.min.js"></script>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/ui-utils.min.js"></script>
	<script type="text/javascript" src="/js/ui-bootstrap-tpls-0.11.0.min.js"></script>
    <!-- <script src="/js/angular-touch.js"></script> -->
    <script src="/js/angular-idle.min.js"></script>

    <script src="/js/moment.min.js"></script>
    <script type="text/javascript" src="/js/fullcalendar.js"></script>
    <script type="text/javascript" src="/js/calendar.js"></script>
    <script type="text/javascript" src="/js/angular-sanitize.min.js"></script>

    <script type="text/javascript" src="/js/snap.min.js"></script>
    <script type="text/javascript" src="/js/angular-snap.min.js"></script>

    <script type="text/javascript" src="/js/angular-filter.js"></script>

	<script src="/js/lodash.min.js"></script>
	<script src="/js/bluebird.js"></script>
	<script src="/js/angular-google-maps.min.js"></script>

	<script src="/js/app.js"></script>
	<script src="/js/dom.js"></script>



</body>
</html>
