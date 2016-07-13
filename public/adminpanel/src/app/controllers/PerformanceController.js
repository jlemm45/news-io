(function () {
    angular
        .module('app')
        .controller('PerformanceController', [
            'performanceService', '$q', 'dataService',
            PerformanceController
        ]);

    function PerformanceController(performanceService, $q, dataService) {
        var vm = this;

        vm.chartOptions = {
            chart: {
                type: 'stackedAreaChart',
                height: 350,
                margin: { left: -15, right: -15 },
                x: function (d) { return d[0] },
                y: function (d) { return d[1] },
                //showLabels: false,
                //showLegend: false,
                //title: 'Over 9K',
                //showYAxis: false,
                //showXAxis: false,
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
                },
                yAxis: {
                    tickFormat: function(d){
                        return d3.format(',.2f')(d);
                    }
                }
            }
        };

        vm.performanceChartData = [];
        vm.performancePeriod = 'week';
        vm.changePeriod = changePeriod;

        activate();

        function activate() {
            var queries = [loadData()];
            $q.all(queries);
        }


        function loadData() {
            dataService.getArticlesReadCount().then(function(resp) {
                //var flat = _.flatten(_.map(resp.data, _.values));
                var flat = _.map(resp.data, function(item) {
                    return [moment(item.created_at).unix()*1000, parseInt(item.count)];
                    //return _.values(item);
                });
                vm.performanceChartData = [{"key": 'New Articles By Day', "values": flat}];
                console.log(flat);
            });
            vm.performanceChartData = performanceService.getPerformanceData(vm.performancePeriod);
            //console.log(dataService.getArticlesReadCount());
        }

        function changePeriod() {
            loadData();
        }
    }
})();
