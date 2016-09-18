'use strict';

(function(angular) {
    angular.module('managefeedscomponent', ['snugfeed.service.feeds']).directive('managefeedscomponent', function(snugfeedFeedsService) {

        function link(scope, element, attrs) {

            scope.remove = function($index, feedID) {

                scope.feeds[$index].loading = true;

                snugfeedFeedsService.removeFeed(feedID).then(function(resp) {
                    scope.feeds[$index].loading = false;
                    if(resp.data.status == 'success') {
                        scope.feeds[$index].removed = true;
                        scope.feeds[$index].btnText = 'Removed';
                    }
                })
            }

        }
        return {
            link: link,
            restrict: 'E',
            scope: {feeds: '='},
            template: '' +
            '<div class="ui middle aligned divided list">' +
            '<div class="item" ng-repeat="feed in feeds">' +
            '<div class="right floated content">' +
            '<div class="ui button negative" ng-click="remove($index, feed.id)" ng-class="{\'loading\':' +
            ' feed.loading, \'positive\': feed.removed, \'negative\': !feed.removed, \'disabled\': feed.removed}">' +
            '{{feed.btnText || \'Remove\'}}' +
            '<i class="checkmark icon" ng-if="feed.added"></i></div>' +
            '</div>' +
            '<img class="ui avatar image" ng-src="{{feed.favicon_url}}">' +
            '<div class="content">{{feed.source | limitTo:30}}...</div>' +
            '</div>' +
            '</div>'
        };
    });
})(angular);
