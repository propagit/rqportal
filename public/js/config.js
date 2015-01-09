angular.module('config', [])

.service('Config', [function(){
    return {
        BASE_URL: '/rqportal/'
    };
}])
