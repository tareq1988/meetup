<?php

/**
 * Ajax handler
 */
class WeDevs_Meetup_Ajax {

    function __construct() {
        add_action( 'wp_ajax_nopriv_meetup_site_new_join', array($this, 'guest_site_registration') );
    }

    /**
     * Guess a suitable username for registration based on email address
     * @param string $email email address
     * @return string username
     */
    function guess_username( $email ) {
        // username from email address
        $username = sanitize_user( substr( $email, 0, strpos( $email, '@' ) ) );

        if ( ! username_exists( $username ) ) {
            return $username;
        }

        // try to add some random number in username
        // and may be we got our username
        $username .= rand( 1, 199 );
        if ( !username_exists( $username ) ) {
            return $username;
        }
    }

    /**
     * Perform guest registration
     *
     * @return void
     */
    function guest_site_registration() {
        check_ajax_referer( 'meetup-site-join-form' );

        $posted     = $_POST;
        $first_name = $posted['meetup_fname'];
        $last_name  = $posted['meetup_lname'];
        $email      = $posted['meetup_email'];
        $seat       = (int) $posted['meetup-fb-join-seat'];
        $meetup_id  = (int) $posted['meetup_id'];

        if ( ! is_email( $email ) ) {
            wp_send_json_error( array(
                'type'    => 'error',
                'message' => __( 'Please enter a valid email address', 'meetup' )
            ) );
        }

        if ( email_exists( $email ) ) {
            wp_send_json_error( array(
                'type'    => 'login',
                'message' => __( 'You are already registered to our site, please login', 'meetup' ),
                'url'     => wp_login_url( get_permalink( $meetup_id ) )
            ) );
        }

        $username = $this->guess_username( $email );
        $user_pass = wp_generate_password( 12, false );

        $errors = new WP_Error();
        do_action( 'register_post', $username, $email, $errors );

        $user_id = wp_create_user( $username, $user_pass, $email );

        // if its a success and no errors found
        if ( $user_id && !is_wp_error( $user_id ) ) {

            // we can turn on/off notification email via this filter
            // default is `true`
            $send_notification = apply_filters( 'meetup_new_user_notificaion', true, $user_id, $username, $email, $user_pass );

            if ( $send_notification ) {
                wp_new_user_notification( $user_id, $user_pass );
            }

            // update display name to full name
            wp_update_user( array(
                'ID'           => $user_id,
                'display_name' => $first_name,
                'first_name'   => $first_name,
                'last_name'    => $last_name
            ) );

            do_action( 'meetup_user_registered', $user_id, $username, $email, $user_pass );

            // lets auto login the user
            wp_set_auth_cookie( $user_id, true );

            // do the booking process
            $booking = meetup_book_seat( $user_id, $meetup_id, $seat );

            if ( is_wp_error( $booking ) ) {
                wp_send_json_error( array(
                    'type'    => 'registered',
                    'message' => $booking->get_error_message()
                ) );
            }

            // seems like we made a booking
            wp_send_json_success( array(
                'type'    => 'registered',
                'message' => __( 'You have successfully booked the seat!', 'meetup' )
            ) );

        } else {
            wp_send_json_error( array(
                'type'    => 'error',
                'message' => $user_id->get_error_message()
            ) );
        }

        exit;
    }
}

new WeDevs_Meetup_Ajax();