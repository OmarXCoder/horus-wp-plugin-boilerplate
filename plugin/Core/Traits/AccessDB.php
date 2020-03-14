<?php

namespace Horus\Core\Traits;

trait AccessDB
{

    /**
     * wpdb instance
     * @var object $db
     */
    protected static $db = null;

    /**
     * Model table in the DB
     * @var string $table name
     */
    protected static $table;

    public static function db()
    {
        global $wpdb;
        if (static::$db === null) {
            static::$db = $wpdb;
        }
        return static::$db;
    }

    public static function table($table = null)
    {
        if (is_string($table)) {
            return static::db()->prefix . 'ox_' . $table;
        }

        if (method_exists(get_called_class(), 'getTableName')) {
            return static::getTableName();
        }

        return static::db()->prefix . 'ox_' . static::$table;
    }
}
