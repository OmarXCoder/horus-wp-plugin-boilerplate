<?php

namespace Horus\Core\Models;

use Horus\Core\Traits\AccessDB;
use Horus\Core\Contracts\Meta_Object;

abstract class Post_Type_Model
{
    use AccessDB;

    public $id = null;

    /**
     * Meta prefix
     * Should be overwritten in sub classess
     * @var string
     */
    protected $prefix = 'ox_';

    /**
     * Product meta data fetched from the DB
     * @var array
     */
    protected $meta;

    /**
     * Product default meta
     * @var array
     */
    protected $default_meta = [];

    protected static $type = 'post';

    public $title = '';
    public $content = '';
    public $post_type = '';
    public $excerpt = '';
    public $permalink = '';

    private function __construct()
    {
    }

    public static function instance($post = null)
    {
        $self = new static();

        if ($post instanceof \WP_Post && is_int($post->ID) && $post->post_type === static::$type) {
            return $self->_fill_from_post_data($post);
        } elseif (intval($post) > 0) {
            if (get_post_status($post) && get_post_type($post) === static::$type) {
                return $self->_fill_from_post_data(get_post($post));
            }
        }

        return null;
    }

    public function getMeta($key = null)
    {
        if (is_null($this->meta)) {
            $this->meta = $this->_load_meta();
        }

        if (!$key) {
            return $this->meta;
        }

        if ($key && (0 == strpos($key, $this->prefix) ||  0 == strpos($key, 'ox_meta_'))) {
            $key = preg_replace("/ox_meta_|{$this->prefix}/", '', $key);
        }

        if (isset($this->meta[$key])) {
            return $this->meta[$key];
        }

        return null;
    }

    public function getMetaObj()
    {
        if (is_null($this->meta)) {
            $this->meta = $this->_load_meta();
        }

        return new Meta_Object($this->meta);
    }

    /**
     * Gets the taxonomy terms of a product
     * 
     * @param string $taxonomy name
     * @return array $terms
     */
    public function getTerms($taxonomy)
    {
        $terms = get_the_terms($this->id, $taxonomy);

        return $terms ?: [];
    }

    public function hasOwnFeaturedImg()
    {
        return has_post_thumbnail($this->id);
    }

    public static function getAsList()
    {
        $posts = static::db()->get_results(
            static::db()->prepare("SELECT ID, post_title FROM " . static::db()->posts . " WHERE post_type = %s", static::$type)
        );

        $list = array_map(function ($post) {
            $obj = new \stdClass;
            $obj->id = $post->ID;
            $obj->title = $post->post_title;

            return $obj;
        }, $posts);

        return $list;
    }

    public static function query($args = [])
    {
        $defaults = [
            'post_type' => static::$type,
            'post_status' => 'publish',
            'posts_per_page' => 10,
        ];

        $args = wp_parse_args($args, $defaults);

        return static::createCollection(get_posts($args));
    }

    public static function random()
    {
        $args = [
            'post_type' => static::$type,
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'orderby' => 'rand',
            'order' => 'DESC',
        ];

        $posts = get_posts($args);

        if (isset($posts[0])) {
            return static::instance($posts[0]);
        }

        return null;
    }

    public static function createCollection($posts)
    {
        if (!is_array($posts)) {
            return [];
        }

        $collection = [];
        foreach ($posts as $post) {
            $model = new static;
            $model->_fill_from_post_data($post);
            $collection[] = $model;
        }

        return $collection;
    }

    public function _load_meta()
    {
        $rows = static::db()->get_results(
            static::db()->prepare("SELECT * FROM " . static::db()->postmeta . " WHERE post_id = %s", $this->id),
            ARRAY_A
        );

        if ($rows && count($rows) > 0) {
            return $this->_normalize_meta($rows);
        }

        return null;
    }

    protected function _normalize_meta($rows)
    {
        $meta = [];
        foreach ($rows as $i => $row) {
            $key = $row['meta_key'];
            if (0 == strpos($key, $this->prefix) || 0 == strpos($key, 'ox_meta_')) {
                $key = preg_replace("/ox_meta_|{$this->prefix}/", '', $key);
            }
            $meta[$key] = maybe_unserialize($row['meta_value']);
        }

        return array_merge($this->default_meta, $meta);
    }

    private function _fill_from_post_data($post)
    {
        if (!$post instanceof \WP_Post) {
            return null;
        }

        $this->id        = $post->ID;
        $this->title     = $post->post_title;
        $this->post_type = $post->post_type;
        $this->content   = $post->post_content;
        $this->excerpt   = $post->post_excerpt;
        $this->permalink = get_the_permalink($post->ID);

        return $this;
    }
}
