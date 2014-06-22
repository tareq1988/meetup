<?php

/**
 * Puts attendee list action link
 *
 * @param array $actions
 * @param object $post
 * @return array
 */
function meetup_admin_row_action( $actions, $post ) {

    if ( 'meetup' != $post->post_type ) {
        return $actions;
    }

    $actions['attendee'] = '<a href="' . admin_url( 'edit.php?post_type=meetup&page=meetup-attendies&meetup_id=' . $post->ID ) . '">' . __( 'Attendee List', 'meetup' ) . '</a>';

    return $actions;
}

add_filter( 'post_row_actions', 'meetup_admin_row_action', 10, 2 );

/**
 * Export atteedee to CSV file format
 *
 * @return void
 */
function meetup_export_users() {

    if ( ! isset( $_POST['meetup_export_users'] ) ) {
        return;
    }

    check_admin_referer( 'meetup-export-user' );

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $meetup_id = isset( $_POST['meetup_id'] ) ? intval( $_POST['meetup_id'] ) : 0;

    if ( ! $meetup_id ) {
        return;
    }

    $attendies = meetup_get_attendies( $meetup_id );

    if ( $attendies ) {

        $file_name = 'meetup-' . $meetup_id . '.csv';

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Pragma: no-cache");
        header("Expires: 0");

        _e( "Name,Email,Seat,Registered\n", 'meetup' );
        foreach ($attendies as $user) {
            printf( "%s,%s,%d,%s\n", $user->display_name, $user->user_email, $user->seat, $user->created );
        }

        die();
    }
}

add_action( 'admin_init', 'meetup_export_users' );


/**
 * Cancel a meetup from admin panel
 *
 * @return void
 */
function meetup_admin_cancel_booking() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    check_admin_referer( 'meetup-cancel-booking' );

    $meetup_id  = isset( $_REQUEST['meetup_id'] ) ? intval( $_REQUEST['meetup_id'] ) : 0;
    $booking_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
    $user_id    = isset( $_REQUEST['user_id'] ) ? intval( $_REQUEST['user_id'] ) : 0;

    meetup_cancel_seat( $user_id, $meetup_id, $booking_id );

    wp_redirect( admin_url( 'edit.php?post_type=meetup&page=meetup-attendies&message=cancel&meetup_id=' . $meetup_id ) );
}

add_action( 'admin_post_meetup_cancel_booking', 'meetup_admin_cancel_booking' );
