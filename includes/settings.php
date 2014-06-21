<?php

/**
 * Settings Page
 */
class WeDevs_Meetup_Admin {

    private $settings_api;

    function __construct() {

        if ( !class_exists( 'WeDevs_Settings_API' ) ) {
            require_once dirname( dirname( __FILE__ ) ) . '/lib/class.settings-api.php';
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
    }

    function plugin_page() {
        ?>
        <div class="wrap">
            <h2><?php _e( 'Settings', 'meetup' ); ?></h2>

            <?php
            settings_errors();

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
            ?>
        </div>
        <?php
    }
}

new WeDevs_Meetup_Admin();