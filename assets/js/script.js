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

        if ( $('#meetup_fname').val() === '' || $('#meetup_lname').val() === '' ) {
            return false;
        }

        form.find('input[type="submit"]').attr('disabled', 'disabled');

        $.post(meetup.ajaxurl, data, function(resp) {

            form.find('input[type="submit"]').removeAttr('disabled');

            if ( resp.data.type === 'error' ) {
                alert( resp.data.message );
            } else if ( resp.data.type === 'login' ) {
                alert( resp.data.message );
                window.location.href = resp.data.url;

            } else if ( resp.data.type === 'registered' ) {
                form.remove();
                alert( resp.data.message );
                window.location.reload();
            }
        });
    });

    $('form#meetup-join-form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this),
            data = form.serialize();

        form.find('input[type="submit"]').attr('disabled', 'disabled');

        $.post(meetup.ajaxurl, data, function(resp) {

            form.find('input[type="submit"]').removeAttr('disabled');

            if ( resp.success === true ) {
                form.remove();
                alert( resp.data.message );
                window.location.reload();
            } else {
                alert( resp.data.message );
            }
        });
    });

    $('a.meetup-cancel-booking').on('click', function(e) {
        e.preventDefault();

        var self = $(this),
            prompt = self.data('confirm'),
            meetup_id = self.data('meetup-id'),
            booking_id = self.data('booking-id');

        if ( confirm( prompt ) ) {
            var data = {
                meetup_id: meetup_id,
                booking_id: booking_id,
                action: 'meetup_booking_cancel',
                _wpnonce: meetup.nonce
            };

            $.post(meetup.ajaxurl, data, function(resp) {
                alert( resp.data );
                self.remove();
                window.location.reload();
            });
        }
    });

})(jQuery);