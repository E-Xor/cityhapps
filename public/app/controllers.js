/**
 * Controllers for CityHapps
 */

angular.module('cityHapps.controllers', []).controller('AuthController', function($auth, $state, $http, $rootScope, authFactory) {
    var vm = this;

    vm.loginError = false;
    vm.loginErrorText;

    vm.login = function() {
        var credentials = {
            email: vm.email,
            password: vm.password
        };
        console.log(credentials);
        authFactory.loginUser(credentials);

        $auth.login(credentials).then(function() {

            // Return an $http request for the now authenticated
            // user so that we can flatten the promise chain
            return $http.get('api/authenticate/user');

            // Handle errors
        }, function(error) {
            vm.loginError = true;
            vm.loginErrorText = error.data.error;

            // Because we returned the $http.get request in the $auth.login
            // promise, we can chain the next promise to the end here
        }).then(function(response) {

            // Stringify the returned data to prepare it
            // to go into local storage
            var user = JSON.stringify(response.data.user);

            // Set the stringified user data into local storage
            localStorage.setItem('user', user);

            // The user's authenticated state gets flipped to
            // true so we can now show parts of the UI that rely
            // on the user being logged in
            $rootScope.authenticated = true;

            // Putting the user's data on $rootScope allows
            // us to access it anywhere across the app
            $rootScope.currentUser = response.data.user;

            // Everything worked out so we can now redirect to
            // the users state to view the data
            $state.go('/', {});
        });
    };
    vm.authenticate = function(provider) {
      $auth.authenticate(provider);
    };
}).controller('MainFilterController', function($scope, $stateParams, HappFilterService) {
    $scope.filterDefaults = HappFilterService.getDefaults();
    $scope.hideFilter = true;
    $scope.$watchCollection('filterDefaults', function(newFilters, oldFilters) {
        for (var key in newFilters) {
            if (newFilters[key] != oldFilters[key]) {
                HappFilterService.updateFilter(key, newFilters[key]);
            }
        }
    });
    jQuery('#date_calendar').datetimepicker({
        timepicker: false,
        mindate: 0,
        format: 'm/d/Y',
        onSelectDate: function(ct, $i, event) {
            $scope.filterDefaults.calendar = $i[0].value;
            $scope.$apply();
        }
    });
    jQuery(window).resize(function() {
        var setHeight = (jQuery('.filter-options').height() * -1) + 'px';
        jQuery('.filter-options').css({bottom: setHeight});
    });
    jQuery(function() {
        $(window).trigger('resize');
    });
}).controller('HappViewController', function($scope, $stateParams, cleanData, Happ) {
    Happ.get({ id: $stateParams.id, include: 'tags,categories,venues'}, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.happ = payload.data[0];
    });
}).controller('HappHomeController', function($scope, $stateParams, cleanData, HappFilterService, Happ) {
    var filter = HappFilterService.getFilters({include: 'categories,venues'});
    Happ.query(filter, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.happs = payload.data;
    });
    $scope.$on('filterUpdate', function() {
        var filter = HappFilterService.getFilters({include: 'categories,venues'});
        Happ.query(filter, function(payload) {
            payload = cleanData.buildRelationships(payload);
            $scope.happs = payload.data;
        });
    });
}).controller('happController', function($scope, $http, $stateParams, $cookies, $cookieStore, cleanData, Happ) {

        $scope.user = $cookies.user ? JSON.parse($cookies.user) : $cookies.user;
        $scope.likeStatus;

        if (typeof $stateParams.id !== 'undefined') {
            Happ.get({ id: $stateParams.id }, function(payload) {
                payload = cleanData.buildRelationships(payload);
                $scope.data = payload.data[0];
            });
        } else {
            // Get filter data here
            var filters = '';
            Happ.query(function(payload) {
                payload = cleanData.buildRelationships(payload);
                $scope.data = payload.data;
            });
        }

        $scope.checkLikeStatus = function() {
            var userId = $scope.user.id;
            var eventId = $scope.data.id;

            $http({
                method: 'POST',
                url: '/checkUserEventVote',
                data: {
                    'user_id' : userId,
                    'event_id' : eventId
                },
                headers: {'Content-Type': 'application/json'}
            }).success(function(data) {
                if (!data) {
                    $scope.likeStatus = false;

                } else if (data) {
                    if (data.status == 'ok') {
                        $scope.likeStatus = true;
                    } else {
                        $scope.likeStatus = false;
                    }
                }
            });
        };

        $scope.vote = {};

        if ($scope.user) {

            $scope.simpleVoteEvent = function(event, action) {

                var eventID = event.id;

                var userID = $scope.user.id;
                var upVote = event.vote.upVote;
                var downVote = event.vote.downVote;

                var eventVote = -1;

                if (action == 'up') {
                    event.vote.downVote = false;
                    if (upVote == true) {
                        eventVote = 1;
                    } else {
                        eventVote = -1
                    }
                } else { // Downvote changed
                    event.vote.upVote = false;
                    if (downVote == true) {
                        eventVote = -1;
                    }
                }
                $http({
                    method: 'POST',
                    url: '/userEvent',
                    data: {
                        'user_id' : userID,
                        'event_id' : eventID,
                        'vote' : eventVote
                    },
                    headers: {'Content-Type': 'application/json'}
                }).success(function(data) {
                    if (!data) {
                        console.log('no vote, man');
                    } else if (data) {
                        console.log(data);
                        $scope.likeStatus = !$scope.likeStatus;
                    }
                });
            };
        }
}).controller('CategoryHappController', function($scope, $stateParams, cleanData, HappFilterService, Happ) {
    var filter = HappFilterService.getFilters({include: 'categories,venues', category: $stateParams.slug});
    Happ.query(filter, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.happs = payload.data;
    });
    $scope.$on('filterUpdate', function() {
        var filter = HappFilterService.getFilters({include: 'categories,venues', category: $stateParams.slug});
        Happ.query(filter, function(payload) {
            payload = cleanData.buildRelationships(payload);
            $scope.happs = payload.data;
        });
    });
}).controller('CategorySidebarController', function($scope, cleanData, Category) {
    Category.query(function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.categories = payload.data;
    });
}).controller('VenueListController', function($scope, $stateParams, cleanData, Venue) {
    var size = 48;
    var page = (typeof $stateParams.page != 'undefined') ? parseInt($stateParams.page) : 1;
    Venue.query({'page[size]': size, 'page[number]': page}, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.venues = payload.data;
        // Build some pagination
        var qd = {};
        payload.links.last.split('?')[1].split("&").forEach(function(item) {var k = item.split("=")[0], v = item.split("=")[1]; v = v && decodeURIComponent(v); (k in qd) ? qd[k].push(v) : qd[k] = [v]})
        var last = parseInt(qd['page[number]'][0]);

        $scope.pagination = [];
        if (page != 1) {
            $scope.pagination.push({'text': '< Previous', 'link': '/venues/' + (page - 1)});
        }
        $scope.pagination.push({'text': '1', 'link': '/venues'});
        
        if (page - 3 > 1)
            $scope.pagination.push({'text': '...'});

        for (var i = page - 2; i < page + 3; i++) {
            if (i > 1 && i < last)
                $scope.pagination.push({'text': String(i), 'link': '/venues/' + i});
        };

        if (page + 3 < last)
            $scope.pagination.push({'text': '...'});

        $scope.pagination.push({'text': String(last), 'link': '/venues/' + last});
        if (page != last) {
            $scope.pagination.push({'text': 'Next >', 'link': '/venues/' + (page + 1)});
        }

    });
}).controller('VenueViewController', function($scope, $stateParams, cleanData, Venue) {
    Venue.get({id: $stateParams.id, include: 'happs'}, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.venue = payload.data[0];
    });
}).controller('venueController', function($scope, $http, $stateParams, $cookies, $cookieStore) {
        $scope.user = $cookies.user ? JSON.parse($cookies.user) : $cookies.user;
        $scope.likeStatus;

        $http.get('/venues?id=' + $stateParams.id)
            .success(function(data) {
                if (data.venues.length > 0) {
                    $scope.data = data.venues[0];
                    if ($scope.user) {
                        $scope.checkLikeStatus();
                    }
                }
            });

        $scope.checkLikeStatus = function() {
            var userId = $scope.user.id;
            var venueId = $scope.data.id;

            $http({
                method: 'POST',
                url: '/checkUserVenueVote',
                data: {
                    'user_id' : userId,
                    'venue_id' : venueId
                },
                headers: {'Content-Type': 'application/json'}
            }).success(function(data) {
                if (!data) {
                    $scope.likeStatus = false;
                } else if (data) {
                    console.log(data);
                    if (data.status == 'ok') {
                        $scope.likeStatus = true;
                    } else {
                        $scope.likeStatus = false;
                    }
                }
            });
        };

        $scope.vote = {};

        if ($scope.user) {

            $scope.simpleVoteVenue = function(venue, action) {

                var venueID = venue.id;

                var userID = $scope.user.id;
                var upVote = venue.vote.upVote;
                var downVote = venue.vote.downVote;

                var venueVote = -1;

                if (action == 'up') {
                    venue.vote.downVote = false;
                    if (upVote == true) {
                        venueVote = 1;
                    } else {
                        venueVote = -1
                    }
                } else { // Downvote changed
                    venue.vote.upVote = false;
                    if (downVote == true) {
                        venueVote = -1;
                    }
                }
                $http({
                    method: 'POST',
                    url: '/userVenue',
                    data: {
                        'user_id' : userID,
                        'venue_id' : venueID,
                        'vote' : venueVote
                    },
                    headers: {'Content-Type': 'application/json'}
                }).success(function(data) {
                    if (!data) {
                        console.log('No vote registered.');
                    } else if (data) {
                        console.log(data);
                        $scope.likeStatus = !$scope.likeStatus;
                    }
                });
            };
        }
}).controller('adminEventController', function($scope, $http, $stateParams, $cookies, $cookieStore, Happ) {

    $scope.user = $cookies.user;

    // Processing the form data for adding an event
    $scope.processForm = function(formData) {
        var edit = ($stateParams.id ? true : false);
        // Validation
        var error = 0;
        if (!formData) {
          $scope.generalError = true;
          return;
        }
        if (typeof formData.title === 'undefined' || formData.title == '') {
          error = 1;
          $scope.titleError = true;
        }
        if (typeof formData.venue_name === 'undefined' || formData.venue_name == '') {
          error = 1;
          $scope.venueError = true;
        }
        if (typeof formData.street_address === 'undefined' || formData.street_address == '') {
          error = 1;
          $scope.addressError = true;
        }
        if (typeof formData.start_time === 'undefined' || formData.start_time == '') {
          error = 1;
          $scope.startDateError = true;
        }
        if (typeof formData.desc === 'undefined' || formData.desc == '') {
          error = 1;
          $scope.descError = true;
        }
        if (formData.parent.length > 0) {
            formData.parent_id = formData.parent[0]['id'];
        }

        // if any error, don't post
        if (error) {
          $scope.generalError = true;
          return;
        }
        console.log(formData);
        if (!edit) {
            $http({
                method: 'POST',
                url: '/admin/event/create',
                    data: formData,
                    headers: {'Content-Type': 'application/json'}
            }).success(function(data) {
                if (!data) {
                    console.log('Data Not Posting');
                }
                else if (data) {
                    if (data.error) {
                        $scope.error = data.message;
                        console.log('Error creating event', data.message);
                    }
                    else {
                        $scope.success = data;
                        console.log('Success');
                    }
                }
            }).error(function(data) {
                    $scope.error = data.error.message;
            });
        } else {
            $http({
                method: 'POST',
                url: '/admin/event/update',
                data: formData,
                headers: {'Content-Type': 'application/json'}
            }).success(function(data) {
                if (!data) {
                    console.log('Data Not Posting');
                }
                else if (data) {
                    if (data.error) {
                        $scope.error = data.message;
                        console.log('Error updating event', data.message);
                    }
                    else {
                        $scope.success = data;
                        console.log('Success');
                    }
                }
            }).error(function(data) {
                $scope.error = data.error.message;
            });
        }
        }
    // Retieving all of the data for the listing page
    $http.get('/events?page_size=all')
        .success(function(data) {
        $scope.eventsCount = data.events.length;
        var allEventsUnformatted = data.events;
        for (var i = 0; i < allEventsUnformatted.length; i++) {
            if (moment(allEventsUnformatted[i].start_time).isValid()) {
                allEventsUnformatted[i].start_date = moment(allEventsUnformatted[i].start_time).format('M/D/YYYY');
                allEventsUnformatted[i].start_only_time = moment(allEventsUnformatted[i].start_time).format('h:mm:ss a');
            }
            else {
                allEventsUnformatted[i].start_date = '';
                allEventsUnformatted[i].start_only_time = '';
            }
            if (moment(allEventsUnformatted[i].end_time).isValid()) {
                allEventsUnformatted[i].end_date = moment(allEventsUnformatted[i].end_time).format('M/D/YYYY');
                allEventsUnformatted[i].end_only_time = moment(allEventsUnformatted[i].end_time).format('h:mm:ss a');
            }
            else {
                allEventsUnformatted[i].end_date = '';
                allEventsUnformatted[i].end_only_time = '';
            }
        }
        $scope.allEvents = allEventsUnformatted;
    });

    // edit page
    if ($stateParams.id) {
        $http.get('/events?id=' + $stateParams.id)
            .success(function(data) {
                if (data.events.length > 0)
                {
                    var singleEvent = data.events[0];
                    $scope.formData.title = singleEvent.event_name;
                    $scope.formData.event_id = singleEvent.id;
                    $scope.formData.parent_id = singleEvent.parent_id;
                    $scope.formData.event_url = singleEvent.url;
                    $scope.formData.event_image_url = singleEvent.event_image_url;
                    $scope.formData.venue_name = singleEvent.venue_name;
                    $scope.formData.venue_url = singleEvent.venue_url;
                    $scope.formData.street_address = singleEvent.address;
                    $scope.formData.city = singleEvent.city;
                    $scope.formData.state = singleEvent.state;
                    $scope.formData.zip_code = singleEvent.zip;
                    $scope.formData.desc = singleEvent.description;
                    $scope.formData.all_day = (singleEvent.all_day_flag ? true : false );
                    $scope.formData.start_time = singleEvent.start_time;
                    $scope.formData.end_time = singleEvent.end_time;
                    dateCheckCreate = new Date(singleEvent.created_at).getTime() / 1000;
                    dateCheckUpdate = new Date(singleEvent.updated_at).getTime() / 1000;
                    if (dateCheckCreate != dateCheckUpdate)
                       $scope.updated_last  = singleEvent.updated_at;
                    $scope.formData.similar_events_model = singleEvent.similar;
                    $scope.formData.similar_events_storage = (function () {
                        var base = [];
                        angular.forEach(singleEvent.similar, function (value) {
                            if (value.parent_id != null) {
                                base.push(value.id);
                            }
                        });
                        return base;
                    })();
                    $scope.formData.tags = singleEvent.tags;
                    $scope.formData.parent = [];

                    $scope.loadTags = function(query) {
                        return $http.get('/tags/' + query);
                    };
                    $scope.loadEvents = function(query){
                        return $http.get('/events/?name=' + query + '&current_id=' + $stateParams.id);
                    };
                    if ($scope.formData.parent_id > 0) {
                        //This event has NO suggested similar, events let's fetch the parent information
                        $http.get('/events/?id=' + $stateParams.id + '&current_id=' + $scope.formData.parent_id ).success(function(data){$scope.formData.parent = data;});
                    }
                }
        })
    }

}).controller('adminVenueController', function($scope, $http, $stateParams, $cookies, $cookieStore, Venue) {

    $scope.user = $cookies.user;

    // Processing the form data for adding an event
    $scope.processForm = function(formData) {
        var edit = ($stateParams.id ? true : false);
        // Validation
        var error = 0;
        if (!formData) {
          $scope.generalError = true;
          return;
        }
        if (typeof formData.venue_name === 'undefined' || formData.venue_name == '') {
          error = 1;
          $scope.venueError = true;
        }
        if (typeof formData.street_address === 'undefined' || formData.street_address == '') {
          error = 1;
          $scope.addressError = true;
        }
        if (typeof formData.desc === 'undefined' || formData.desc == '') {
          error = 1;
          $scope.descError = true;
        }
        if (formData.parent) {
            if (formData.parent.length > 0) {
                formData.parent_id = formData.parent[0]['id'];
            }
        }
        // if any error, don't post
        if (error) {
          $scope.generalError = true;
          return;
        }
        if (!edit) {
            $http({
                method: 'POST',
                url: '/admin/venue/create',
                    data: formData,
                    headers: {'Content-Type': 'application/json'}
            }).success(function(data) {
                if (!data) {
                    console.log('Data Not Posting');
                }
                else if (data) {
                    if (data.error) {
                        $scope.error = data.message;
                        console.log('Error creating venue', data.message);
                    }
                    else {
                        $scope.success = data;
                        console.log('Success');
                    }
                }
            }).error(function(data) {
                    $scope.error = data.error.message;
            });
        } else {
            $http({
                method: 'POST',
                url: '/admin/venue/update',
                data: formData,
                headers: {'Content-Type': 'application/json'}
            }).success(function(data) {
                if (!data) {
                    console.log('Data Not Posting');
                }
                else if (data) {
                    if (data.error) {
                        $scope.error = data.message;
                        console.log('Error updating venue', data.message);
                    }
                    else {
                        $scope.success = data;
                        console.log('Success');
                    }
                }
            }).error(function(data) {
                $scope.error = data.error;//.message;
            });
        }
    };
    // Retrieving all of the data for the listing page
    var pageNumber = ($stateParams.page) ? '&page=' + $stateParams.page : '&page=1';
    $http.get('/api/venues?page[size]=500' + pageNumber)
        .success(function(data) {
            $scope.venuesCount = data.length;
            $scope.allVenues = data.venues;
    });

    // edit page
    if ($stateParams.id) {
        Venue.get({ id: $stateParams.id }, function(payload) {
            var singleVenue = payload.data[0];
            $scope.formData = {};
            $scope.formData.venue_name = singleVenue.name;
            $scope.formData.venue_id = singleVenue.id;
            $scope.formData.parent_id = singleVenue.parent_id;
            $scope.formData.venue_url = singleVenue.url;
            $scope.formData.venue_image_url = singleVenue.image;
            $scope.formData.phone = singleVenue.phone;
            $scope.formData.street_address = singleVenue.address_1;
            $scope.formData.city = singleVenue.city;
            $scope.formData.state = singleVenue.state;
            $scope.formData.zip_code = singleVenue.postal_code;
            $scope.formData.desc = singleVenue.description;
            $scope.formData.hours = singleVenue.hours;
            dateCheckCreate = new Date(singleVenue.created_at).getTime() / 1000;
            dateCheckUpdate = new Date(singleVenue.updated_at).getTime() / 1000;
            if (dateCheckCreate != dateCheckUpdate)
               $scope.updated_last = singleVenue.updated_at;
            $scope.formData.parent_id = singleVenue.parent_id;
            $scope.formData.similar_venues_model = singleVenue.similar;
            console.log($scope.formData);
            $scope.formData.similar_venues_storage = (function() {
                var base = [];
                angular.forEach(singleVenue.similar, function(value) {
                    if (value.parent_id != null) {
                        base.push(value.id);
                    }
                });
                return base;
            })();
            $scope.formData.tags = singleVenue.tags;
            $scope.loadTags = function(query) {
                return $http.get('/tags/' + query);
            };

            console.log($scope.formData);
        });
    }
}).controller('appController', function($scope, $window, $idle, $rootScope, authService, registerDataService, voteService, authFactory, $http, $modal, $location, getCategories, getUserCategories, search, $cookies, $cookieStore) {

        $scope.$on('$idleStart', function() {

            alert('idle firing now');

        });

        $scope.user = $cookies.user;

        $scope.filterData = {};
        $scope.filterData.categories = {};

        // I modified this Gist to flatten return object from php
        // https://gist.github.com/penguinboy/762197
            var flattenObject = function(ob) {
                var toReturn = {};

                for (var i in ob) {
                    if (!ob.hasOwnProperty(i)) continue;

                    if ((typeof ob[i]) == 'object') {
                        var flatObject = flattenObject(ob[i]);
                        for (var x in flatObject) {
                            if (!flatObject.hasOwnProperty(x)) continue;

                            toReturn[x] = flatObject[x];
                        }
                    } else {
                        toReturn[i] = ob[i];
                    }
                }
                return toReturn;
            };



            $scope.categoryToggle = function() {
                $(".categoriesDropdownUser").fadeToggle();

                getCategories.success(function(data){

                    $scope.categories = data;

                    var user = localStorage.user;
                    user = JSON.parse(user);
                    params = "?id=" + user.id;

                    getUserCategories.params(params).success(function(data){

                        $scope.filterData = {};
                        $scope.filterData.userCategories = flattenObject(data);

                    });

                });
            };

            if ($rootScope.user) {

                $scope.filterCategoryUser = function() {

                    var userID = null;

                    var cookie = $cookies.user;
                    if (cookie) {
                        userID = cookie.id;
                    }


                    $scope.nowGet = moment().format();
                    $scope.nowDateGet = moment().format('YYYY-MM-DD');

                    var queryString = '';

                    for (var i in $scope.filterData.categories){
                        console.log(i);
                        if ($scope.filterData.categories[i] == true) {
                            queryString += "category=[]" + i;
                            //+ "&";
                        }
                    }

                    $http.get("/events?user_id=" + userID + "&start_date=" + $scope.nowDateGet + '&start_time=' + $scope.nowGet + "&page_count=1" + "&page_size=10" + queryString)
                        .success(function(data){
                            $scope.eventData = data;
                            eventSuccess(data);
                            recommendedEventSuccess(data);

                            console.log($scope.filterData.categories);
                    });

                }
            };

        $scope.helpFade = function() {
            $('.help-overlay').fadeToggle();
        };

        $('div').fadeIn('fast');

        $scope.mobile = function() {
            if ($window.innerWidth <= 768) {
                return true;
            } else {
                return false;
            }
        };

        $scope.search = function(query) {
            if ($window.innerWidth <= 768) {
                $('.search-large').blur();
                search.searchData(query).success(function(data) {
                    $scope.$broadcast('search', data);
                    $scope.searchQuery = data.events.length + ' results for ' + '<div class="red">' + query + '</div>';
                });
            } else {
                $location.path('/search');
                search.searchData(query).success(function(data) {
                    $scope.$broadcast('search', data);
                    $scope.searchAmount = data.events.length + ' results for ';
                    $scope.searchLength = data.events.length;
                    $scope.searchQuery = query;
                });
            }

        };

        $rootScope.user = $cookies.user;

        if ($rootScope.user) {

            $scope.voteEvent = function(event, num, action) {

                var eventID = event[num].event_id;
                if (eventID == undefined) {
                    eventID = event[num].id; // Recommended events have event_id, regular events have id
                }

                console.log(event);

                var userID = $scope.user.id;
                var upVote = event[num].vote.upVote;
                var downVote = event[num].vote.downVote;

                var eventVote = -1;

                if (action == 'up') {
                    event[num].vote.downVote = false;
                    if (upVote == true) {
                        eventVote = 1;
                    }
                } else { // Downvote changed
                    event[num].vote.upVote = false;
                    if (downVote == true) {
                        eventVote = 0;
                    }
                }

                $http({
                    method: "POST",
                    url: '/userEvent',
                    data: {
                        'user_id' : userID,
                        'event_id' : eventID,
                        'vote' : eventVote
                    },

                    headers : {"Content-Type": "application/json"}
                }).success(function(data) {

                    if (!data) {
                        console.log("no vote, man");
                        // $scope.loggedOut = false;;
                    } else if(data) {
                        console.log(data);
                        // $scope.loggedOut = true;
                    }
                });

            };

        } else {

            $scope.voteEvent = function(event) {
                $modal.open({
                    templateUrl: "templates/registrationModal.html",
                    controller: 'modalInstanceController'
                });
            }
        }

        $scope.formData = {
            email : '',
            username : '',
            password: ''
        };

        //$scope.loginUser = function(formData) {
        //  authFactory.loginUser(formData);
        //};


        $scope.vote = registerDataService.vote;

        $scope.$on('event:loginConfirmed', function(){
            // alert("youre logged in");

            $scope.upvoted = '';

        });
    }
).controller('registerFormController', function($scope, $http, $modal, registerDataService, $timeout, authFactory, Facebook, Category, $controller, $cookieStore, $cookies) {
    //Facebook Auth

    // Define user empty data :/
    $scope.user = {};

    /**
    * Watch for Facebook to be ready.
    * There's also the event that could be used
    */
    $scope.$watch(
    function() {
      return Facebook.isReady();
    },
    function(newVal) {
      if (newVal)
        $scope.facebookReady = true;
    }
    );

    var userIsConnected = false;

    Facebook.getLoginStatus(function(response) {
    if (response.status == 'connected') {
      userIsConnected = true;

    }
    });

    $scope.IntentLogin = function() {
    if(!userIsConnected) {
      $scope.login();
    }
    };


    $scope.login = function() {
        Facebook.login(function(response) {
            if (response.status == 'connected') {
                $scope.logged = true;
                $scope.user.token = response;

                console.log($scope.user.token);

                Facebook.api('/me', function(response) {
                    $scope.$apply(function() {

                        console.log(response);
                        $scope.user.info = response;

                        $scope.fbInfo = {
                            "categories" : {},
                            "email" : $scope.user.info.email,
                            "password" : $scope.user.token.authResponse.accessToken,
                            "name" : $scope.user.info.name
                        };

                        authFactory.userCheck($scope.user.info.email, function(response){

                            console.log(response);

                            $scope.fbUser = {
                                "email" : $scope.fbInfo.email,
                                "password" : $scope.fbInfo.password
                            };

                            if (response.isValid == true ) {
                                registerDataService.data = $scope.fbInfo;

                                $modal.open({
                                    templateUrl: "templates/categoriesModal.html",
                                    controller: 'modalInstanceController'
                                });

                            } else {
                                console.log(response.id);

                                $http({
                                    method: 'PATCH',
                                    url: '/user/' + response.id,
                                    data: {"password" : $scope.fbUser.password},
                                    headers: {"Content-Type": "application/json"}
                                }).success(function(data){
                                    authFactory.loginUser($scope.fbUser);
                                });

                            }

                        });
                    });
                });
            }
        }, {scope: 'email'});
    };

        $scope.loginUser = function(formData) {
            console.log("login data");
            console.log(formData);
          authFactory.loginUser(formData);
        };

      $scope.logout = function() {

      };

      $scope.remove = function() {
        Facebook.api(
            '/me/permissions/user_profile',
            'DELETE',
            function(response) {
              if (response && !response.error) {
                alert('access revoked');
              }
            }
        );
      };
        $scope.registerCategories = {};

        $scope.getAllCategories = function() {
            Category.query(function(payload) {
                $scope.registerCategories = payload.data;
            });
        };

      // $scope.$on('Facebook:statusChange', function(ev, data) {
      //   console.log('Status: ', data);
      //   if (data.status == 'connected') {
      //     $scope.$apply(function() {
      //       $scope.salutation = true;
      //       $scope.byebye     = false;
      //     });
      //   } else {
      //     $scope.$apply(function() {
      //       $scope.salutation = false;
      //       $scope.byebye     = true;

      //       // Dismiss byebye message after two seconds
      //       $timeout(function() {
      //         $scope.byebye = false;
      //       }, 2000);
      //     });
      //   }


      // });

    $scope.formData = registerDataService.data;

    // $scope.categoryService = categoryService.getCategories();

    var credentials = {
        'email': $scope.formData.email,
        'password': $scope.formData.password,
        'username': $scope.formData.username
    };

    $scope.processForm = function(formData) {
        $http({
            method: 'POST',
            url: '/user',
            data: formData,
            headers: {'Content-Type': 'application/json'}
        }).success(function(data) {
            if (!data) {
                console.log('not working');
            } else if (data) {
                var fbInfo = {
                    'email' : data.email,
                    'password' : data.fb_token
                };
                $scope.id = data.id;

                var auth = $controller('AuthController');
                auth.email = formData.email;
                auth.password = formData.password;

                authFactory.loginUser({
                    'email': formData.email,
                    'password': formData.password
                });

                auth.login();
            }
        });
    };

    $scope.checkCategories = function() {
        var obj = $scope.formData.categories;

        for (var key in obj) {
            if (obj[key] === false) {
                return false;
                console.log(false);
            } else {
                return true;
                console.log(true);
            }
        }

    };

        $scope.getUserData = function() {
            var data = {id: $cookieStore.get('user').id};

            $http({
                method: 'POST',
                url: '/user/getData',
                data: data,
                headers: {"Content-Type": "application/json"}
            })
                .success(function (res) {
                    console.log(res);
                    $scope.formData.username = res.username;
                    $scope.formData.email = res.email;
                })
                .error(function (res) {
                    console.log('Errors');
                    console.log(res);
                });
        };

        $scope.logoutUser = function() {
            authFactory.logoutUser();
        };

        $scope.userExist = function() {
            authFactory.userExist();
        };


        $scope.resetPassword = function (data) {
            authFactory.resetPassword($scope.formData);
        };

        $scope.editUserData = function () {
            authFactory.editUserData($scope.formData);
        };

        $scope.nextStep = function(formData) {

        };

    // Just a quick set of JS to keep the box vertically centered
    jQuery(window).resize(function() {
        var setHeight = 0;
        if (jQuery(window).height() - jQuery('.main-column').height() > 0)
            setHeight = (jQuery(window).height() - jQuery('.main-column').height()) / 2;
        jQuery('.main-column').css({'margin-top': setHeight + 'px'});
    });
    jQuery(function() {
        jQuery(window).trigger('resize');
    });

}).controller("modalController", function($scope, $modal, $http, authFactory, registerDataService){

        $scope.formData = registerDataService.data;

}).controller("eventModalInstanceController", function($scope, registerDataService, $rootScope, voteService, $http, $modalInstance, data, num, vote, $cookies, $cookieStore, Facebook){

        if (num === null || num === undefined) {
            $scope.data = data;
            // $scope.data.status = voteService.vote;
        } else if (num) {
            $scope.data = data[num];
            // $scope.data.vote = voteService.vote;
        } else {
            $scope.data = data;
        }

        $scope.vote = {};

        $scope.user = $cookies.user;

            if ($rootScope.user) {

                $scope.simpleVoteEvent = function(event, action) {

                    var eventID = event.id;

                    console.log(event.vote);

                    var userID = $scope.user.id;
                    var upVote = event.vote.upVote;
                    var downVote = event.vote.downVote;

                    var eventVote = -1;

                    if (action == 'up') {
                        event.vote.downVote = false;
                        if (upVote == true) {
                            eventVote = 1;
                        }
                    } else { // Downvote changed
                        event.vote.upVote = false;
                        if (downVote == true) {
                            eventVote = 0;
                        }
                    }
                    $http({
                        method: "POST",
                        url: '/userEvent',
                        data: {
                            'user_id' : userID,
                            'event_id' : eventID,
                            'vote' : eventVote
                        },
                        headers : {"Content-Type": "application/json"}
                    }).success(function(data){
                        if (!data) {
                            console.log("no vote, man");
                        } else if(data) {
                            console.log(data);
                        }
                    });

                };

            } else {

                $scope.voteEvent = function(event) {
                    $modal.open({
                        templateUrl: "templates/registrationModal.html",
                        controller: 'modalInstanceController',
                    });
                }
            }


            $scope.shareReveal =  function() {
            $('.share-overlay').fadeToggle();
        };

        $scope.currentURL = "http://" + window.location.host + "/share/";

        $scope.fbShare = function(url, title) {

            Facebook.ui({
                method: 'feed',
                link: url,
                caption: title
            }, function(response){
                if (response && !response.error_code) {
                    //alert(response);
                } else {
                    //alert('Error while posting.');
                }
            });
        };

            $scope.socialShare = function(url, name, size) {
                window.open(url, name, size);
            }

            $scope.sharedPush =  function(event_id, target) {

                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                for( var i=0; i < 5; i++ )
                    text += possible.charAt(Math.floor(Math.random() * possible.length));

                var sharedEvent;

                $scope.user = $cookies.user;

                if ($scope.user) {
                    var sharedEvent = {
                        'user_id' : $scope.user.id,
                        'event_id' : event_id,
                        'share_link_key' : text,
                        'share_target_platform' : target
                    }
                }

                $http.post("/sharedEvent", sharedEvent).success(function(data){

                });
            };


           //$scope.left = (screen.width/2)-(w/2);
           // $scope.top = (screen.height/2)-(h/2);

        //THIS IS WORKING AND REFLECTING VOTE IN MODAL, NEED TO DO THE OPPOSITE
        //The 'vote' service being registered in the controller is what is being resolved by firing the modal,
        //thus giving the new template access to it

        $scope.vote = vote;

        $scope.ok = function () {
            $modalInstance.close($scope.selected.item);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    }
);
