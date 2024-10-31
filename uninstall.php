<?php 

// if uninstall.php is not called by WordPress, die
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}
 
$options_name = 'rmm_settings';
delete_option( $options_name );

 
// drop a custom database table
/*global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");*/