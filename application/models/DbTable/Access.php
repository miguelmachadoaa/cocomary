<?php

class Application_Model_DbTable_Access extends Zend_Db_Table_Abstract
{

    protected $_name = 'hk_access';
    
    public function getAccess($role_id, $resource_id) {
        
        $select = $this->select()
                ->where('role_id = ?', $role_id)
                ->where('resource_id = ?', $resource_id);
        
        $result = $this->fetchAll($select);
        
        return $result;
        
    }


}

