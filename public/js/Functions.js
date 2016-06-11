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
        }
    }
})();