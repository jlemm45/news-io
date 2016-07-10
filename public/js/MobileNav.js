(function($) {
    $('#mobile-menu')
        .sidebar({
            context: $('#mobile-segment')
        })
        .sidebar('attach events', '#toggle-mobile');
})($);