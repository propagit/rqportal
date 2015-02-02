angular.module('controllers.billing', [])

.controller('BillingInvoiceCtrl', function($rootScope, $scope, $http, Config, $modal){

    $scope.current_invoice = {};


    $rootScope.loading++;
    $http.post(Config.BASE_URL + 'billingajax/searchInvoices')
    .success(function(response){
        $scope.invoices = response.invoices;
    }).error(function(error){
        console.log("ERROR: ", error);
    }).finally(function(){
        $rootScope.loading--;
    });

    $scope.processInvoice = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'billingajax/processInvoice', {id: id})
        .success(function(response){
            var updated_invoices = [];
            $scope.invoices.forEach(function(invoice){
                if (invoice.id == id) {
                    invoice = response.invoice;
                }
                updated_invoices.push(invoice);
            });
            $scope.invoices = updated_invoices;
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.viewInvoice = function(index) {
        $scope.current_invoice = $scope.invoices[index];
    };
    $scope.listInvoices = function() {
        $scope.current_invoice = {};
    };

    $scope.emailInvoice = function(index){
        $rootScope.invoice = $scope.invoices[index];
        $rootScope.modalInstance = $modal.open({
            templateUrl: 'emailForm',
            controller: 'EmailInvoiceCtrl'
        });
    };

    $scope.deleteInvoice = function(index) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'billingajax/deleteInvoice/' + $scope.invoices[index].id)
        .success(function(response){
            $scope.invoices.splice(index,1);
            console.log(response);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

})
.controller('EmailInvoiceCtrl', function($rootScope, $scope, $http, Config){
    $scope.invoice = $rootScope.invoice;
    $scope.success = 0;
    $scope.send = function() {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'billingajax/emailInvoice',{
            id: $scope.invoice.id,
            email: $scope.invoice.supplier.email
        }).success(function(response){
            $scope.success = 2;
        }).error(function(error){
            $scope.success = 1;
            $scope.error = error;
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.cancel = function() {
        $rootScope.modalInstance.dismiss('cancel');
    };
})

.controller('BillingQuoteCtrl', function($scope, $http, Config){

    $scope.current_user_id = null;

    $rootScope.loading++;
    $http.get(Config.BASE_URL + 'billingajax/getSuppliers')
    .success(function(response){
        $scope.suppliers = response.suppliers;
    }).error(function(error){
        console.log("ERROR: ", error);
    }).finally(function(){
        $rootScope.loading--;
    });

    $scope.listQuotes = function(user_id) {
        $rootScope.loading++;
        $http.get(Config.BASE_URL + 'billingajax/getQuotes/' + user_id)
        .success(function(response){
            $scope.quotes = response.quotes;
            $scope.current_user_id = user_id;
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.deleteQuote = function(quote_id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'billingajax/deleteQuote/' + quote_id)
        .success(function(response){
            var updated_suppliers = [];
            $scope.suppliers.forEach(function(supplier){
                if (supplier.user_id == $scope.current_user_id) {
                    supplier.quotes--;
                }
                updated_suppliers.push(supplier);
            });
            $scope.suppliers = updated_suppliers;
            $scope.listQuotes($scope.current_user_id);
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };

    $scope.generateInvoice = function(user_id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'billingajax/createInvoice/' + user_id)
        .success(function(response){
            console.log(response);
            var updated_suppliers = [];
            $scope.suppliers.forEach(function(supplier){
                if (supplier.user_id != user_id) {
                    updated_suppliers.push(supplier);
                }
            });
            $scope.suppliers = updated_suppliers;
            $scope.current_user_id = null;
            $scope.quotes = {};
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };
})
