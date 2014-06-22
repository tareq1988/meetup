<header class="meetup-entry-header">
    <div class="meetup-cover-wrap">
        <div class="meetup-cover">
            <?php if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'full' );
            } ?>
        </div>

        <div class="meetup-event-header">
            <div class="meetup-date-title-wrap">
                <div class="meetup-date-wrap">
                    <?php
                    $post_id = get_the_id();
                    $from    = get_post_meta( $post_id, 'from', true );
                    ?>
                    <div class="meetup-month"><?php echo date_i18n( 'M', $from ); ?></div>
                    <div class="meetup-date"><?php echo date_i18n( 'j', $from ); ?></div>
                </div>

                <div class="meetup-title">
                    <h1 class="meetup-entry-title">
                    <?php if ( is_singular( 'meetup' ) ) { ?>
                        <?php the_title(); ?>
                    <?php } else { ?>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php } ?>
                    </h1>
                </div>
            </div><!-- .meetup-date-title-wrap -->
        </div><!-- .meetup-event-header -->
    </div><!-- .meetup-cover-wrap -->

    <div class="meetup-nav-wrap">
        <div class="meetup-nav">
            <?php echo meetup_navigation(); ?>
        </div><!-- .meetup-nav -->

        <?php
        $capacity       = meetup_get_capacity( $post_id );
        $booked         = meetup_num_booked_seat( $post_id );
        $seat_available = meetup_num_available_seat( $post_id );
        ?>

        <div class="meetup-actions-wrap">
            <ul class="meetup-actions">
                <li>
                    <div class="meetup-count"><?php echo $capacity; ?></div>
                    <div class="meetup-span-text"><?php _e( 'Capacity', 'meetup' ); ?></div>
                </li>
                <li>
                    <div class="meetup-count"><?php echo $booked; ?></div>
                    <div class="meetup-span-text"><?php _e( 'Booked', 'meetup' ); ?></div>
                </li>
                <li>
                    <div class="meetup-count"><?php echo $seat_available; ?></div>
                    <div class="meetup-span-text"><?php _e( 'Available', 'meetup' ); ?></div>
                </li>
            </ul>
        </div><!-- .meetup-actions-wrap -->
    </div><!-- .meetup-nav-wrap -->

</header><!-- .meetup-entry-header -->