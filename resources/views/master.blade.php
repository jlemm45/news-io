<!DOCTYPE html>
<html lang="en" ng-app="@yield('app')">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="News IO">
    <title>SnugFeed</title>
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
@yield('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
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
<script src="/js/Functions.js"></script>
@yield('scripts')
</body>
</html>