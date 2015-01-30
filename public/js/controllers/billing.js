angular.module('controllers.billing', [])

.controller('BillingInvoiceCtrl', function($scope, $http, Config){

    $scope.current_invoice = {};

    $http.post(Config.BASE_URL + 'billingajax/searchInvoices')
    .success(function(response){
        $scope.invoices = response.invoices;
    }).error(function(error){
        console.log("ERROR: ", error);
    });

    $scope.processInvoice = function(id) {
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
        });
    };

    $scope.viewInvoice = function(index) {
        $scope.current_invoice = $scope.invoices[index];
    };
    $scope.listInvoices = function() {
        $scope.current_invoice = {};
    };
})

.controller('BillingQuoteCtrl', function($scope, $http, Config){

    $scope.current_user_id = null;
    $http.get(Config.BASE_URL + 'billingajax/getSuppliers')
    .success(function(response){
        $scope.suppliers = response.suppliers;
    }).error(function(error){
        console.log("ERROR: ", error);
    });

    $scope.listQuotes = function(user_id) {
        $http.get(Config.BASE_URL + 'billingajax/getQuotes/' + user_id)
        .success(function(response){
            $scope.quotes = response.quotes;
            $scope.current_user_id = user_id;
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };

    $scope.deleteQuote = function(quote_id) {
        $http.delete(Config.BASE_URL + 'billingajax/deleteQuote/' + quote_id)
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
        });
    };

    $scope.generateInvoice = function(user_id) {
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
        });
    };
})