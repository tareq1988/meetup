<?php

/**
 * Rewrite rules for meetup
 *
 * @package meetup
 * @author Tareq Hasan
 */
class WeDevs_Meetup_Rewrites {

    function __construct() {
        add_action( 'init', array($this, 'rewrite_rule' ) );
        add_filter( 'query_vars', array($this, 'register_query_var' ) );
    }

    /**
     * Register rewrite rules
     *
     * @return void
     */
    function rewrite_rule() {
        add_rewrite_rule( 'meetup/([^/]+)/speakers', 'index.php?meetup=$matches[1]&speakers=yes', 'top' );
        add_rewrite_rule( 'meetup/([^/]+)/sponsors', 'index.php?meetup=$matches[1]&sponsors=yes', 'top' );
        add_rewrite_rule( 'meetup/([^/]+)/attendies', 'index.php?meetup=$matches[1]&attendies=yes', 'top' );
        add_rewrite_rule( 'meetup/([^/]+)/gallery', 'index.php?meetup=$matches[1]&gallery=yes', 'top' );
    }

    /**
     * Register query vars
     *
     * @param  array $vars
     * @return array
     */
    function register_query_var( $vars ) {
        $vars[] = 'speakers';
        $vars[] = 'sponsors';
        $vars[] = 'attendies';
        $vars[] = 'gallery';

        return $vars;
    }
}

new WeDevs_Meetup_Rewrites();