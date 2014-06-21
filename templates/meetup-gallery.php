<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">

        <div class="meetup-gallery-listing">
            <?php
            $gallery = get_post_meta( get_the_id(), 'gallery' );

            if ( $gallery ) {
                ?>
                <ul class="meetup-image-gallery">

                <?php foreach ($gallery as $attachment_id) { ?>
                    <li>
                        <a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" rel="meetup-image">
                            <?php echo wp_get_attachment_image( $attachment_id, 'thumbnail' ) ?>
                        </a>
                    </li>
                <?php } ?>

                </ul>

                <?php
            } else {
                _e( 'No image has been found!', 'meetup' );
            }
            ?>
        </div>

    </div>

</article><!-- #post-<?php the_ID(); ?> -->
