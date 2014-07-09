<?php

/**
 * Meta fields for meetup
 *
 * @param  array  $meta_boxes
 * @return array
 */
function meetup_cmb_fields( $meta_boxes = array() ) {

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
            'name'    => __( 'Capacity', 'meetup' ),
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
            'id'      => 'location',
            'name'    => __( 'Map Location', 'meetup' ),
            'type'    => 'gmap',
            'desc'    => __( 'Location of the meetup. Requires <code>Latitude</code> and <code>Longitude</code> value.', 'meetup' ),
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
        array(
            'id'   => 'book_limit',
            'name' => __( 'Per User Booking Limit', 'meetup' ),
            'type' => 'text',
            'desc' => __( 'How many seats a user can book?', 'meetup' ),
            'default' => 1
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
            array(
                'id'   => 'slide_url',
                'name' => __( 'Slide URL', 'meetup' ),
                'type' => 'url',
                'desc' => __( 'Website url of the slide', 'meetup' ),
            ),
        ),
    );

    $sponsor_fields = array(
        array(
            'id'   => 'sponsor_details',
            'name' => __( 'Details', 'meetup' ),
            'type' => 'textarea',
            'desc' => __( 'Some texts about sponsors', 'meetup' ),
        ),
        array(
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
        )
    );

    $schedule = array(
        array(
            'id'         => 'schedule',
            'name'       => __( 'Time Entry', 'meetup' ),
            'type'       => 'group',
            'repeatable' => true,
            'fields'     => array(
                array(
                    'id'   => 'time',
                    'name' => __( 'Time', 'meetup' ),
                    'type' => 'datetime_unix',
                ),
                array(
                    'id'   => 'agenda',
                    'name' => __( 'Agenda', 'meetup' ),
                    'type' => 'text',
                ),
                array(
                    'id'   => 'comments',
                    'name' => __( 'Comments', 'meetup' ),
                    'type' => 'textarea',
                    'desc' => __( 'Additional details about the schedule', 'meetup' ),
                ),
            ),
        )
    );

    $gallery = array(
        array(
            'id'   => 'gallery',
            'name' => __( 'Gallery Images', 'meetup' ),
            'type' => 'image',
            'repeatable' => true,
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

    $meta_boxes[] = array(
        'title'  => __( 'Schedule', 'meetup' ),
        'pages'  => 'meetup',
        'fields' => $schedule
    );

    $meta_boxes[] = array(
        'title'  => __( 'Image Gallery', 'meetup' ),
        'pages'  => 'meetup',
        'fields' => $gallery
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'meetup_cmb_fields' );

/**
 * Register our google map field
 *
 * @param  array $types
 * @return array
 */
function meetup_register_cmb_gmap( $types ) {

    $types['gmap'] = 'CMB_Gmap_Field';

    return $types;
}

add_filter( 'cmb_field_types', 'meetup_register_cmb_gmap' );


/**
 * Google map field class for CMB
 *
 * @author Tareq Hasan
 */
class CMB_Gmap_Field extends CMB_Field {

    public function enqueue_scripts() {

        parent::enqueue_scripts();

        wp_enqueue_script( 'cmb-google-maps', '//maps.google.com/maps/api/js?sensor=true&libraries=places' );
        wp_enqueue_script( 'cmb-google-maps-script', MEETUP_URL . '/assets/js/cmb-gmap.js' );
    }

    public function html() {
        $value = $this->get_value();
        $lat   = isset( $value['lat'] ) ? $value['lat'] : '54.800685';
        $long  = isset( $value['long'] ) ? $value['long'] : '-4.130859';
        ?>

        <input type="text" <?php $this->class_attr( 'map-search' ); ?> <?php $this->id_attr(); ?> />
        <div class="map" style="width: 100%; height: 250px; border: 1px solid #eee; margin-top: 10px;"></div>

        <input type="hidden" class="latitude" <?php $this->name_attr( '[lat]' ); ?> value="<?php echo $lat; ?>" />
        <input type="hidden" class="longitude" <?php $this->name_attr( '[long]' ); ?> value="<?php echo $long; ?>" />

        <?php
    }
}