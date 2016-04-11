/**
 * Created by jefferson on 22/01/2016.
 */

angular.module('starter.services', [])
    .service('oauthFixInterceptor', ['$rootScope', '$q', 'OAuthToken', function ($rootScope, $q, OAuthToken) {
        return {
            request: function (config) {
                //config.data = '';
                if (OAuthToken.getAuthorizationHeader()) {
                    config.headers = config.headers || {};
                    config.headers.Authorization = OAuthToken.getAuthorizationHeader(); //Generates the Bearer
                }

                return config;
            },
            responseError: function (rejection) {

                if (400 === rejection.status && rejection.data && ("invalid_request" === rejection.statusText || "invalid_grant" === rejection.statusText)) {
                    OAuthToken.removeToken();
                    $rootScope.$emit("oauth:error", rejection);
                }
                if (401 === rejection.status) {
                    var deferred = $q.defer();
                    $rootScope.$emit("oauth:error", {rejection: rejection, deferred: deferred});
                    return deferred.promise;
                }
               return $q.reject(rejection);


                //Testing only
               /* var deffered = $q.defer() //Defer means (adiar nossa decisão)
                deffered.resolve({nome: 'Jeff'});
                return deffered.promise;
                */
                //return $q.reject(rejection); //Reject
                // return $q.resolve({nome: 'Jeff'}); //Treat the error and response as success
            }
        };
    }])
