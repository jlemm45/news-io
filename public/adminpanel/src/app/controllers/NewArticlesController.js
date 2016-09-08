(function () {
    angular
        .module('app')
        .controller('NewArticlesController', [
            '$q', 'dataService',
            NewArticlesController
        ]);

    function NewArticlesController($q, dataService) {
        var vm = this;

        vm.chartOptions = {
            chart: {
                type: 'stackedAreaChart',
                height: 350,
                margin: { left: 30},
                x: function (d) { return d[0] },
                y: function (d) { return d[1] },
                color: ['rgb(0, 150, 136)', 'rgb(204, 203, 203)', 'rgb(149, 149, 149)', 'rgb(44, 44, 44)'],
                tooltip: { contentGenerator: function (d) { return '<div class="custom-tooltip">' + d.point.y + '</div>' } },
                showControls: false,
                xAxis: {
                    axisLabel: 'X Axis',
                    tickFormat: function(d) {
                        return d3.time.format('%x')(new Date(d))
                    },
                    rotateLabels: 30,
                    showMaxMin: false
                }
            }
        };

        vm.chartData = [];
        vm.period = 'week';
        vm.changePeriod = changePeriod;

        activate();

        function activate() {
            var queries = [loadData()];
            $q.all(queries);
        }

        function loadData() {
            dataService.getArticlesReadCount().then(function(resp) {
                var flat = _.map(resp.data, function(item) {
                    return [moment(item.created_at).unix()*1000, parseInt(item.count)];
                });
                vm.chartData = [{"key": 'New Articles By Day', "values": flat}];
            });
        }

        function changePeriod() {
            loadData();
        }
    }
})();
