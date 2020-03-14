<?php

namespace Horus\Core;

/**
 * Class responsible for creating/droping db schema of the plugin
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class DB_Schema
{
    private $tables;

    private static $instance;

    /**
     * Private Constructor
     * 
     * @global $wpdb
     */
    private function __construct()
    {
        global $wpdb;
        $this->tables = [
            'example_table' => $wpdb->prefix . 'ox_example',
        ];
    }

    /**
     * Singleton instance 
     * 
     * @return DB_Schema $instance
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * Creates tables
     * 
     * @since 1.0.0
     * @global $wpdb
     */
    public function create()
    {
        global $wpdb;

        $sql = [];

        $charset_collate = $wpdb->get_charset_collate();

        /**
         * Create example_table table
         */
        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->tables['example_table']}'") !== $this->tables['example_table']) {
            $sql[] = "CREATE TABLE {$this->tables['example_table']} (
            cookie_hash VARCHAR(100) UNIQUE,
            content LONGTEXT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )$charset_collate;";
        }

        if (!empty($sql)) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
            add_option("ox_plugin_db_version", OX_DB_VERSION);
        }
    }


    /**
     * Drops the database tables of this plugin
     * runs when the plugin gets deactivated
     * 
     * @since 1.0.0
     * @global $wpdb
     */
    public function drop()
    {
        global $wpdb;

        $wpdb->query('set foreign_key_checks=0');

        foreach ($this->tables as $name => $full_name) {
            $wpdb->query("DROP TABLE IF EXISTS {$full_name}");
        }

        $wpdb->query('set foreign_key_checks=1');
    }
}
