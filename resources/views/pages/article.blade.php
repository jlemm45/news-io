@extends('pagemaster')
@section('app', 'snug-article')

@section('copy')
    <div class="ui container" ng-controller="articleController">
        <h2>{{$article->article_title}}</h2>
        <p>{!!$article->article_description!!}</p>
    </div>
@endsection

@section('scripts2')
    @if(env('APP_ENV') == 'prod')
        <script src="{{env('CDN_URL')}}/js/article-bundle.js"></script>
    @else
        <script src="/js/Controllers/ArticleController.js"></script>
    @endif
@endsection