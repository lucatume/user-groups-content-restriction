<?php


class ugcr_TermMetabox {

	public static function instance() {
		return new self;
	}

	public function add_metabox() {
		$prefix = '_ugcr_';

		$tax_box = new_cmb2_box( array(
			'id'           => $prefix . 'user_group_taxonomy',
			'title'        => __( 'User Group Content Restriction', 'ugcr' ),
			'object_types' => ugcr_Plugin::instance()
			                             ->get_restricted_post_types(),
			'context'      => 'side',
			'priority'     => 'high',
			'show_names'   => true,
			'cmb_styles'   => false
		) );
		$tax_box->add_field( array(
			'name'     => __( 'User Groups', 'ugcr' ),
			'desc'     => __( 'Select the user groups that will be able to access the content', 'ugcr' ),
			'id'       => $prefix . 'taxonomy_terms',
			'taxonomy' => ugcr_Plugin::instance()->taxonomy_name,
			'type'     => 'taxonomy_multicheck',
			'default'  => ugcr_Terms::instance()
			                              ->get_default_role_slug()
		) );
	}
}