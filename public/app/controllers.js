/**
 * Controllers for CityHapps
 */

angular.module('cityHapps.controllers', []).controller('AuthController', function($auth, $state, $http, $rootScope) {
    var vm = this;

    vm.loginError = false;
    vm.loginErrorText;

    vm.login = function() {
        var credentials = {
            email: vm.email,
            password: vm.password
        };

        $auth.login(credentials).then(function() {

            // Return an $http request for the now authenticated
            // user so that we can flatten the promise chain
            return $http.get('api/authenticate/user');

            // Handle errors
        }, function(error) {
            $rootScope.loginError = 'There was a problem with your username or password';

            // Because we returned the $http.get request in the $auth.login
            // promise, we can chain the next promise to the end here
        }).then(function(response) {
            if(response !== undefined) {
              var user = JSON.stringify(response.data.user);
              localStorage.setItem('user', user);
              $rootScope.authenticated = true;
              $rootScope.currentUser = response.data.user;
              $state.go('main.home', {});
            }
        });
    };
    vm.authenticate = function(provider) {
      $auth.authenticate(provider);
    };
}).controller('UserLogoutController', function($auth, $rootScope, $state) {
    $auth.logout().then(function() {
        localStorage.removeItem('user');
        $rootScope.authenticated = false;
        $rootScope.currentUser = null;
    }).then(function() {
        $state.go('main.home', {});
    });
}).controller('FavoriteController', function($cookies, $scope, $rootScope, $state, getFavorites) {
    $scope.happs = {};

    var userString = localStorage.getItem('user');
    var user = angular.fromJson(userString);

    getFav();

    function getFav(){
      getFavorites.get(user.id).success(function(data){
          $scope.happs = data;
      });
    }

    $scope.isFav = false;

    $scope.addToFavorites = function(id){
        getFavorites.add(user.id, id).success(function(data){
            $scope.isFav = !$scope.isFav;
            getFav();
        });
    }

    function checkFavorite(id){
        getFavorites.check(user.id, id).success(function(data){
            console.log(data)
            return data;
        });
    }

    $scope.ifFavorite = function(id){
        for (i = 0; i < $scope.happs.length; i++) {
            if(id == $scope.happs[i].id) return 'favorited';
        }
    }
}).controller('EditWelcomeMessageController', function($scope, $stateParams, welcomeMessage, $http) {
  $scope.welcomeMessage = welcomeMessage;
  $scope.save = function() {
    $http.put('/admin/welcome-message', { welcome_message: $scope.welcomeMessage }).then(function(res) {
      notify_success("Welcome Message updated successfully!");

    }, function(res) {
      notify_error("Welcome Message could not be updated");
    });
  };
}).controller('MainFilterController', function($scope, $stateParams, $timeout, HappFilterService, AgeLevel) {
    AgeLevel.query(function(payload) {
        $scope.ageLevels = payload.data.sort(function(a, b) {return (a.id > b.id) ? 1 : ((b.id > a.id) ? -1 : 0);});
        var baseObject = {ageLevel: {}};
        for (var i = 0; i < $scope.ageLevels.length; i++) {
            baseObject.ageLevel[$scope.ageLevels[i].id] = true;
        }
        $scope.filterDefaults = HappFilterService.getDefaults(baseObject);
        $scope.$watchCollection('filterDefaults', function(newFilters, oldFilters) {
            for (var key in newFilters) {
                if (newFilters[key] != oldFilters[key]) {
                    HappFilterService.updateFilter(key, newFilters[key]);
                }
            }
        });
        $scope.$watchCollection('filterDefaults.ageLevel', function(newFilters, oldFilters) {
            for (var key in newFilters) {
                if (newFilters[key] != oldFilters[key]) {
                    HappFilterService.updateAgeLevelFilter(key, newFilters[key]);
                }
            }
        });
    });
    $scope.hideFilter = true;

    jQuery('#date_calendar').datetimepicker({
        timepicker: false,
        mindate: 0,
        format: 'm/d/Y',
        onSelectDate: function(ct, $i, event) {
            $scope.filterDefaults.calendar = $i[0].value;
            $scope.$apply();
        }
    });
    jQuery('#date_calendar_footer').datetimepicker({
        timepicker: false,
        mindate: 0,
        format: 'm/d/Y',
        onSelectDate: function(ct, $i, event) {
            $scope.filterDefaults.calendar = $i[0].value;
            $scope.$apply();
        }
    });
    jQuery(window).resize(function() {
        var previousCss = $('.filter-options').attr('style');
        $('.filter-options').attr('style', 'visibility: hidden; display: block !important;');
        var actualHeight = $('.filter-options').height();
        $('.filter-options').attr('style', previousCss ? previousCss : '');
        var setHeight = (actualHeight * -1) + 'px';
        jQuery('.filter-options').css({bottom: setHeight});
    });
    jQuery(function() {
        $(window).trigger('resize');
        // Hacky way to try to correct the filter positioning
        setTimeout(function() {$(window).trigger('resize');}, 2000);
    });
}).controller('HappViewController', function($scope, $location, $window, $stateParams, $rootScope, cleanData, Happ) {
    Happ.get({ id: $stateParams.id, include: 'tags,categories,venues'}, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.happ = payload.data[0];
        $rootScope.title = payload.data[0].event_name + ' | City Happs';
        $scope.get_tickets = function(){
          $window.location.href = $scope.happ.url;
        }
    });
}).controller('HappHomePageController', function ($scope, $controller, $stateParams, welcomeMessage, cleanData, HappFilterService, Happ) {
  // NOTE: The HappHomeController is used all over so the welcomeMessage was not
  // safe to resolve.  Extending it and using this for home page makes it safe.
  angular.extend(this, $controller('HappHomeController', {$scope: $scope}));
  $scope.welcomeMessage = welcomeMessage;
}).controller('HappHomeController', function($scope, $stateParams, cleanData, HappFilterService, Happ) {
    var filter = HappFilterService.getFilters({include: 'categories,venues'});
    Happ.query(filter, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.happs = payload.data;


        $scope.displayStartTime = function(happ) {
          if (happ.hasOwnProperty('start') && happ.start != null) {
            return happ.start.hasOwnProperty('local') ? happ.start.local : happ.start;
          } else { return happ.start_time }
        }
        $scope.displayEndTime = function(happ) {
          if (happ.hasOwnProperty('end') && happ.end != null) {
            return happ.end.hasOwnProperty('local') ? happ.end.local : happ.end;
          } else { return happ.end_time }
        }

        $scope.curDate = new Date();
        $scope.toDate = function(date) { return new Date(date); }
        $scope.displayDay = function(happ) {
          if (typeof(happ) === 'undefined') return;

          // This check *should* never be false, because if it was the happ would not appear
          if (happ.hasOwnProperty('start') && happ.start != null) {
            startDate = new Date(happ.start.hasOwnProperty('local') ? happ.start.local : happ.start);
          } else {
            return '';
          }
          if (happ.hasOwnProperty('end') && happ.end != null) {
            endDate = new Date(happ.end.hasOwnProperty('local') ? happ.end.local : "");
          } else {
            endDate = startDate;
          }

          today = new Date();
          tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
          startDate.setHours(0, 0, 0, 0);
          endDate.setHours(0, 0, 0, 0);
          today.setHours(0, 0, 0, 0);
          tomorrow.setHours(0, 0, 0, 0);
          var result = '';
          var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

          // Get the start date
          if (startDate <= today) {
            result = 'TODAY';
          } else if (startDate == tomorrow) {
            result = 'TOMORROW';
          } else {
            result = month[startDate.getMonth()] + ' ' + startDate.getDate();
          }

          if ((startDate.getMonth() == endDate.getMonth()) && (startDate.getDate() == endDate.getDate())) {
            // Return if the dates match
            return result;
          } else if (startDate.getMonth() != endDate.getMonth() || result == 'TODAY' || result == 'TOMORROW') {
            // Append the whole end date if needed
            return result + ' - ' + month[endDate.getMonth()] + ' ' + endDate.getDate();
          }
          // Append the partial date if we're showing the month already
          return result + ' - ' + endDate.getDate();
        }
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
            $scope.pagination.push({'text': '< Previous', 'link': '/venues/' + (page - 1), 'class': 'prevnext'});
            $scope.pagination.push({'text': '1', 'link': '/venues'});
        } else {
            $scope.pagination.push({'text': '1', 'link': '/venues', 'class': 'active'});
        }

        if (page - 3 > 1)
            $scope.pagination.push({'text': '...', 'link': '#'});

        for (var i = page - 2; i < page + 3; i++) {
            if (i > 1 && i < last) {
                if (page == i) {
                    $scope.pagination.push({'text': String(i), 'link': '/venues/' + i, 'class': 'active'});
                } else {
                    $scope.pagination.push({'text': String(i), 'link': '/venues/' + i});
                }
            }
        };

        if (page + 3 < last)
            $scope.pagination.push({'text': '...', 'link': '#'});

        if (page != last) {
            $scope.pagination.push({'text': String(last), 'link': '/venues/' + last});
            $scope.pagination.push({'text': 'Next >', 'link': '/venues/' + (page + 1), 'class': 'prevnext'});
        } else {
            $scope.pagination.push({'text': String(last), 'link': '/venues/' + last, 'class': 'active'});
        }

    });
}).controller('VenueViewController', function($scope, $http, $stateParams, cleanData, Venue) {
    Venue.get({id: $stateParams.id, include: 'happs,tags'}, function(payload) {
        payload = cleanData.buildRelationships(payload);
        $scope.venue = payload.data[0];

        $scope.displayStartTime = function(happ){
          if(happ.hasOwnProperty('start')){
            return happ.start.hasOwnProperty('local') ? happ.start.local : happ.start;
          } else { return happ.start_time }
        }
        $scope.displayEndTime = function(happ){
          if(happ.hasOwnProperty('end')){
            return happ.end.hasOwnProperty('local') ? happ.end.local : happ.end;
          } else { return happ.end_time }
        }
        $scope.display = function(word){
          if(word == undefined) {
            return ' ';
          } else {
            return word;
          }
        }
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
                url: '/checkUser',
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
}).controller('adminEventController', function($scope, $http, $stateParams, $cookies, $cookieStore, Happ, AgeLevel, cleanData, $filter, Category) {

    $scope.toggleMinDate = function() {
      $scope.minDate = $scope.minDate ? null : new Date();
    };
    $scope.maxDate = new Date('2014-06-22');
    $scope.toggleMinDate();
    $scope.dateOptions = {
      startingDay: 1,
      showWeeks: false
    };
    // Disable weekend selection
    $scope.disabled = function(calendarDate, mode) {
      return mode === 'day' && ( calendarDate.getDay() === 0 || calendarDate.getDay() === 6 );
    };
    $scope.hourStep = 1;
    $scope.minuteStep = 15;
    $scope.timeOptions = {
      hourStep: [1, 2, 3],
      minuteStep: [1, 5, 10, 15, 25, 30]
    };
    $scope.showMeridian = true;
    $scope.timeToggleMode = function() {
      $scope.showMeridian = !$scope.showMeridian;
    };
    $scope.resetHours = function() {
      $scope.date.setHours(1);
    };

    $scope.user = $cookies.user;
    $scope.formData = {};
    AgeLevel.query(function(payload) {
        $scope.formData.ageLevels = payload.data.sort(function(a, b) {return (a.id > b.id) ? 1 : ((b.id > a.id) ? -1 : 0);});
    });
    Category.query(function(payload) {
        $scope.categories = payload.data.sort(function(a, b) {return (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0);});
    });

    // Processing the form data for adding an event
    $scope.processForm = function(formData) {
        var edit = $stateParams.id ? true : false;
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
        if (typeof formData.desc === 'undefined' || formData.desc == '') {
          error = 1;
          $scope.descError = true;
        }
        if (typeof formData.parent !== 'undefined' || formData.parent == '') {
            if (formData.parent.length > 0) {
                formData.parent_id = formData.parent[0]['id'];
            }
        }
        if (formData.hasOwnProperty('venue')) {
            formData.venue_id = formData.venue.id;
        }

        // if any error, don't post
        if (error) {
          $scope.generalError = true;
          return;
        }

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
            console.log(formData);
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
                $scope.error = data.error;
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

    $scope.reload = function() {
        document.location.reload(true);
    };

    // This does not work with a resource. I could not tell you why :(
    $scope.getVenues = function(typed) {
        return $http.get('api/venue', {params: {search: typed}}).then(function(response) {
            return response.data.data;
        });
    };

    // edit page
    if ($stateParams.id) {
        Happ.get({ id: $stateParams.id, include: 'tags,categories,venues,ageLevels'}, function(payload) {
            payload = cleanData.buildRelationships(payload);
            var singleEvent = payload.data[0];
            $scope.formData = {};
            AgeLevel.query(function(payload) {
                $scope.formData.ageLevels = payload.data.sort(function(a, b) {return (a.id > b.id) ? 1 : ((b.id > a.id) ? -1 : 0);});
                // Loop through and set all the values on age levels
                if (singleEvent.hasOwnProperty('relationships')) {
                    if (singleEvent.relationships.hasOwnProperty('ageLevels')) {
                        for (var i = 0; i < singleEvent.relationships.ageLevels.length; i++) {
                            for (var j = 0; j < $scope.formData.ageLevels.length; j++) {
                                if ($scope.formData.ageLevels[j].id == singleEvent.relationships.ageLevels[i].id)
                                    $scope.formData.ageLevels[j].value = true;
                            }
                        }
                    }
                }
            });
            $scope.formData.title = singleEvent.event_name;
            $scope.formData.status = singleEvent.status;
            $scope.formData.event_id = singleEvent.id;
            $scope.formData.parent_id = singleEvent.parent_id;
            $scope.formData.event_url = singleEvent.url;
            $scope.formData.event_image_url = singleEvent.event_image_url;
            $scope.formData.venue_name = singleEvent.venue_name;
            $scope.formData.venue_url = singleEvent.venue_url;
            $scope.formData.street_address = singleEvent.address.street_1;
            $scope.formData.city = singleEvent.address.city;
            $scope.formData.state = singleEvent.address.state;
            $scope.formData.zip_code = singleEvent.address.zip;
            $scope.formData.desc = singleEvent.description;
            $scope.formData.all_day = singleEvent.all_day_flag ? true : false;
            $scope.formData.start_time = $filter('date')(singleEvent.start.local, 'MM/dd/yyyy hh:mm a');
            $scope.formData.end_time = $filter('date')(singleEvent.end.local, 'MM/dd/yyyy hh:mm a');
            dateCheckCreate = new Date(singleEvent.created_at).getTime() / 1000;
            dateCheckUpdate = new Date(singleEvent.updated_at).getTime() / 1000;
            if (dateCheckCreate != dateCheckUpdate)
                $scope.updated_last = singleEvent.updated_at;
            $scope.formData.similar_events_model = singleEvent.similar;
            $scope.formData.similar_events_storage = (function() {
                var base = [];
                angular.forEach(singleEvent.similar, function(value) {
                    if (value.parent_id != null) {
                        base.push(value.id);
                    }
                });
                return base;
            })();

            if (singleEvent.hasOwnProperty('relationships')) {
                if (singleEvent.relationships.hasOwnProperty('categories')) {
                    $scope.formData.categories = (function() {
                        var base = [];
                        angular.forEach(singleEvent.relationships.categories, function(value) {
                            base.push(value.id);
                        });
                        return base;
                    })();
                }
                if (singleEvent.relationships.hasOwnProperty('tags')) {
                  console.log('tags:');
                  console.log(singleEvent.relationships.tags);
                    $scope.formData.tags = singleEvent.relationships.tags;
                }
                if (singleEvent.relationships.hasOwnProperty('venue')) {
                    $scope.formData.venue = singleEvent.relationships.venue[0];
                }
            }

            $scope.formData.locationType = {};
            if (singleEvent.location_type == 'Outdoor') {
                $scope.formData.locationType.outdoor = true;
            } else if (singleEvent.location_type == 'Indoor') {
                $scope.formData.locationType.indoor = true;
            }

            $scope.formData.parent = [];

            $scope.loadTags = function(query) {
                return $http.get('/tags/' + query);
            };
            $scope.loadEvents = function(query){
                return $http.get('/events/?name=' + query + '&current_id=' + $stateParams.id);
            };
            if ($scope.formData.parent_id > 0) {
                //This event has NO suggested similar, events let's fetch the parent information
                $http.get('/events/?id=' + $stateParams.id + '&current_id=' + $scope.formData.parent_id).success(function(data){$scope.formData.parent = data;});
            }
        });
    }
}).controller('adminVenueController', function($scope, $http, $stateParams, $cookies, $cookieStore, Venue, Tag) {

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
          console.log(error);
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
            if(typeof formData.venue_id === 'undefined' || formData.venue_id == '') {
                formData.venue_id = $stateParams.id;
            }
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
    $scope.formData = {};

    $scope.reload = function() {
        document.location.reload(true);
    };

    if ($stateParams.id) {
        Venue.get({ id: $stateParams.id, include: 'tags' }, function(payload) {
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
            $scope.formData.similar_venues_storage = (function() {
                var base = [];
                angular.forEach(singleVenue.similar, function(value) {
                    if (value.parent_id != null) {
                        base.push(value.id);
                    }
                });
                return base;
            })();

            $scope.loadTags = function(query) {
                return $http.get('/tags/' + query);
            };
        });
    }
}).controller('appController', function($scope, $window, $idle, $rootScope, authService, registerDataService, voteService, authFactory, $http, $modal, $location, getCategories, getUserCategories, search, $cookies, $cookieStore) {

        $scope.$on('$idleStart', function() {

            alert('idle firing now');

        });

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

                    for (var i in $scope.filterData.categories) {
                        if ($scope.filterData.categories[i] == true) {
                            queryString += 'category=[]' + i;
                        }
                    }

                    $http.get('/events?user_id=' + userID + '&start_date=' + $scope.nowDateGet + '&start_time=' + $scope.nowGet + '&page_count=1' + '&page_size=10' + queryString)
                        .success(function(data) {
                            $scope.eventData = data;
                            eventSuccess(data);
                            recommendedEventSuccess(data);
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

        if ($rootScope.user) {

            $scope.voteEvent = function(event, num, action) {

                var eventID = event[num].event_id;
                if (eventID == undefined) {
                    eventID = event[num].id; // Recommended events have event_id, regular events have id
                }

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
            email: '',
            username: '',
            password: ''
        };

        $scope.vote = registerDataService.vote;

        $scope.$on('event:loginConfirmed', function(){
            // alert("youre logged in");

            $scope.upvoted = '';

        });
}).controller('UserProfileController', function($state, $scope, $http, $rootScope, userProfile) {
    $scope.changePassword = function(){
      var user = $scope.currentUser;
      var error = 0;
      if(user.password.length < 6 || user.password_confirmation.length < 6){
        error = 1;
        notify_error('New password length must be greater than 6 characters');
      }
      if(user.password != user.password_confirmation) {
        error = 1;
        notify_error('New passwords do not match');
      }

      if(error < 1){
        userProfile.changePassword(user)
          .then(function(res){
            notify_success("Password Changed");
          }, function(e) {
            error = 1;
            notify_error('Your original password is invalid');
          });
      }
    };

    $scope.processForm = function() {
        $http.put('/user/' + $rootScope.currentUser.id, $rootScope.currentUser).success(function(data) {
            if (!data) {
                console.log('not working');
            } else if (data) {
                if (data.hasOwnProperty('id')) {
                    $state.go('main.home', {});
                } else {
                    console.log(data);
                }
            }
        });
    };

}).controller('registerFormController', function($scope, $state, $http, $modal, registerDataService, $timeout, authFactory, Facebook, Category, $controller, $cookieStore, $cookies, $auth, $rootScope) {
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
    if (!userIsConnected) {
      $scope.login();
    }
    };


    $scope.login = function() {
        Facebook.login(function(response) {
            if (response.status == 'connected') {
                $scope.logged = true;
                $scope.user.token = response;

                console.log($scope.user.token);

                Facebook.api('/me?fields=email,name', function(response) {
                    $scope.$apply(function() {

                        console.log(response);
                        $scope.user.info = response;

                        $scope.fbInfo = {
                            "categories" : {},
                            "email" : $scope.user.info.email,
                            "password" : $scope.user.token.authResponse.accessToken,
                            "name" : $scope.user.info.name,
                            "location" : "Atlanta , GA"
                        };

                        console.log($scope.fbInfo.location);

                        authFactory.userExist($scope.user.info.email , function(response){

                            console.log(response);

                            $scope.fbUser = {
                                "password_confirmation" : $scope.fbInfo.password,
                                "user_name" : $scope.fbInfo.name,
                                "name" : $scope.fbInfo.name,
                                "city" : $scope.fbInfo.location,
                                "email" : $scope.fbInfo.email,
                                "password" : $scope.fbInfo.password,
                                "categories" : $scope.fbInfo.categories
                            };

                            if (response.id) {
                                registerDataService.data = $scope.fbInfo;
                                $rootScope.authenticated = true;
                                $rootScope.currentUser = $scope.fbUser;
                                $state.go('main.home', {});
                            } else if (response.isValid) {
                                $http({
                                    method: 'POST',
                                    url: '/user',
                                    data: $scope.fbUser,
                                    headers: {"Content-Type": "application/json"}
                                }).success(function(data){
                                    authFactory.loginUser($scope.fbUser);
                                });
                                registerDataService.data = $scope.fbInfo;
                                $rootScope.authenticated = true;
                                $rootScope.currentUser = $scope.fbUser;
                                $state.go('main.home', {});


                            }

                        });
                    });
                });
            }
        }, {scope: 'email'});
    };

    $scope.loginUser = function(formData) {
      authFactory.loginUser(formData);
    };

    $scope.logout = function() {
        $auth.logout().then(function() {
            localStorage.removeItem('user');
            $rootScope.authenticated = false;
            $rootScope.currentUser = null;
        })
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
            // Per the client, we're filtering out some of the categories here.
            for (var i = payload.data.length - 1; i >= 0; i--) {
                switch (payload.data[i].slug) {
                    case 'arts':
                    case 'comedy':
                    case 'music':
                    case 'family':
                    case 'movies':
                    case 'food':
                    case 'community':
                    case 'sports':
                    case 'science-tech':
                    case 'hobbies':
                    case 'books':
                    case 'attractions':
                        break;
                    default:
                        payload.data.splice(i, 1);
                }
            }
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

                /*authFactory.loginUser({
                    'email': formData.email,
                    'password': formData.password
                });*/

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
            .success(function(res) {
                console.log(res);
                $scope.formData.username = res.user_name;
                $scope.formData.email = res.email;
            })
            .error(function(res) {
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
    jQuery(window).on('resize load', function() {
        if (jQuery(window).height() < jQuery('.main-column').height())
            jQuery('.main-column').addClass('fix-vertical');
        else
            jQuery('.main-column').removeClass('fix-vertical');
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
function notify_error(message){
    new PNotify({
                title: 'Oh No!',
                text: message,
                type: 'error'
            });
}
function notify_success(message){
    new PNotify({
                title: 'Success',
                text: message,
                type: 'success'
            });
}
