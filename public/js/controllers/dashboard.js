angular.module('controllers.dashboard', [])

.controller('DashboardCtrl', function($rootScope, $scope, $http, Config){
    $http.get(Config.BASE_URL + 'dashboardajax/getSales')
    .success(function(response){
        $scope.labels = response.labels;
        $scope.series = response.series;
        $scope.data = response.data;
        console.log(response);
    }).error(function(error){
        console.log("ERROR: ", error);
    });
    $scope.options = {
        bezierCurve: false,
        scaleBeginAtZero: false,
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%>$<%= value %>",
        multiTooltipTemplate: "$<%= value %>",
    };

    $scope.$watch('time', function(val) {
        console.log(val);
        loadStats(val);
    });

    $scope.time = 'month';

    function loadStats(time) {
        $http.post(Config.BASE_URL + 'dashboardajax/getStats', {
            time: time
        }).success(function(response){
            $scope.stats = response;
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };
})
