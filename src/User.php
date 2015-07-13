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
		// TODO: Implement get_user_slugs() method.
	}
}