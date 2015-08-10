/**
 * Controllers for CityHapps
 */

angular.module('cityHapps.controllers', []).controller('HappViewController', function($scope, $stateParams, Happ) {
    Happ.get({ id: $stateParams.id, include: 'tags,categories'}, function(payload) {
        console.log(payload);
      $scope.happ = payload.data[0];
    });
}).controller('HappHomeController', function($scope, $stateParams, Happ) {
    $scope.filters = {
        today: true,
        tomorrow: false,
        weekend: false,
        calendar: false,
        morning: true,
        afternoon: true,
        evening: true,
        night: true,
        zip: ''
    };

    $scope.getHapps = function() {
        var filter = {include: 'categories'}; //Ultimately will include venues when they start working
        // Clean up the filters
        if ($scope.filters.zip.length == 5)
            filter.zip = $scope.filters.zip;

        var date = ''
        if ($scope.filters.today)
            date += String($scope.todayDate());
        if ($scope.filters.tomorrow) {
            if (date != '')
                date += ',';
            date += String($scope.tomorrowDate());
        }
        if ($scope.filters.weekend) {
            if (date != '')
                date += ',';
            date += String($scope.weekendDate());
        }
        if (date != '')
            filter.date = date;


        var time = '';
        if ($scope.filters.morning)
            time += 'morning';
        if ($scope.filters.afternoon) {
            if (time != '')
                time += ',';
            time += 'afternoon';
        }
        if ($scope.filters.evening) {
            if (time != '')
                time += ',';
            time += 'evening';
        }
        if ($scope.filters.night) {
            if (time != '')
                time += ',';
            time += 'night';
        }
        if (time != '' && (time.match(/,/g) || []).length != 3)
            filter.timeofday = time;
        console.log($scope.filters);
        console.log(filter);
        Happ.query(filter, function(payload) {
            console.log(payload);
          $scope.happs = payload.data;
        });
    };

    $scope.todayDate = function() {
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
    $scope.tomorrowDate = function() {
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
    $scope.weekendDate = function() {
        var today = new Date();
        switch (today.getDay()) {
            case 0:
                return $scope.todayDate();
            case 6:
                return $scope.todayDate() + ',' + $scope.tomorrowDate();
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

    $scope.getHapps();
}).controller('happController', function($scope, $http, $routeParams, $stateParams, $cookies, $cookieStore, Happ) {

        $scope.user = $cookies.user ? JSON.parse($cookies.user) : $cookies.user;
        $scope.likeStatus;

        if (typeof $stateParams.id !== 'undefined') {
            Happ.get({ id: $stateParams.id }, function(payload) {
              $scope.data = payload.data[0];
            });
        } else {
            // Get filter data here
            var filters = '';
            Happ.query(function(payload) {
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
}).controller('CategoryHappController', function($scope, $stateParams, Category, Happ) {
    // First let's get the category id
    Category.get({ slug: $stateParams.slug }, function(payload) {
        $scope.category = payload.data[0];
        // Then let's get our data
        Happ.query({category: $scope.category.id}, function(happPayload) {
            $scope.happs = happPayload.data;
            console.log(payload);
            console.log(happPayload);

        });
    });
}).controller('CategorySidebarController', function($scope, Category) {
    Category.query(function(payload) {
      $scope.categories = payload.data;
    });
}).controller('VenueListController', function($scope, $stateParams, Venue) {
    var size = 48;
    var page = (typeof $stateParams.page != 'undefined') ? parseInt($stateParams.page) : 1;
    Venue.query({'page[size]': size, 'page[number]': page}, function(payload) {
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
}).controller('VenueViewController', function($scope, $stateParams, Venue) {
    Venue.get({id: $stateParams.id, include: 'happs'}, function(payload) {
        $scope.venue = payload.data[0];
    });
}).controller('venueController', function($scope, $http, $routeParams, $stateParams, $cookies, $cookieStore) {

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
}).controller('adminEventController', function($scope, $http, $routeParams, $stateParams, $cookies, $cookieStore) {

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
    if ($routeParams.id) {
        $http.get('/events?id=' + $routeParams.id)
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
                        return $http.get('/events/?name=' + query + '&current_id=' + $routeParams.id);
                    };
                    if ($scope.formData.parent_id > 0) {
                        //This event has NO suggested similar, events let's fetch the parent information
                        $http.get('/events/?id=' + $routeParams.id + '&current_id=' + $scope.formData.parent_id ).success(function(data){$scope.formData.parent = data;});
                    }
                }
        })
    }

}).controller('adminVenueController', function($scope, $http, $routeParams, $stateParams, $cookies, $cookieStore) {

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
        if (formData.parent.length > 0) {
            formData.parent_id = formData.parent[0]['id'];
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
                $scope.error = data.error.message;
            });
        }
    };
    // Retrieving all of the data for the listing page
    var pageNumber = ($routeParams.page) ? '&page=' + $routeParams.page : '';
    console.log($routeParams.page);
    $http.get('/venues?page_size=500' + pageNumber)
        .success(function(data) {
            $scope.venuesCount = data.meta.count;
            $scope.allVenues = data.venues;
    });

    // edit page
    if ($routeParams.id) {
        $http.get('/venues?id=' + $routeParams.id)
            .success(function(data) {
                if (data.venues.length > 0)
                {
                    var singleVenue = data.venues[0];
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

                    $scope.formData.similar_venues_storage = (function () {
                        var base = [];
                        angular.forEach(singleVenue.similar, function (value) {
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
                }


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
).controller('registerFormController', function($scope, $http, $modal, registerDataService, $timeout, authFactory, Facebook) {
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


      $scope.logout = function() {

      };

      $scope.remove = function() {
        Facebook.api(
            "/me/permissions/user_profile",
            "DELETE",
            function (response) {
              if (response && !response.error) {
                alert('access revoked');
              }
            }
        );
      };

        $scope.registerOpen = function(size) {

            var modalInstance = $modal.open({
                templateUrl: "templates/registrationModal.html",
                controller: 'modalInstanceController',
                size: size
            });
        };

        $scope.resetPasswordOpen = function(size) {

            var modalInstance = $modal.open({
                templateUrl: "templates/resetPasswordModal.html",
                controller: 'modalInstanceController',
                size: size
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
        "email" :  $scope.formData.email,
        "password" : $scope.formData.password,
        "username" : $scope.formData.username
    };

    $scope.processForm = function(formData) {
        $http({
            method: 'POST',
            url: '/user',
            data: formData,
            headers: {"Content-Type": "application/json"}
        }).success(function(data){
            if(!data) {
                console.log('not working');
            } else if (data) {
                var fbInfo = {
                    "email" : data.email,
                    "password" : data.fb_token
                };
                $scope.id = data.id;

                authFactory.loginUser({"email":formData.email, "password":formData.password});
            }
            console.log(data);
        });
    };


    }
).controller("modalController", function($scope, $modal, $http, authFactory, registerDataService) {

    $scope.formData = registerDataService.data;

    $scope.registerOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/registrationModal.html",
            controller: 'modalInstanceController',
            size: size
        });
    };

    $scope.loginOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/loginModal.html",
            controller: 'modalInstanceController',
            size: size
        });
    };

    $scope.editOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/editModal.html",
            controller: 'modalInstanceController',
            size: size
        });
    };

    $scope.getUserData = function() {
        $http
            .post('/user/getData')
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

    $scope.resetPasswordOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/resetPasswordModal.html",
            controller: 'modalInstanceController',
            size: size
        });
    };

    $scope.categoriesOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/categoriesModal.html",
            controller: 'modalInstanceController',
            size: size
        });
    };

    $scope.confirmationOpen = function(size) {

        var modalInstance = $modal.open({
            templateUrl: "templates/confirmationModal.html",
            controller: 'modalInstanceController',
            size: size
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
