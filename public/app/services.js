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
}).factory('AgeLevel', function($resource) {
    return $resource('/api/agelevel/:id', {}, {
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

            //document.location.href = '/';

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

            document.location.href = '/';
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
                window.location.href = '/login';
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
                window.location.reload(true);
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
}).service('HappFilterService', function($rootScope, AgeLevel) {
    var service = {};

    AgeLevel.query(function(payload) {
        service.ageLevel = {};
        for (var i = 0; i < payload.data.length; i++) {
            service.ageLevel[payload.data[i].id] = true;
        }
    });
    service.today = true;
    service.tomorrow = false;
    service.weekend = false;
    service.calendar = '';
    service.morning = true;
    service.afternoon = true;
    service.evening = true;
    service.night = true;
    service.indoor = true;
    service.outdoor = true;
    service.zip = '';
    service.search = '';

    service.getDefaults = function(baseObject) {
        baseObject.today = true;
        baseObject.tomorrow = false;
        baseObject.weekend = false;
        baseObject.calendar = '';
        baseObject.morning = true;
        baseObject.afternoon = true;
        baseObject.evening = true;
        baseObject.night = true;
        baseObject.indoor = true;
        baseObject.outdoor = true;
        baseObject.zip = '';
        baseObject.search = '';
        return baseObject;
    };

    service.updateFilter = function(key, value) {
        this[key] = value;
        $rootScope.$broadcast('filterUpdate');
    };

    service.updateAgeLevelFilter = function(key, value) {
        this.ageLevel[key] = value;
        $rootScope.$broadcast('filterUpdate');
    };

    service.getFilters = function(additional) {
        var filter = {};

        // Zip Filter
        if (this.zip.length == 5)
            filter.zip = this.zip;

        // Search Filter
        if (this.search.trim().length > 0) {
            filter.search = this.search;
        }

        // Date Filters
        var date = '';
        if (this.today)
            date += String(this.todayDate());
        if (this.tomorrow) {
            if (date != '')
                date += ',';
            date += String(this.tomorrowDate());
        }
        if (this.weekend) {
            if (date != '')
                date += ',';
            date += String(this.weekendDate());
        }
        if (this.calendar) {
            if (date != '')
                date += ',';
            var calFixed = this.calendar.split('/');
            date += calFixed[2] + calFixed[0] + calFixed[1];
        }
        if (date != '')
            filter.date = date;

        // Time Filters
        var time = '';
        if (this.morning)
            time += 'morning';
        if (this.afternoon) {
            if (time != '')
                time += ',';
            time += 'afternoon';
        }
        if (this.evening) {
            if (time != '')
                time += ',';
            time += 'evening';
        }
        if (this.night) {
            if (time != '')
                time += ',';
            time += 'night';
        }
        if (time != '' && (time.match(/,/g) || []).length != 3)
            filter.timeofday = time;

        // Indoor Outdoor Filter
        var io = '';
        if (this.indoor)
            io += 'indoor';
        if (this.outdoor) {
            if (io != '')
                io += ',';
            io += 'outdoor';
        }
        if (io != '' && (io.match(/,/g) || []).length != 1)
            filter.location_type = io;

        // Age Level filters
        var trueCount = 0;
        var count = 0;
        var ageLevelString = '';
        for (var key in this.ageLevel) {
            if (this.ageLevel[key]) {
                if (ageLevelString != '') {
                    ageLevelString += ',';
                }
                ageLevelString += key;
                trueCount++;
            }
            count++;
        }
        if (ageLevelString != '' && count != trueCount) {
            filter.agelevel = ageLevelString;
        }

        // Get the other items in the argument
        if (typeof additional === 'object') {
            for (key in additional) {
                filter[key] = additional[key];
            }
        }
        return filter;
    };

    service.todayDate = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        if (dd < 10)
            dd = '0' + dd;
        if (mm < 10)
            mm = '0' + mm;
        return yyyy + mm + dd;
    };

    service.tomorrowDate = function() {
        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        var dd = tomorrow.getDate();
        var mm = tomorrow.getMonth() + 1; //January is 0!
        var yyyy = tomorrow.getFullYear();
        if (dd < 10)
            dd = '0' + dd;
        if (mm < 10)
            mm = '0' + mm;
        return yyyy + mm + dd;
    };

    service.weekendDate = function() {
        var today = new Date();
        switch (today.getDay()) {
            case 0:
                return this.todayDate();
            case 6:
                return this.todayDate() + ',' + this.tomorrowDate();
            default:
                var saturday = new Date();
                saturday.setDate(saturday.getDate() + 6 - today.getDay());
                var dd = saturday.getDate();
                var mm = saturday.getMonth() + 1; //January is 0!
                var yyyy = saturday.getFullYear();
                if (dd < 10)
                    dd = '0' + dd;
                if (mm < 10)
                    mm = '0' + mm;
                var sat = yyyy + mm + dd;
                var sunday = new Date();
                sunday.setDate(saturday.getDate() + 1);
                dd = sunday.getDate();
                mm = sunday.getMonth() + 1; //January is 0!
                yyyy = sunday.getFullYear();
                if (dd < 10)
                    dd = '0' + dd;
                if (mm < 10)
                    mm = '0' + mm;
                return sat + ',' + yyyy + mm + dd;
        }
    };

    return service;
});
