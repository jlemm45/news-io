<!DOCTYPE html>
<html lang="en" ng-app="@yield('app')">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="News IO">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>SnugFeed</title>
    <link rel="stylesheet" type="text/css" href="/semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
@yield('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
@if(env('APP_ENV') == 'prod')
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-sanitize.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-animate.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-cookies.min.js"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-80489718-1', 'auto');
    ga('send', 'pageview');

</script>
<script src="{{env('CDN_URL')}}/js/main-bundle.js"></script>
@else
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-sanitize.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-animate.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-cookies.js"></script>

<script src="/semantic/dist/semantic.min.js"></script>
<script src="/js/Directives/Login.js"></script>
<script src="/js/Directives/Register.js"></script>
<script src="/js/Directives/ManageFeeds.js"></script>
<script src="/js/Services/UserService.js"></script>
<script src="/js/Services/FeedService.js"></script>
<script src="/js/Directives/Modal.js"></script>
<script src="/js/Directives/Dropdown.js"></script>
<script src="/js/Functions.js"></script>
@endif

@yield('scripts')
</body>
</html>