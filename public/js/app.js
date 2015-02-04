angular.module('rqportal', [
    'config',
    'ng-bs3-datepicker',
    'angucomplete-alt',
    'chart.js',
    'ui.bootstrap',
    'ui.utils.masks',
    'uiGmapgoogle-maps',
    'controllers.dashboard',
    'controllers.applicant',
    'controllers.supplier',
    'controllers.quote',
    'controllers.billing'
])
.config(function($interpolateProvider) {
    // Avoid confliction with Phalcon
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
})
.config(function(uiGmapGoogleMapApiProvider){
    uiGmapGoogleMapApiProvider.configure({
        key: 'AIzaSyCJLXHwzgv6qUZ8qtQhYvKm03173zJ2kyQ',
        v: '3.17',
        libraries: 'places,weather,geometry,visualization,drawing'
    });
})
.controller('AppCtrl', function($rootScope, $window, Config){
    $rootScope.loading = 0;
    $rootScope.$watch('search_invoice_id', function(val){
        if(val) {
            $window.location = Config.BASE_URL + 'billing/invoice?id=' + val.originalObject.id;
        }
    });
})

.filter('customCurrency', ["$filter", function ($filter) {
    return function(amount, currencySymbol){
        amount = parseFloat(amount);
        var currency = $filter('currency');
        if(amount < 0){
            return currency(amount, currencySymbol).replace("(", "-").replace(")", "").replace(".00", "");
        }
        return currency(amount, currencySymbol).replace(".00", "");
    };
}])

.filter('roundCurrency', ["$filter", function ($filter) {
    return function(amount, currencySymbol){
        amount = parseFloat(amount);
        var currency = $filter('currency');
        return currency(amount, currencySymbol).replace(".00", "");
    };
}])
