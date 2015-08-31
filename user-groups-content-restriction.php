<?php

/*
Plugin Name: User Groups Content Restriction
Version: 0.1-alpha
Description: An extension of theAverageDev Restricted Content plugin to restrict content on a user group base.
Author: Luca Tumedei
Author URI: http://theaveragedev.com
Plugin URI: http://theaveragedev.com
Text Domain: ugcr
Domain Path: /languages
*/

function _ugcr_autoload( $class ) {
	if ( strpos( $class, 'ugcr_' ) === 0 ) {
		require 'src/' . str_replace( 'ugcr_', '', $class ) . '.php';
	}
}

spl_autoload_register( '_ugcr_autoload' );

function ugcr_load() {
	if ( ! ( class_exists( 'trc_Core_Plugin' ) ) ) {
		return;
	}

	ugcr_Plugin::instance()->root_dir = dirname( __FILE__ );
	ugcr_Plugin::instance()->hooks();
}

add_action( 'plugins_loaded', 'ugcr_load' );

register_activation_hook( __FILE__, array( 'ugcr_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ugcr_Plugin', 'deactivate' ) );
