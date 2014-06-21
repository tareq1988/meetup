/*jshint devel:true */
/*global meetup */

;(function($) {

    $('.meetup-reg-link').on('click', 'a', function(e) {
        e.preventDefault();

        var self = $(this),
            section = self.closest('section');

        section.slideUp('fast');
        section.siblings('section').slideDown('fast');
    });

    $('form#meetup-site-join-form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this),
            data = form.serialize();

        form.find('input[type="submit"]').attr('disabled', 'disabled');

        $.post(meetup.ajaxurl, data, function(resp) {

            form.find('input[type="submit"]').removeAttr('disabled');

            if ( resp.data.type === 'error' ) {
                alert( resp.data.message );
            } else if ( resp.data.type === 'login' ) {
                alert( resp.data.message );
                window.location.href = resp.data.url;

            } else if ( resp.data.type === 'registered' ) {
                alert( resp.data.message );
                window.location.reload();
            }
        });
    });

})(jQuery);