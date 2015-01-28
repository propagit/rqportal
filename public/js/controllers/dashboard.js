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
        scaleBeginAtZero: false
    };
    $scope.onClick = function (points, evt) {
        console.log(points, evt);
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
