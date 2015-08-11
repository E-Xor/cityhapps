/**
 * Services for CityHapps
 */

angular.module('cityHapps.services', []).factory('Happ', function($resource) {
    return $resource('/api/happ/:id', {}, {
        query: { isArray: false }
    });
}).factory('Category', function($resource) {
    return $resource('/api/category/:id', {}, {
        query: { isArray: false }
    });
}).factory('Tag', function($resource) {
    return $resource('/api/tag/:id', {}, {
        query: { isArray: false }
    });
}).factory('Venue', function($resource) {
    return $resource('/api/venue/:id', {}, {
        query: { isArray: false }
    });
}).factory('voteService', function() {

    var vote = {};

    return {
        getData: function() {
            return vote;
        }
    };

}).factory('registerDataService', function() {
    var registerDataService = {};
    registerDataService.data = {};
    registerDataService.data.categories = {};
    registerDataService.recEventCount = {};
    registerDataService.vote = {};
    return registerDataService;
}).factory('authFactory', function($http, authService, $rootScope, $modal, $cookies, $cookieStore, $modalStack) {

    var auth = {};

    auth.loginUser = function(formData) {
        $http.post('/auth/login', formData).success(function(res) {
            console.log(res);
            delete res.password;
            localStorage.setItem('user', JSON.stringify(res));

            $cookieStore.put('user', res);
            //ipCookie('user', res, {expires: 2, expirationUnit: 'hours'});

            var userString = localStorage.getItem('user');
            var user = angular.fromJson(userString);

            authService.loginConfirmed();

            $modalStack.dismissAll('submit');

            document.location.reload(true);

        }).
        error(function(data) {
            $rootScope.loginError = 'There was a problem with your username or password';
        });
    };

    auth.logoutUser = function() {
        $http({
            method: 'GET',
            url: '/auth/logout',
            headers: {'Content-Type': 'application/json'}
        }).success(function(data) {
            $cookieStore.remove('user');
            //ipCookie.remove("user");

            document.location.reload(true);
            if (!data) {
                console.log('There was an error logging you out');
            } else if (data) {
                console.log('You have logged out');
            }
        });
    };

    //for checking emails on registration
    auth.userCheck = function(email, callback) {
        $http({
            method: 'POST',
            url: '/user/check',
            data: { 'email' : email },
            headers: {'Content-Type': 'application/json'}
        }).success(function(data) {
            console.log(data);
            if (typeof callback === 'function') {
                callback(data);
            }

        });
    };

    auth.userExist = function(email, callback) {
        $http({
            method: 'POST',
            url: '/user/exist',
            data: { 'email' : email },
            headers: {'Content-Type': 'application/json'}
        }).success(function(data) {
            console.log(data);
            if (typeof callback === 'function') {
                callback(data);
            }
        });
    };

    auth.resetPassword = function(email) {

        $http
            .post('/user/reset-password', email)
            .success(function (res) {
                console.log(res);
            })
            .error(function(res) {
                console.log(res);
            });
    };

    auth.editUserData = function(data) {

        $http
            .post('/user/edit', data)
            .success(function(res) {
                console.log(res);
            })
            .error(function(res) {
                console.log(res);
            });
    };

    return auth;


}).factory('getCategories', function($http) {
    return $http({
        method: 'GET',
        url: '/category',
        headers: {'Content-Type': 'application/json'}
    }).success(function(data) {
        if (!data) {
        } else if (data) {
        }
    });
}).factory('getUserCategories', function($http) {
    return {
        params: function(args) {
            return $http({
                method: 'GET',
                url: '/getUserCategories' + args,
                headers: {'Content-Type': 'application/json'}
            }).success(function(data) {
                if (!data) {
                    console.log('Unable to Get Categories');
                } else if (data) {
                    console.log('successfully Getting Categories');
                    console.log(data);
                }
            });
        }
    };
}).factory('search', function($http, $cookies, $cookieStore) {
    var now = moment().format('YYYY-MM-DD');

    return {
        searchData: function(query) {
            var userID = null;

            var cookie = $cookies.user;
            if (cookie) {
                userID = cookie.id;
            }

            return $http.get('/events?user_id=' + userID + '&search=' + query + '&start_date=' + now)
                .success(function(data, scope) {
                    console.log(data);
                });
        }
    };
}).factory('cleanData', function() {
    return {
        buildRelationships: function(payload) {
            if (payload.included) {
                var data = payload.data;
                var includes = payload.included;
                for (var i = 0; i < data.length; i++) {
                    var relationships = {};
                    for (var k in data[i].links) {
                        if (data[i].links[k].linkage.length != 0) {
                            relationships[k] = [];
                            if (Object.prototype.toString.call(data[i].links[k].linkage) === '[object Array]') {
                                for (var j = 0; j < data[i].links[k].linkage.length; j++) {
                                    for (var l = 0; l < includes.length; l++) {
                                        if (data[i].links[k].linkage[j].id == includes[l].id && data[i].links[k].linkage[j].type == includes[l].type) {
                                            relationships[k].push(includes[l]);
                                        }
                                    }
                                }
                            } else {
                                for (var l = 0; l < includes.length; l++) {
                                    if (data[i].links[k].linkage.id == includes[l].id && data[i].links[k].linkage.type == includes[l].type) {
                                        relationships[k].push(includes[l]);
                                    }
                                }
                            }
                        }
                    }
                    data[i].relationships = relationships;
                }
                payload.data = data;
            }
            return payload;
        }
    };
});
