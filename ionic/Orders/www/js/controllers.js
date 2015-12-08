/**
 * Created by jefferson on 15/11/2015.
 */

angular.module('starter.controllers', [])
    .controller('LoginCtrl', ['$scope', '$http', '$state', 'OAuth', 'OAuthToken',
        function ($scope, $http, $state, OAuth, OAuthToken) {
            $scope.login = function (data) {
                OAuth.getAccessToken(data).then(function () {
                        $state.go('tabs.orders');
                        // console.log(OAuthToken.getToken());
                    }, function (data) {
                        $scope.error_login = 'Usuário ou senha inválidos.';
                    }
                );
            }
        }
    ])

    .controller('OrdersCtrl', ['$scope', '$http', '$state',
        function ($scope, $http, $state) {
            $scope.getOrders = function () {
                $http.get('http://api.pedidos.dev/orders').then(
                    function (data) {
                        $scope.orders = data.data._embedded.orders;
                        console.log($scope.orders);
                    }
                )
            };

            $scope.doRefresh = function () {
                $scope.getOrders();
                $scope.$broadcast('scroll.refreshComplete');
            };

            $scope.getOrders();

        }
    ])