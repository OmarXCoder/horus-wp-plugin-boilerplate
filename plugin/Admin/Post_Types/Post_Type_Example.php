<?php

namespace Horus\Admin\Post_Types;

use Horus\Admin\Contracts\Post_Type;

/**
 * Register Example post type
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */

class Post_Type_Example extends Post_Type
{
    public static function register()
    {
        $labels = array(
            'name'                  => _x('Examples', 'Post type general name', 'horus'),
            'singular_name'         => _x('Example', 'Post type singular name', 'horus'),
            'menu_name'             => _x('Examples', 'Admin Menu text', 'horus'),
            'name_admin_bar'        => _x('Example', 'Add New on Toolbar', 'horus'),
            'add_new'               => __('Add New', 'horus'),
            'add_new_item'          => __('Add New Example', 'horus'),
            'new_item'              => __('New Example', 'horus'),
            'edit_item'             => __('Edit Example', 'horus'),
            'view_item'             => __('View Example', 'horus'),
            'all_items'             => __('All Examples', 'horus'),
            'search_items'          => __('Search Examples', 'horus'),
            'parent_item_colon'     => __('Parent Examples:', 'horus'),
            'not_found'             => __('No examples found.', 'horus'),
            'not_found_in_trash'    => __('No examples found in Trash.', 'horus'),
            'featured_image'        => _x('Example featured image', 'Overrides the “Featured Image” phrase for this post type', 'horus'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the “Set featured image” phrase for this post type', 'horus'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the “Remove featured image” phrase for this post type', 'horus'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the “Use as featured image” phrase for this post type', 'horus'),
            'archives'              => _x('Example archives', 'The post type archive label used in nav menus. Default “Post Archives”', 'horus'),
            'insert_into_item'      => _x('Insert into example', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post)', 'horus'),
            'uploaded_to_this_item' => _x('Uploaded to this example', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post)', 'horus'),
            'filter_items_list'     => _x('Filter examples list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”', 'horus'),
            'items_list_navigation' => _x('Examples list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”', 'horus'),
            'items_list'            => _x('Examples list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”', 'horus')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'example'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_icon'          => OX_PLUGIN_URL . 'images/beach_access-24px.svg',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_position'     => 4,
            'exclude_from_search' => true
        );

        register_post_type('example-post-type', $args);
    }
}
