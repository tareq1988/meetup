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

    <?php if ( isset( $_GET['message' ] ) ) {

        if ( $_GET['message'] == 'cancel' ) {
            ?>
            <div class="updated"><p><?php _e( 'Booking has been cancelled!', 'meetup' ); ?></p></div>
        <?php } ?>

        <?php if ( $_GET['message'] == 'checkin' ) {
            ?>
            <div class="updated"><p><?php _e( 'Check In status has been changed!', 'meetup' ); ?></p></div>
        <?php } ?>

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
                    <th width="25%"><?php _e( 'Name', 'meetup' ); ?></th>
                    <th><?php _e( 'Email', 'meetup' ); ?></th>
                    <th><?php _e( 'Phone', 'meetup' ); ?></th>
                    <th><?php _e( 'No. of Seat', 'meetup' ); ?></th>
                    <th><?php _e( 'Status', 'meetup' ); ?></th>
                    <th><?php _e( 'Registered', 'meetup' ); ?></th>
                    <th width="15%"><?php _e( 'Action', 'meetup' ); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($attendies as $key => $user) {
                    $class = ( ($key % 2) == 0 ) ? 'alternate' : 'even';
                    $class .= ( $user->status == '1' ) ? ' booked' : '';
                    $class .= ( $user->status == '2' ) ? ' confirmed' : '';
                    $class .= ( $user->status == '3' ) ? ' checkin' : '';
                    ?>
                    <tr class="<?php echo $class; ?>">
                        <td width="25%" class="username column-username">
                            <?php echo get_avatar( $user->user_email, 32 ); ?>
                            <strong><?php echo $user->display_name; ?></strong>
                        </td>
                        <td>
                            <?php echo $user->user_email; ?>
                        </td>
                        <td>
                            <?php echo get_user_meta( $user->user_id, 'phone', true ); ?>
                        </td>
                        <td>
                            <?php echo $user->seat; ?>
                        </td>
                        <td>
                            <?php echo meetup_get_seat_status( $user->status ); ?>
                        </td>
                        <td>
                            <?php echo date_i18n( 'F j, Y g:ia', strtotime( $user->created ) ); ?>
                        </td>
                        <td class="meetup-actions" width="15%">

                            <?php
                            $cancel_url = add_query_arg( array( 'action' => 'meetup_cancel_booking', 'id' => $user->id, 'meetup_id' => $meetup_id, 'user_id' => $user->user_id ), admin_url( 'admin-post.php' ) );
                            $checkin_url = add_query_arg( array( 'action' => 'meetup_checkin', 'id' => $user->id, 'meetup_id' => $meetup_id, 'current' => $user->status ), admin_url( 'admin-post.php' ) );
                            $edit_url   = add_query_arg( array( 'user_id' => $user->user_id ), admin_url( 'user-edit.php' ) );
                            ?>

                            <?php if ( $user->status == '1' || $user->status == '2' ) { ?>
                                <a class="button tooltip button-primary meetup-checkin" title="<?php esc_attr_e( 'Check In', 'meetup' ); ?>" href="<?php echo wp_nonce_url( $checkin_url, 'meetup-checkin' ); ?>"><div class="dashicons dashicons-yes"></div></a>
                            <?php } else { ?>
                                <a class="button tooltip button-destroy meetup-checkin" title="<?php esc_attr_e( 'Cancel Check In', 'meetup' ); ?>" href="<?php echo wp_nonce_url( $checkin_url, 'meetup-checkin' ); ?>"><div class="dashicons dashicons-no"></div></a>
                            <?php } ?>

                            <a class="button tooltip" title="<?php esc_attr_e( 'Edit User', 'meetup' ); ?>" href="<?php echo $edit_url; ?>"><div class="dashicons dashicons-edit"></div></a>
                            <a class="button tooltip" title="<?php esc_attr_e( 'Cancel Booking', 'meetup' ); ?>" href="<?php echo wp_nonce_url( $cancel_url, 'meetup-cancel-booking' ); ?>"><div class="dashicons dashicons-dismiss"></div></a>
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

    $('.tooltip').tipTip();
});
</script>