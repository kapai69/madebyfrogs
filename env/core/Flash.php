<?php

/**
 * Flash service
 *
 * Purpose of this service is to make some data available across pages. Flash
 * data is available on the next page but deleted when execution reach its end.
 *
 * Usual use of Flash is to make possible that current page pass some data
 * to the next one (for instance success or error message before HTTP redirect).
 *
 * Flash service as a concep is taken from Rails. This thing really rocks!
 *
 * @version 0.2
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Flash
{

    private $_previous = array(); // Data that prevous page left in the Flash
    private $_next     = array(); // Data that current page is saving for the next page

    /**
     * Read flash data on construction
     */
    public function __construct()
    {
        $this->_read();
    } // __construct

    /**
     * Return specific variable from the flash. If value is not found NULL is
     * returned
     *
     * @param string $var Variable name
     *
     * @return mixed
     */
    public function get($var)
    {
        return isset($this->_previous[$var]) ? $this->_previous[$var] : '';
    } // get

    /**
     * Add specific variable to the flash. This variable will be available on the
     * next page unlease removed with the removeVariable() or clear() method
     *
     * @param string $var Variable name
     * @param mixed $value Variable value
     *
     * @return void
     */
    public function set($var, $value)
    {
        $this->_next[$var] = $value;
        $this->_write();
    } // set

    /**
     * Remove specific variable for the Flash
     *
     * @param string $var Name of the variable that need to be removed
     *
     * @return void
     */
    public function remove($var)
    {
        if (isset($this->_next[$var])) {
            unset($this->_next[$var]);
        }
        $this->_write();
    } // remove

    /**
     * Call this function to clear flash. Note that data that previous page
     * stored will not be deleted - just the data that this page saved for
     * the next page
     *
     * @param none
     *
     * @return void
     */
    public function clear()
    {
        $this->_next = array();
    } // clear

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
            $instance = new Flash();
        }
        return $instance;
    }

    /**
     * This function will read flash data from the $_SESSION variable
     * and load it into $this->previous array
     *
     * @param none
     * @return void
     */
    private function _read()
    {
        // Get flash data...
        $flash_data = null;
        if (!empty($_SESSION['frog_flash_data'])) {
            $flash_data = $_SESSION['frog_flash_data'];
        }

        // If we have flash data set it to previous array and forget it :)
        if (!is_null($flash_data)) {
            if (is_array($flash_data)) {
                $this->_previous = $flash_data;
            }
            unset($_SESSION['frog_flash_data']);
        }
    } // _read

    /**
     * Save content of the $this->next array into the $_SESSION autoglobal var
     *
     * @param none
     *
     * @return void
     */
    private function _write()
    {
        $_SESSION['frog_flash_data'] = $this->_next;
    } // _write

} // End Flash class

//
// Public globals flash functions
//

/**
 * Interface to flash add method
 *
 * @param string $name Variable name
 * @param mixed $value Value that need to be set
 * @return void
 */
function flash_set($name, $value)
{
    Flash::getInstance()->set($name, $value);
} // flash_set

// alias of flash_set
function flash_add($name, $value)
{
    Flash::getInstance()->set($name, $value);
}

/**
 * Return variable from flash. If variable not set, 
 * an empty string is returned
 *
 * @param string $name Variable name
 *
 * @return mixed
 */
function flash_get($name)
{
    return Flash::getInstance()->get($name);
} // flash_get

/**
 * Adding success var to flash
 *
 * @param string $message Success message
 *
 * @return void
 */
function flash_success($msg)
{
    Flash::getInstance()->set('success', $msg);
} // flash_success

/**
 * Adding error var to flash
 *
 * @param string $message Error message
 *
 * @return void
 */
function flash_error($msg)
{
    Flash::getInstance()->set('error', $msg);
} // flash_error
