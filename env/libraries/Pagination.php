<?php

  /**
   * Pagination class
   *
   * @version 0.1
   * @package Frog
   * @author Philippe Archambault <philippe.archambault@gmail.com>
   * @license http://www.opensource.org/licenses/mit-license.html MIT License
   */

  class Pagination extends Object 
  {

    var $base_url           = ''; // The page we are linking to
    var $total_rows         = ''; // Total number of items (database results)
    var $per_page           = 10; // Max number of items you want shown per page
    var $num_links          =  3; // Number of "digit" links to show before/after the currently viewed page
    var $cur_page           =  0; // The current page being viewed
    var $uri_param          =  0;
    var $first_link         = '&lsaquo; First';
    var $next_link          = '&gt;';
    var $prev_link          = '&lt;';
    var $last_link          = 'Last &rsaquo;';
    var $full_tag_open      = '';
    var $full_tag_close     = '';
    var $first_tag_open     = '';
    var $first_tag_close    = '&nbsp;';
    var $last_tag_open      = '&nbsp;';
    var $last_tag_close     = '';
    var $cur_tag_open       = '&nbsp;<b>';
    var $cur_tag_close      = '</b>';
    var $next_tag_open      = '&nbsp;';
    var $next_tag_close     = '&nbsp;';
    var $prev_tag_open      = '&nbsp;';
    var $prev_tag_close     = '';
    var $num_tag_open       = '&nbsp;';
    var $num_tag_close      = '';

    /**
     * Constructor
     *
     * @param array initialization parameters
     */
    function __construct($params = array())
    {
        if (count($params) > 0) {
            $this->initialize($params);
        }
    }

    /**
     * Initialize Preferences
     *
     * @param	array	initialization parameters
     * @return	void
     */
    function initialize($params = array())
    {
      if (count($params) > 0) {
        foreach ($params as $key => $val) {
          if (isset($this->$key))  {
              $this->$key = $val;
          }
        }
      }
    } // initialize

    /**
     * Generate the pagination links
     *
     * @return	string
     */	
    function createLinks()
    {
      // If our item count or per-page total is zero there is no need to continue.
      if ($this->total_rows == 0 || $this->per_page == 0)  {
        return '';
      }

      // Calculate the total number of pages
      $num_pages = intval($this->total_rows / $this->per_page);

      // Use modulus to see if our division has a remainder.If so, add one to our page number.
      if ($this->total_rows % $this->per_page) {
        $num_pages++;
      }

      // Is there only one page? Hm... nothing more to do here then. 
      if ($num_pages == 1) {
        return '';
      }

      $this->cur_page = (int) array_var(getParams(), $this->uri_param, 1);
      $this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

      // Calculate the start and end numbers. These determine
      // which number to start and end the digit links with
      $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
      $end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

      // Add a trailing slash to the base URL if needed
      $this->base_url = preg_replace("/(.+?)\/*$/", "\\1/",  $this->base_url);

      // And here we go...
      $output = '';

      // Render the "First" link
      if ($this->cur_page > $this->num_links) {
        $output .= $this->first_tag_open.'<a href="'.$this->base_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
      }

      // Render the "previous" link
      if ($this->cur_page > 1) {
        $previous = ($this->cur_page - 2) * $this->per_page; // -1 for the offset 0 and -1 for the previous = -2
        if ($previous == 0) $previous = '';
        $output .= $this->prev_tag_open.'<a href="'.$this->base_url.$previous.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
      }

      // Write the digit links
      for ($page = $start -1; $page <= $end; $page++) {
        if ($page > 0) {
          if ($this->cur_page == $page) {
            $output .= $this->cur_tag_open.$page.$this->cur_tag_close; // Current page
          } else {
            $page_offset = ($page-1)*$this->per_page;
            $output .= $this->num_tag_open.'<a href="'.$this->base_url.$page_offset.'">'.$page.'</a>'.$this->num_tag_close;
          }
        }
      } // for

      // Render the "next" link
      if ($this->cur_page < $num_pages) {
        $output .= $this->next_tag_open.'<a href="'.$this->base_url.($this->cur_page*$this->per_page).'">'.$this->next_link.'</a>'.$this->next_tag_close;
      }

      // Render the "Last" link
      if (($this->cur_page + $this->num_links) < $num_pages) {
        $page = (($num_pages * $this->per_page) - $this->per_page);
        $output .= $this->last_tag_open.'<a href="'.$this->base_url.$page.'">'.$this->last_link.'</a>'.$this->last_tag_close;
      }

      // Kill double slashes.  Note: Sometimes we can end up with a double slash 
      // in the penultimate link so we'll kill all double shashes.
      $output = preg_replace("#([^:])//+#", "\\1/", $output);  

      // Add the wrapper HTML if exists
      $output = $this->full_tag_open.$output.$this->full_tag_close;

      return $output;
    } // createLinks

  } // Pagination
  
?>