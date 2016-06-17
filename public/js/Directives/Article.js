angular.module('article', ['ngSanitize', 'snugfeed.service.articles']).directive('article', function($sce,snugfeedArticlesService,$timeout) {

    function link(scope, element, attrs) {
        scope.toTrustedHTML = function( html ){
            return $sce.trustAsHtml( html );
        };

        scope.saveArticle = function(article) {
            snugfeedArticlesService.saveArticle(article.id).then(function() {
                $timeout(function() { //weird but had to do this to run on next digest
                    scope.$emit('article saved', article);
                })
            });
        };
    }

    return {
        link: link,
        restrict: 'E',
        scope: {article: '=article', view: '=view'},
        template: '' +
        '<div class="actions">' +
        '<i ng-click="saveArticle(article)" class="save icon pointer"></i>' +
        '</div>' +
        '<div class="icon">' +
        '<img ng-if="article.icon_name" ng-src="https://s3-us-west-2.amazonaws.com/news-io/icons/{{article.icon_name}}.png">' +
        '</div>' +
        '<h2 class="ui header">{{article.article_title}}</h2>' +
        '<p ng-bind-html="toTrustedHTML(article.article_description)" ng-if="view"></p>' +
        '<p><a href="#">Read More</a></p>'
    };
});