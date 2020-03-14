<?php

namespace Horus\Core\Models;

defined('ABSPATH') || exit('Forbidden!');

use BadFunctionCallException;

abstract class Model
{

    /**
     * Model id
     * @var int $id
     */
    public $id;

    /**
     * created_at field
     * @var int $created_at
     */
    public $created_at;

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

    /**
     * Class constructor.
     */
    public function __construct()
    {
    }

    public static function instance($id = null)
    {
        if (null !== $id) {
            return static::find($id);
        }

        return new static();
    }

    public static function find($id)
    {
        $self = new static();
        $results = static::db()->get_results(
            static::db()->prepare("SELECT * FROM " . static::table() . " WHERE id = %s", $id)
        );

        if (count($results) > 0) {
            return $self->_fill_attributes($results[0]);
        }

        return null;
    }

    /**
     * Finds a record by column
     * 
     * @param $column column name
     * @param $value column value
     * @return Model $instance of the the model if a matching recodr found, null otherwise
     */
    public static function findBy($column, $value)
    {
        $self = new static();
        $results = static::db()->get_results(
            static::db()->prepare("SELECT * FROM " . static::table() . " WHERE $column = %s", $value)
        );

        if (count($results) > 0) {
            $self->_fill_attributes($results[0]);
            return $self;
        }

        return null;
    }

    /**
     * Finds a record by column
     * 
     * @param string $column column name
     * @param mixed $value column value
     * @param bool $first if true only the first row will be returned
     * @param string $order_by column to order by
     * @param string $order ASC / DESC
     * @return Model $instance of the the model if a matching recodr found, null otherwise
     */
    public static function where($column, $value, $first = false, $order_by = null, $order = 'DESC')
    {
        $order_clouse = '';

        if (null !== $order_by) {
            $order_clouse = "ORDER BY $order_by $order";
        }

        $results = static::db()->get_results(
            static::db()->prepare("SELECT * FROM " . static::table() . " WHERE $column = %s $order_clouse", $value)
        );

        if (count($results) > 0) {
            if ($first) {
                $model = new static();
                $model->_fill_attributes($results[0]);
                return $model;
            }
            return self::createCollection($results);
        }

        return null;
    }

    /**
     * Finds a record by multiple columns values
     * 
     * @param array $columns array of columns
     * @param string $relation relation for the where clouse
     * @param bool $first if true only the first row will be returned
     * @param string $order_by column to order by
     * @param string $order ASC / DESC
     * @return Model $instance of the the model if a matching recodr found, null otherwise
     */
    public static function whereColumns($columns, $relation, $first = false, $order_by = null, $order = 'DESC')
    {
        $where = 'WHERE 1';
        $order_clouse = '';

        foreach ($columns as $column) {
            $name = $column[0];
            $operator = $column[1];
            $value = $column[2];
            if (strtolower($operator) === 'like') {
                $value = "%$value%";
            }
            $where .= sprintf(" %s %s %s '%s'", $relation, $name, $operator, esc_sql($value));
        }

        if (null !== $order_by) {
            $order_clouse = "ORDER BY $order_by $order";
        }

        $results = static::db()->get_results("SELECT * FROM " . static::table() . " $where $order_clouse");

        if (count($results) > 0) {
            if ($first) {
                $model = new static();
                $model->_fill_attributes($results[0]);
                return $model;
            }
            return self::createCollection($results);
        }

        return null;
    }

    public static function create($attributes)
    {
        $self = new static;

        foreach ($attributes as $key => $value) {
            if (!property_exists($self, $key)) {
                unset($attributes[$key]);
            }
        }

        static::db()->insert(static::table(), $attributes);

        if (!static::db()->last_error) {
            return static::find(static::db()->insert_id);
        }

        return null;
    }

    public static function getAll()
    {
        $results = static::db()->get_results("SELECT * FROM " . static::table());

        return self::createCollection($results);
    }

    public static function createCollection($items)
    {
        $collection = [];
        foreach ($items as $item) {
            $model = new static;
            $model->_fill_attributes($item);
            $collection[] = $model;
        }

        return $collection;
    }

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

    /**
     * Updates a column in the DB table
     * 
     * @param string $column column name
     * @param mixed $column column value
     * @return bool true if updated, false otherwise
     */
    public function updateColumn($column, $value)
    {
        $updated = static::db()->update(static::table(), [$column => $value], ['id' => $this->id]);
        return !!$updated;
    }

    public function delete()
    {
        $deleted = static::db()->delete(static::table(), ['id' => $this->id]);

        return $deleted;
    }

    protected function _fill_attributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            $key = strtolower($key);

            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        return $this;
    }

    public static function __callStatic($method, $args)
    {
        $instance = new static;
        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $args);
        }
        throw new BadFunctionCallException("The method you called not found");
    }
}
