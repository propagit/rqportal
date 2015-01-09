/**
 * model/zonelocal.js
 */

angular.module('model.zonelocal', [])

.factory('ZoneLocal', function($http, $q, Config) {

    function ZoneLocal(zoneLocalData) {
        if (zoneLocalData) {
            this.setData(zoneLocalData);
        }
    };

    ZoneLocal.prototype = {
        setData: function(zoneLocalData) {
            angular.extend(this, zoneLocalData);
        },

        create: function(zoneLocalData) {
            var deferred = $q.defer();
            $http.post(Config.BASE_URL + 'applicant/addLocal', zoneLocalData)
            .success(function(response){
                deferred.resolve(response);
            })
            .error(function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        },
    };

    return ZoneLocal;

})

.factory('zoneLocalManager', function($http, $q, Config, ZoneLocal) {
    return {
        _pool: {},
        _retrieveInstance: function(id, data) {
            var instance = this._pool[id];
            if(instance) {
                instance.setData(data);
            } else {
                instance = new ZoneLocal(data);
                this._pool[id] = instance;
            }
            return instance;
        },
        _search: function(id) {
            return this._pool[id];
        },
        _load: function(id, deferred) {
            var scope = this;
            $http.get(Config.BASE_URL + 'applicant/getLocal/' + id)
            .success(function(zoneLocalData) {
                var zoneLocal = scope._retrieveInstance(zoneLocalData.id, zoneLocalData);
                deferred.resolve(zoneLocal);
            })
            .error(function(error){
                deferred.reject(error);
            });
        },

        loadAllZoneLocal: function() {
            var deferred = $q.defer();
            var scope = this;
            $http.get(Config.BASE_URL + 'applicant/allLocal')
            .success(function(response) {

            })
            .error(function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        }
    };
})
