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
	
    <body ng-controller="appController" ng-cloak>
		<div ng-if="!mobile()">

<!--<ng-include src="/public/templates/helpSlider.html"></ng-include>-->

			<div class="bg-image">
                <!-- if mobile add snap-content -->
                <header class="header-container proxima">
					<div class="wide">
						<div class='wide-search'>
                            <form ng-submit="search(query)">
                                <input type="text" placeholder="Search" ng-model="query" class="search-large">
                                <input type="submit" style="display: none;">
                            </form>
						</div>
						<div class="header-search">
							<div class="row sm-12">
								<div class="col-sm-4 center">
									<a href="#/"><img src="/img/logo.png"></a>
								</div>
								<div class="col-sm-4 center logo">
                                    <form ng-sumbit="search(query)" ui-keypress="{13:'search(query)'}">
                                        <input type="text" placeholder="Find Events &amp; Activities" ng-model="query" class="main-search" />
                                    </form>

								</div>
									<div class="col-sm-4 auth" ng-controller="modalController">
                                        <div ng-cloak ng-show="!user" class="margin">
                                            <h4 class="proxima-light recs" ng-click="registerOpen('md'); getCategories() ">Get Your Daily Recs</h4>
                                            <h5 class="red italic inline accountCreate" ng-click="registerOpen('md'); getCategories() ">create an account</h5>
                                            <h5 class="red italic inline accountSignIn" ng-click="loginOpen('md')">sign in</h5>
                                            <h5 class="red italic inline accountSignIn" ng-click="helpFade()">help</h5>
                                        </div>
                                        <div ng-cloak ng-show="user">
                                            <div class="user-info">
                                                <h5 class="red italic inline accountSignIn" ng-click="helpFade()">help</h5>
                                                    <div ng-click="categoryToggle()"> {{user.email}} </div>
                                                    <div class="categoriesDropdownUser" ng-init="getCategoriesUser()">
                                                        <div class="toggle-controls">
                                                            <div class="right red uppercase bold pointer" ng-click="categoryToggle()">Close</div>
                                                            <div class="left red uppercase bold pointer" ng-click="clearAll()">Clear All</div>
                                                        </div>
                                                        <div ng-repeat="userCategory in userCategories">
                                                            <label class="event-category" ng-model="filterData.categories[userCategory.id]" ng-change="filterCategoryUser(filterData.categories[userCategory.id])" btn-checkbox>{{category.name}}</label>
                                                        </div>
                                                    </div>
                                                <a class="red logout" ng-click="logoutUser()">sign out</a>
                                            </div>
                                        </div>
                                        <div class="rec-arrow" ng-cloak ng-show="user" ng-click="recToggle()">
                                            {{recEventCount}}
                                        </div>
									</div>

								</div>
							</div>
						</div>

                    <!-- Help Section -->
                    <carousel interval="interval" class="help-overlay">
                        <slide class="help-slide">
                            <div class="center white tut">
                                <h1 class="uppercase header">How it Works</h1>
                                <p class="red center number">1</p>
                                <h4 class="center margin20 callout">Sign up and tell us what kind of things you like to do.</h4>
                                <div class="tut-categories">
                                    <label class="event-category large">Movies</label>
                                    <label class="event-category large">Kids</label>
                                    <label class="event-category large">Family</label>
                                    <label class="event-category large">Concerts</label>
                                    <label class="event-category large active" >Shopping</label>
                                    <label class="event-category large">Bars</label>
                                </div>
                            </div>
                        </slide>
                        <slide class="help-slide">
                            <div class="center white tut">
                                <h1 class="uppercase header">How it Works</h1>
                                <p class="red center number">2</p>
                                <h4 class="center margin20 callout">Use the thumbs-up/thumbs-down buttons to indicate if you like or dislike this kind of activity</h4>
                                <div class="btn-group">
                                    <label class="like-icon large"></label>
                                    <label class="dislike-icon large"></label>
                                </div>
                            </div>
                        </slide>
                        <slide class="help-slide">
                            <div class="center white tut">
                                <h1 class="uppercase header">How it Works</h1>
                                <p class="red center number">3</p>
                                <h4 class="center margin20 callout">Come back daily to get your City Happs recommendations</h4>
                                <div class="btn-group">
                                    <div class="question-box">?</div>
                                    <div class="question-box">?</div>
                                    <div class="question-box">?</div>
                                    <div class="question-box">?</div>
                                </div>
                                <a class="buy tutorial" ng-controller="modalController" ng-click="registerOpen('md')">
                                    Create Account
                                </a>
                            </div>
                        </slide>
                    </carousel>
					</header>
			<!-- Get the home body partial -->	
			<div ng-view class="main-content" ng-animate="{enter: 'animate', leave: 'animate'}"></div>
			</div>
		</div>
		<!-- if mobile -->
		<div ng-if="mobile()">
            <div snap-drawer="right">
               <ul ng-controller="modalController">
                    <li><a class="drawer-link" href="" ng-click="userCategoryToggle()" ng-if="user">{{user.email}}</a></li>
                    <li><a class="drawer-link" ng-if="user" ng-click="logoutUser()">sign out</a></li>
                    <li><a class="drawer-link"  ng-controller="modalController" ng-if="!user" ng-click="registerOpen('md')">Sign Up</a></li>
                    <li><a class="drawer-link"  ng-controller="modalController" ng-if="!user" ng-click="loginOpen('md')">Log In</a></li>
                    <li><a class="drawer-link" ng-if="user" href="">Recommendations Today</a></li>
                    <li><a class="drawer-link" href="">Sort by Distance</a></li>
                        <li ng-controller="eventsController">
                       <a class="drawer-link" href="" ng-click="categoryToggle()">Show All Categories</a>
                       <div class="categoriesDropdown drawer" ng-init="getCategories()">
                           <div class="toggle-controls">
                               <div class="right red uppercase bold pointer" ng-click="categoryToggle()">Close</div>
                               <div class="left red uppercase bold pointer" ng-click="clearAll()">Clear All</div>
                           </div>
                           <div ng-repeat="category in categories">
                               <label class="event-category" ng-model="filterData.categories[category.id]" ng-change="filterCategory(filterData.categories[category.id])" btn-checkbox>{{category.name}}</label>
                           </div>
                       </div>

                   </li>
               </ul>
            </div>
			<div class="bg-image scrollable" snap-content>
				<header class="header-container proxima" ng-animate=" 'animate' " >
					<div class="wide">
						<div class='wide-search'>
                            <form ng-submit="search(query)">
                                <input type="text" placeholder="Search" ng-model="query" class="search-large">
                                <input type="submit" style="display: none;">
                            </form>
						</div>
						<div class="header-search">
							<div class="mobile-header">
								<div class="mobile-search left"></div>	
								<a href="#/"><img class="mobile-logo" src="/img/logo.png"></a>
								<div class="mobile-menu right" snap-toggle="right"></div>
							</div>

							</div>
						</div>
					</header>
            <!--Get the help section-->
<!--            <ng-include src="/templates/helpSlider.html"></ng-include>-->


			<!-- Get the home body partial -->
			<div ng-view class="main-content" ng-animate="{enter: 'animate', leave: 'animate'}"></div>

			</div>
		</div>
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
