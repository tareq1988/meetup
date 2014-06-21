<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">

        <div class="meetup-attendies-listing">
            <?php
            $attendies = meetup_get_attendies( get_the_id() );

            if ( $attendies ) {
                ?>
                <ul class="meetup-attendee-list">
                    <?php foreach ($attendies as $user) { ?>
                        <li>
                            <div class="meetup-thumb">
                                <?php echo get_avatar( $user->user_email, 64, false, $user->display_name ); ?>
                            </div>

                            <div class="meetup-caption">
                                <?php echo $user->display_name; ?>
                            </div>

                        </li>
                    <?php } ?>
                </ul>

                <?php
            } else {
                _e( 'No Attendies found!', 'meetup' );
            }
            ?>
        </div>

    </div>

</article><!-- #post-<?php the_ID(); ?> -->
