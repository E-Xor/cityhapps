<!DOCTYPE html>
<html lang="en" ng-app="cityHapps">
<head>
    <meta charset="UTF-8">
    <title>City Happs</title>
    
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,700italic,500italic,400italic|Roboto+Condensed:400italic,700italic,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="/assets/css/angular-snap.min.css" />
    <link href="/assets/css/jquery.bxslider.css" rel="stylesheet" />

    <link rel="stylesheet" href="/assets/css/fullcalendar.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.datetimepicker.css"/ >
    <link rel="stylesheet" type="text/css" href="/assets/css/ng-tags-input.min.css">

    <!-- load angular from CDN-->
    
    <script src='//maps.googleapis.com/maps/api/js?sensor=false'></script>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,height=device-height" >

    <base href="/">

</head>
<div id="fb-root"></div>

<!-- default is set to 120 minutes, which our angular cookie is synced to -->
<!-- echo(Config::get('session.lifetime')); -->

<body ng-controller="appController" ng-cloak>
    <aside class="snap-sidebar" ui-view="sidebar" snap-drawer="left"></aside>
    <div class="page-wrapper" snap-content>
        <aside class="sidebar" ui-view="sidebar"></aside>
        <div class="content-wrapper" >
            <header class="header-container container-fluid">
                <div class="row logo-row">
                    <div class="col-xs-12 center">
                        <a href="/"><img src="/assets/img/logo-atl.png"></a>
                    </div>
                    <div class="menu-button" snap-toggle="left"><i class="fa fa-bars"></i></div>
                </div>
                <hr>
                <div ui-view="menubar"></div>
                <div class="col-sm-4 auth" ng-controller="modalController">
                    <!--div ng-cloak ng-show="!user" class="margin">
                        <h4 class="proxima-light recs" ng-click="registerOpen('md'); getCategories() ">Get Your Daily Recs</h4>
                        <h5 class="red italic inline accountCreate" ng-click="registerOpen('md'); getCategories() ">create an account</h5>
                        <h5 class="red italic inline accountSignIn" ng-click="loginOpen('md')">sign in</h5>
                    </div-->
                    <!--div ng-cloak ng-show="user" ng-init="getUserData();">
                        <div class="user-info">
                            <h5 class="red italic inline accountHelp" ng-click="helpFade()">help</h5>
                                <div ng-click="categoryToggle()">  </div>
                                <div class="categoriesDropdownUser">
                                    <div class="toggle-controls">
                                        <div class="right red uppercase bold pointer" ng-click="categoryToggle(); updateUserCategories();">Close</div>
                                        <div class="left red uppercase bold pointer">Edit your categories here</div>
                                    </div>
                                    <div ng-repeat="category in categories">
                                        <label class="event-category" ng-model="filterData.userCategories[category.id]" ng-change="filterCategory()" btn-checkbox>{{category.name}}</label>
                                    </div>
                                </div>
                            <a class="red logout" ng-click="editOpen('md'); close();">Edit</a>
                            <a class="red logout" ng-click="logoutUser()">Sign out</a>
                        </div>
                    </div-->
                </div>
            </header>
            <!-- Get the home body partial -->
            <div class="main-content container-fluid" ng-animate="{enter: 'animate', leave: 'animate'}" ui-view></div>
            <footer class="footer container-fluid">
                <div class="footer-social">
                    <a href="#" class="fa-stack"><i class="fa fa-square fa-stack-1x"></i><i class="fa fa-facebook-square fa-stack-1x"></i></a>
                    <a href="#" class="fa-stack"><i class="fa fa-square fa-stack-1x"></i><i class="fa fa-twitter-square fa-stack-1x"></i></a>
                </div>
                <ul class="footer-menu">
                    <li><a href="/">Events</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
                <div class="footer-copyright">
                    City Happs | &copy;2015 All Rights Reserved.
                </div>
            </footer>
        </div>
    </div>

    <!-- ng- scripts and things -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.16/angular.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.16/angular-resource.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.15/angular-ui-router.min.js"></script>

    <script src="/js/satellizer.min.js"></script>

    <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
    <script src="http://platform.twitter.com/widgets.js"></script>

    <script src="/assets/js/angular/angular-route.min.js"></script>
    <script src="/js/angular-facebook.js"></script>

    <script src="/js/http-auth-interceptor.js"></script>
    <script src="/js/ngRemoteValidate.0.4.1.min.js"></script>
    <script src="/assets/js/angular/angular-cookies.min.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/ui-utils.min.js"></script>
    <script type="text/javascript" src="/js/ui-bootstrap-tpls-0.11.0.min.js"></script>
    <!-- <script src="/assets/js/angular/angular-touch.js"></script> -->
    <script src="/js/angular-idle.min.js"></script>
    <script src="/js/jquery.datetimepicker.js"></script>

    <script src="/js/moment.min.js"></script>
    <script type="text/javascript" src="/js/fullcalendar.js"></script>
    <script type="text/javascript" src="/js/calendar.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular-sanitize.min.js"></script>

    <script type="text/javascript" src="/js/snap.min.js"></script>
    <script type="text/javascript" src="/js/angular-snap.min.js"></script>

    <script type="text/javascript" src="/js/angular-filter.js"></script>

    <script src="/js/lodash.min.js"></script>
    <script src="/js/bluebird.js"></script>
    <script src="/js/angular-google-maps.min.js"></script>

    <script src="/js/checklist-model.js"></script>

    <script src="/js/ng-tags-input.min.js"></script>

    <script src="/app/app.js"></script>
    <script src="/app/controllers.js"></script>
    <script src="/app/services.js"></script>
    <script src="/app/filters.js"></script>
    <script src="/app/directives.js"></script>
    <script src="/js/dom.js"></script>

</body>
</html>