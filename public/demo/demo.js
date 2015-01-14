angular.module('rqdemo', [
    'ng-bs3-datepicker',
    'angucomplete-alt',
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
        var postUrl = 'http://propatest.com/rqportal/api/quote/'
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
