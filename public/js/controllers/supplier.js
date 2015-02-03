angular.module('controllers.supplier', [])

.controller('SupplierCtrl', function($rootScope, $scope, $http, Config){

    $rootScope.loading++;
    $http.post(Config.BASE_URL + 'supplierajax/getAll')
    .success(function(response){
        $scope.suppliers = response.suppliers;
        console.log(response);
    }).error(function(error){
        console.log("ERROR: ", error);
    }).finally(function(){
        $rootScope.loading--;
    });

    $scope.$watch('status', function(value){
        if (value)
        {
            $rootScope.loading++;
            $http.post(Config.BASE_URL + 'supplierajax/getAll', { status: value })
            .success(function(response){
                $scope.suppliers = response.suppliers;
                console.log(response);
            }).error(function(error){
                console.log("ERROR: ", error);
            }).finally(function(){
                $rootScope.loading--;
            });
        }
    });

    $scope.reject = function(index) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/reject/' + $scope.suppliers[index].id)
        .success(function(response){
            $scope.suppliers.splice(index, 1);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.deactivate = function(index) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/deactivate/' + $scope.suppliers[index].id)
        .success(function(response){
            $scope.suppliers.splice(index, 1);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.delete = function(index) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/delete/' + $scope.suppliers[index].id)
        .success(function(response){
            $scope.suppliers.splice(index, 1);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };
})
