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
}]).directive('chHapp', function() {
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
        templateUrl: 'app/shared/templates/single-happ.tpl.html'
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
  ]);
