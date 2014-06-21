<?php
$post_id    = get_the_id();

$from       = get_post_meta( $post_id, 'from', true );
$to         = get_post_meta( $post_id, 'to', true );
$capacity   = get_post_meta( $post_id, 'capacity', true );
$address    = get_post_meta( $post_id, 'address', true );
$reg_starts = get_post_meta( $post_id, 'reg_starts', true );
$reg_ends   = get_post_meta( $post_id, 'reg_ends', true );

$same_day   = true;
$current_time = time();

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

                        <?php if ( ! is_user_logged_in() ) { ?>

                            <section class="meetup-fb-register meetup-join-form">

                                <span class="meetup-select-box">
                                    <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                                        <?php for ($i = 1; $i < 10; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </span>

                                <button class="meetup-fb-button" href="#">
                                    <i class="fa fa-facebook-square"></i>
                                    <span><?php _e( 'Connect to Register', 'meetup' ); ?></span>
                                </button>

                                <div class="meetup-reg-link">
                                    <a href="#">or, register with email</a>
                                </div>
                            </section><!-- .meetup-fb-register -->

                            <section class="meetup-site-join meetup-join-form">

                                <div class="meetup-reg-link">
                                    <a href="#">Register with Facebook</a>
                                </div>

                                <form action="" method="post" id="meetup-site-join-form">
                                    <div class="meetup-form-row meetup-col-wrap">
                                        <div class="meetup-form-half">
                                            <label for="meetup_fname">First Name</label>
                                            <input type="text" name="meetup_fname" id="meetup_fname" value="" placeholder="<?php esc_attr_e( 'First Name', 'meetup' ); ?>" required>
                                        </div>

                                        <div class="meetup-form-half">
                                            <label for="meetup_lname">Last Name</label>
                                            <input type="text" name="meetup_lname" id="meetup_lname" value="" placeholder="<?php esc_attr_e( 'Last Name', 'meetup' ); ?>" required>
                                        </div>

                                    </div>

                                    <div class="meetup-form-row meetup-email-wrap">
                                        <label for="meetup_email">Email Address</label>
                                        <input type="email" name="meetup_email" id="meetup_email" value="" placeholder="you@example.com" required>
                                    </div>

                                    <div class="meetup-form-row">

                                        <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                                            <?php for ($i = 1; $i < 10; $i++) { ?>
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

                                    <form action="" method="post" id="meetup-site-join-form">

                                        <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                                            <?php for ($i = 1; $i < 10; $i++) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>

                                        <input type="submit" name="meetup_submit" value="<?php _e( 'Book My Seat', 'meetup' ); ?>">

                                    </form>
                                </section>
                            <?php } else { ?>

                                <p>
                                    <?php printf( __( 'Congratulations! You\'ve booked %d seat(s).', 'meetup' ), $has_booked->seat ); ?>
                                </p>

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

                        <a href="#">Add to my calendar</a>
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
