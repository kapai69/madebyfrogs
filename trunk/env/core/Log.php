<?php

/**
 * Log class
 *
 * easy log debug, info and error message
 *
 * @version 0.1
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Log
{
    const LEVEL_DEBUG = 1;
    const LEVEL_INFO  = 2;
    const LEVEL_ERROR = 4;
    const LEVEL_ALL   = 999;
     
    public $log_path;
    
    private $_display_level;
    private $_date_fmt  = 'Y-m-d H:i:s';
    private $_enabled   = true;
    private $_logs = array();
    private $_levels = array(1 => 'DEBUG', 
                             2 => 'INFO ',
                             4 => 'ERROR');
    /**
     * Constructor
     *
     * @access public
     * @param  string the log file path
     * @param  string the error threshold
     * @param  string the date formatting codes
     */
    public function __construct($display_level=self::LEVEL_ALL)
    {
        $this->log_path = defined('LOG_PATH') ? LOG_PATH : ENV_PATH.'/logs';

        if (!is_dir($this->log_path) || !is_writable($this->log_path)) {
            $this->_enabled = false;
        }
      
        $this->_display_level = (int) $display_level;
    }

    public function __destruct()
    {
        if ($this->_enabled === true && count($this->_logs) >= 1) {
            $this->_write();
        }
    }

    public function add($level, $msg)
    {
        if (DEBUG && $level > $this->_display_level) {
            return false;
        }
        $now = date($this->_date_fmt);
        $this->_logs[] = array('level' => $level, 'msg' => $now .' --> '. $msg);
    }
    
    /**
     * Return a singleton instance of this class
     *
     * @param none
     * @return object this instance by reference
     */
    static public function &getInstance()
    {
        static $instance;

        if (!$instance) $instance = new Log();

        return $instance;
    }

    // --------------------------------------------------------------

    private function _write()
    {
        $filepath = $this->log_path.'/log-'.date('Y-m-d');

        if (!$fp = fopen($filepath, "a")) {
            return false;
        }

        flock($fp, LOCK_EX);
        foreach ($this->_logs as $log) {
            fwrite($fp, $this->_levels[$log['level']] .' - '. $log['msg'] ."\n");
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        chmod($filepath, 0666);
        return true;
    }

} // End Log class

function log_error($msg)
{
    Log::getInstance()->add(Log::LEVEL_ERROR, $msg);
}

function log_debug($msg)
{
    Log::getInstance()->add(Log::LEVEL_DEBUG, $msg);
}

function log_info($msg)
{
    Log::getInstance()->add(Log::LEVEL_INFO, $msg);
}
