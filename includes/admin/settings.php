<?php

/**
 * Settings Page
 */
class WeDevs_Meetup_Admin {

    private $settings_api;

    function __construct() {

        if ( ! class_exists( 'WeDevs_Settings_API' ) ) {
            require_once MEETUP_PATH . '/lib/class.settings-api.php';
        }

        $this->settings_api = new WeDevs_Settings_API();

        add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'admin_init', array($this, 'admin_init') );
    }

    function admin_init() {
        $sections = array(
            array(
                'id' => 'meetup_facebook',
                'title' => __( 'Facebook API', 'meetup' )
            ),
        );

        $settings_fields = array(
            'meetup_facebook' => array(
                array(
                    'name' => 'app_id',
                    'label' => __( 'Facebook App ID ', 'meetup' ),
                    'desc' => __( 'Enter the app id from your facebook application', 'meetup' ),
                    'type' => 'text',
                ),
            )
        );

        //set the settings
        $this->settings_api->set_sections( $sections );
        $this->settings_api->set_fields( $settings_fields );

        //initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Register the admin menu
     *
     * @since 1.0
     */
    function admin_menu() {
        add_submenu_page( 'edit.php?post_type=meetup', __( 'Settings', 'meetup' ), __( 'Settings', 'meetup' ), 'manage_options', 'meetup-settings', array($this, 'plugin_page') );
        add_submenu_page( 'edit.php?post_type=meetup', __( 'Attendies', 'meetup' ), __( 'Attendies', 'meetup' ), 'manage_options', 'meetup-attendies', array($this, 'attendies_page') );

        remove_submenu_page( 'edit.php?post_type=meetup', 'meetup-attendies' );
    }

    function plugin_page() {
        ?>
        <div class="wrap">
            <?php
            settings_errors();

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
            ?>
        </div>
        <?php
    }

    function attendies_page() {
        $meetup_id = isset( $_GET['meetup_id'] ) ? intval( $_GET['meetup_id'] ) : 0;

        if ( ! $meetup_id ) {
            wp_die( __( 'No meetup has been found!', 'meetup' ) );
        }

        $meetup = get_post( $meetup_id );

        if ( ! $meetup || $meetup->post_type != 'meetup' ) {
            return;
        }

        include dirname( __FILE__ ) . '/attendee-list.php';
    }
}

new WeDevs_Meetup_Admin();