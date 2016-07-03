@extends('pagemaster')
@section('app', 'snug-register')

@section('copy')
    <div class="banner">
        <div class="ui container">
            <h1>Register</h1>
        </div>
    </div>
    <div ng-controller="registerController">
        <div class="ui grid container">
            <div class="ui two column centered grid">
                <div class="column">
                    <registercomponent></registercomponent>
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
    <script src="/js/Controllers/RegisterController.js"></script>
@endsection