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

    unset( $actions['inline hide-if-no-js'] );

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

        header("Content-type: text/csv; charset=utf-8");
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

/**
 * Toggle "check in" for a user
 *
 * @return void
 */
function meetup_admin_user_checkin() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    check_admin_referer( 'meetup-checkin' );

    $meetup_id  = isset( $_REQUEST['meetup_id'] ) ? intval( $_REQUEST['meetup_id'] ) : 0;
    $booking_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
    $current = isset( $_REQUEST['current'] ) ? intval( $_REQUEST['current'] ) : 0;

    // toggle the status
    $status = ( $current == '3' ) ? '1' : '3'; // 3:checkin, 1:booked

    meetup_seat_change_status( $booking_id, $status );

    wp_redirect( admin_url( 'edit.php?post_type=meetup&page=meetup-attendies&message=checkin&meetup_id=' . $meetup_id ) );
}

add_action( 'admin_post_meetup_checkin', 'meetup_admin_user_checkin' );


/**
 * Columns form builder list table
 *
 * @param type $columns
 * @return string
 */
function meetup_post_type_admin_column( $columns ) {
    $columns = array(
        'cb'          => '<input type="checkbox" />',
        'title'       => __( 'Meetup Name', 'meetup' ),
        'meetup_date' => __( 'Date', 'meetup' ),
        'booked'      => __( 'Booked', 'meetup' ),
        'available'   => __( 'Available', 'meetup' ),
        'capacity'    => __( 'Capacity', 'meetup' ),
    );

    return $columns;
}

add_filter( 'manage_edit-meetup_columns', 'meetup_post_type_admin_column' );


/**
 * Custom Column value for post form builder
 *
 * @param string $column_name
 * @param int $post_id
 */
function meetup_post_type_admin_column_value( $column_name, $post_id ) {
    switch ($column_name) {
        case 'meetup_date':
            $from = get_post_meta( $post_id, 'from', true );

            if ( ! empty( $from ) ) {
                echo date_i18n( 'F j, Y g:ia', $from );
            }

            break;

        case 'capacity':
            echo meetup_get_capacity( $post_id );
            break;

        case 'booked':
            echo meetup_num_booked_seat( $post_id );
            break;

        case 'available':
            echo meetup_num_available_seat( $post_id );
            break;

        default:
            # code...
            break;
    }
}

add_action( 'manage_meetup_posts_custom_column', 'meetup_post_type_admin_column_value', 10, 2 );