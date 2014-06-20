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
            'link' => trailingslashit( get_permalink() ) . 'speakers',
        ),
        'schedule' => array(
            'title' => __( 'Schedule', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'schedule',
        ),
        'sponsors' => array(
            'title' => __( 'Sponsors', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'sponsors',
        ),
        'attendies' => array(
            'title' => __( 'Attendies', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'attendies',
        ),
        'gallery' => array(
            'title' => __( 'Gallery', 'meetup' ),
            'link' => trailingslashit( get_permalink() ) . 'gallery',
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