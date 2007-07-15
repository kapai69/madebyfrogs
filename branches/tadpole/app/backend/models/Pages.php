<?php

/**
 * class Pages
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

load_library('NestedTree');

class Pages extends NestedTree
{
    protected $db;
    
    function __construct()
    {
        global $_PDO;
        
        $this->db = $_PDO;
        parent::__construct('parent_id', 'title');
    }

    function find($arguments = null)
    {
        // Collect attributes...
        $one     = (bool) array_var($arguments, 'one', false);
        $where   = array_var($arguments, 'where', '');
        $orderBy = array_var($arguments, 'order', '');
        $offset  = (int) array_var($arguments, 'offset', 0);
        $limit   = (int) array_var($arguments, 'limit', 0);

        // Prepare query parts
        $whereString = trim($where) == '' ? '' : "where ".$where;
        $orderByString = trim($orderBy) == '' ? '' : "order by ".$orderBy;
        $limitString = $limit > 0 ? "limit $offset, $limit" : '';

        $tablename = $this->getTablename();

        // Prepare SQL
        $sql = "select $tablename.*, creator.name as created_by_name, updator.name as updated_by_name from $tablename".
               " left join ".TABLE_PREFIX."users as creator on $tablename.created_by_id = creator.id".
               " left join ".TABLE_PREFIX."users as updator on $tablename.updated_by_id = updator.id".
               " $whereString $orderByString $limitString";

        log_debug('SQL: '.$sql);

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // Run!
        if ($one) {
            return $stmt->fetchObject();
        } else {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    } // find

    public function childrenOf($id)
    {
        return $this->find(array('where' => 'parent_id='.$id, 'order' => 'created_on'));
    }
    
    public function hasChildren($id)
    {
        return (boolean) $this->count(array('where'=>'parent_id = '.$id));
    }
    
    public function pathOf($id)
    {
        $path = '';
        $page = $this->findById($id);
        if ($page->parent_id != 0) {
            $path = $this->pathOf($page->parent_id) . '/' . $page->slug;
        }
        return $path;
    }
} // Pages
