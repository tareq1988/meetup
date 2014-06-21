<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">

        <div class="meetup-schedule-listing">
            <?php
            $schedules = get_post_meta( get_the_id(), 'schedule' );
            $days = array();

            if ( $schedules ) {
                foreach ($schedules as $schedule) {
                    $date = date( 'Ymd', $schedule['time'] );

                    $days[$date][] = $schedule;
                }

                foreach ($days as $date => $day_events) {
                    $this_day = date_i18n( 'F j, Y', strtotime( $date ) );

                    printf( '<h3>' . __( 'Schedule for: %s', 'meetup' ) . '</h3>', $this_day );
                    ?>
                        <table class="meetup-table">
                            <thead>
                                <tr>
                                    <th><?php _e( 'Time', 'meetup' ); ?></th>
                                    <th><?php _e( 'Agenda', 'meetup' ); ?></th>
                                    <th><?php _e( 'Comments', 'meetup' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($day_events as $agenda) { ?>

                                    <tr>
                                        <td><?php echo date_i18n( 'g:i a', $agenda['time'] ); ?></td>
                                        <td><?php echo $agenda['agenda']; ?></td>
                                        <td><?php echo wp_kses_post( $agenda['comments'] ); ?></td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>

                    <?php
                }

            } else {
                _e( 'No schedules have been made yet!', 'meetup' );
            }


            // var_dump( $schedule );
            // var_dump( $days );
            ?>
        </div>

    </div>

</article><!-- #post-<?php the_ID(); ?> -->
