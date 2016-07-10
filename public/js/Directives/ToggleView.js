'use strict';

(function(angular) {

    angular.module('toggleviewcomponent', []).component('toggleviewcomponent', {
            bindings: {
                change: '<'
            },
            template: '' +
            '<span>Grid View</span>' +
            '<div class="ui slider checkbox">' +
            '<input type="checkbox" name="public" ng-change="$ctrl.change(toggle)" ng-model="toggle" ng-init="toggle=articleView">' +
            '<label></label>' +
            '</div>' +
            '<span>List View</span>'
    });

})(angular);
