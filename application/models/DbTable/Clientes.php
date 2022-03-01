<?php

class Application_Model_DbTable_Clientes extends Zend_Db_Table_Abstract {

    protected $_name = 'hk_clientes';     
    public function getCliente($id) {
        $select = $this->select()
                        ->from(array('e'  => 'hk_clientes'),array('id','cedula' ,'nombre','direccion','tlf','estado','email','enlace'))  
                        ->where('id = ?', $id);
        //$row = $this->fetchRow('id = ' . (int)$id);

        $row = $this->fetchRow($select);
        if (!$row) {
            throw new Exception('No se encontró el registro');
        }
        return $row->toArray();
    }

    public function getCedula(){
        //se crea la consulta 
            $select=$this->select()
            ->from(array('e' => 'hk_clientes'),
                    array('id', 'cedula'));

            //se eliminan las restricciones de una sola tabla
             $select->setIntegrityCheck(false);


             //se ejecuta la consulta 
            $result = $this->fetchAll($select);

            foreach ($result as $row) {
            $cedula[$row->id] = utf8_encode($row->cedula);
        }
        
        return $cedula;

         //se envia la respuesta
        
    }
    
    public function addCliente($data = array()) {
        $rs = $this->insert($data);
        return $rs;
    }
    
    public function updateCliente($id, $data = array()) {
        $rs = $this->update($data, 'id = ' . (int)$id);
        return $rs;
    }
    
    public function deleteCliente($id) {
        $rs = $this->delete('id = ' . (int)$id);
        return $rs;
    }

    public function getClientes($options = array()) {
        
        $select = $this->select()
             ->from(array('e'  => 'hk_clientes'),array('id','cedula' ,'nombre','email','enlace','direccion','tlf','estado'))  
             ->join(array('s' => 'hk_states'),'e.estado = s.id', array('estado'=>'state'))
              ->order('e.id DESC');
        
       

        if (isset($options['f_state']) && !empty($options['f_state'])) {
            $state = $options['f_state'];
            $select->where('estado = ?', $state);
        }

        if (isset($options['f_city']) && !empty($options['f_city'])) {
            $ciudad = $options['f_city'];
            $select->where('ciudad = ?', $ciudad);
        }
                
        $select->setIntegrityCheck(false);

        return $this->fetchAll($select);
        
    }


       public function getUltimo() {
        $select = $this->select()
                        ->from(array('e'  => 'hk_clientes'),array('id','cedula' ,'nombre','email','enlace','direccion','tlf','estado'))  
                        ->order('e.id DESC');
        //$row = $this->fetchRow('id = ' . (int)$id);

        $row = $this->fetchRow($select);
        
        return $row;
    }

    
}