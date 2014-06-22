<?php
/*
Plugin Name: Meetup
Plugin URI: http://tareq.wedevs.com/
Description: Create meetup events
Version: 0.1
Author: Tareq Hasan
Author URI: http://tareq.wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2014 Tareq Hasan (email: tareq@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WeDevs_Meetup class
 *
 * @class WeDevs_Meetup The class that holds the entire WeDevs_Meetup plugin
 */
class WeDevs_Meetup {

    /**
     * Constructor for the WeDevs_Meetup class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {

        $this->file_includes();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
        add_action( 'init', array( $this, 'register_post_type' ) );

        add_filter( 'template_include', array( $this, 'template_loader' ) );

        // Loads frontend scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Initializes the WeDevs_Meetup() class
     *
     * Checks for an existing WeDevs_Meetup() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WeDevs_Meetup();
        }

        return $instance;
    }

    /**
     * Activation function
     *
     * Creates our table when installing the plugin
     */
    public function activate() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'meetup_users';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) DEFAULT NULL,
            `meetup_id` bigint(20) DEFAULT NULL,
            `seat` int(11) DEFAULT NULL,
            `status` tinyint(1) DEFAULT '1',
            `created` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `meetup_id` (`meetup_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'meetup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Enqueue admin scripts
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts() {

        // don't load the scripts in other pages
        if ( ! is_singular( 'meetup' ) && ! is_post_type_archive( 'meetup' ) ) {
            return;
        }

        $asset_url = plugins_url( 'assets/', __FILE__ );

        /**
         * All styles goes here
         */
        wp_enqueue_style( 'fontawesome', $asset_url . 'css/font-awesome.min.css', false, date( 'Ymd' ) );
        wp_enqueue_style( 'meetup-styles', $asset_url . 'css/style.css', false, date( 'Ymd' ) );

        /**
         * All scripts goes here
         */
        wp_enqueue_script( 'meetup-scripts', $asset_url . 'js/script.js', array( 'jquery' ), false, true );
        wp_localize_script( 'meetup-scripts', 'meetup', array(
            'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
            'nonce' => wp_create_nonce( 'meetup-nonce' )
        ) );
    }

    /**
     * Includes required files
     *
     * @return void
     */
    function file_includes() {

        if ( is_admin() ) {

            if ( ! function_exists( 'CMB_Field' ) ) {
                define( 'CMB_URL', $this->plugin_url() . '/lib/Custom-Meta-Boxes' );

                require_once dirname( __FILE__ ) . '/lib/Custom-Meta-Boxes/custom-meta-boxes.php';
            }

            require_once dirname( __FILE__ ) . '/includes/metadata.php';
            require_once dirname( __FILE__ ) . '/includes/settings.php';
        }

        require_once dirname( __FILE__ ) . '/includes/functions.php';
        require_once dirname( __FILE__ ) . '/includes/booking.php';
        require_once dirname( __FILE__ ) . '/includes/rewrites.php';

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            require_once dirname( __FILE__ ) . '/includes/ajax.php';
        }
    }

    /**
     * Register Custom Post Type
     *
     * @return void
     */
    function register_post_type() {

        $labels = array(
            'name'                => _x( 'Meetups', 'Post Type General Name', 'meetup' ),
            'singular_name'       => _x( 'Meetup', 'Post Type Singular Name', 'meetup' ),
            'menu_name'           => __( 'Meetups', 'meetup' ),
            'parent_item_colon'   => __( 'Parent Meetup:', 'meetup' ),
            'all_items'           => __( 'All Meetups', 'meetup' ),
            'view_item'           => __( 'View Meetup', 'meetup' ),
            'add_new_item'        => __( 'Create New Meetup', 'meetup' ),
            'add_new'             => __( 'Create New', 'meetup' ),
            'edit_item'           => __( 'Edit Meetup', 'meetup' ),
            'update_item'         => __( 'Update Meetup', 'meetup' ),
            'search_items'        => __( 'Search Meetup', 'meetup' ),
            'not_found'           => __( 'No meetup found', 'meetup' ),
            'not_found_in_trash'  => __( 'No meetup found in Trash', 'meetup' ),
        );

        $args = array(
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail', 'editor' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-format-chat',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );

        register_post_type( 'meetup', $args );
    }


    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return apply_filters( 'meetup_template_path', 'meetup/' );
    }

    /**
     * Load a template.
     *
     * Handles template usage so that we can use our own templates instead of the themes.
     *
     * Templates are in the 'templates' folder. meetup looks for theme
     * overrides in /theme/meetup/ by default
     *
     * For beginners, it also looks for a meetup.php template first. If the user adds
     * this to the theme (containing a meetup() inside) this will be used for all
     * meetup templates.
     *
     * @param mixed   $template
     * @return string
     */
    public function template_loader( $template ) {
        $find = array();
        $file = '';

        if ( is_single() && get_post_type() == 'meetup' ) {

            $file   = 'single-meetup.php';
            $find[] = $file;
            $find[] = $this->template_path() . $file;

        } elseif ( is_post_type_archive( 'meetup' ) ) {

            $file   = 'archive-meetup.php';
            $find[] = $file;
            $find[] = $this->template_path() . $file;
        }

        if ( $file ) {
            $template = locate_template( $find );

            if ( ! $template ) {
                $template = $this->plugin_path() . '/templates/' . $file;
            }
        }

        return $template;
    }

} // WeDevs_Meetup

/**
 * Returns the main instance of meetup to prevent the need to use globals.
 *
 * @return \WeDevs_Meetup
 */
function meetup() {
    return WeDevs_Meetup::init();
}

// initialize the plugin
meetup();
