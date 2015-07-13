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

function ugcr_autoload( $class ) {
	if ( strpos( $class, 'ugcr_' ) === 0 ) {
		require 'src/' . str_replace( 'ugcr_', '', $class ) . '.php';
	}
}

spl_autoload_register( 'ugcr_autoload' );

function ugcr_load() {
	if ( ! ( class_exists( 'trc_Core_Plugin' ) && class_exists( 'CMB2' ) ) ) {
		return;
	}
	ugcr_Plugin::instance()
	           ->hooks();
}

add_action( 'plugins_loaded', 'ugcr_load' );