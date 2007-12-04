<?php 

/**
 * class Users 
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class Users extends Model
{

    function getTablename()
    {
        return TABLE_PREFIX.'users';
    } // getTablename

    function findByUsername($username)
    {
        $option = array('where' => "`username`='".$username."'");
        return $this->findOne($option);
    } // findByUsername

} // Users
