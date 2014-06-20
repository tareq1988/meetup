/*jshint devel:true */

;(function($) {

    $('.meetup-reg-link').on('click', 'a', function(e) {
        e.preventDefault();

        var self = $(this),
            section = self.closest('section');

        section.slideUp('fast');
        section.siblings('section').slideDown('fast');
    });

})(jQuery);