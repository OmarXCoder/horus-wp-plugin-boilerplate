<?php

namespace Horus\Core;

defined('ABSPATH') || exit('Forbidden!');


class Session
{
    const SESSION_STARTED = true;
    const SESSION_NOT_STARTED = false;

    // The state of the session
    private static $state = self::SESSION_NOT_STARTED;

    /**
     * singleton instance of Session
     * @var Session
     */
    private static $instance;

    /**
     * session prefix
     * @var string
     */
    private $prefix;

    private function __construct($prefix = null)
    {
        $this->prefix   = ($prefix && is_string($prefix)) ? $prefix : 'ox_';
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name)
    {
        return isset($_SESSION[$this->_prefix_session_name($name)]);
    }


    public function __unset($name)
    {
        $this->remove($name);
    }

    /**
     * Singleton instance
     * @return Session
     */
    public static function instance($prefix = null)
    {
        if (null === self::$instance || $prefix !== self::$instance->prefix) {
            self::$instance = new static($prefix);
        }

        self::$instance->start();
        return self::$instance;
    }

    /**
     * Starts a session
     * 
     * @return bool
     */
    public function start()
    {
        if (self::$state === self::SESSION_NOT_STARTED) {
            self::$state = session_start();
        }

        return self::$state;
    }

    /**
     * Sets a session variable
     * 
     * @param string $name session variable name
     * @param mixed $value session variable value
     * @return Session $instance
     */
    public function set($name, $value)
    {
        if (!is_string($value)) {
            $value = maybe_serialize($value);
        }

        $_SESSION[$this->_prefix_session_name($name)] = $value;
        return $this;
    }

    /**
     * Gets a session variable
     * 
     * @param string $name session variable name
     * @return mixed session variable value if exists or null
     */
    public function get($name)
    {
        $name =  $this->_prefix_session_name($name);
        if (isset($_SESSION[$name])) {
            return maybe_unserialize($_SESSION[$name]);
        }
        return null;
    }

    /**
     * Unsets a session variable
     * 
     * @param string $name session variable name
     */
    public function remove($name)
    {
        unset($_SESSION[$this->_prefix_session_name($name)]);
    }

    /**
     * Checks if a cookie is set
     * 
     * @param $name the cookie name
     * @return bool true if cookie isset and not empty, false otherwise
     */
    public function issetCookie($name)
    {
        $name = $this->_prefix_session_name($name);
        return isset($_COOKIE[$name]) && !empty($_COOKIE[$name]);
    }

    /**
     * Set a cookie - wrapper for setcookie using WP constants.
     *
     * @param  string  $name   Name of the cookie being set.
     * @param  string  $value  Value of the cookie.
     * @param  integer $expire Expiry of the cookie.
     * @param  bool    $secure Whether the cookie should be served only over https.
     * @param  bool    $httponly Whether the cookie is only accessible over HTTP, not scripting languages like JavaScript.
     */
    public function setCookie($name, $value, $expire = 0, $secure = false, $httponly = false)
    {
        $name = $this->_prefix_session_name($name);
        if (!headers_sent()) {
            setcookie($name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, $httponly);
        } elseif (defined('WP_DEBUG') && WP_DEBUG) {
            headers_sent($file, $line);
            trigger_error("{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE); // @codingStandardsIgnoreLine
        }

        return $value;
    }

    /**
     * Gets cookie's raw value
     * 
     * @param $name cookie name
     * @return string the cookie value
     */
    public function getCookie($name)
    {
        $name = $this->_prefix_session_name($name);

        if (isset($_COOKIE[$name]) && !empty($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }

        return null;
    }

    /**
     * Gets the resolved (unhashed) value of a cookie
     * 
     * @param $name cookie name
     * @return mixed the value of the cookie
     */
    public function resolveCookie($name)
    {
        $value = $this->getCookie($name);

        if (null !== $value) {
            return json_decode(base64_decode($value), true);
        }

        return null;
    }

    /**
     * gets the prefix of the webhooks
     * 
     * @return string $prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Gets a session value and removes the session instance afterwards
     * 
     * @param $name session name
     * @return mixed the session value
     */
    public function getAndRemove($name)
    {
        if (($value = $this->{$name})) {
            unset($_SESSION[$this->_prefix_session_name($name)]);
        }

        return $value;
    }

    /**
     * prefixes a session name
     * 
     * @param string $name
     * @return string prefixed session name
     */
    public function _prefix_session_name($name)
    {
        return $this->prefix . str_replace($this->prefix, '', $name);
    }
}
