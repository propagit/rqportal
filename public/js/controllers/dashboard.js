angular.module('controllers.dashboard', [])

.controller('DashboardCtrl', function($rootScope, $scope, $http, Config){
    $scope.labels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    $scope.series = ['Billed', 'Predicted'];
    $scope.data = [
        [1200, 1400, 1800, 1000, 900, 2100, 1500, 1200, 1400, 1800, 1000, 900, 2100, 1500],
        [1200, 1800, 2000, 1400, 1500, 3000, 2000, 1200, 1800, 2000, 1400, 1500, 3000, 2000]
    ];
    $scope.options = {
        bezierCurve: false,
        scaleBeginAtZero: false,
        // legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
    };
    $scope.onClick = function (points, evt) {
        console.log(points, evt);
    };

    $scope.removals = [];
    $scope.storages = [];

    $http.post(Config.BASE_URL + 'quoteAjax/search', {})
    .success(function(response){
        if(response.results && response.results.length > 0) {
            response.results.forEach(function(quote){
                if (quote.removal) {
                    $scope.removals.push(quote);
                } else {
                    $scope.storages.push(quote);
                }
            });
        }
    }).error(function(error){
        console.log("ERROR: ", error);
    });
})
