<?php

/**
 * User dropdown depending on a user role
 *
 * @param string $role user capability
 * @return array
 */
function meetup_users_dropdown( $role = false ) {
    $items = array();
    $users = get_users();

    foreach ($users as $user) {
        //check role
        if ( $role && user_can( $user->ID, $role ) ) {
            $items[$user->ID] = $user->display_name;
        } else if ( !$role ) {
            $items[$user->ID] = $user->display_name;
        }
    }

    return $items;
}

/**
 * Meta fields for meetup
 *
 * @param  array  $meta_boxes
 * @return array
 */
function meetup_cmb_fields( $meta_boxes = array() ) {
    $user_dropdown = meetup_users_dropdown( 'delete_others_pages' );

    $meetup_fields = array(
        array(
            'id'   => 'from',
            'name' => __( 'Start Time', 'meetup' ),
            'type' => 'datetime_unix',
        ),
        array(
            'id'   => 'to',
            'name' => __( 'End Time', 'meetup' ),
            'type' => 'datetime_unix',
        ),
        array(
            'id'      => 'capacity',
            'name'    => __( 'Limit', 'meetup' ),
            'type'    => 'text',
            'desc'    => __( 'How many guests can join this meetup?', 'meetup' ),
            'default' => 50
        ),
        array(
            'id'      => 'address',
            'name'    => __( 'Address', 'meetup' ),
            'type'    => 'textarea',
            'desc'    => __( 'Address of the meetup place', 'meetup' ),
        ),
        array(
            'id'   => 'reg_starts',
            'name' => __( 'Registration Start Date', 'meetup' ),
            'type' => 'datetime_unix',
        ),
        array(
            'id'   => 'reg_ends',
            'name' => __( 'Registration End Date', 'meetup' ),
            'type' => 'datetime_unix',
        ),
    );

    $speaker_fields[] = array(
        'id'         => 'speakers',
        'name'       => __( 'Speakers', 'meetup' ),
        'type'       => 'group',
        'repeatable' => true,
        'fields'     => array(
            array(
                'id'   => 'name',
                'name' => __( 'Speaker Name', 'meetup' ),
                'type' => 'text',
            ),
            array(
                'id'   => 'topic',
                'name' => __( 'Topic Name', 'meetup' ),
                'type' => 'text',
            ),
            array(
                'id'   => 'email',
                'name' => __( 'Email', 'meetup' ),
                'type' => 'text',
                'desc' => __( 'This email address will be used to pull photo from Gravatar', 'meetup' ),
            ),
            array(
                'id'   => 'url',
                'name' => __( 'Website', 'meetup' ),
                'type' => 'url',
                'desc' => __( 'Website url of the speaker', 'meetup' ),
            ),
            array(
                'id'   => 'bio',
                'name' => __( 'Speaker Bio', 'meetup' ),
                'type' => 'textarea',
                'desc' => __( 'Some details about the speaker', 'meetup' ),
            ),
        ),
    );

    $sponsor_fields[] = array(
        'id'         => 'sponsors',
        'name'       => __( 'Sponsors', 'meetup' ),
        'type'       => 'group',
        'repeatable' => true,
        'fields'     => array(
            array(
                'id'   => 'name',
                'name' => __( 'Sponsor Name', 'meetup' ),
                'type' => 'text',
            ),
            array(
                'id'   => 'logo',
                'name' => __( 'Sponsor Logo', 'meetup' ),
                'type' => 'image',
            ),
            array(
                'id'   => 'details',
                'name' => __( 'Sponsor Details', 'meetup' ),
                'type' => 'wysiwyg',
                'desc' => __( 'Additional details about the sponsor', 'meetup' ),
            ),
        ),
    );

    $meta_boxes[] = array(
        'title'  => __( 'Meetup Details', 'meetup' ),
        'pages'  => 'meetup',
        'fields' => $meetup_fields
    );

    $meta_boxes[] = array(
        'title'  => __( 'Speaker Details', 'meetup' ),
        'pages'  => 'meetup',
        'fields' => $speaker_fields
    );

    $meta_boxes[] = array(
        'title'  => __( 'Sponsor Details', 'meetup' ),
        'pages'  => 'meetup',
        'fields' => $sponsor_fields
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'meetup_cmb_fields' );