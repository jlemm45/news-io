'use strict';

(function(angular) {
    function newFeedController($scope, $element, $attrs, snugfeedFeedsService,$timeout) {
        $scope.submit = function() {
            var feed = $scope.selected;
            //adding a already indexed feed
            var dataObj = {};
            if(snug.isInt(feed)) {
                dataObj = {
                    feed_id: feed
                }
            }
            //brand new feed
            else {
                dataObj = {
                    feed_url: feed
                }
            }
            $scope.loading = true;
            $scope.checking = true;
            $scope.message = {
                top: 'Just on second',
                bottom: 'Checking if feed is valid...'
            };
            $scope.negative = false;
            snugfeedFeedsService.addFeed(dataObj).then(function(resp) {
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
                    init();
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
        $scope.selected = '';

        function init() {
            snugfeedFeedsService.getUnusedFeeds().then(function(data) {
                $scope.values = data.data;
            });
        }

        init();

        $scope.$on('reload feeds', function() {
            init();
        });

        $scope.$watch('selected', function(newValue) {
            if(newValue.length > 3)
            snugfeedFeedsService.searchForFeed(newValue).then(function(data) {
                console.log(data.data);
                $scope.values = _.union($scope.values, data.data);
                //$scope.values = data.data;
            });
        });
    }

    angular.module('newfeedcomponent', ['snugfeed.service.feeds', 'dropdown']).component('newfeedcomponent', {
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
        '<dropdowncomponent values="values" selected="selected"></dropdowncomponent>' +
        '<button class="ui green button" type="submit" ng-click="submit()">Add Feed</button>' +
        ''
    });
})(angular);