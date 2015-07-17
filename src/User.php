<?php


class ugcr_User implements trc_Public_UserSlugProviderInterface {

	/**
	 * @var string
	 */
	protected $taxonomy_name;

	/**
	 * @param string $taxonomy_name
	 *
	 * @return trc_Public_UserSlugProviderInterface
	 */
	public static function instance( $taxonomy_name ) {
		$instance                = new self;
		$instance->taxonomy_name = $taxonomy_name;

		return $instance;
	}

	/**
	 * @return string The name of the taxonomy the class will provide user slugs for.
	 */
	public function get_taxonomy_name() {
		return $this->taxonomy_name;
	}

	/**
	 * @return string[] An array of term slugs the user can access for the taxonomy.
	 */
	public function get_user_slugs() {
		$slugs = array( 'visitor' );
		if ( is_user_logged_in() ) {
			$slugs[] = 'logged-in';
			$_slugs  = wp_get_object_terms( get_current_user_id(), $this->taxonomy_name );
			$_slugs  = wp_list_pluck( $_slugs, 'slug' );
			$slugs   = empty( $_slugs ) ? $slugs : array_merge( $slugs, $_slugs );
		}

		return $slugs;
	}

	public function set_taxonomy_name( $taxonomy_name ) {
		$this->taxonomy_name = $taxonomy_name;
	}
}