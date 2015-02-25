angular.module('controllers.supplier', [])

.controller('SupplierCtrl', function($rootScope, $scope, $http, Config){
    if (!$scope.query)
    {
        $scope.filter_status = 2;
    }
    $scope.filterSupplier = function(supplier) {
        if ($scope.query) {
            if (supplier.status == 0 || supplier.status == 1) {
                return true;
            } else {
                return false;
            }
        }
        if ($scope.filter_status) {
            if (supplier.status == $scope.filter_status) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    };

    $scope.$watch('filter_status', function(val) {
        if (val) {
            $scope.query = null;
        }
    });

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

    $scope.reject = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/reject/' + id)
        .success(function(response){
            // $scope.suppliers.splice(index, 1);
            // $scope.suppliers[index] = response.supplier;
            _updateSupplier(id, response.supplier);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    function _updateSupplier(id, supplier) {
        angular.forEach($scope.suppliers, function(s, key){
            if (s.id == supplier.id) {
                $scope.suppliers[key] = supplier;
            }
        });
    };
    function _deleteSupplier(id) {
        angular.forEach($scope.suppliers, function(s, key){
            if (s.id == id) {
                console.log(key);
                $scope.suppliers.splice(key, 1);
            }
        });
    };

    $scope.deactivate = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/deactivate/' + id)
        .success(function(response){
            _updateSupplier(id, response.supplier);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.reactivate = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/reactivate/' + id)
        .success(function(response){
            _updateSupplier(id, response.supplier);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.delete = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/delete/' + id)
        .success(function(response){
            _deleteSupplier(id);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.setFree = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'supplierajax/setfree/' + id)
        .success(function(response){
            _updateSupplier(id, response.supplier);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };
})
