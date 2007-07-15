<?php

/**
 * NestedTree class
 *
 * @version 0.1
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class NestedTree extends Model
{
    /**
     * Constructor. Set the database table name and necessary field names
     *
     * @param string $parent_field Name of the parent ID field
     * @param string $sort_field Name of the field to sort data.
     */
    public function __construct($parent_field='parent_id', $sort_field='position')
    {
        $this->fields = array(
            'parent' => $parent_field,
            'sort'   => $sort_field
        );
    }

    /**
     * Fetch the node data for the node identified by $id
     *
     * @param int $id The ID of the node to fetch
     *
     * @return object An object containing the node's data, or null if node not found
     */
    public function getNode($id)
    {
        global $_PDO;
        
        $sql = sprintf("select * from %s where %s = '%d'", $this->getTablename(),
                                                         $this->getPrimaryKey(),
                                                         $id);

        $stmt = $_PDO->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchObject();
    }

    /**
     * Fetch the descendants of a node, or if no node is specified, fetch the
     * entire tree. Optionally, only return child data instead of all descendant
     * data.
     *
     * @param int $id The ID of the node to fetch descendant data for.
     *                Specify an invalid ID (e.g. 0) to retrieve all data.
     * @param bool $includeSelf Whether or not to include the passed node in the
     *                          the results. This has no meaning if fetching entire tree.
     * @param bool $childOnly True if only returning child data. False if
     *                        returning all descendant data
     *
     * @return array The descendants of the passed now
     */
    public function getDescendants($id = 0, $includeSelf = false, $childOnly = false)
    {
        global $_PDO;
        
        $idField = $this->getPrimaryKey();

        $node = $this->getNode($id);
        if (empty($node)) {
            $nleft = 0;
            $nright = 0;
            $parent_id = 0;
        } else {
            $nleft = $node->nleft;
            $nright = $node->nright;
            $parent_id = $node->$idField;
        }

        if ($childOnly) {
            if ($includeSelf) {
                $sql = sprintf('select * from %s where %s = %d or %s = %d order by nleft',
                               $this->getTablename(),
                               $idField,
                               $parent_id,
                               $this->fields['parent'],
                               $parent_id);
            }
            else {
                $sql = sprintf('select * from %s where %s = %d order by nleft',
                               $this->getTablename(),
                               $this->fields['parent'],
                               $parent_id);
            }
        }
        else {
            if ($nleft > 0 && $includeSelf) {
                $sql = sprintf('select * from %s where nleft >= %d and nright <= %d order by nleft',
                               $this->getTablename(),
                               $nleft,
                               $nright);
            }
            else if ($nleft > 0) {
                $sql = sprintf('select * from %s where nleft > %d and nright < %d order by nleft',
                               $this->getTablename(),
                               $nleft,
                               $nright);
            }
            else {
                $sql = sprintf('select * from %s order by nleft', $this->getTablename());
            }
        }

        $stmt = $_PDO->prepare($sql);
        $stmt->execute();
        
        $arr = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $arr[$row->$idField] = $row;
        }

        return $arr;
    }

    /**
     * Fetch the child of a node, or if no node is specified, fetch the
     * top level items.
     *
     * @param int $id The ID of the node to fetch child data for.
     * @param bool $includeSelf Whether or not to include the passed node in the results.
     *
     * @return array The child of the passed node
     */
    public function getChild($id = 0, $includeSelf = false)
    {
        return $this->getDescendants($id, $includeSelf, true);
    }

    /**
     * Fetch the path to a node. If an invalid node is passed, an empty array is returned.
     * If a top level node is passed, an array containing on that node is included (if
     * 'includeSelf' is set to true, otherwise an empty array)
     *
     * @param int $id The ID of the node to fetch child data for.
     * @param bool $includeSelf Whether or not to include the passed node in the results.
     *
     * @return array An array of each node to passed node
     */
    public function getPath($id = 0, $includeSelf = false)
    {
        global $_PDO;
        
        $node = $this->getNode($id);
        if (is_null($node))
            return array();

        if ($includeSelf) {
            $sql = sprintf('select * from %s where nleft <= %d and nright >= %d order by nlevel',
                           $this->getTablename(),
                           $node->nleft,
                           $node->nright);
        }
        else {
            $sql = sprintf('select * from %s where nleft < %d and nright > %d order by nlevel',
                           $this->getTablename(),
                           $node->nleft,
                           $node->nright);
        }

        $stmt = $_PDO->prepare($sql);
        $stmt->execute();
        
        $idField = $this->getPrimaryKey();
        $arr = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $arr[$row->$idField] = $row;
        }

        return $arr;
    }

    /**
     * Check if one node descends from another node. If either node is not
     * found, then false is returned.
     *
     * @param int $descendant_id The node that potentially descends
     * @param int $ancestor_id The node that is potentially descended from
     *
     * @return bool True if $descendant_id descends from $ancestor_id, false otherwise
     */
    public function isDescendantOf($descendant_id, $ancestor_id)
    {
        $node = $this->getNode($ancestor_id);
        if (is_null($node))
            return false;

        $options = array('where' => $this->getPrimaryKey().'='.$descendant_id.
                                    ' and nleft > '.$node->nleft.
                                    ' and nright < '.$node->nright);

        return (bool) $this->count($options);
    }

    /**
     * Check if one node is a child of another node. If either node is not
     * found, then false is returned.
     *
     * @param int $child_id The node that is possibly a child
     * @param int $parent_id The node that is possibly a parent
     *
     * @return bool True if $child_id is a child of $parent_id, false otherwise
     */
    public function isChildOf($child_id, $parent_id)
    {
        $options = array('where' => $this->getPrimaryKey().'='.$child_id.' and '.
                                    $this->fields['parent'].'='.$parent_id);

        return (bool) $this->count($options);
    }

    /**
     * Find the number of descendants a node has
     *
     * @param int $id The ID of the node to search for. Pass 0 to count all nodes in the tree.
     * @return int The number of descendants the node has, or -1 if the node isn't found.
     */
    public function numDescendants($id)
    {
        if ($id == 0) {
            return $this->count();
        } else {
            $node = $this->getNode($id);
            if (!is_null($node)) {
                return ($node->nright - $node->nleft - 1) / 2;
            }
        }
        return -1;
    }

    /**
     * Find the number of child a node has
     *
     * @param int $id The ID of the node to search for. Pass 0 to count the first level items
     *
     * @return int The number of descendants the node has, or -1 if the node isn't found.
     */
    public function numChild($id)
    {
        $options = array('where' => $this->fields['parent'].'='.$id);
        return $this->count($options);
    }

    /**
     * Fetch the tree data, nesting within each node references to the node's child
     *
     * @return array The tree with the node's child data
     */
    public function getTreeWithChild()
    {
        global $_PDO;
        
        $idField = $this->getPrimaryKey();
        $parentField = $this->fields['parent'];

        $sql = sprintf('select * from %s order by %s',
                       $this->getTablename(),
                       $this->fields['sort']);

        $stmt = $_PDO->prepare($sql);
        $stmt->execute();
        
        // create a root node to hold child data about first level items
        $root = new stdClass;
        $root->$idField = 0;
        $root->child = array();

        $arr = array($root);

        // populate the array and create an empty child array
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $arr[$row->$idField] = $row;
            $arr[$row->$idField]->child = array();
        }

        // now process the array and build the child data
        foreach ($arr as $id => $row) {
            if (isset($row->$parentField))
                $arr[$row->$parentField]->child[$id] = $id;
        }

        return $arr;
    }

    /**
     * Rebuilds the tree data and saves it to the database
     *
     * @param none
     *
     * @return void
     */
    public function rebuild()
    {
        global $_PDO;
        
        $data = $this->getTreeWithChild();

        $n = 0; // need a variable to hold the running n tally
        $level = 0; // need a variable to hold the running level tally

        // invoke the recursive function. Start it processing
        // on the fake "root node" generated in getTreeWithChild().
        // because this node doesn't really exist in the database, we
        // give it an initial nleft value of 0 and an nlevel of 0.
        $this->_generateTreeData($data, 0, 0, $n);

        // at this point the the root node will have nleft of 0, nlevel of 0
        // and nright of (tree size * 2 + 1)

        foreach ($data as $id => $row) {
            // skip the root node
            if ($id == 0)
                continue;

            $sql = sprintf('update %s set nlevel = %d, nleft = %d, nright = %d where %s = %d',
                           $this->getTablename(),
                           $row->nlevel,
                           $row->nleft,
                           $row->nright,
                           $this->getPrimaryKey(),
                           $id);
            
            $_PDO->exec($sql);
        }
    }

    /**
     * Generate the tree data. A single call to this generates the n-values for
     * 1 node in the tree. This function assigns the passed in n value as the
     * node's nleft value. It then processes all the node's child (which
     * in turn recursively processes that node's child and so on), and when
     * it is finally done, it takes the update n-value and assigns it as its
     * nright value. Because it is passed as a reference, the subsequent changes
     * in subrequests are held over to when control is returned so the nright
     * can be assigned.
     *
     * @param array &$arr A reference to the data array, since we need to
     *                    be able to update the data in it
     * @param int $id The ID of the current node to process
     * @param int $level The nlevel to assign to the current node
     * @param int &$n A reference to the running tally for the n-value
     *
     * @return void
     */
    private function _generateTreeData(&$arr, $id, $level, &$n)
    {
        $arr[$id]->nlevel = $level;
        $arr[$id]->nleft = $n++;

        // loop over the node's child and process their data
        // before assigning the nright value
        foreach ($arr[$id]->child as $child_id) {
            $this->_generateTreeData($arr, $child_id, $level + 1, $n);
        }
        $arr[$id]->nright = $n++;
    }

} // NestedTree
