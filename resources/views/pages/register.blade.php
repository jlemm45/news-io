@extends('pagemaster')
@section('app', 'snug-register')

@section('copy')
    <div class="banner">
        <div class="ui container">
            <h1>Register</h1>
        </div>
    </div>
    <div ng-controller="registerController">
        <div class="ui container">
            <registercomponent></registercomponent>
        </div>
    </div>
@endsection

@section('scripts2')
    @if(env('APP_ENV') == 'prod')
        <script src="{{env('CDN_URL')}}/js/register-bundle.js"></script>
    @else
    <script src="/js/Controllers/RegisterController.js"></script>
    @endif
@endsection