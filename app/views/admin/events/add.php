<!doctype html>
<html lang="en" ng-app="cityHapps">
<head>
	<meta charset="UTF-8">
	<title>City Happs</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css"/ >


    <link rel="stylesheet" href="/css/angular-snap.min.css" />
	<link href="/css/jquery.bxslider.css" rel="stylesheet" />

    <link rel="stylesheet" href="/css/fullcalendar.css" />
    <!-- <link rel="stylesheet" href="/css/bootstrap-responsive.min.css" /> -->
	<link rel="stylesheet" href="/css/style.css" />
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);
    html { background:#fff;}
    .form_container {
    width:675px;
    margin:50px auto;
    }
    .form_container label {
    display:block;
    }
    .form_container input.full { width:100%; }
    .form_container input, .form_container select, .form_container textarea {
      border: 1px solid #ccc;
      -moz-border-radius: 6px;
      -webkit-border-radius: 6px;
      border-radius: 6px;
      padding: 4px 7px;
      outline: 0;
      margin:5px 0;
    }
    .form_container input { -webkit-appearance: none;}
    .form_container select { background: #fff;height: 31px;}
    .form_container input:focus {
      border-color: #339933;
    }
    .form_container input[type='submit'] {
    background: #87cefa;
    color:#fff;
    }
	</style>


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
        <div class="form_container">
        <form method="post">
          <fieldset>
            <div>
              <label for="title">Event Name</label>
              <input type="text" class="full" id="title" name="title" value="" placeholder="Event Name" ng-model="formData.title" />
            </div>

            <div>
              <label for="event_url">Event URL</label>
              <input type="text" id="event_url" class="full" name="event_url" value="" placeholder="http://www.event.com" ng-model="formData.event_url" />
            </div>

            <div>
              <label for="venue_name">Venue Name</label>
              <input type="text" id="venue_name" class="full" name="venue_name" value="" placeholder="Venue Name" ng-model="formData.venue_name" />
            </div>

            <div>
              <label for="venue_url">Venue URL</label>
              <input type="text" id="venue_url" class="full" name="venue_url" value="" ng-model="formData.venue_url" placeholder="http://www.venue.com" />
            </div>

            <div>
              <label for="street_address" style="display:inline-block;width:331px;">Street Address</label>
              <label for="building" style="display:inline-block;width:155px;">Building / Suite</label>

              <input type="text" id="street_address" name="street_address" value="" ng-model="formData.street_address" placeholder="Street Address" style="width:315px;margin-right:15px;"/>
              <input type="text" id="building" name="building" value="" ng-model="formData.building" style="width:341px;" />
            </div>

            <div>
              <label for="city" style="display:inline-block;width:244px;">City</label>
              <label for="state" style="display:inline-block;width:261px;">State</label>
              <label for="zip_code" style="display:inline-block;width:162px;">Zip Code</label>

              <input type="text" id="city" name="city" value="" ng-model="formData.city" placeholder="City" style="width:222px;margin-right:20px;"/>
              <select id="state" name="state" placeholder="State" ng-model="formData.state" style="width:245px;margin-right:20px;">
                <option>Georgia</option>
              </select>
              <input type="text" id="zip_code" name="zip_code" value="" ng-model="formData.zip_code" placeholder="Zip Code" style="width:160px;" />
            </div>

            <div>
              <label for="start_time" style="display:inline-block;width:262px;">Start Date/Time</label>
              <label for="end_time" style="display:inline-block;width:146px;">End Date/Time</label>


              <input id="start_time" name="start_time" placeholder="State" date-time-picker ng-model="formData.start_time" style="width:225px;margin-right:35px;"/>
              <input id="end_time" name="end_time" placeholder="State" date-time-picker ng-model="formData.end_time" style="width:225px;"/>
            </div>

            <div>
              <label for="desc">Description</label>
              <textarea name="desc" id="desc" style="width:488px;height:180px;" ng-model="formData.desc"></textarea>
            </div>
            <div>
              <label for="tags">Tags</label>
              <textarea name="tags" id="tags" style="width:488px;height:180px;" ng-model="formData.tags"></textarea>
            </div>
            <div>
            <input type="submit" value="Submit" ng-click="processForm(formData); cancel()" />
        </fieldset>
        </form>
        </div>
			<!-- <div ng-view class="main-content" ng-animate="{enter: 'animate', leave: 'animate'}"></div> -->
<div style="clear:both;"></div>
		</div>
<div style="clear:both;"></div>
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
   <script src="/js/jquery.datetimepicker.js"></script>

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
