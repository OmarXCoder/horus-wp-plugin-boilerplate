<?php

namespace Horus\Core;

defined('ABSPATH') || exit('Forbidden!');

class Settings
{

    /**
     * DB instance
     * @var object $wpdb instance
     */
    private $db;

    /**
     * Options table in the DB
     * @var object $wpdb instance
     */
    private $table;

    /**
     * @var object
     */
    private static $instances = array();

    /**
     * The prefix of wp option name will be stored in database
     * @var string
     */
    public $prefix = 'ox_';

    /**
     * Key => value options array fetched from the DB
     * @var array
     */
    private $options = array();

    /**
     * Settings constructor.
     *
     * @param string|null  $new_prefix
     */
    private function __construct($new_prefix = null)
    {
        global $wpdb;

        $this->db = $wpdb;
        $this->table = $wpdb->options;


        if ($new_prefix) {
            $this->prefix = $new_prefix;
        }

        $this->_load_options();
    }

    /**
     * Get unique instance of Settings
     * Create a new one if it is not created
     *
     * @param string|null $prefix
     * @param array $defaults
     *
     * @return Settings $instances[prefix]
     */
    public static function instance($prefix = null)
    {
        if (!$prefix || !is_string($prefix)) {
            $prefix = 'ox_';
        }

        if (empty(self::$instances[$prefix])) {
            self::$instances[$prefix] = new self($prefix);
        }

        return self::$instances[$prefix];
    }

    /**
     * get the prefix of the current settings instance
     * 
     * @return string $prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get an option
     *
     * @param string
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (strpos($name, 'ox_') === 0) {
            $name = str_replace('ox_', '', $name);
        }

        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        return $default;
    }

    /**
     * Update new value for an option
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $autoload
     * @return Settings $instances[$prefix]
     */
    public function set($key, $value, $autoload = false)
    {
        // update option
        update_option($this->_prefix_option_name($key), $value, $autoload);
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * Remove an option
     *
     * @param string $key
     * @return Settings $instance
     */
    public function remove($key)
    {
        if (array_key_exists($key, $this->options)) {
            unset($this->options[$key]);
            delete_option($this->_prefix_option_name($key));
        }

        return $this;
    }

    /**
     * Deletes all options prefixed with the current 
     * prefix from the the wp_options table
     * 
     * @return Settings $instance
     */
    public function deleteAll()
    {

        foreach ($this->options as $name => $value) {
            delete_option($this->_prefix_option_name($name));
        }

        $this->options = [];

        return $this;
    }
    /**
     * Update all options into database
     * 
     * @return Settings $instnces[$prefix]
     */
    public function update()
    {
        if ($this->options) {
            foreach ($this->options as $key => $value) {
                update_option($this->_prefix_option_name($key), $value);
            }
        }

        return $this;
    }

    public function getAll()
    {
        return $this->options;
    }

    public function isGroup($key)
    {
        return isset($this->options[$key]) && (is_array($this->options[$key]) || is_object($this->options[$key]));
    }

    public function getGroup($key)
    {
        $group = new \stdClass;

        if ($this->isGroup($key)) {
            foreach ($this->options[$key] as $key => $value) {
                $group->{$key} = $value;
            }
        }

        return $group;
    }

    /**
     * @return array
     */
    private function _load_options()
    {
        $options = $this->db->get_results(
            $this->db->prepare("SELECT option_name, option_value FROM {$this->table} WHERE option_name LIKE %s", $this->prefix . '%')
        );

        if ($options && count($options) > 0) {
            foreach ($options as $option) {
                $name                    = str_replace($this->prefix, '', $option->option_name);
                $this->options[$name] = maybe_unserialize($option->option_value);
            }
        }

        return $this->options;
    }

    /**
     * Get the name of field
     *
     * @param string
     * @return string
     */
    private function _prefix_option_name($name)
    {
        return $this->prefix . $name;
    }

    /**
     * Magic function to convert object to string with json format
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->options);
    }
}
