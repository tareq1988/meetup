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

/**
 * Check if registration has been started
 *
 * @param  int $meetup_id
 * @return boolean
 */
function meetup_is_registration_started( $meetup_id ) {
    $reg_starts = get_post_meta( $meetup_id, 'reg_starts', true );

    return time() > $reg_starts;
}

/**
 * Check if registration has been finished
 *
 * @param  int $meetup_id
 * @return boolean
 */
function meetup_is_registration_finished( $meetup_id ) {
    $reg_ends = get_post_meta( $meetup_id, 'reg_ends', true );

    return time() > $reg_ends;
}

/**
 * Signup fields for meetup
 *
 * @param  integer $meetup_id
 * @return void
 */
function meetup_signup_fields( $meetup_id = 0 ) {
    $userdata   = wp_get_current_user();
    $book_limit = get_post_meta( $meetup_id, 'book_limit', true );

    if ( $userdata ) {

        $first_name = $userdata->first_name;
        $last_name  = $userdata->last_name;
        $email      = $userdata->user_email;
        $phone      = $userdata->phone;
        $website    = $userdata->user_url;
        $twitter    = $userdata->twitter;
        $career     = $userdata->career;

    } else {
        $first_name = isset( $_POST['meetup_fname'] ) ? $_POST['meetup_fname'] : '';
        $last_name  = isset( $_POST['meetup_lname'] ) ? $_POST['meetup_lname'] : '';
        $email      = isset( $_POST['meetup_email'] ) ? $_POST['meetup_email'] : '';
        $phone      = isset( $_POST['meetup_phone'] ) ? $_POST['meetup_phone'] : '';
        $website    = isset( $_POST['meetup_siteurl'] ) ? $_POST['meetup_siteurl'] : '';
        $twitter    = isset( $_POST['meetup_twitter'] ) ? $_POST['meetup_twitter'] : '';
        $career     = isset( $_POST['meetup_career'] ) ? $_POST['meetup_career'] : '';
    }

    $careers = array(
        'Programmer'      => __( 'Programmer', 'meetup' ),
        'Designer'        => __( 'Designer', 'meetup' ),
        'Journalist'      => __( 'Journalist', 'meetup' ),
        'Blogger'         => __( 'Blogger', 'meetup' ),
        'Project Manager' => __( 'Project Manager', 'meetup' ),
        'Businessman'     => __( 'Businessman', 'meetup' ),
        'Student'         => __( 'Student', 'meetup' ),
        'Other'           => __( 'Other', 'meetup' ),
    );
    ?>
    <form action="" method="post" id="meetup-join-form">

        <div class="meetup-form-row meetup-col-wrap">
            <div class="meetup-form-half">
                <label for="meetup_fname"><?php _e( 'First Name', 'meetup' ); ?> <span class="required">*</span></label>
                <input type="text" name="meetup_fname" id="meetup_fname" value="<?php echo esc_attr( $first_name ); ?>" placeholder="<?php esc_attr_e( 'First Name', 'meetup' ); ?>" required="required">
            </div>

            <div class="meetup-form-half">
                <label for="meetup_lname"><?php _e( 'Last Name', 'meetup' ); ?> <span class="required">*</span></label>
                <input type="text" name="meetup_lname" id="meetup_lname" value="<?php echo esc_attr( $last_name ); ?>" placeholder="<?php esc_attr_e( 'Last Name', 'meetup' ); ?>" required="required">
            </div>
        </div>

        <?php if ( ! is_user_logged_in() ) { ?>
            <div class="meetup-form-row">
                <label for="meetup_email"><?php _e( 'Email', 'meetup' ); ?> <span class="required">*</span></label>
                <input type="email" id="meetup_email" name="meetup_email" value="<?php echo esc_attr( $email ); ?>" required="required">
            </div>
        <?php } ?>

        <div class="meetup-form-row">
            <label for="meetup_phone"><?php _e( 'Phone', 'meetup' ); ?> <span class="required">*</span></label>
            <input type="tel" id="meetup_phone" name="meetup_phone" value="<?php echo esc_attr( $phone ); ?>" required="required">
        </div>

        <div class="meetup-form-row meetup-col-wrap">
            <div class="meetup-form-half">
                <label for="meetup_site_url"><?php _e( 'Website', 'meetup' ); ?></label>
                <input type="url" id="meetup_site_url" name="meetup_site_url" value="<?php echo esc_url( $website ); ?>" placeholder="http://yoursite.com">
            </div>

            <div class="meetup-form-half">
                <label for="meetup_twitter"><?php _e( 'Twitter', 'meetup' ); ?></label>
                <input type="text" id="meetup_twitter" name="meetup_twitter" value="<?php echo esc_attr( $twitter ); ?>" placeholder="<?php esc_attr_e( 'Give your twitter handle (username)', 'meetup' ); ?>">
            </div>
        </div>

        <div class="meetup-form-row">
            <label for="meetup_career"><?php _e( 'Profession', 'meetup' ); ?></label>

            <select name="meetup_career" id="meetup_career">
                <?php foreach ($careers as $key => $value) { ?>
                    <option<?php selected( $career, $key ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo $value ?></option>
                <?php } ?>
            </select>
        </div>

        <?php if ( $book_limit > 1 ) { ?>
            <div class="meetup-form-row">
                <label for="m_seat"><?php _e( 'Number of Seat', 'meetup' ); ?></label>

                <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                    <?php for ($i = 1; $i <= $book_limit; $i++) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } ?>

        <div class="meetup-form-row">
            <?php wp_nonce_field( 'meetup-site-join-form' ); ?>
            <input type="hidden" name="meetup_id" value="<?php echo $meetup_id; ?>">
            <input type="hidden" name="action" value="meetup_user_join">

            <?php if ( $book_limit == '1' ) { ?>
                <input type="hidden" name="meetup-fb-join-seat" value="1">
            <?php } ?>

            <input type="submit" name="meetup_submit" value="<?php _e( 'Book My Seat', 'meetup' ); ?>">
        </div>

    </form>
    <?php
}