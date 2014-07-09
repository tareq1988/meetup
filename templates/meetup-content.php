<?php
$post_id    = get_the_id();

$from       = get_post_meta( $post_id, 'from', true );
$to         = get_post_meta( $post_id, 'to', true );
$capacity   = get_post_meta( $post_id, 'capacity', true );
$address    = get_post_meta( $post_id, 'address', true );
$location   = get_post_meta( $post_id, 'location', true );
$reg_starts = get_post_meta( $post_id, 'reg_starts', true );
$reg_ends   = get_post_meta( $post_id, 'reg_ends', true );
$book_limit = get_post_meta( $post_id, 'book_limit', true );

$current_time = time();
$same_day   = true;

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

                <?php if ( meetup_is_registration_started( $post_id ) ) { ?>

                    <?php if ( ! meetup_is_registration_finished( $post_id ) ) { ?>
                        <h3><?php _e( 'Join the Meetup', 'meetup' ); ?></h3>

                        <?php if ( meetup_num_available_seat( $post_id ) ) { ?>
                            <?php if ( ! is_user_logged_in() ) { ?>

                                <section class="meetup-fb-register meetup-join-form">

                                    <button class="meetup-fb-button" data-meetup-id="<?php echo $post_id; ?>">
                                        <i class="fa fa-facebook-square"></i>
                                        <span><?php _e( 'Connect to Register', 'meetup' ); ?></span>
                                    </button>

                                    <div class="meetup-reg-link">
                                        <a href="#"><?php _e( 'or, register with email', 'meetup' ); ?></a>
                                    </div>

                                    <?php include MEETUP_PATH . '/includes/views/fb-scripts.php'; ?>

                                </section><!-- .meetup-fb-register -->

                                <section class="meetup-site-join meetup-join-form">

                                    <div class="meetup-reg-link">
                                        <a href="#"><?php _e( 'Register with Facebook', 'meetup' ); ?></a>
                                    </div>

                                    <?php meetup_signup_fields( $post_id ); ?>

                                </section><!-- .meetup-site-join -->

                            <?php } else { ?>

                                <?php $has_booked = meetup_has_user_booked( get_current_user_id(), $post_id ); ?>

                                <?php if ( ! $has_booked ) { ?>

                                    <section class="meetup-loggedin-join meetup-join-form">

                                        <?php meetup_signup_fields( $post_id ); ?>

                                    </section>

                                <?php } else { ?>

                                    <div class="meetup-joined">
                                        <p>
                                            <?php printf( __( 'Congratulations! You\'ve booked %d seat(s).', 'meetup' ), $has_booked->seat ); ?>
                                        </p>

                                        <p>
                                            <a href="#" class="meetup-cancel-booking" data-meetup-id="<?php echo $post_id; ?>" data-booking-id="<?php echo $has_booked->id; ?>" data-confirm="<?php esc_attr_e( 'Are you sure to cancel the booking?', 'meetup' ); ?>">
                                                <?php _e( 'Cancel your booking?', 'meetup' ); ?>
                                            </a>
                                        </p>
                                    </div>

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
                    </div>
                </li>

                <li>
                    <div class="meetup-icon">
                        <i class="fa fa-calendar"></i>
                    </div>

                    <div class="meetup-details">
                        <a class="meetup-add-to-calendar" href="#"><?php _e( 'Add to my calendar', 'meetup' ); ?></a>

                        <div class="meetup-add-calendar-wrap">
                            <?php
                            $gmt_offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

                            $google_calendar = add_query_arg( array(
                                'text'     => urlencode( get_the_title() ),
                                'dates'    => date( "Ymd\THis\Z", ( $from + $gmt_offset ) ) . '/' . date( "Ymd\THis\Z", ($to + $gmt_offset) ),
                                'details'  => urlencode( sprintf( __( 'For details, link here: %s', 'meetup' ), get_permalink() ) ),
                                'location' => urlencode( $address )
                            ), 'https://www.google.com/calendar/render?action=TEMPLATE' );

                            $ical_ics = add_query_arg( array(
                                'meetup_action' => 'ical_gen',
                                '_wpnonce'      => wp_create_nonce( 'meetup-ical-gen' )
                            ), get_permalink() );
                            ?>
                            <ul class="meetup-calendar-provider">
                                <li><a href="<?php echo $google_calendar; ?>" target="_blank" rel="nofollow"><?php _e( 'Google Calendar', 'meetup' ); ?></a></li>
                                <li><a href="<?php echo $ical_ics; ?>"><?php _e( 'Apple iCal', 'meetup' ); ?></a></li>
                                <li><a href="<?php echo $ical_ics; ?>"><?php _e( 'Microsoft Outlook', 'meetup' ); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </li>

                <?php if ( $address ) { ?>
                    <li>
                        <div class="meetup-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>

                        <div class="meetup-details">
                            <address>
                                <?php echo nl2br( $address ); ?>
                            </address>

                            <?php if ( $location ) { ?>
                                <div id="meetup-map"></div>

                                <script type="text/javascript">
                                    jQuery(function($){
                                        var curpoint = new google.maps.LatLng(<?php echo $location['lat']; ?>, <?php echo $location['long']; ?>);

                                        var gmap = new google.maps.Map( $('#meetup-map')[0], {
                                            center: curpoint,
                                            zoom: 17,
                                            mapTypeId: window.google.maps.MapTypeId.ROADMAP
                                        });

                                        var marker = new window.google.maps.Marker({
                                            position: curpoint,
                                            map: gmap,
                                            draggable: true
                                        });
                                    });
                                </script>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div><!-- .meetup-col-right -->
    </div>

</article><!-- #post-<?php the_ID(); ?> -->
