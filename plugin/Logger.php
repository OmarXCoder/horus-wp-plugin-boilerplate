<?php

namespace Horus;


/**
 * Logger writes logs to a file
 */
class Logger
{

    private $handle, $dateFormat;

    public function __construct($file = null, $mode = "a")
    {
        $file = null !== $file ? $file : OX_PLUGIN_PATH . 'logs/log.txt';

        $this->handle = fopen($file, $mode);
        $this->dateFormat = "d/M/Y H:i:s";
    }

    public function dateFormat($format)
    {
        $this->dateFormat = $format;
    }

    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Writes info to the log
     * @param mixed, string or an array to write to log
     * @access public
     */
    public function log($entries, $file = null, $sep = true)
    {
        $file = $file ?: $this->backtrace();

        if ($sep) {
            fwrite($this->handle, str_repeat('-', 100) . "\n");
        }

        if (is_array($entries)) {
            fwrite($this->handle, "[" . date($this->dateFormat) . '|' . $file . "] ARRAY: \n" . json_encode($entries) . "\n");
        } elseif (is_object($entries)) {
            fwrite($this->handle, "[" . date($this->dateFormat) . '|' . $file . "] OBJECT: \n" . json_encode($entries) . "\n");
        } else {
            fwrite($this->handle, "[" . date($this->dateFormat) . '|' . $file . "] PRIMATIVE: " . $entries . "\n");
        }
    }

    public function writeSeparator()
    {
        fwrite($this->handle, str_repeat('-', 100) . "\n");
    }

    public function backtrace()
    {
        $fileinfo = 'no_file_info';
        $backtrace = debug_backtrace();
        if (!empty($backtrace[0]) && is_array($backtrace[0])) {
            $fileinfo = $backtrace[0]['file'] . ":" . $backtrace[0]['line'];
        }
        return str_replace(OX_PLUGIN_PATH, '', $fileinfo);
    }
}
