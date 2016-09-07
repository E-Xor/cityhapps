<!DOCTYPE html>
<html lang="en" ng-app="cityHapps">
  <head>
    <title ng-bind-html="title">CityHapps</title>

    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,height=device-height" >

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/asset-styles.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,700italic,500italic,400italic|Roboto+Condensed:400italic,700italic,400,700' rel='stylesheet' type='text/css'>
    <base href="/">

    <!--Google Anaylitics cityhapps.com Google for Business account -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-83633966-1', 'auto');
      ga('send', 'pageview');

    </script>
  </head>
  <body>
    <div ui-view autoscroll="true"></div>

    <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
    <script src="//platform.twitter.com/widgets.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.5.1/lodash.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <!-- ng- scripts and things -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.16/angular.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.16/angular-animate.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.16/angular-resource.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.15/angular-ui-router.min.js"></script>
    <script src='//maps.googleapis.com/maps/api/js?sensor=false'></script>

    <script src="/assets/js/angular-libs.js"></script>
    <script src="/assets/js/libs.js"></script>
    <script src="/assets/js/app.js"></script>
  </body>
</html>
