<div class="wrap">
    <h2><?php printf( __( 'Attendee List: %s', 'meetup' ), $meetup->post_title ); ?></h2>

    <br>

    <form action="" method="post">
        <a class="button button-primary" href="<?php echo admin_url( 'post.php?action=edit&post=' . $meetup_id ); ?>"><?php _e( 'Edit Meetup', 'meetup' ); ?></a>
        <a class="button" href="<?php echo get_permalink( $meetup_id ); ?>" target="_blank"><?php _e( 'View Meetup', 'meetup' ); ?></a>

        <?php wp_nonce_field( 'meetup-export-user' ); ?>
        <input type="hidden" name="meetup_id" value="<?php echo $meetup_id; ?>">
        <input class="button" type="submit" name="meetup_export_users" value="<?php _e( 'Export Users', 'meetup' ); ?>">
    </form>

    <?php if ( isset( $_GET['message' ] ) && $_GET['message'] == 'cancel' ) { ?>
        <div class="updated"><p><?php _e( 'Booking has been cancelled!', 'meetup' ); ?></p></div>
    <?php } ?>

    <?php
    $attendies = meetup_get_attendies( $meetup_id );

    if ( ! $attendies ) {
        ?>

        <div class="updated error"><p><?php _e( 'No attendies has been found!', 'meetup' ); ?></p></div>

        <?php
    } else {
        ?>

        <table class="wp-list-table widefat fixed" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th><?php _e( 'Name', 'meetup' ); ?></th>
                    <th><?php _e( 'Email', 'meetup' ); ?></th>
                    <th><?php _e( 'No. of Seat', 'meetup' ); ?></th>
                    <th><?php _e( 'Registered', 'meetup' ); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($attendies as $key => $user) { ?>
                    <tr class="<?php echo ( ($key % 2) == 0 ) ? 'alternate' : 'even'; ?>">
                        <td class="username column-username">
                            <?php echo get_avatar( $user->user_email, 32 ); ?>
                            <strong><?php echo $user->display_name; ?></strong>

                            <div class="row-actions">

                                <?php
                                $cancel_url = add_query_arg( array( 'action' => 'meetup_cancel_booking', 'id' => $user->id, 'meetup_id' => $meetup_id, 'user_id' => $user->user_id ), admin_url( 'admin-post.php' ) );
                                $edit_url   = add_query_arg( array( 'user_id' => $user->user_id ), admin_url( 'user-edit.php' ) );
                                ?>
                                <span>
                                    <a href="<?php echo wp_nonce_url( $cancel_url, 'meetup-cancel-booking' ); ?>"><?php _e( 'Cancel Booking', 'meetup' ); ?></a> |
                                </span>
                                <span>
                                    <a href="<?php echo $edit_url; ?>"><?php _e( 'Edit User', 'meetup' ); ?></a>
                                </span>
                            </div>
                        </td>
                        <td>
                            <?php echo $user->user_email; ?>
                        </td>
                        <td>
                            <?php echo $user->seat; ?>
                        </td>
                        <td>
                            <?php echo date_i18n( 'F j, Y g:ia', strtotime( $user->created ) ); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php
    }
    ?>
</div>

<script type="text/javascript">
jQuery(function($) {
    $('table').addTableFilter({
        labelText: "<?php _e( 'Filter: ' ); ?>",
    });
});
</script>