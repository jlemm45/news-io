angular.module('article', ['ngSanitize']).directive('article', function($sce) {

    function link(scope, element, attrs) {
        scope.toTrustedHTML = function( html ){
            return $sce.trustAsHtml( html );
        };
    }

    return {
        link: link,
        restrict: 'E',
        scope: {article: '=article'},
        template: '' +
        '<div class="icon">' +
        '<img ng-src="https://s3-us-west-2.amazonaws.com/news-io/icons/{{article.icon_name}}.png">' +
        '</div>' +
        '<h2 class="ui header">{{article.article_title}}</h2>' +
        '<p ng-bind-html="toTrustedHTML(article.article_description)"></p>' +
        '<p><a href="#">Read More</a></p>'
    };
});