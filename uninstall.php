<?php
// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

function ugcr_delete_terms() {
	$taxonomy = 'post-user-group';
	$terms    = get_terms( $taxonomy, array( 'hide_empty' => false ) );
	foreach ( $terms as $term ) {
		wp_delete_term( $term->term_id, $taxonomy );
	}
}

if ( is_multisite() ) {
	$sites = wp_get_sites();
	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		ugcr_delete_terms();
	}
} else {
	ugcr_delete_terms();
}
