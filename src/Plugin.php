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

	/**
	 * @var string The name of the the parallel taxonomy the plugin uses to relate posts to user groups.
	 */
	public static $post_taxonomy_name = 'post-user-group';

	/**
	 * @var string The absolute path to the plugin root folder.
	 */
	public $root_dir;

	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function activate() {
		ugcr_TermManager::instance()->remove_terms();
		ugcr_TermManager::instance()->insert_terms();
	}

	public static function deactivate() {
	}

	public function hooks() {
		add_action( 'init', array( $this, 'localization_init' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );

		trc_Core_Plugin::instance()->post_types->add_restricted_post_type( $this->get_restricted_post_types() );
		trc_Core_Plugin::instance()->user->add_user_slug_provider( self::$post_taxonomy_name, ugcr_User::instance( self::$post_taxonomy_name ) );
		trc_Core_Plugin::instance()->taxonomies->add( self::$post_taxonomy_name );

		add_action( 'cmb2_init', array( ugcr_TermMetabox::instance(), 'add_metabox' ) );

		add_action( 'create_' . self::$taxonomy_name, array( ugcr_TermManager::instance(), 'create_term' ), 10, 2 );
		add_action( 'delete_' . self::$taxonomy_name, array( ugcr_TermManager::instance(), 'delete_term', 10, 3 ) );

		return $this;
	}

	public function localization_init() {
		$path = $this->root_dir . '/languages/';
		load_plugin_textdomain( 'ugcr', false, $path );
	}

	public function register_taxonomy() {
		register_taxonomy( self::$post_taxonomy_name, $this->get_restricted_post_types(), array( 'show_ui' => false ) );
	}

	public function get_restricted_post_types() {
		return apply_filters( 'ugcr_restricted_post_types', array( 'post', 'page' ) );
	}
}
