<?php
$fb_option = get_option( 'meetup_facebook' );
$fb_api_key = isset( $fb_option['app_id'] ) ? $fb_option['app_id'] : '';
?>

<script type="text/javascript">
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '<?php echo $fb_api_key; ?>',
            cookie     : true,  // enable cookies to allow the server to access
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.0' // use version 2.0
        });
    };

    jQuery('.meetup-fb-button').on('click', function(e) {
        e.preventDefault();

        var self = jQuery(this);
        var div = jQuery('.meetup-join-event');

        div.block({ message: null, overlayCSS: { background: '#fff url(' + meetup.ajax_loader + ') no-repeat center', opacity: 0.6 } });

        FB.login( function( response ) {

            if ( response.status === 'connected' ) {
                FB.api('/me', function(response) {
                    console.log(response);

                    response.action = 'meetup_fb_register';

                    jQuery.post(meetup.ajaxurl, response, function(resp) {
                        window.location.reload();
                    });
                });
            }

        }, {scope: 'email'});
    });

    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>