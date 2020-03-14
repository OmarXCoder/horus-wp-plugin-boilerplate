<?php

namespace Horus\Admin\Taxonomies;

use Horus\Admin\Contracts\Taxonomy;

/**
 * Register taxonomy examples
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Taxonomy_Example extends Taxonomy
{
    public function enqueue_scripts($hook)
    {
        if (($hook === 'edit-tags.php' || $hook === 'term.php') && isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] === 'example') {

            // enqueue custom scripts
        }
    }

    /**
     * Registers the taxonomy
     * 
     * @param array $post_types this taxonomy is for
     */
    public function register($post_types = [])
    {
        $labels = array(
            'name'                       => _x('Examples', 'taxonomy general name', 'horus'),
            'singular_name'              => _x('Example', 'taxonomy singular name', 'horus'),
            'search_items'               => __('Search Examples', 'horus'),
            'popular_items'              => __('Popular Examples', 'horus'),
            'all_items'                  => __('All Examples', 'horus'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Example', 'horus'),
            'update_item'                => __('Update Example', 'horus'),
            'add_new_item'               => __('Add New Example', 'horus'),
            'new_item_name'              => __('New Example Name', 'horus'),
            'separate_items_with_commas' => __('Separate examples with commas', 'horus'),
            'add_or_remove_items'        => __('Add or remove examples', 'horus'),
            'choose_from_most_used'      => __('Choose from the most used examples', 'horus'),
            'not_found'                  => __('No examples found.', 'horus'),
            'menu_name'                  => __('Examples', 'horus'),
            'view_item'                  => __('View Example', 'horus'),
            'back_to_items'              => __('Back To Examples', 'horus'),
        );

        $args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'example'),
            'show_in_quick_edit'    => true, // false to hide the taxonomy from post's quick edit
            'meta_box_cb'           => false // false to hide the taxonomy's metabox form post edit page
        );

        if (empty($post_types)) {
            $post_types = ['post-type'];
        }

        register_taxonomy('example', $post_types, $args);
    }
}
