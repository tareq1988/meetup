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
                    <h1 class="meetup-entry-title"><?php the_title(); ?></h1>
                </div>
            </div><!-- .meetup-date-title-wrap -->
        </div><!-- .meetup-event-header -->
    </div><!-- .meetup-cover-wrap -->

    <div class="meetup-nav-wrap">
        <div class="meetup-nav">
            <?php echo meetup_navigation(); ?>
        </div><!-- .meetup-nav -->

        <div class="meetup-actions-wrap">
            <!--
            <ul class="meetup-actions">
                <li><a class="meetup-button-going" href="#"><i class="fa fa-plus-square-o"></i> Join</a></li>
                <li><a class="meetup-button-maybe" href="#">Maybe</a></li>
                <li><a class="meetup-button-notgoing" href="#">Decline</a></li>
                <li><a class="meetup-button-actions" href="#">...</a></li>
            </ul>
             -->
        </div><!-- .meetup-actions-wrap -->
    </div><!-- .meetup-nav-wrap -->

</header><!-- .meetup-entry-header -->