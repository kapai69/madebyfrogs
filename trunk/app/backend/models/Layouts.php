<?php

class Layouts extends Model
{
    function find($arguments = null)
    {
        global $_PDO;
        
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

        $stmt = $_PDO->prepare($sql);
        $stmt->execute();

        // Run!
        if ($one) {
            return $stmt->fetchObject();
        } else {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    } // find
}
