<?php

class Application_Model_DbTable_Fotos extends Zend_Db_Table_Abstract {

    protected $_name = 'hk_fotos';
    

  
    public function get($id) {
        $row = $this->fetchRow('id = ' . (int)$id);
        if (!$row) {
            throw new Exception('No se encontrĂ³ el registro');
        }
        return $row->toArray();
    }

    public function gets($id) {
        $row = $this->fetchAll('id_solicitud = "' .$id.'"');
        
        return $row;
    }


 
    public function add($data = array()) {
        $rs = $this->insert($data);
        return $rs;
    }
    
    public function upd($id, $data = array()) {
        $rs = $this->update($data, 'id = ' . (int)$id);
        return $rs;
    }
    
    public function del($id) {
        $rs = $this->delete('id = ' . (int)$id);
        return $rs;
    }
    
}