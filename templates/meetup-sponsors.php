<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">

        <div class="meetup-speaker-listing">
            <?php
            $sponsors = get_post_meta( get_the_id(), 'sponsors' );
            // var_dump($sponsors);

            if ( $sponsors ) {
                ?>

                <ul class="meetup-listing">

                <?php foreach ($sponsors as $sponsor) { ?>

                    <li>
                        <div class="meetup-thumb">
                            <?php echo get_avatar( '', 112, false, $sponsor['name'] ); ?>
                        </div>

                        <div class="meetup-caption">

                            <h2 class="topic-name"><?php echo $sponsor['name'] ?></h2>

                            <div class="speaker-bio">
                                <?php echo wp_kses_post( $sponsor['details'] ); ?>
                            </div>
                        </div>
                    </li>

                <?php } ?>

                </ul>

            <?php } else { ?>

                <?php _e( 'No sponsor found!', 'meetup' ); ?>

            <?php } ?>

        </div>

    </div>

</article><!-- #post-<?php the_ID(); ?> -->
