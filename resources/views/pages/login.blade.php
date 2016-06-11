@extends('pagemaster')
@section('app', 'snug-login')

@section('copy')
    <div class="banner">
        <div class="ui container">
            <h1>Login</h1>
        </div>
    </div>
    <div ng-controller="loginController">
        <div class="ui grid container">
            <div class="ui two column centered grid">
                <div class="column">
                    <logincomponent success="loginSuccess"></logincomponent>
                </div>
                <div class="four column centered row">
                    <div class="column"></div>
                    <div class="column"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/Controllers/LoginController.js"></script>
@endsection