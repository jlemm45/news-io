'use strict';

(function(angular) {
    angular.module('article', ['ngSanitize', 'snugfeed.service.articles']).directive('article', function($sce,snugfeedArticlesService,$timeout) {

        function link(scope, element, attrs) {

            function charLimit(content) {
                if(content) {
                    content = content.split('<br>')[0];
                    content = content.split('</p>')[0];
                    if(content.length > 400) content = content.substring(0,400)+'...';
                    return content;
                }
                return '';
            }

            scope.toTrustedHTML = function( html ){
                //charLimit(html);
                return $sce.trustAsHtml( charLimit(html) );
            };

            scope.saveArticle = function(article) {
                snugfeedArticlesService.saveArticle(article.id).then(function() {
                    $timeout(function() { //weird but had to do this to run on next digest
                        scope.$emit('article saved', article);
                    })
                });
            };

            scope.deleteArticle = function(article) {
                snugfeedArticlesService.deleteArticle(article.id).then(function() {
                    $timeout(function() { //weird but had to do this to run on next digest
                        scope.$emit('article deleted', article);
                    })
                });
            };

            scope.readMore = function(article) {
                scope.$emit('read article', article);
            };

            scope.parseDate = function(date) {
                return 'Added '+snug.timePassed(date);
            };

            scope.favicon = getFavicon(scope.article.feed_id);

            function getFavicon(id) {
                if(scope.$parent.activeFeeds && id) {
                    var arr = _.find(scope.$parent.activeFeeds, function(feed){ return feed.id == id });
                    return arr.favicon_url;
                }
                return '';
            }
        }

        return {
            link: link,
            restrict: 'E',
            scope: {article: '=article', view: '=view', showSaved: '=showsaved'},
            template: '' +
            '<div class="actions">' +
            '<i ng-click="saveArticle(article)" class="save icon pointer" ng-if="!showSaved"></i>' +
            '<i ng-click="deleteArticle(article)" class="trash outline icon pointer" ng-if="showSaved"></i>' +
            '</div>' +
            '<div class="icon">' +
            '<img ng-src="{{favicon}}">' +
            '</div>' +
            '<h2 class="ui header">{{article.article_title}}</h2>' +
            '<p ng-bind-html="parseDate(article.created_at)"></p>' +
            '<p ng-bind-html="toTrustedHTML(article.article_description)" ng-if="view"></p>' +
            '<p><a href="#" ng-click="readMore(article)">Read More</a></p>'
        };
    });
})(angular);