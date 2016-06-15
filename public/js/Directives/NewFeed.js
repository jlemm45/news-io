function newFeedController($scope, $element, $attrs, snugfeedFeedsService) {
    $scope.submit = function(feed) {
        snugfeedFeedsService.addFeed({feed_url: feed})
    }
}

angular.module('newfeedcomponent', ['snugfeed.service.feeds']).component('newfeedcomponent', {
    controller: newFeedController,
    //bindings: {data: '='},
    template: '' +
    '<form class="ui large form" ng-submit="submit(newFeed)">' +
        '<input type="text" name="newfeed" ng-model="newFeed" placeholder="Add a Feed">' +
        '<button class="ui button" type="submit">Add</button>' +
    '</form>' +
    ''
});