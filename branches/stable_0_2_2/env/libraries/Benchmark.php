<?php

/**
 * Benchmark class
 *
 * easy start, stop and display methodes for benchmarking
 *
 * @version 0.2
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Benchmark
{

    private $_marks;

    public function __destruct()
    {
        if (DEBUG) {
            log_debug('execution time: '. $this->fetch() .' sec');
        }
    }
    
    /**
     * Register a mark time
     *
     * @param none
     * @return void
     */
    public function mark($name)
    {
        $this->_marks[$name] = $this->_getTime();
    }

    /**
     * Register a starting mark time
     *
     * @param none
     * @return void
     */
    public function start()
    {
        $this->_marks['start'] = $this->_getTime();
    }

    /**
     * Fetch benchmark and return output as string
     *
     * @return string
     */
    public function fetch()
    {
        ob_start();
        $this->display();
        return ob_get_clean();
    } // fetch
    
    /**
     * Enter description here...
     *
     * @param none
     * @return void
     */
    public function display()
    {
        printf("%01.4f", $this->_getTime() - $this->_marks['start']);
    } // display

    /**
     * Enter description here...
     *
     * @param $mark_begin string
     * @param $mark_end   string
     * @return void
     */
    public function elapsedTime($mark_begin, $mark_end)
    {
        if (!isset($this->_marks[$mark_begin])) return 0;
        if (!isset($this->_marks[$mark_end])) return 0;
        printf("%01.4f", $this->_marks[$mark_end] - $this->_marks[$mark_begin]);
    }

    /**
     * Return a singleton instance of this class
     *
     * @param none
     * @return object this instance by reference
     */
    public static function &getInstance()
    {
        static $instance;

        if (!$instance) {
            $instance = new Benchmark();
        }
        return $instance;
    }

    /**
     * Return current microtime
     *
     * @param none
     * @return float
     */
    private function _getTime()
    {
        $time = explode(' ', microtime());
        return doubleval($time[0]) + $time[1];
    } // _getTime

} // Benchmark
