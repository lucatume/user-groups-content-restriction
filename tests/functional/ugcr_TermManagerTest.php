<?php
class ugcr_TermManagerTest extends \WP_UnitTestCase
{
    
    protected $backupGlobals = false;
    
    /**
     * @var ugcr_TermManager
     */
    protected $sut;
    
    public function setUp()
    {
        
        // before
        parent::setUp();
        
        // your set up methods here
        activate_plugin('user-groups-content-restriction/user-groups-content-restriction.php');
        register_taxonomy('user-group', 'user');
        $this->sut = new ugcr_TermManager();
    }
    
    public function tearDown()
    {
        
        // your tear down methods here
        
        // then
        parent::tearDown();
    }
    
    /**
     * @test
     * it should add all the terms on activation if none present
     */
    public function it_should_add_all_the_terms_on_activation_if_none_present()
    {
        $terms = $this->sut->get_terms();
        $this->assertCount(0, get_terms('user-group'));
        
        $this->sut->insert_terms();
        
        $this->assertCount(count($terms) , get_terms('user-group', ['hide_empty' => false]));
    }
    
    /**
     * @test
     * it should not add a term again if already inserted
     */
    public function it_should_not_add_a_term_again_if_already_inserted()
    {
        $_terms = $terms = $this->sut->get_terms();
        wp_insert_term(array_pop(array_keys($terms)) , 'user-group', array_pop($_terms));
        $this->assertCount(1, get_terms('user-group', ['hide_empty' => false]));
        
        $this->sut->insert_terms();
        
        $this->assertCount(count($terms) , get_terms('user-group', ['hide_empty' => false]));
    }
    
    /**
     * @test
     * it should not add any term if all already inserted
     */
    public function it_should_not_add_any_term_if_all_already_inserted()
    {
        $terms = $this->sut->get_terms();
        foreach ($terms as $name => $args) {
            wp_insert_term($name, 'user-group', $args);
        }
        $this->assertCount(count($terms) , get_terms('user-group', ['hide_empty' => false]));
        
        $this->sut->insert_terms();
        
        $this->assertCount(count($terms) , get_terms('user-group', ['hide_empty' => false]));
    }
    
    /**
     * @test
     * it should remove the terms
     */
    public function it_should_remove_the_terms()
    {
        $terms = $this->sut->get_terms();
        foreach ($terms as $name => $args) {
            wp_insert_term($name, 'user-group', $args);
        }
        $this->assertCount(count($terms) , get_terms('user-group', ['hide_empty' => false]));
        
        $this->sut->remove_terms();
        
        $this->assertCount(0, get_terms('user-group', ['hide_empty' => false]));
    }
    
    /**
     * @test
     * it should not remove terms not added by the plugin
     */
    public function it_should_not_remove_terms_not_added_by_the_plugin()
    {
        $terms = ['One' => ['slug' => 'one'], 'Two' => ['slug' => 'two']];
        foreach ($terms as $name => $args) {
            wp_insert_term($name, 'user-group', $args);
        }
        $this->assertCount(count($terms) , get_terms('user-group', ['hide_empty' => false]));
        
        $this->sut->insert_terms();
        $this->sut->remove_terms();
        
        $_terms = get_terms('user-group', ['hide_empty' => false]);
        $this->assertCount(2, $_terms);
        $names = wp_list_pluck($_terms, 'name');
        $this->assertContains('One', $names);
        $this->assertContains('Two', $names);
    }
    
    /**
     * @test
     * it should not remove any term if terms were never added
     */
    public function it_should_not_remove_any_term_if_terms_were_never_added()
    {
        $terms = ['One' => ['slug' => 'one'], 'Two' => ['slug' => 'two']];
        foreach ($terms as $name => $args) {
            wp_insert_term($name, 'user-group', $args);
        }
        $this->assertCount(count($terms) , get_terms('user-group', ['hide_empty' => false]));
        
        $this->sut->remove_terms();
        
        $_terms = get_terms('user-group', ['hide_empty' => false]);
        $this->assertCount(2, $_terms);
        $names = wp_list_pluck($_terms, 'name');
        $this->assertContains('One', $names);
        $this->assertContains('Two', $names);
    }
}
