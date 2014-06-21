<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">

        <div class="meetup-speaker-listing">
            <?php
            $speakers = get_post_meta( get_the_id(), 'speakers' );

            if ( $speakers ) {
                ?>

                <ul class="meetup-listing">

                <?php foreach ($speakers as $speaker) { ?>

                    <li>
                        <div class="meetup-thumb">
                            <?php echo get_avatar( $speaker['email'], 112, false, $speaker['name'] ); ?>
                        </div>

                        <div class="meetup-caption">

                            <h2 class="topic-name"><?php echo $speaker['topic'] ?></h2>

                            <h3 class="speaker-name">
                                <small><?php _e( 'by', 'meetup' ); ?></small>

                                <?php if ( ! empty( $speaker['url'] ) ) { ?>
                                    <a href="<?php echo esc_url( $speaker['url'] ); ?>" target="_blank"><?php echo $speaker['name'] ?></a>
                                <?php } else { ?>
                                    <?php echo $speaker['name'] ?>
                                <?php } ?>
                            </h3>

                            <?php if ( isset( $speaker['slide_url'] ) && !empty( $speaker['slide_url'] ) ) { ?>
                                <strong><?php _e( 'Slide:', 'meetup' ); ?></strong> <a target="_blank" href="<?php echo $speaker['slide_url']; ?>"><?php echo $speaker['slide_url']; ?></a>
                            <?php } ?>

                            <div class="speaker-bio">
                                <?php echo wp_kses_post( $speaker['bio'] ); ?>
                            </div>
                        </div>
                    </li>

                <?php } ?>

                </ul>

            <?php } else { ?>

                <?php _e( 'No speaker found!', 'meetup' ); ?>

            <?php } ?>

        </div>

    </div>

</article><!-- #post-<?php the_ID(); ?> -->
