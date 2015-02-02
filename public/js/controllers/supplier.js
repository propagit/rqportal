angular.module('controllers.supplier', [])

.controller('SupplierCtrl', function($rootScope, $scope, $http, Config){
    $http.get(Config.BASE_URL + 'supplierajax/getAll')
    .success(function(response){
        $scope.suppliers = response.suppliers;
        console.log(response);
    }).error(function(error){
        console.log("ERROR: ", error);
    });

    $scope.reject = function(index) {
        $http.post(Config.BASE_URL + 'supplierajax/reject/' + $scope.suppliers[index].id)
        .success(function(response){
            $scope.suppliers.splice(index, 1);
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };

    $scope.deactivate = function(index) {
        $http.post(Config.BASE_URL + 'supplierajax/deactivate/' + $scope.suppliers[index].id)
        .success(function(response){
            $scope.suppliers.splice(index, 1);
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };

    $scope.delete = function(index) {
        $http.delete(Config.BASE_URL + 'supplierajax/delete/' + $scope.suppliers[index].id)
        .success(function(response){
            $scope.suppliers.splice(index, 1);
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };
})
