angular.module('controllers.applicant', [])


.controller('LocalMapCtrl', function($rootScope, $scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles = [];
    $scope.markers = [];
    $rootScope.loading++;
    uiGmapGoogleMapApi.then(function(maps) {
        $scope.map = { center: { latitude: -26.4390917, longitude: 133.281323 }, zoom: 4 };
        $scope.options = {scrollwheel: false};

        $http.get(Config.BASE_URL + 'applicantajax/allLocal')
        .success(function(response){
            response.zones.forEach(function(zone){
                $scope.zones.push(zone);
                $scope.circles.push(zone.circle);
                $scope.markers.push(zone.marker);
            });
        })
        .error(function(error){
            console.log("Error gettting zones: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    });

    $scope.addZone = function(center, distance) {
        $rootScope.loading++;
        var geocoder = new google.maps.Geocoder();
        var address = center.originalObject.name + " Australia";
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK)
            {
                $http.post(Config.BASE_URL + 'applicantajax/addLocal', {
                    postcode: center.originalObject.postcode,
                    latitude: results[0].geometry.location.lat(),
                    longitude: results[0].geometry.location.lng(),
                    distance: distance
                }).success(function(response){
                    $scope.zones.push(response.zone);
                    $scope.circles.push(response.zone.circle);
                    $scope.markers.push(response.zone.marker);

                    uiGmapGoogleMapApi.then(function(maps) {
                        $scope.map = { center: { latitude: response.zone.latitude, longitude: response.zone.longitude }, zoom: 8 };
                        $scope.options = {scrollwheel: false};
                    });
                    $scope.distance = null;
                }).error(function(error){
                    console.log("Error adding zone: ", error);
                }).finally(function(){
                    $rootScope.loading--;
                });
            }
        });
    };

    $scope.deleteZone = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'applicantajax/deleteLocal/' + id)
        .success(function(response){
            for(var i=0; i<$scope.zones.length; i++) {
                if ($scope.zones[i].id == id) {
                    $scope.circles.splice(i, 1);
                    $scope.markers.splice(i, 1);
                    $scope.zones.splice(i, 1);
                    break;
                }
            }
        }).error(function(error){
            console.log("Error deleting zone: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };
})

.controller('CountryMapCtrl', function($rootScope, $scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles = [];
    $scope.markers = [];
    $rootScope.loading++;
    uiGmapGoogleMapApi.then(function(maps) {
        $scope.map = { center: { latitude: -26.4390917, longitude: 133.281323 }, zoom: 4 };
        $http.get(Config.BASE_URL + 'applicantajax/allCountry')
        .success(function(response){
            response.zones.forEach(function(zone){
                $scope.zones.push(zone);
                $scope.circles.push(zone.circle);
                $scope.markers.push(zone.marker);
            });
        })
        .error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    });
    $scope.addZone = function(local_id, distance) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'applicantajax/addCountry', {
            local_id: local_id,
            distance: distance
        }).success(function(response) {
            $scope.zones.push(response.zone);
            $scope.circles.push(response.zone.circle);
            $scope.markers.push(response.zone.marker);
            $scope.local_id = null;
            $scope.distance = null;
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };
    $scope.deleteZone = function(id) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'applicantajax/deleteCountry/' + id)
        .success(function(response){
            for(var i=0; i<$scope.zones.length; i++) {
                if ($scope.zones[i].id == id) {
                    $scope.circles.splice(i, 1);
                    $scope.markers.splice(i, 1);
                    $scope.zones.splice(i, 1);
                    break;
                }
            }
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };
})

.controller('StateMapCtrl', function($rootScope, $scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles1 = [];
    $scope.circles2 = [];
    $scope.paths = [];
    $scope.polylines = [];
    $rootScope.loading++;
    uiGmapGoogleMapApi.then(function(maps) {
        $scope.map = { center: { latitude: -26.4390917, longitude: 133.281323 }, zoom: 4 };
        $http.post(Config.BASE_URL + 'applicantajax/allInterstate')
        .success(function(response){
            response.zones.forEach(function(zone){
                $scope.zones.push(zone);
                $scope.circles1.push(zone.circle1);
                $scope.circles2.push(zone.circle2);
                $scope.paths.push(zone.path);
            });
        })
        .error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    });

    $scope.addZone = function(zone) {
        $rootScope.loading++;
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode( { 'address': zone.postcode1.title + " Australia"}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK)
            {
                zone.latitude1 =  results[0].geometry.location.lat();
                zone.longitude1 = results[0].geometry.location.lng();
                geocoder.geocode( { 'address': zone.postcode2.title + " Australia"}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK)
                    {
                        zone.latitude2 =  results[0].geometry.location.lat();
                        zone.longitude2 = results[0].geometry.location.lng();
                        $http.post(Config.BASE_URL + 'applicantajax/addInterstate', zone)
                        .success(function(response){
                            $scope.zones.push(response.zone);
                            $scope.circles1.push(response.zone.circle1);
                            $scope.circles2.push(response.zone.circle2);
                            $scope.paths.push(response.zone.path);
                            $scope.distance1 = null;
                            $scope.distance2 = null;
                        }).error(function(error){
                            console.log("ERROR: ", error);
                        }).finally(function(){
                            $rootScope.loading--;
                        });
                    }
                });
            }
        });
    };

    $scope.deleteZone = function(id) {
        $http.post(Config.BASE_URL + 'applicantajax/deleteInterstate/' + id)
        .success(function(response){
            for(var i=0; i<$scope.zones.length; i++) {
                if ($scope.zones[i].id == id) {
                    $scope.circles1.splice(i, 1);
                    $scope.circles2.splice(i, 1);
                    $scope.paths.splice(i, 1);
                    $scope.zones.splice(i, 1);
                    break;
                }
            }
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };
})

.controller('ApplicantPaymentCtrl', function($scope, $locale, $http, $modal, $timeout, Config) {
    $scope.currentYear = new Date().getFullYear();
    $scope.currentMonth = new Date().getMonth() + 1;
    $scope.months = $locale.DATETIME_FORMATS.MONTH;
    $scope.card = { // Populate for testing
        name: 'John Doe',
        number: '4444333322221111',
        month: 12,
        year: 2016,
        cvv: 123,
        agree: false
    };
    $scope.complete = function() {
        var loadingModal = $modal.open({
            templateUrl: 'welcomeAboard',
            controller: 'WelcomeCtrl',
            size: 'lg',
            backdrop: 'static',
            resolve: {
                items: function () {
                    return $scope.items;
                }
            }
        });

        $http.post(Config.BASE_URL + 'applicantajax/complete')
        .success(function(response){
        }).error(function(error){
        });
    };

    $scope.policy = function() {
        var policyModal = $modal.open({
            templateUrl: 'paymentPolicy',
            controller: '',
            size: 'lg',

        });
    };
})
.controller('WelcomeCtrl', function($scope){
    $scope.goToPortal = function() {
        window.location = '..';
    };
})
.filter('range', function(){
    var filter = function(arr, lower, upper) {
        for (var i = lower; i <= upper; i++) {
            arr.push(i);
        }
        return arr;

    }
    return filter;
})
