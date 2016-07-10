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
    <script src="/js/Controllers/LoginController.js"></script>
@endsection