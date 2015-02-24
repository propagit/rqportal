angular.module('rqdemo', [
    'ng-bs3-datepicker',
    'angucomplete-alt',
])
.config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.useXDomain = true;
        $httpProvider.defaults.headers.common = 'Content-Type: application/json';
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
    }
])

.controller('AddQuoteCtrl', function($scope, $http) {
    $scope.quote = {
        moving_type: 'moving_home'
    };

    $scope.bedrooms = [
        'No bedrooms', 1, 2, 3, 4, 5, 6, 7, 8, 9, '10+'
    ];

    $scope.packings = [
        'Full packing service',
        'Fragile items only (Avoid breakages)',
        'No thanks'
    ];

    $scope.periods = [
        '0-6 months',
        '12 months',
        '24 months',
        '24 months +'
    ];

    $scope.containers = [
        '1 room',
        '2 rooms',
        '3 rooms',
        '3+ rooms'
    ];


    $scope.submit = function(quote) {
        var postUrl = 'http://localhost/rqportal/api/quote/'
            + ((quote.moving_type == 'storage') ? 'storage' : 'removal');
        $http.post(postUrl, quote)
        .success(function(response){
            $scope.result = 'success';
            console.log("Success: ", response);
        }).error(function(error){
            $scope.result = 'error';
            $scope.error_message = error.message;
            console.log("Error: ", error);
        });
    };

})

.controller('AddSupplierCtrl', function($scope, $http) {
    $scope.supplier = {
        name: 'Nam Nguyen',
        business: 'Propgate World Wide Pty Ltd',
        company: 'Propagate',
        abn_acn: '12 345 567 789',
        address: '620 St Kilda Road',
        suburb: 'Melbourne',
        state: 'VIC',
        postcode: '3004',
        phone: '0402133066',
        email: 'nam@propagate.com.au',
        website: 'http://www.propagate.com.au',
        about: 'API Test'
    };
    $scope.submit = function(supplier) {
        $http.post('http://member.removalistquote.com.au/api/supplier', supplier)
        .success(function(response){
            $scope.result = 'success';
            console.log("Success: ", response);
        }).error(function(error){
            $scope.result = 'error';
            $scope.error_message = error.message;
            console.log("Error: ", error);
        });
    };
})
