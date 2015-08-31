<?php


class ugcr_TermManager {

	public static function instance() {
		return new self;
	}

	public function remove_terms() {
		$terms = get_terms( ugcr_Plugin::$post_taxonomy_name, array( 'hide_empty' => false ) );

		if ( is_wp_error( $terms ) ) {
			return;
		}

		foreach ( $terms as $term ) {
			wp_delete_term( $term->term_id, ugcr_Plugin::$post_taxonomy_name );
		}
	}

	public function insert_terms() {
		register_taxonomy( ugcr_Plugin::$post_taxonomy_name, null );
		$terms = $this->get_additional_terms();

		foreach ( $terms as $name => $args ) {
			if ( get_term_by( 'slug', $args['slug'], ugcr_Plugin::$post_taxonomy_name ) ) {
				continue;
			}
			$group_suffix = apply_filters( 'ugcr_group_suffix', __( 'group', 'ugcr' ) );
			wp_insert_term( $name . ' ' . $group_suffix, ugcr_Plugin::$post_taxonomy_name, $args );
		}
	}

	public function create_term( $term_id, $tt_id ) {
		$term = get_term( $term_id, ugcr_Plugin::$taxonomy_name );

		$args         = array(
			'slug'        => $term->slug,
			'description' => $term->description
		);
		$group_suffix = apply_filters( 'ugcr_group_suffix', __( 'group', 'ugcr' ) );
		$exit         = wp_insert_term( $term->name . ' ' . $group_suffix, ugcr_Plugin::$post_taxonomy_name, $args );
	}

	public function delete_term( $term_id, $tt_id, $deleted_term ) {
		$to_delete = get_term_by( 'slug', $deleted_term->slug, ugcr_Plugin::$post_taxonomy_name );
		if ( is_wp_error( $to_delete ) ) {
			return;
		}
		wp_delete_term( $to_delete->term_id, ugcr_Plugin::$post_taxonomy_name );
	}

	/**
	 * @return array
	 */
	public function get_additional_terms() {
		return array(
			__( 'Logged-in users', 'ugcr' ) => array(
				'slug' => 'logged-in'
			),
			__( 'Visitors', 'ugcr' )        => array(
				'slug' => 'visitor'
			)
		);
	}
}
