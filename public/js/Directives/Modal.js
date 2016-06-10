angular.module('modal', []).directive('modal', function($sce) {

    function link(scope, element, attrs) {
        scope.options.show = function() {
            $('#'+scope.options.id).modal('show');
        };
        scope.template = '/js/templates/modals/' + scope.options.template + '.html';
    }

    return {
        link: link,
        restrict: 'E',
        scope: {options: '=options'},
        template: '' +
        '<div class="ui small modal" id="{{options.id}}">' +
            '<i class="close icon"></i>' +
            '<div class="header">' +
            '{{options.title}}' +
            '</div>'+
            '<div ng-include="template"></div>' +
        '</div>'
    };
});