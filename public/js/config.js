angular.module('config', [])

.service('Config', [function(){
    return {
        BASE_URL: '/rqportal/'
        //BASE_URL: '/' // zack's configuration
    };
}])
