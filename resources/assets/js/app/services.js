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
}).factory('authFactory', function($http, authService, $rootScope, $modal, $cookies, $cookieStore, $modalStack, userDecorator) {

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
            data: { 'value' : email },
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
}).factory('userDecorator', function() {
  return {
    decorate: function(rawUser) {
      var methods = {
        isAdmin: function() {
          return this.isRole('admin');
        },
        isCurator: function() {
          return this.isRole('curator');
        },
        isRole: function(role) {
          return this.role === "ROLE_" + role.toUpperCase();
        },
        canEdit: function(happ) {
          return this.isAdmin() || this.isCurator() && this.id === happ.user_id;
        },
        canCreate: function() {
          return this.isAdmin() || this.isCurator();
        }
      };

      return angular.extend({}, rawUser, methods);
    }
  };
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
}).factory('siteSettings', function($http) {
  return {
    get: function(key) {
      return $http.get('/site-settings').then(function(res) {
        return res.data[key];
      }, function(res) {
        console.log("Failed to fetch site settings", key);
      });
    }
  };
}).factory('userProfile', function($http) {
      return {
         get: function(id){
              return $http({
              method: 'GET',
              url: '/api/favorites/' + id,
            }).success(function(data) {
                if (!data) {
                } else if (data) {
                }
            })
        },
        changePassword: function(user){
            return $http({
              method: 'POST',
              url: '/user/change-password',
              data: { 'user': user}
            }).success(function(data) {
                if (!data) {
                } else if (data) {
                }
            }).error(function(error){
                console.log('unable to change password');
                console.log(error);
            })
        },
        check: function(user_id, id){
            return $http({
              method: 'POST',
              url: '/api/favorites/check',
              data: { 'user_id': user_id, 'id': id}
            }).success(function(data) {
                if (!data) {
                } else if (data) {
                }
            }).error(function(error){
            })
          }
      };
}).factory('getFavorites', function($http) {
      return {
         get: function(id){
              return $http({
              method: 'GET',
              url: '/api/favorites/' + id,
            }).success(function(data) {
                if (!data) {
                } else if (data) {
                }
            })
        },
        add: function(user_id, id){
            return $http({
              method: 'POST',
              url: '/api/favorites/add',
              data: { 'user_id': user_id, 'id': id}
            }).success(function(data) {
                if (!data) {
                } else if (data) {
                }
            }).error(function(error){
                console.log('unable to add item in favorites');
            })
        },
        check: function(user_id, id){
            return $http({
              method: 'POST',
              url: '/api/favorites/check',
              data: { 'user_id': user_id, 'id': id}
            }).success(function(data) {
                if (!data) {
                } else if (data) {
                }
            }).error(function(error){
            })
          }
      };
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
    service.today = false;
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
        baseObject.today = false;
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
        if (date != '') {
            filter.date = date;
        }
        else {
            filter.date = '>' + String(this.todayDate());
            filter.sort = '+event_date,-user_id,+start_time';
        }

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
        return yyyy.toString() + mm.toString() + dd.toString();
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
        return yyyy.toString() + mm.toString() + dd.toString();
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
                var sat = yyyy.toString() + mm.toString() + dd.toString();
                var sunday = new Date();
                sunday.setDate(saturday.getDate() + 1);
                dd = sunday.getDate();
                mm = sunday.getMonth() + 1; //January is 0!
                yyyy = sunday.getFullYear();
                if (dd < 10)
                    dd = '0' + dd;
                if (mm < 10)
                    mm = '0' + mm;
                return sat + ',' + yyyy.toString() + mm.toString() + dd.toString();
        }
    };

    return service;
}).factory('socialLinker', [
    '$window', '$location', function($window, $location) {
      return function(urlFactory) {
        return function(scope, element, attrs) {
          var getCurrentUrl, handler, popupWinAttrs;
          popupWinAttrs = "status=no, width=" + (scope.socialWidth || 640) + ", height=" + (scope.socialWidth || 480) + ", resizable=yes, toolbar=no, menubar=no, scrollbars=no, location=no, directories=no";
          getCurrentUrl = function() {
            return attrs.customUrl || $location.absUrl();
          };
          attrs.$observe('customUrl', function() {
            var url;
            url = urlFactory(scope, getCurrentUrl());
            if (element[0].nodeName === 'A' && ((attrs.href == null) || attrs.href === '')) {
              return element.attr('href', url);
            }
          });
          element.attr('rel', 'nofollow');
          handler = function(e) {
            var url, win;
            e.preventDefault();
            url = urlFactory(scope, getCurrentUrl());
            return win = $window.open(url, 'popupwindow', popupWinAttrs).focus();
          };
          if (attrs.customHandler != null) {
            element.on('click', handler = function(event) {
              var url;
              url = urlFactory(scope, getCurrentUrl());
              element.attr('href', url);
              return scope.handler({
                $event: event,
                $url: url
              });
            });
          } else {
            element.on('click', handler);
          }
          return scope.$on('$destroy', function() {
            return element.off('click', handler);
          });
        };
      };
    }
]).factory('happEditFormData', function(Happ, AgeLevel, cleanData, $http, $filter, $q) {
  return {
    get: function(id) {
      return $q(function(resolve, reject) {
        Happ.get({ id: id, include: 'tags,categories,venues,ageLevels' }, function(payload) {
          payload = cleanData.buildRelationships(payload);
          var singleEvent = payload.data[0];
          var formatDate = function(date) {
            if (date && date.local) {
              return $filter('date')(date.local, 'MM/dd/yyyy hh:mm a');
            }
          };
          var formData = {
            relationships: singleEvent.relationships,
            title: singleEvent.event_name,
            status: singleEvent.status,
            event_id: singleEvent.id,
            parent_id: singleEvent.parent_id,
            event_url: singleEvent.url,
            event_image_url: singleEvent.event_image_url,
            organization_image_url: singleEvent.organization_image_url,
            venue_name: singleEvent.venue_name,
            venue_url: singleEvent.venue_url,
            street_address: singleEvent.address.street_1,
            city: singleEvent.address.city,
            state: singleEvent.address.state,
            zip_code: singleEvent.address.zip,
            desc: singleEvent.description,
            all_day: Boolean(parseInt(singleEvent.all_day_flag)),
            start_time: formatDate(singleEvent.start),
            end_time: formatDate(singleEvent.end),
            business_hours: singleEvent.business_hours,
            created_at: singleEvent.created_at,
            updated_at: singleEvent.updated_at,
            similar_events_model: singleEvent.similar,
            similar_events_storage: singleEvent.similar.reduce(function(memo, value) {
              if (value.parent_id) memo.push(value.id);
              return memo;
            }, [])
          };

          if (singleEvent.hasOwnProperty('relationships')) {
            if (singleEvent.relationships.hasOwnProperty('categories')) {
              formData.categories = singleEvent.relationships.categories.map(function(value) { return value.id; });
            }
            if (singleEvent.relationships.hasOwnProperty('tags')) {
              console.log('tags:');
              console.log(singleEvent.relationships.tags);
              formData.tags = singleEvent.relationships.tags;
            }
            if (singleEvent.relationships.hasOwnProperty('venue')) {
              formData.venue = singleEvent.relationships.venue[0];
            }
          }

          formData.locationType = {
            outdoor: singleEvent.location_type == 'Outdoor',
            indoor: singleEvent.location_type == 'Indoor'
          };

          formData.parent = [];

          if (formData.parent_id > 0) {
            //This event has NO suggested similar, events let's fetch the parent information
            $http.get('/events/?id=' + $stateParams.id + '&current_id=' + formData.parent_id).success(function(data){formData.parent = data;});
          }
          resolve(formData);
        });
      });
    }
  };
}).factory('venueEditFormData', function(Venue, $q, $http) {
  return {
    get: function(id) {
      return $q(function(resolve, reject) {
        Venue.get({ id: id, include: 'tags' }, function(payload) {
          var singleVenue = payload.data[0];

          var formData = {
            venue_name: singleVenue.name,
            venue_id: singleVenue.id,
            parent_id: singleVenue.parent_id,
            venue_url: singleVenue.url,
            venue_image_url: singleVenue.image,
            organization_image_url: singleVenue.organization_image_url,
            phone: singleVenue.phone,
            street_address: singleVenue.address_1,
            city: singleVenue.city,
            state: singleVenue.state,
            zip_code: singleVenue.postal_code,
            desc: singleVenue.description,
            hours: singleVenue.hours,
            business_hours: singleVenue.business_hours,
            created_at: singleVenue.created_at,
            updated_at: singleVenue.updated_at,
            parent_id: singleVenue.parent_id
          };

          $http.get('/api/venue/'+id+'/similar').then(function(res) {
            formData.similar_venues_model = res.data;
            formData.similar_venues_storage = res.data.reduce(function(memo, value) {
              if (value.parent_id) memo.push(value.id);
              return memo;
            }, []);

            resolve(formData);
          }, function() {
            formData.similar_venues_model = [];
            formData.similar_venues_storage = [];

            resolve(formData);
          });
        });
      });
    }
  };
}).factory('venueTypeahead', function($q, $http) {
  return {
    get: _.debounce(function(typed) {
      if (typed.length < 3) return $q.resolve([]);
      return $q(function(resolve, reject) {
        return $http.get('/api/venue', { params: {search: typed} })
          .then(function(res) {
            resolve(res.data.data);
          }, reject);
      });
    }, 200)
  };
});
