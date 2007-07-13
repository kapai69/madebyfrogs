<?php

/**
 * Model class
 *
 * @version 0.2
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Model
{
    public function getPrimaryKey()
    {
        return 'id'; // in general... must be rewrite if different!
    } // getPrimaryKey

    public function getTablename()
    {
        return TABLE_PREFIX . underscore(get_class($this));
    } // getTablename

    // ---------------------------------------------------
    //  Lazy sql query maker methods
    // ---------------------------------------------------

    /**
     * Generates a insert or update string from the supplied data
     * depending if the primary key is set or not and execute it
     *
     * @param array $data the insert values array('fieldname'=>value, ...)
     *
     * @return boolean
     */
    public function save($data)
    {
        global $_PDO;
        
        $pk = $this->getPrimaryKey();
        $id = !empty($data[$pk]) ? $data[$pk] : null;

        // primary key exist, need update
        if (!is_null($id)) {
            return $this->update($data, $pk.'='.$_PDO->quote($id));
        } else {
            return $this->insert($data);
        }
    } // save

    /**
     * Generates a insert string from the supplied data and execute it
     *
     * @param array $data the insert values array('fieldname'=>value, ...)
     *
     * @return boolean
     */
    public function insert($data)
    {
        global $_PDO;
        
        //$keys = array_keys($data);
        //$values = array_values($data);
        
        //array_walk($values, array($_PDO, 'quote'));
        $keys = array();
        $values = array();
        
        foreach ($data as $key => $value) {
            array_push($keys, $key);
            array_push($values, $_PDO->quote($value));
        }
        
        // make the SQL query
        $sql = sprintf('insert into `%s` (`%s`) values (%s)',
                       $this->getTablename(),
                       join('`, `', $keys),
                       join(', ', $values));

        // Run it !!...
        return $_PDO->exec($sql);
    }

    /**
     * Generates a update string from the supplied data and execute it
     *
     * @param array $data the update values array('fieldname'=>value, ...)
     * @param string $where the query condition
     *
     * @return boolean
     */
    public function update($data, $where='1=1')
    {
        global $_PDO;
        
        $values = array();

        // prepare request by binding keys
        foreach ($data as $key => $value) {
            $values[] = '`'.$key.'`='.$_PDO->quote($value);
        }

        $sql = sprintf('update `%s` set %s where %s',
                       $this->getTablename(),
                       join(', ', $values),
                       $where);

        // Run it !!...
        return $_PDO->exec($sql);
    }

    /**
     * Generates a delete string and execute it
     *
     * @param string $where the query condition
     *
     * @return boolean
     */
    public function delete($where='1=1')
    {
        global $_PDO;
        
        $sql = sprintf('delete from `%s` where %s',
                       $this->getTablename(),
                       $where);
        
        // Run it !!...
        return $_PDO->exec($sql);
    }

    /**
     * Delete the id (pk)
     *
     * @param string $id int or string of the id to delete
     *
     * @return boolean
     */
    public function deleteId($id)
    {
        global $_PDO;
        return $this->delete('`'.$this->getPrimaryKey().'`='.$_PDO->quote($id));
    }

    // ---------------------------------------------------
    //  Finders methods
    // ---------------------------------------------------

    /**
     * Do a SELECT query over database with specified arguments
     *
     * @param array $arguments Array of query arguments. Fields:
     * 
     *  - one - select first row
     *  - where - additional conditions
     *  - order - order by string
     *  - offset - limit offset, valid only if limit is present
     *  - limit
     * 
     * @return one or many objects
     */
    public function find($arguments = null)
    {
        global $_PDO;
        
        // Collect attributes...
        $one     = isset($arguments['one']) ? (bool) $arguments['one']: false;
        $where   = isset($arguments['where']) ? $arguments['where']: '';
        $orderBy = isset($arguments['order']) ? $arguments['order']: '';
        $offset  = isset($arguments['offset']) ? (int) $arguments['offset']: 0;
        $limit   = isset($arguments['limit']) ? (int) $arguments['limit']: 0;

        // Prepare query parts
        $whereString = trim($where) == '' ? '' : 'where '.$where;
        $orderByString = trim($orderBy) == '' ? '' : 'order by '.$orderBy;
        $limitString = $limit > 0 ? 'limit '.$offset.', '.$limit : '';

        // Prepare SQL
        $sql = 'select * from `'.$this->getTablename().'` '.$whereString.' '.$orderByString.' '.$limitString;
        
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

    /**
     * Find one specific record
     *
     * @param array $arguments same as the method "find"
     *
     * @return object
     */
    public function findOne($arguments = null) 
    {
        // We don't have an array?
        if (!is_array($arguments)) $arguments = array();

        // Set value of one
        $arguments['one'] = true;

        // And find
        return $this->find($arguments);
    } // findOne

    /**
     * alias for "find"
     */
    public function findAll($arguments = null)
    {
        return $this->find($arguments);
    } // findAll

    /**
     * Find one specific record
     *
     * @param mixed $id int or string of the id to find
     *
     * @return object
     */
    public function findById($id)
    {
        global $_PDO;
        
        return $this->find(array(
            'one' => true, 
            'where' => $this->getTablename().'.'.$this->getPrimaryKey().'='.$_PDO->quote($id))
        );
    } // findById

    /**
     * Return number of rows in this table
     *
     * @param array $arguments Array of query arguments. Fields:
     *  - where - additional conditions
     *  - order - order by string
     *  - offset - limit offset, valid only if limit is present
     *  - limit
     *
     * @return integer
     */
    public function count($arguments = null)
    {
        global $_PDO;
        
        $where   = isset($arguments['where']) ? $arguments['where']: '';
        $orderBy = isset($arguments['order']) ? $arguments['order']: '';
        $offset  = isset($arguments['offset']) ? (int) $arguments['offset']: 0;
        $limit   = isset($arguments['limit']) ? (int) $arguments['limit']: 0;

        // Prepare query parts
        $whereString = trim($where) == '' ? '' : 'where '.$where;
        $orderByString = trim($orderBy) == '' ? '' : 'order by '.$orderBy;
        $limitString = $limit > 0 ? 'limit '.$offset.', '.$limit : '';

        $sql = 'select count(*) as nb_rows from `'.$this->getTablename().'` '.$whereString.' '.$orderByString.' '.$limitString;

        $stmt = $_PDO->prepare($sql);
        $stmt->execute();
        // return value if there's one , else return 0
        return $stmt->fetchColumn();
    } // count

    /**
     * Return last number inserted in db
     *
     * @param none
     *
     * @return integer
     */
    public function insertId()
    {
        global $_PDO;
        
        return $_PDO->lastInsertId();
    } // insertId

} // End Model class
