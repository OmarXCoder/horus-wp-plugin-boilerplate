<?php

namespace Horus\Admin\Contracts;


/**
 * The base admin page abstract class
 * 
 * holdes the common functionality for admin pages
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
abstract class Admin_Page
{
    /**
     * Unique page key to be used as identifier in
     * menus and other contexts
     */
    protected $page_slug;

    /**
     * The title of the page
     * 
     * @var string $page_title
     */
    protected $page_title;

    /**
     * The menu title of the page
     * 
     * @var string $menu_title
     */
    protected $menu_title;

    /**
     * The parent page
     * 
     * @var string $parent_page
     */
    protected $parent_page;

    public function __construct($page_slug = null, $page_title = null, $menu_title = null, $parent_page = null)
    {
        $this->page_slug = $page_slug;
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        $this->parent_page = $parent_page;

        /**
         * Add the page to wordpress menu
         */
        add_action('admin_menu', [$this, 'add_to_menu']);
        /**
         * Enqueue page scripts
         */
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * page_slug getter
     * 
     * @return string $this->page_slug
     */
    public function get_page_slug()
    {
        return $this->page_slug;
    }

    /**
     * page_title getter
     * 
     * @return string $this->page_title
     */
    public function get_page_title()
    {
        return $this->page_title;
    }

    /**
     * menu_title getter
     * 
     * @return string $this->menu_title
     */
    public function get_menu_title()
    {
        if (!$this->menu_title) {
            $this->menu_title = $this->page_title;
        }
        return $this->menu_title;
    }

    /**
     * Parent page slug
     * 
     * @return string $parent_page_slug
     */
    public function get_parent_page_slug()
    {
        if ($this->parent_page != null && $this->parent_page instanceof Admin_Page) {
            return $this->parent_page->get_page_slug();
        } else {
            return 'index.php';
        }
    }

    /**
     * Parent page getter
     * 
     * @return Admin_Page $parent_page
     */
    public function get_parent_page()
    {
        return $this->parent_page;
    }


    public function get_page_hook()
    {
        if ($this->parent_page != null && is_object($this->parent_page)) {
            return get_plugin_page_hook($this->get_page_slug(), $this->parent_page->get_page_slug());
        }

        return null;
    }

    /**
     * Enqueue scripts as styles if any
     * 
     * @package Horus Toolbox
     * @since 1.0.0
     */
    public function enqueue_scripts($hook)
    {
    }

    /**
     * Adds the page to WP menu
     */
    public function add_to_menu()
    {
    }


    /**
     * Renders the page.
     */
    abstract public function display();
}
