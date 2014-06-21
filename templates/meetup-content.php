<?php
$post_id    = get_the_id();

$from       = get_post_meta( $post_id, 'from', true );
$to         = get_post_meta( $post_id, 'to', true );
$capacity   = get_post_meta( $post_id, 'capacity', true );
$address    = get_post_meta( $post_id, 'address', true );
$reg_starts = get_post_meta( $post_id, 'reg_starts', true );
$reg_ends   = get_post_meta( $post_id, 'reg_ends', true );
$book_limit = get_post_meta( $post_id, 'book_limit', true );

$current_time = time();
$same_day   = true;
$registration_started = ( $current_time > $reg_starts ) ? true : false;

if ( date( 'dmY', $from ) !== date( 'dmY', $to ) ) {
    $same_day = false;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">
        <div class="meetup-col-left">

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <div class="meetup-join-event">

                <?php if ( $current_time > $reg_starts ) { ?>

                    <?php if ( $current_time < $reg_ends ) { ?>
                        <h3><?php _e( 'Join the Meetup', 'meetup' ); ?></h3>

                        <?php if ( meetup_num_available_seat( $post_id ) ) { ?>
                            <?php if ( ! is_user_logged_in() ) { ?>

                                <section class="meetup-fb-register meetup-join-form">

                                    <span class="meetup-select-box">
                                        <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                                            <?php for ($i = 1; $i <= $book_limit; $i++) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>

                                    <button class="meetup-fb-button" data-meetup-id="<?php echo $post_id; ?>">
                                        <i class="fa fa-facebook-square"></i>
                                        <span><?php _e( 'Connect to Register', 'meetup' ); ?></span>
                                    </button>

                                    <div class="meetup-reg-link">
                                        <a href="#"><?php _e( 'or, register with email', 'meetup' ); ?></a>
                                    </div>

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

        FB.login(function(response){
            if ( response.status === 'connected' ) {
                FB.api('/me', function(response) {

                    response.action = 'meetup_fb_register';
                    response.seat = jQuery('#meetup-fb-join-set').val();
                    response.meetup_id = self.data('meetup-id');

                    jQuery.post(meetup.ajaxurl, response, function(resp) {
                        alert( resp.data.message );
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

                                </section><!-- .meetup-fb-register -->

                                <section class="meetup-site-join meetup-join-form">

                                    <div class="meetup-reg-link">
                                        <a href="#"><?php _e( 'Register with Facebook', 'meetup' ); ?></a>
                                    </div>

                                    <form action="" method="post" id="meetup-site-join-form">
                                        <div class="meetup-form-row meetup-col-wrap">
                                            <div class="meetup-form-half">
                                                <label for="meetup_fname"><?php _e( 'First Name', 'meetup' ); ?></label>
                                                <input type="text" name="meetup_fname" id="meetup_fname" value="" placeholder="<?php esc_attr_e( 'First Name', 'meetup' ); ?>" required>
                                            </div>

                                            <div class="meetup-form-half">
                                                <label for="meetup_lname"><?php _e( 'Last Name', 'meetup' ); ?></label>
                                                <input type="text" name="meetup_lname" id="meetup_lname" value="" placeholder="<?php esc_attr_e( 'Last Name', 'meetup' ); ?>" required>
                                            </div>

                                        </div>

                                        <div class="meetup-form-row meetup-email-wrap">
                                            <label for="meetup_email"><?php _e( 'Email Address', 'meetup' ); ?></label>
                                            <input type="email" name="meetup_email" id="meetup_email" value="" placeholder="you@example.com" required>
                                        </div>

                                        <div class="meetup-form-row">

                                            <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                                                <?php for ($i = 1; $i <= $book_limit; $i++) { ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>

                                            <?php wp_nonce_field( 'meetup-site-join-form' ); ?>
                                            <input type="hidden" name="meetup_id" value="<?php echo $post_id; ?>">
                                            <input type="hidden" name="action" value="meetup_site_new_join">

                                            <input type="submit" name="meetup_submit" value="<?php _e( 'Book My Seat', 'meetup' ); ?>">
                                        </div>

                                    </form>
                                </section><!-- .meetup-site-join -->

                            <?php } else { ?>

                                <?php $has_booked = meetup_has_user_booked( get_current_user_id(), $post_id ); ?>

                                <?php if ( ! $has_booked ) { ?>
                                    <section class="meetup-loggedin-join meetup-join-form">

                                        <form action="" method="post" id="meetup-join-form">

                                            <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                                                <?php for ($i = 1; $i <= $book_limit; $i++) { ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>

                                            <?php wp_nonce_field( 'meetup-site-join-form' ); ?>
                                            <input type="hidden" name="meetup_id" value="<?php echo $post_id; ?>">
                                            <input type="hidden" name="action" value="meetup_user_join">

                                            <input type="submit" name="meetup_submit" value="<?php _e( 'Book My Seat', 'meetup' ); ?>">
                                        </form>
                                    </section>
                                <?php } else { ?>

                                    <p>
                                        <?php printf( __( 'Congratulations! You\'ve booked %d seat(s).', 'meetup' ), $has_booked->seat ); ?>
                                    </p>

                                    <p>
                                        <a href="#" class="meetup-cancel-booking" data-meetup-id="<?php echo $post_id; ?>" data-booking-id="<?php echo $has_booked->id; ?>" data-confirm="<?php esc_attr_e( 'Are you sure to cancel the booking?', 'meetup' ); ?>">
                                            <?php _e( 'Cancel your booking?', 'meetup' ); ?>
                                        </a>
                                    </p>

                                <?php } ?>

                            <?php } ?>

                        <?php } else { ?>

                            <div class="meetup-message">
                                <h4><?php _e( 'We are filled up! No more seat available!', 'meetup' ); ?></h4>
                            </div>

                            <?php if ( is_user_logged_in() ) { ?>
                                <?php $has_booked = meetup_has_user_booked( get_current_user_id(), $post_id ); ?>

                                <?php if ( $has_booked ) { ?>

                                    <p>
                                        <?php printf( __( 'Congratulations! You\'ve booked %d seat(s).', 'meetup' ), $has_booked->seat ); ?>
                                    </p>

                                    <p>
                                        <a href="#" class="meetup-cancel-booking" data-meetup-id="<?php echo $post_id; ?>" data-booking-id="<?php echo $has_booked->id; ?>" data-confirm="<?php esc_attr_e( 'Are you sure to cancel the booking?', 'meetup' ); ?>">
                                            <?php _e( 'Cancel your booking?', 'meetup' ); ?>
                                        </a>
                                    </p>

                                <?php } ?>

                            <?php } ?>

                        <?php } ?>

                        <div class="meetup-reg-ends">
                            <strong><?php _e( 'Registration Ends:', 'meetup' ); ?></strong> <?php echo date_i18n( 'F j, Y g:ia', $reg_ends ); ?>
                        </div>

                    <?php } else { ?>

                        <h3><?php _e( 'Registration is closed!', 'meetup' ); ?></h3>

                    <?php } ?>

                <?php } else { ?>

                        <h3><?php _e( 'Registration will open at:', 'meetup' ); ?> <small><?php echo date_i18n( 'F j, Y g:ia', $reg_starts ); ?></small></h3>

                    <?php } ?>

            </div><!-- .meetup-join-event -->
        </div><!-- .meetup-col-left -->

        <div class="meetup-col-right">
            <ul>
                <li>
                    <div class="meetup-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>

                    <div class="meetup-details">

                        <?php $to_format = $same_day ? 'g:ia' : 'F j, Y g:ia'; ?>
                        <time><?php echo date_i18n( 'F j, Y g:ia', $from ); ?> - <?php echo date_i18n( $to_format, $to ); ?></time><br>

                        <a href="#"><?php _e( 'Add to my calendar', 'meetup' ); ?></a>
                    </div>
                </li>

                <li class="clearfix">
                    <div class="meetup-icon">
                        <i class="fa fa-map-marker"></i>
                    </div>

                    <div class="meetup-details">
                        <address>
                            <?php echo nl2br( $address ); ?>
                        </address>

                        <div class="meetup-map"></div>
                    </div>
                </li>
            </ul>
        </div><!-- .meetup-col-right -->
    </div>

</article><!-- #post-<?php the_ID(); ?> -->
