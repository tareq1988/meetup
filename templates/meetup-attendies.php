<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">

        <div class="meetup-attendies-listing">
            <?php
            $attendies = get_post_meta( get_the_id(), 'attendies', true );

            if ( $attendies ) {
                var_dump($attendies);
            } else {
                _e( 'No Attendies found!', 'meetup' );
            }
            ?>
        </div>

    </div>

</article><!-- #post-<?php the_ID(); ?> -->
