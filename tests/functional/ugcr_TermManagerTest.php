<?php


class ugcr_TermManagerTest extends \WP_UnitTestCase {

	protected $backupGlobals = false;

	/**
	 * @var ugcr_TermManager
	 */
	protected $sut;

	public function setUp() {

		// before
		parent::setUp();

		// your set up methods here
		activate_plugin( 'user-groups-content-restriction/user-groups-content-restriction.php' );
		register_taxonomy( 'user-group', 'user' );
		register_taxonomy( 'post-user-group', 'post' );
		$this->factory->user_group = new WP_UnitTest_Factory_For_Term( $this->factory, 'user-group' );
		$this->factory->post_user_group = new WP_UnitTest_Factory_For_Term( $this->factory, 'post-user-group' );
		$this->sut = new ugcr_TermManager();
	}

	public function tearDown() {

		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should add just two virtual group terms if no user group present
	 */
	public function it_should_add_just_two_virtual_group_terms_if_no_user_group_present() {
		$terms = $this->sut->get_additional_terms();
		$this->assertCount( 0, get_terms( 'user-group' ) );
		$this->assertCount( 0, get_terms( 'post-user-group' ) );

		$this->sut->insert_terms();

		$this->assertCount( count( $terms ), get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );
		$this->assertCount( 0, get_terms( 'user-group', [ 'hide_empty' => false ] ) );
	}

	/**
	 * @test
	 * it should not add a term again if already inserted
	 */
	public function it_should_not_add_a_term_again_if_already_inserted() {
		$_terms = $terms = $this->sut->get_additional_terms();
		wp_insert_term( array_pop( array_keys( $terms ) ), 'post-user-group', array_pop( $_terms ) );
		$this->assertCount( 1, get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );

		$this->sut->insert_terms();

		$this->assertCount( count( $terms ), get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );
	}

	/**
	 * @test
	 * it should not add any term if all already inserted
	 */
	public function it_should_not_add_any_term_if_all_already_inserted() {
		$terms = $this->sut->get_additional_terms();
		foreach ( $terms as $name => $args ) {
			wp_insert_term( $name, 'post-user-group', $args );
		}
		$this->assertCount( count( $terms ), get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );

		$this->sut->insert_terms();

		$this->assertCount( count( $terms ), get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );
	}

	/**
	 * @test
	 * it should remove the terms
	 */
	public function it_should_remove_the_terms() {
		$terms = $this->sut->get_additional_terms();
		foreach ( $terms as $name => $args ) {
			wp_insert_term( $name, 'post-user-group', $args );
		}

		$this->factory->term->create_many( 5, [ 'taxonomy' => 'post-user-group' ] );

		$this->assertCount( count( $terms ) + 5, get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );

		$this->sut->remove_terms();

		$this->assertCount( 0, get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );
	}

	/**
	 * @test
	 * it should add a term to the post taxonomy when added to the user taxonomy
	 */
	public function it_should_add_a_term_to_the_post_taxonomy_when_added_to_the_user_taxonomy() {
		$user_term = $this->factory->user_group->create_and_get();

		$this->sut->create_term( $user_term->term_id, $user_term->term_taxonomy_id );

		$post_terms = get_terms( 'post-user-group', [ 'hide_empty' => false ] );
		$user_terms = get_terms( 'user-group', [ 'hide_empty' => false ] );
		$this->assertCount( 1, $user_terms );
		$this->assertCount( 1, $post_terms );
		$post_term = $post_terms[0];
		$this->assertEquals( $user_term->slug, $post_term->slug );
		$this->assertEquals( $user_term->name . ' ' . __( 'group', 'ugcr' ), $post_term->name );
	}

	/**
	 * @test
	 * it should remove the term from the post taxonomy when removed from the user group taxonomy
	 */
	public function it_should_remove_the_term_from_the_post_taxonomy_when_removed_from_the_user_group_taxonomy() {
		$user_term = $this->factory->user_group->create_and_get();
		$pugt      = $this->factory->post_user_group->create_and_get( array(
			'slug' => $user_term->slug,
			'name' => $user_term->name . ' ' . __( 'group', 'ugcr' )
		) );
		$this->assertCount( 1, get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );

		$this->sut->delete_term( $pugt->term_id, $pugt->term_taxonomy_id, $user_term );

		$this->assertCount( 0, get_terms( 'post-user-group', [ 'hide_empty' => false ] ) );
	}
}
