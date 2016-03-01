var sharedScopeDefinition;

sharedScopeDefinition = {
  handler: '&customHandler'
};

angular.module('cityHapps.directives', []).directive('autoActive', ['$location', function ($location) {
  return {
    restrict: 'A',
    scope: false,
    link: function(scope, element) {
      function setActive() {
        var path = $location.path();
        if (path) {
          angular.forEach(element.find('li'), function(li) {
            var anchor = li.querySelector('a');
            if (anchor.href.match(path + '(?=\\?|$)')) {
              angular.element(li).addClass('active');
            } else {
              angular.element(li).removeClass('active');
            }
          });
        }
      }

      setActive();

      scope.$on('$locationChangeSuccess', setActive);
    }
  };
}]).directive('businessHoursWeekForm', function() {
  return {
    restrict: 'E',
    templateUrl: 'templates/app/shared/business-hours-week-form.tpl.html',
    require: 'ngModel',
    scope: {
      daysOfWeek: '=ngModel'
    },
    link: function(scope, el, attrs) {
      var weekDays = [
        "Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"
      ];
      if (!scope.daysOfWeek || scope.daysOfWeek.length === 0) {
        scope.daysOfWeek = weekDays.reduce(function(memo, day) {
          memo.push({
            name: day,
            open: false,
            open_time: null,
            close_time: null
          });
          return memo;
        }, []);
      }
    }
  };
}).directive('businessHours', function() {
  return {
    restrict: 'E',
    templateUrl: 'templates/app/shared/business-hours-widget.tpl.html',
    scope: {
      daysOfWeek: '=ngModel'
    },
    link: function(scope, el, attrs) {
      if (!scope.daysOfWeek) {
        scope.daysOfWeek = [];
      }

      var currentDayOfWeek = (new Date()).getDay();
      var today = scope.today = scope.daysOfWeek[currentDayOfWeek];
      var daysOfWeek = [
        "Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"
      ];
      scope.openToday = Boolean(today && today.open);
      // NOTE: Consider diffing daysOfWeek and days.name to ensure we have only valid keys.
      var usedDayNames = scope.daysOfWeek.map(function(day) { return day.name; });
      scope.validDaysOfWeek = scope.daysOfWeek.filter(function(day) {
        return usedDayNames.indexOf(day.name);
      });

      scope.hasDateInformation = scope.daysOfWeek.filter(function(day) {
        return day.open === true;
      }).length > 0 ;

      scope.showHoursForDay = function(day) {
        var output = [];
        if (day.open) {
          if (day.open_time) {
            output.push(day.open_time);
            if (day.close_time) {
              output.push(day.close_time);
            }
          } else {
            output.push('Open');
          }
        } else {
          output.push('Closed');
        }

        return output.join(' - ');
      };

      scope.toggleMoreInfo = function() {
        scope.showMoreInfo = !scope.showMoreInfo;
      };
    }
  };
}).directive('chHapp', function() {
  function link(scope, element, attributes) {
    scope.$watch(
      attributes.chHapp,
      function handleHappBindingChangeEvent(newValue) {
        scope.happ = newValue;
      }
    );
  }

  return {
    link: link,
    templateUrl: 'templates/app/shared/single-happ.tpl.html'
  };
}).directive('socialFacebook', [
  'socialLinker', function(linker) {
    return {
      restrict: 'ACEM',
      scope: sharedScopeDefinition,
      link: linker(function(scope, url) {
        var shareUrl;
        shareUrl = ["https://facebook.com/sharer.php?"];
        shareUrl.push("u=" + (encodeURIComponent(url)));
        return shareUrl.join('');
      })
    };
  }
]).directive('socialTwitter', [
  'socialLinker', function(linker) {
    return {
      restrict: 'ACEM',
      scope: angular.extend({
        status: '@status'
      }, sharedScopeDefinition),
      link: linker(function(scope, url) {
        scope.status || (scope.status = "Check this out! - " + url);
        return "https://twitter.com/intent/tweet?text=" + (encodeURIComponent(scope.status));
      })
    };
  }
]).directive('gravatarImage', function() {
  return {
    restrict:"EAC",
    link: function(scope, elm, attrs) {
      // by default the values will come in as undefined so we need to setup a
      // watch to notify us when the value changes
      scope.$watch(attrs.gravatarImage, function(value) {
        // let's do nothing if the value comes in empty, null or undefined
        if ((value !== null) && (value !== undefined) && (value !== '')) {
          // convert the value to lower case and then to a md5 hash
          var hash = md5(value.toLowerCase());
          // construct the tag to insert into the element
          var tag = '<img alt="" src="http://www.gravatar.com/avatar/' + hash + '?s=150&r=pg&d=identicon" />';
          // insert the tag into the element
          elm.append(tag);
        }
      });
    }};
}).directive('wysiwygInput', function() {
  return {
    templateUrl: '/templates/app/shared/wysiwyg-input.html',
    restrict: 'E',
    scope: {
      field: "=ngModel",
      name: "@name",
      label: "@label",
      help: "@help"
    },
    link: function (scope, el, attrs) {
      if (! scope.field) {
        scope.field = '';
      }
      scope.cols = attrs.cols || '10';
      scope.rows = attrs.rows || '3';
    }
  };
}).directive('imageUploader', function() {
  return {
    restrict: 'E',
    templateUrl: '/templates/app/shared/image-uploader.html',
    scope: {
      label: '@',
      onCrop: '&',
      toggled: '=toggle',
      minWidth: '=',
      minHeight: '=',
      cropWidth: '=',
      cropHeight: '=',
      keepAspect: '='
    },
    controller: function($scope) {
      $scope.minWidth = $scope.minWidth || 150;
      $scope.minHeight = $scope.minHeight || 150;
      $scope.cropWidth = $scope.cropWidth || 150;
      $scope.cropHeight = $scope.cropHeight || 150;
      $scope.keepAspect = $scope.keepAspect || true;
      $scope.toggled = $scope.toggled || false;
      $scope.cropper = { source: null, cropped: null };
      $scope.bounds = { left: 0, right: 0, top: 0, bottom: 0 };
      console.log($scope);
      $scope.toggle = function() {
        $scope.toggled = !$scope.toggled;
      };
      $scope.cancel = function() {
        $scope.cropper = { source: null, cropped: null };
        $scope.toggle();
      };

      if ($scope.onCrop) {
        $scope.$watch('cropper.cropped', function(val) {
          $scope.onCrop({datauri: val});
        });
      }
    },
    link: function(scope, el, attrs) {
      //scope.cropWidth = attrs.cropWidth || 150;
      //scope.cropHeight = attrs.cropHeight || 150;
      //scope.keepAspect = attrs.keepAspect || true;
      //scope.touchRadius = attrs.touchRadius || 30;
      //scope.cropper = {
      //  sourceImage: null,
      //  croppedImage: null
      //};
      //scope.bounds = scope.bounds || {
      //  left: 0,
      //  right: 0,
      //  top: 0,
      //  bottom:0
      //};
    }
  };
});
