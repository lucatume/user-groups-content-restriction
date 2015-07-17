<?php


class ugcr_UserTest extends \WP_UnitTestCase {

	protected $backupGlobals = false;

	/**
	 * @var ugcr_User
	 */
	protected $sut;

	public function setUp() {

		// before
		parent::setUp();

		// your set up methods here
		activate_plugin( 'tad-restricted-content/tad-restricted-content.php' );
		activate_plugin( 'user-groups-content-restriction/user-groups-content-restriction.php' );
		$this->sut = new ugcr_User();
		$this->sut->set_taxonomy_name( 'user-group' );
	}

	private function _user_is_not_logged_in() {
		global $_user_logged_in;
		$_user_logged_in = false;
	}

	private function _user_is_logged_in() {
		global $_user_logged_in;
		$_user_logged_in = true;
	}

	public function tearDown() {


		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should return the visitor slug for not logged in users
	 */
	public function it_should_return_the_visitor_slug_for_not_logged_in_users() {
		$this->_user_is_not_logged_in();

		$slugs = $this->sut->get_user_slugs();

		$this->assertCount( 1, $slugs );
		$this->assertEquals( 'visitor', $slugs[0] );
	}

	/**
	 * @test
	 * it should return the logged-in and the visitor slugs for logged in users
	 */
	public function it_should_return_the_logged_in_and_the_visitor_slugs_for_logged_in_users() {
		$this->_user_is_logged_in();

		$slugs = $this->sut->get_user_slugs();

		$this->assertCount( 2, $slugs );
		$this->assertContains( 'visitor', $slugs );
		$this->assertContains( 'logged-in', $slugs );
	}

	/**
	 * @test
	 * it should return slugs for user in one user group
	 */
	public function it_should_return_slugs_for_user_in_one_user_group() {
		$user = $this->factory->user->create_and_get();
		tests_set_current_user( $user );
		$this->_user_is_logged_in();
		wp_set_object_terms( $user->ID, [ 'group_one' ], 'user-group' );

		$slugs = $this->sut->get_user_slugs();

		$this->assertCount( 3, $slugs );
		$this->assertContains( 'visitor', $slugs );
		$this->assertContains( 'logged-in', $slugs );
		$this->assertContains( 'group_one', $slugs );
	}

	/**
	 * @test
	 * it should return all of a user slugs
	 */
	public function it_should_return_all_of_a_user_slugs() {
		$user = $this->factory->user->create_and_get();
		tests_set_current_user( $user );
		$this->_user_is_logged_in();
		wp_set_object_terms( $user->ID, [ 'group_one', 'group_two', 'group_three' ], 'user-group' );

		$slugs = $this->sut->get_user_slugs();

		$this->assertCount( 5, $slugs );
		$this->assertContains( 'visitor', $slugs );
		$this->assertContains( 'logged-in', $slugs );
		$this->assertContains( 'group_one', $slugs );
		$this->assertContains( 'group_two', $slugs );
		$this->assertContains( 'group_three', $slugs );
	}

	/**
	 * @test
	 * it should return logged-in and visitor user slugs if not valid user
	 */
	public function it_should_return_logged_in_and_visitor_user_slugs_if_not_valid_user() {
		tests_set_current_user( 'not-a-user' );

		$slugs = $this->sut->get_user_slugs();

		$this->assertCount( 2, $slugs );
		$this->assertContains( 'visitor', $slugs );
		$this->assertContains( 'logged-in', $slugs );
	}
}
