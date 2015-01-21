angular.module('rqportal', [
    'config',
    'ng-bs3-datepicker',
    'angucomplete-alt',
    'ui.bootstrap',
    'ui.utils.masks',
    'uiGmapgoogle-maps'
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
.controller('LocalMapCtrl', function($scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles = [];
    $scope.markers = [];
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
        });
    });

    $scope.addZone = function(center, distance) {
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
                });
            }
        });
    };

    $scope.deleteZone = function(id) {
        $http.delete(Config.BASE_URL + 'applicantajax/deleteLocal/' + id)
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
        });
    };
})

.controller('CountryMapCtrl', function($scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles = [];
    $scope.markers = [];
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
        });
    });
    $scope.addZone = function(local_id, distance) {
        $http.post(Config.BASE_URL + 'applicantajax/addCountry', {
            local_id: local_id,
            distance: distance
        }).success(function(response) {
            console.log(response);
            $scope.zones.push(response.zone);
            $scope.circles.push(response.zone.circle);
            $scope.markers.push(response.zone.marker);
            $scope.local_id = null;
            $scope.distance = null;
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };
    $scope.deleteZone = function(id) {
        $http.delete(Config.BASE_URL + 'applicantajax/deleteCountry/' + id)
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
        });
    };
})

.controller('StateMapCtrl', function($scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles1 = [];
    $scope.circles2 = [];
    $scope.paths = [];
    $scope.polylines = [];
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
        });
    });

    $scope.addZone = function(zone) {
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
                            console.log(response);
                            $scope.zones.push(response.zone);
                            $scope.circles1.push(response.zone.circle1);
                            $scope.circles2.push(response.zone.circle2);
                            $scope.paths.push(response.zone.path);
                            $scope.distance1 = null;
                            $scope.distance2 = null;
                        }).error(function(error){
                            console.log("ERROR: ", error);
                        });
                    }
                });
            }
        });
    };

    $scope.deleteZone = function(id) {
        $http.delete(Config.BASE_URL + 'applicantajax/deleteInterstate/' + id)
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

        $http.post(Config.BASE_URL + 'applicant/complete')
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

.controller('QuoteCtrl', function($scope, $http, Config, uiGmapGoogleMapApi) {
    $scope.current_quote = {};
    $scope.removals = [];
    $scope.storages = [];

    $scope.params = {
        from_date: moment().format("YYYY-MM-DD"),
    };
    angular.element(document).ready(function () {
        $scope.searchQuotes($scope.params);
    });


    $scope.searchQuotes = function(params) {
        $scope.removals = [];
        $scope.storages = [];
        $http.post(Config.BASE_URL + 'quoteAjax/search', params)
        .success(function(response){
            if(response.results && response.results.length > 0) {
                response.results.forEach(function(quote){
                    if (quote.removal) {
                        $scope.removals.push(quote);
                    } else {
                        $scope.storages.push(quote);
                    }
                });
                if ($scope.removals.length > 0) {
                    $scope.removalDetails($scope.removals[0]);
                }
            } else {
                $scope.current_quote = {};
            }
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };
    $scope.removalDetails = function(quote) {
        var removal = quote.removal;
        $scope.current_quote = quote;
        console.log(quote);
        $scope.paths = [];
        $scope.updateQuoteStatus(quote.id, 1);

        uiGmapGoogleMapApi.then(function(maps) {
            // Calculate Zoom
            var latlngList = [];
            latlngList.push(new google.maps.LatLng(removal.from_lat, removal.from_lon));
            latlngList.push(new google.maps.LatLng(removal.to_lat, removal.to_lon));
            var bounds = new google.maps.LatLngBounds();
            latlngList.forEach(function(n){
                bounds.extend(n);
            });
            var mapDim = {
                height: $('#map-wrapper').height() > 0 ? $('#map-wrapper').height() : 600,
                width: $('#map-wrapper').width() > 0 ? $('#map-wrapper').width() : 500
            };
            var zoom = getBoundsZoomLevel(bounds, mapDim);
            var lat = (parseFloat(removal.from_lat) + parseFloat(removal.to_lat))/2;
            var lon = (parseFloat(removal.from_lon) + parseFloat(removal.to_lon))/2;

            $scope.map = { center: { latitude: lat, longitude: lon }, zoom: zoom };
            $scope.paths.push(removal.path);
            $scope.from_marker = removal.from_marker;
            $scope.to_marker = removal.to_marker;
        });
    };
    $scope.storageDetails = function(quote) {
        var storage = quote.storage;
        $scope.current_quote = quote;
        $scope.updateQuoteStatus(quote.id, 1);

        uiGmapGoogleMapApi.then(function(maps) {
            $scope.map = { center: { latitude: storage.pickup_lat, longitude: storage.pickup_lon }, zoom: 10 };
            $scope.paths = [];
            $scope.from_marker = storage.pickup_marker;
            $scope.to_marker = {};
        });
    };

    $scope.updateQuoteStatus = function(id, status) {
        $http.post(Config.BASE_URL + 'quote/ajaxUpdate/' + id , {status: status })
        .success(function(quote){
            if (quote.job_type == 'removal') {
                var updated_removals = [];
                $scope.removals.forEach(function(removal_quote){
                    if (removal_quote.id == quote.id) {
                        updated_removals.push(quote);
                    } else {
                        updated_removals.push(removal_quote);
                    }
                });
                $scope.removals = updated_removals;
            } else { // Storage
                var updated_storages = [];
                $scope.storages.forEach(function(storage_quote) {
                    if (storage_quote.id == quote.id) {
                        updated_storages.push(quote);
                    } else {
                        updated_storages.push(storage_quote);
                    }
                });
                $scope.storages = updated_storages;
            }
            $scope.current_quote = quote;
        }).error(function(error){
            console.log("Error update quote status: ", error);
        });
    };

    // Private function, calculate zoom level
    function getBoundsZoomLevel(bounds, mapDim) {
        var WORLD_DIM = { height: 256, width: 256 };
        var ZOOM_MAX = 21;

        function latRad(lat) {
            var sin = Math.sin(lat * Math.PI / 180);
            var radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
            return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
        }

        function zoom(mapPx, worldPx, fraction) {
            return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
        }

        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();

        var latFraction = (latRad(ne.lat()) - latRad(sw.lat())) / Math.PI;

        var lngDiff = ne.lng() - sw.lng();
        var lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;

        var latZoom = zoom(mapDim.height, WORLD_DIM.height, latFraction);
        var lngZoom = zoom(mapDim.width, WORLD_DIM.width, lngFraction);

        return Math.min(latZoom, lngZoom, ZOOM_MAX);
    };

})
