<?php


class ugcr_Plugin {

	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * @var string The name of the taxonomy the User Groups plugin uses to relate users to groups.
	 */
	public static $taxonomy_name = 'user-group';

	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function activate() {
		ugcr_TermManager::instance()->insert_terms();
	}

	public static function deactivate() {
		ugcr_TermManager::instance()->remove_terms();
	}

	public function hooks() {
		add_action( 'init', array( $this, 'localization_init' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );

		trc_Core_Plugin::instance()->post_types->add_restricted_post_type( $this->get_restricted_post_types() );
		trc_Core_Plugin::instance()->user->add_user_slug_provider( $this->taxonomy_name, ugcr_User::instance( $this->taxonomy_name ) );
		trc_Core_Plugin::instance()->taxonomies->add( $this->taxonomy_name );

		add_action( 'cmb2_init', array( ugcr_TermMetabox::instance(), 'add_metabox' ) );

		return $this;
	}

	public function localization_init() {
		$path = $this->root_dir . '/languages/';
		load_plugin_textdomain( 'urcr', false, $path );
	}

	public function register_taxonomy() {
		register_taxonomy( $this->taxonomy_name, $this->get_restricted_post_types(), array( 'show_ui' => false ) );
	}

	public function get_restricted_post_types() {
		return apply_filters( 'ugcr_restricted_post_types', array( 'post', 'page' ) );
	}
}