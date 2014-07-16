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
 * Handle bulk actions from the admin side
 *
 * @return void
 */
function meetup_admin_attendee_bulk_action() {

    if ( ! isset( $_POST['meetup_attendee_bulk_action'] ) ) {
        return;
    }

    check_admin_referer( 'meetup-attendee-bulk' );

    $action = $_POST['meetup_action'];

    // var_dump($action, $_POST); die();
    $meetup_id = isset( $_POST['meetup_id'] ) ? intval( $_POST['meetup_id'] ) : 0;
    $users     = isset( $_POST['meetup_user'] ) ? $_POST['meetup_user'] : array();
    $bookings  = isset( $_POST['booking_id'] ) ? $_POST['booking_id'] : array();

    switch ($action) {
        case 'confirm_mail':

            if ( ! $users ) {
                return;
            }

            $meetup       = get_post( $meetup_id );
            $meetup_date  = date_i18n( 'F j, Y g:ia', get_post_meta( $meetup_id, 'from', true ) );
            $mail_title   = meetup_get_option( 'conf_subject', 'meetup_email', __( 'Confirm Your Booking', 'meetup' ) );
            $mail_content = meetup_get_option( 'conf_body', 'meetup_email', meetup_admin_conf_mail_default_content() );

            foreach ($users as $key => $user_id) {
                $user = get_user_by( 'id', $user_id );
                $booking_id = $bookings[$key];

                if ( ! $user || is_wp_error( $user ) ) {
                    continue;
                }

                $search_pattern = apply_filters( 'meetup_email_search', array(
                    '%name%',
                    '%meetup_title%',
                    '%meetup_date%'
                ) );

                $replace_pattern = apply_filters( 'meetup_email_replace', array(
                    $user->display_name,
                    $meetup->post_title,
                    $meetup_date
                ) );

                $replaced = str_replace( $search_pattern, $replace_pattern, $mail_content);
                $hash     = hash_hmac( 'sha1', $user->ID . $meetup_id, 'meetup_hash' );
                $query_args = array(
                    'uid' => $user->ID,
                    'mid' => $meetup_id,
                    'bid' => $booking_id,
                    'key' => $hash
                );

                $conf_args   = array_merge( $query_args, array( 'action' => 'meetup_confirm' ) );
                $cancel_args = array_merge( $query_args, array( 'action' => 'meetup_cancel' ) );

                $conf_link   = add_query_arg( $conf_args, get_permalink( $meetup_id ) );
                $cancel_link = add_query_arg( $cancel_args, get_permalink( $meetup_id ) );

                meetup_admin_send_conf_mail_user( array(
                    'mail_content' => $replaced,
                    'mail_title'   => $mail_title,
                    'to'           => $user->user_email,
                    'conf_link'    => $conf_link,
                    'cancel_link'  => $cancel_link
                ) );

                update_user_meta( $user->ID, '_meetup_email_hash', $hash );
                meetup_seat_change_status( $booking_id, 4 ); // set status to awaiting
            }

            break;

        case 'trash':
            foreach ($users as $key => $user_id) {
                $booking_id = $bookings[$key];

                meetup_cancel_seat( $user_id, $meetup_id, $booking_id );
            }

            break;
    }
}

add_action( 'admin_init', 'meetup_admin_attendee_bulk_action' );

/**
 * Send confirmation email
 *
 * @param  array  $args
 * @return void
 */
function meetup_admin_send_conf_mail_user( $args = array() ) {
    $defaults = array(
        'mail_title'   => '',
        'mail_content' => '',
        'conf_link'    => '',
        'cancel_link'  => ''
    );

    $args = wp_parse_args( $args, $defaults );
    extract( $args );

    ob_start();
    include MEETUP_PATH . '/includes/email/header.php';
    include MEETUP_PATH . '/includes/email/confirmation.php';
    include MEETUP_PATH . '/includes/email/footer.php';
    $message_body = ob_get_clean();

    // echo $message_body; die();

    // send email
    add_filter( 'wp_mail_content_type', 'meetup_email_html_content_type', 99 );
    add_filter( 'wp_mail_from_name', 'meetup_email_from_name', 99 );

    wp_mail( $to, $mail_title, $message_body );

    remove_filter( 'wp_mail_content_type', 'meetup_email_html_content_type', 99 );
    remove_filter( 'wp_mail_from_name', 'meetup_email_from_name', 99 );
}

/**
 * Change the content type of confirmation email
 *
 * @param  string $type
 * @return string
 */
function meetup_email_html_content_type( $type ) {
    return 'text/html';
}

/**
 * Change the sender name for email
 *
 * @param  string $from_name
 * @return string
 */
function meetup_email_from_name( $from_name ) {
    return get_bloginfo( 'name', 'display' );
}

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

/**
 * Adds additional contact methods to user profile
 *
 * @param  array $methods
 * @return array
 */
function meetup_user_contact_methods( $methods ) {

    $methods['twitter']  = __( 'Twitter', 'meetup' );
    $methods['career']   = __( 'Profession', 'meetup' );
    $methods['phone']    = __( 'Phone', 'meetup' );
    $methods['_fb_link'] = __( 'Facebook', 'meetup' );

    return $methods;
}

add_filter( 'user_contactmethods', 'meetup_user_contact_methods' );

/**
 * Default mail body for confirmation mail
 *
 * @return string
 */
function meetup_admin_conf_mail_default_content() {
    $mail_content = <<<EOD
Hello %name%,

You've been selected to attend <strong>%meetup_title%</strong> which is happening at <strong>%meetup_date%</strong>.

If you wish to attend the event, it's time to confirm finally! Or if you've changed your mind, we could allocate the seat to someone else.
EOD;

    return $mail_content;
}