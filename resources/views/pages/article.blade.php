@extends('pagemaster')
@section('app', 'snug-article')

@section('copy')
    <div class="ui container" ng-controller="articleController">
        <h2>{{$article->article_title}}</h2>
        <p>{!!$article->article_description!!}</p>
    </div>
@endsection