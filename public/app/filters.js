angular.module('cityHapps.filters', []).filter('dateToISO', function() {
    return function(input) {
        input = moment(input).toISOString();
        return input;
    };
}).filter('dateSuffix', function($filter) {
    var suffixes = ['th', 'st', 'nd', 'rd'];
    return function(input) {
        var dtfilter = $filter('date')(input, 'MMMM dd');
        var day = parseInt(dtfilter.slice(-2));
        var relevantDigits = (day < 30) ? day % 20 : day % 30;
        var suffix = (relevantDigits <= 3) ? suffixes[relevantDigits] : suffixes[0];
        return dtfilter + suffix;
    };
}).filter('fixLinks', function(payload) {
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
    return payload;
});
