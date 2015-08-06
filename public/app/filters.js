angular.module('cityHapps.filters', []).filter('dateToISO', function() {
    return function(input) {
        input = moment(input).toISOString();
        return input;
    };
});
