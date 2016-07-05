'use strict';
var snug = snug || {};
(function() {
    snug = {
        generateAvatarInitials: function(name) {
            var initals = '';
            name = name.split(' ');
            name.map(function(v) {
                initals+= v.slice(0,1);
            });
            return initals;
        },
        parseDate: function(date) {
            return moment(date).format('MMMM Do YYYY, h:mm a');
        },
        timePassed: function(date) {
            return moment(date).fromNow();
        },
        isInt: function(value) {
            return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value));
        }
    }
})();