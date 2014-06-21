<?php

/**
 * Check if the user has already booked a seat
 *
 * @param  int $user_id
 * @param  int $meetup_id
 * @return object|boolean
 */
function meetup_has_user_booked( $user_id, $meetup_id ) {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}meetup_users WHERE meetup_id = %d AND user_id = %d AND status = 1";
    $result = $wpdb->get_row( $wpdb->prepare( $sql, $meetup_id, $user_id ) );

    if ( $result ) {
        return $result;
    }

    return false;
}

/**
 * Check if the user has already booked a seat
 *
 * @param  int $user_id
 * @param  int $meetup_id
 * @return object|boolean
 */
function meetup_book_seat( $user_id, $meetup_id, $num_of_seat = 1 ) {
    global $wpdb;

    if ( $prev_booking = meetup_has_user_booked( $user_id, $meetup_id ) ) {
        // update the booking
        $quantity  = intval( $prev_booking->seat );
        $total     = $num_of_seat - $quantity;

        if ( $total < 0 ) {
            // user is reducing amount of seat
            // so we don't have any problem to update
        } else {
            // user is increasing the number of seat
            // we should check the availibility to see
            // if the additional seat is available
            if ( ! meetup_is_seat_available( $meetup_id, $total ) ) {
                return new WP_Error( 'no-seat', __( 'Sorry, additional seat is not available.', 'meetup' ) );
            }
        }

        $wpdb->update( $wpdb->prefix . 'meetup_users',
            array( 'seat' => $num_of_seat ),
            array(
                'user_id'   => $user_id,
                'meetup_id' => $meetup_id
            ),
            array( '%d' ),
            array( '%d', '%d' )
        );
    } else {
        // create new booking

        if ( ! meetup_is_seat_available( $meetup_id, $num_of_seat ) ) {
            return new WP_Error( 'no-seat', __( 'No seat available', 'meetup' ) );
        }

        $wpdb->insert( $wpdb->prefix . 'meetup_users',
            array(
                'user_id'   => $user_id,
                'meetup_id' => $meetup_id,
                'seat'      => $num_of_seat,
                'created'   => current_time( 'mysql' )
            ),
            array(
                '%d',
                '%d',
                '%d',
                '%s'
            )
        );
    }

    return true;
}

/**
 * Get the total number of booked seat for a meetup
 *
 * @param  int $meetup_id
 * @return int
 */
function meetup_num_booked_seat( $meetup_id ) {
    global $wpdb;

    $sql   = "SELECT SUM(seat) FROM {$wpdb->prefix}meetup_users WHERE meetup_id = %d AND status = 1";
    $count = (int) $wpdb->get_var( $wpdb->prepare( $sql, $meetup_id ) );

    return $count;
}

/**
 * Get the number of available seat
 *
 * @param  int $meetup_id
 * @return int
 */
function meetup_num_available_seat( $meetup_id ) {
    $capacity = (int) get_post_meta( $meetup_id, 'capacity', true );
    $filled   = meetup_num_booked_seat( $meetup_id );

    return $capacity - $filled;
}

/**
 * Check if the number of seat is available for the meetup
 *
 * @param  int  $meetup_id
 * @param  integer $num_of_seat
 * @return boolean
 */
function meetup_is_seat_available( $meetup_id, $num_of_seat = 1 ) {
    $available = meetup_num_available_seat( $meetup_id );

    if ( $available >= $num_of_seat ) {
        return true;
    }

    return false;
}