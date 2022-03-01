<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract {

    protected $_name = 'hk_admin_users';
    
    public function getUser($id) {
        $row = $this->fetchRow('id = ' . (int)$id);
        if (!$row) {
            throw new Exception('No se encontró el registro');
        }
        return $row->toArray();
    }
    
    public function addUser($data = array()) {
        $rs = $this->insert($data);
        return $rs;
    }
    
    public function updateUser($id, $data = array()) {
        $rs = $this->update($data, 'id = ' . (int)$id);
        return $rs;
    }
    
    public function deleteUser($id) {
        $rs = $this->delete('id = ' . (int)$id);
        return $rs;
    }
    
}

