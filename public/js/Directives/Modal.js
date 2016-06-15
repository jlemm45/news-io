//angular.module('modal', []).directive('modal', function($sce) {
//
//    function link(scope, element, attrs) {
//        scope.options.show = function() {
//            $('#'+scope.options.id).modal('show');
//        };
//        scope.options.hide = function() {
//            $('#'+scope.options.id).modal('hide');
//        };
//        scope.template = '/js/templates/modals/' + scope.options.template + '.html';
//    }
//
//    return {
//        link: link,
//        restrict: 'E',
//        scope: {options: '=options'},
//        template: '' +
//        '<div class="ui small modal" id="{{options.id}}">' +
//            '<i class="close icon"></i>' +
//            '<div class="header">' +
//            '{{options.title}}' +
//            '</div>'+
//            '<div ng-include="template"></div>' +
//        '</div>'
//    };
//});

angular.module('modal', [])
    .directive('modal', function() {
        return {
            restrict: 'E',
            transclude: true,
            scope: {
                header: '@',
                modalId: '@',
                options: '='
            },
            link: function(scope, elem, attrs) {
                if(attrs.template) scope.template = '/js/templates/modals/' + attrs.template + '.html';
            },
            template: '' +
            '<div class="ui small modal" id="{{ modalId }}">' +
            '<div class="header"><span>{{header}}</span></div>' +
            '<div class="content">' +
            '<div ng-transclude></div>' +
            '<div ng-include="template" ng-if="template"></div>' +
            '</div>' +
            '</div>'
        };
    });