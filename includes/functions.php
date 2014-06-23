<?php

/**
 * Get template part (for templates like the shop-loop).
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 * @return void
 */
function meetup_get_template_part( $slug, $name = '' ) {
    $template = '';

    // Look in yourtheme/slug-name.php and yourtheme/meetup/slug-name.php
    if ( $name ) {
        $template = locate_template( array( "{$slug}-{$name}.php", meetup()->template_path() . "{$slug}-{$name}.php" ) );
    }

    // Get default slug-name.php
    if ( ! $template && $name && file_exists( meetup()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
        $template = meetup()->plugin_path() . "/templates/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/meetup/slug.php
    if ( ! $template ) {
        $template = locate_template( array( "{$slug}.php", meetup()->template_path() . "{$slug}.php" ) );
    }

    // Allow 3rd party plugin filter template file from their plugin
    $template = apply_filters( 'meetup_get_template_part', $template, $slug, $name );

    if ( $template ) {
        load_template( $template, false );
    }
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param mixed $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return void
 */
function meetup_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }

    $located = meetup_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $located ) ) {
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
        return;
    }

    do_action( 'meetup_before_template_part', $template_name, $template_path, $located, $args );

    include( $located );

    do_action( 'meetup_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *      yourtheme       /   $template_path  /   $template_name
 *      yourtheme       /   $template_name
 *      $default_path   /   $template_name
 *
 * @access public
 * @param mixed $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function meetup_locate_template( $template_name, $template_path = '', $default_path = '' ) {
    if ( ! $template_path ) {
        $template_path = meetup()->template_path();
    }

    if ( ! $default_path ) {
        $default_path = meetup()->plugin_path() . '/templates/';
    }

    // Look within passed path within the theme - this is priority
    $template = locate_template(
        array(
            trailingslashit( $template_path ) . $template_name,
            $template_name
        )
    );

    // Get default template
    if ( ! $template ) {
        $template = $default_path . $template_name;
    }

    // Return what we found
    return apply_filters('meetup_locate_template', $template, $template_name, $template_path);
}

/**
 * Check if the current page is speaker page
 *
 * @return boolean
 */
function meetup_is_speaker_page() {
    if ( get_post_type() == 'meetup' && get_query_var( 'speakers' ) == 'yes' ) {
        return true;
    }

    return false;
}

/**
 * Check if the current page is schedule page
 *
 * @return boolean
 */
function meetup_is_schedule_page() {
    if ( get_post_type() == 'meetup' && get_query_var( 'schedule' ) == 'yes' ) {
        return true;
    }

    return false;
}

/**
 * Check if the current page is sponsor page
 *
 * @return boolean
 */
function meetup_is_sponsor_page() {
    if ( get_post_type() == 'meetup' && get_query_var( 'sponsors' ) == 'yes' ) {
        return true;
    }

    return false;
}

/**
 * Check if the current page is attendies page
 *
 * @return boolean
 */
function meetup_is_attendies_page() {
    if ( get_post_type() == 'meetup' && get_query_var( 'attendies' ) == 'yes' ) {
        return true;
    }

    return false;
}

/**
 * Check if the current page is gallery page
 *
 * @return boolean
 */
function meetup_is_gallery_page() {
    if ( get_post_type() == 'meetup' && get_query_var( 'gallery' ) == 'yes' ) {
        return true;
    }

    return false;
}

/**
 * Navigation menu for a single meetup
 *
 * @return array
 */
function meetup_get_navigation() {
    $nav = array(
        'details' => array(
            'title' => __( 'Event Details', 'meetup' ),
            'link' => get_permalink()
        ),
        'speakers' => array(
            'title' => __( 'Speakers', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'speakers/',
        ),
        'schedule' => array(
            'title' => __( 'Schedule', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'schedule/',
        ),
        'sponsors' => array(
            'title' => __( 'Sponsors', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'sponsors/',
        ),
        'attendies' => array(
            'title' => __( 'Attendies', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'attendies/',
        ),
        'gallery' => array(
            'title' => __( 'Gallery', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'gallery/',
        ),
    );

    return apply_filters( 'meetup_navigation', $nav );
}

/**
 * Generates the navigation menu
 *
 * @return string
 */
function meetup_navigation() {
    $items = meetup_get_navigation();
    $menu = '<ul class="meetup-navigation">';
    $current_menu = 'details';

    if ( meetup_is_speaker_page() ) {

        $current_menu = 'speakers';

    } elseif ( meetup_is_sponsor_page() ) {

        $current_menu = 'sponsors';

    } elseif ( meetup_is_schedule_page() ) {

        $current_menu = 'schedule';

    } elseif ( meetup_is_attendies_page() ) {

        $current_menu = 'attendies';

    } elseif ( meetup_is_gallery_page() ) {

        $current_menu = 'gallery';

    }

    $current_menu = apply_filters( 'meetup_current_menu', $current_menu, $items );

    foreach ($items as $key => $item) {
        $class = ( $key == $current_menu ) ? ' class="active"' : '';
        $menu .= sprintf( '<li%s><a href="%s">%s</a></li>', $class, $item['link'], $item['title'] );
    }

    $menu .= '</ul>';

    return $menu;
}

/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @return void
 */
function meetup_content_nav( $html_id ) {
    global $wp_query;

    $html_id = esc_attr( $html_id );

    if ( $wp_query->max_num_pages > 1 ) : ?>
        <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
            <h3 class="assistive-text"><?php _e( 'Post navigation', 'meetup' ); ?></h3>
            <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older Meetups', 'meetup' ) ); ?></div>
            <div class="nav-next"><?php previous_posts_link( __( 'Newer Meetups <span class="meta-nav">&rarr;</span>', 'meetup' ) ); ?></div>
        </nav><!-- #<?php echo $html_id; ?> .navigation -->
    <?php endif;
}

/**
 * Escapes a string of characters
 *
 * @param  string $string
 * @return string
 */
function meetup_clean_string( $string ) {
    $string = strip_tags( $string );
    $string = str_replace( array( "\n", "\r" ), ' ', $string );

    return $string;
}

/**
 * Generate iCal file for Apple Calendar
 *
 * @return void
 */
function meetup_generate_ical() {
    if ( ! isset( $_GET['meetup_action' ] ) ) {
        return;
    }

    if ( $_GET['meetup_action' ] != 'ical_gen' ) {
        return;
    }

    check_admin_referer( 'meetup-ical-gen' );

    global $post;

    if ( $post->post_type != 'meetup' ) {
        return;
    }

    $post_id    = $post->ID;
    $gmt_offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
    $from       = get_post_meta( $post_id, 'from', true );
    $to         = get_post_meta( $post_id, 'to', true );
    $address    = meetup_clean_string( get_post_meta( $post_id, 'address', true ) );

    $url        = get_permalink();
    $title      = meetup_clean_string( get_the_title() );
    $starts     = date( "Ymd\THis\Z", ( $from + $gmt_offset ) );
    $ends       = date( "Ymd\THis\Z", ($to + $gmt_offset) );
    $details    = sprintf( __( 'For details, link here: %s', 'meetup' ), get_permalink() );
    $filename   = 'meetup-' . $post_id;


    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    echo <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
URL:$url
DTSTART:$starts
DTEND:$ends
SUMMARY:$title
DESCRIPTION:$details
LOCATION:$address
END:VEVENT
END:VCALENDAR
ICS;

    die();
}

add_action( 'template_redirect', 'meetup_generate_ical' );
