<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">

        <div class="meetup-sponsor-listing">
            <?php
            $sponsors = get_post_meta( get_the_id(), 'sponsors' );
            $details = get_post_meta( get_the_id(), 'sponsor_details', true );

            if ( !empty( $details ) ) {
                ?>
                <div class="meetup-sponsor-text">
                    <p><?php echo wp_kses_post( $details ); ?></p>
                </div>
                <?php
            }

            if ( $sponsors ) {
                ?>

                <ul class="meetup-listing">

                <?php foreach ($sponsors as $sponsor) { ?>

                    <li>
                        <?php if ( $sponsor['logo'] ) { ?>
                            <div class="meetup-thumb">
                                <?php echo wp_get_attachment_image( $sponsor['logo'], 'thumbnail' ) ?>
                            </div>
                        <?php } ?>

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
