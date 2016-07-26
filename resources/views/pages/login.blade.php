@extends('pagemaster')
@section('app', 'snug-login')

@section('copy')
    <div class="banner">
        <div class="ui container">
            <h1>Login</h1>
        </div>
    </div>
    <div ng-controller="loginController">
        <div class="ui container">
            <logincomponent success="loginSuccess"></logincomponent>
        </div>
    </div>
@endsection

@section('scripts2')
    @if(env('APP_ENV') == 'prod')
        <script src="{{env('CDN_URL')}}/js/login-bundle.js"></script>
    @else
    <script src="/js/Controllers/LoginController.js"></script>
    @endif
@endsection