'use strict';

(function(angular) {
    function newFeedController($scope, $element, $attrs, snugfeedFeedsService,$timeout) {
        $scope.submit = function(feed) {
            $scope.loading = true;
            $scope.checking = true;
            $scope.message = {
                top: 'Just on second',
                bottom: 'Checking if feed is valid...'
            };
            $scope.negative = false;
            snugfeedFeedsService.addFeed({feed_url: feed}).then(function(resp) {
                $scope.loading = false;
                var r = resp.data;
                if(r.status == 'success') {
                    $scope.newFeed = '';
                    $scope.message.top = 'Success!';
                    $scope.message.bottom = '';
                    $timeout(function() {
                        $scope.$emit('add feed success');
                    });
                    $timeout(function() {
                        $scope.message = {
                            top: 'Just on second',
                            bottom: 'Checking if feed is valid...'
                        };
                        $scope.checking = false;
                    },1000);
                }
                else if(r.status == 'feed already added') {
                    $scope.negative = true;
                    $scope.message.top = 'Feed has already been added.';
                    $scope.message.bottom = '';
                }
                else {
                    $scope.negative = true;
                    $scope.message.top = 'Sorry';
                    $scope.message.bottom = 'This feed is invalid';
                }
            });
        };
        $scope.allowSubmit = false;
        $scope.loading = false;
        $scope.checking = false;
        $scope.message = {};
    }

    angular.module('newfeedcomponent', ['snugfeed.service.feeds']).component('newfeedcomponent', {
        controller: newFeedController,
        //bindings: {data: '='},
        template: '' +
        '<div class="ui icon message" ng-show="checking" ng-class="{\'negative\': negative}">' +
        '<i class="notched circle loading icon" ng-show="loading"></i>' +
        '<div class="content">' +
        '<div class="header">' +
        '{{message.top}}' +
        '</div>' +
        '<p>{{message.bottom}}</p>' +
        '</div>' +
        '</div>' +
        '<form class="ui large form" ng-submit="submit(newFeed)">' +
        '<input type="text" name="newfeed" ng-model="newFeed" placeholder="Add a Feed">' +
        '<button class="ui green button" type="submit">Add Feed</button>' +
        '</form>' +
        ''
    });
})(angular);