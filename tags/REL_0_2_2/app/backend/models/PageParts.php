<?php

/**
 * class PageParts
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class PageParts extends Model
{
    function getTablename()
    {
        return TABLE_PREFIX . 'page_parts';
    } // getTablename

    function findByPageId($id)
    {
        global $_PDO;
        return $this->find(array('where' => '`page_id`='.$_PDO->quote($id)));
    } // findById

    function deleteByPageId($id)
    {
        global $_PDO;
        $sql = 'delete from '.$this->getTablename().' where `page_id`='.$_PDO->quote($id);
        return $_PDO->exec($sql);
    } // deleteByPageId

} // PageParts
