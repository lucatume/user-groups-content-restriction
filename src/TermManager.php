<?php

class ugcr_TermManager
{
    
    public static function instance()
    {
        return new self;
    }
    
    public function remove_terms()
    {
        $terms = $this->get_terms();
        
        foreach ($terms as $name => $args) {
            if (!($term = get_term_by('slug', $args['slug'], ugcr_Plugin::$taxonomy_name, ARRAY_A))) {
                continue;
            }
            wp_delete_term($term['term_id'], ugcr_Plugin::$taxonomy_name);
        }
    }
    
    public function insert_terms()
    {
        $terms = $this->get_terms();
        
        foreach ($terms as $name => $args) {
            if (get_term_by('slug', $args['slug'], ugcr_Plugin::$taxonomy_name)) {
                continue;
            }
            wp_insert_term($name, ugcr_Plugin::$taxonomy_name, $args);
        }
    }
    
    /**
     * @return array
     */
    public function get_terms()
    {
        return array(
            __('Logged-in', 'ugcr') => array(
                'slug' => 'logged-in'
            ) ,
            __('Visitor', 'ugcr') => array(
                'slug' => 'visitor'
            )
        );
    }
}
