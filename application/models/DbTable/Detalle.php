<?php

class Application_Model_DbTable_Detalle extends Zend_Db_Table_Abstract {

    protected $_name = 'hk_detalle_pedido';   

    public function getDetalle($id) {
        $select = $this->select()
                        ->from(array('e'  => 'hk_detalle_pedido'),array('id','id_pedido' ,'id_producto', 'cantidad', 'precio', 'total')  )
                        ->where('id = ?', $id);
        //$row = $this->fetchRow('id = ' . (int)$id);

        $row = $this->fetchRow($select);
        if (!$row) {
            throw new Exception('No se encontró el registro');
        }
        return $row->toArray();
    }


    public function getDetallePedido($id) {
        $select = $this->select()
                        ->from(array('e'  => 'hk_detalle_pedido')  )
                        ->join(array('s' => 'hk_productos'),'e.id_producto = s.id', array('nombre'))
                        ->where('id_pedido = ?', $id);
        

        $select->setIntegrityCheck(false);

        $row = $this->fetchAll($select);
        
        return $row;
    }


    public function getUltimo() {
        $select = $this->select()
                        ->from(array('e'  => 'hk_detalle_pedido')  )                     
                        ->join(array('s' => 'hk_productos'),'e.id_producto = s.id', array('descripcion'))
                        ->order('e.id DESC');
        //$row = $this->fetchRow('id = ' . (int)$id);

        $select->setIntegrityCheck(false);
        $row = $this->fetchRow($select);
        
        return $row;
    }


     public function getUpdateId($id) {
        $select = $this->select()
                        ->from(array('e'  => 'hk_detalle_pedido')  )                     
                        ->join(array('s' => 'hk_productos'),'e.id_producto = s.id', array('descripcion'))
                        ->where('e.id = ?', $id);
                        
        //$row = $this->fetchRow('id = ' . (int)$id);

        $select->setIntegrityCheck(false);
        $row = $this->fetchRow($select);
        
        return $row;
    }

   
    
    public function addDetalle($data = array()) {
        $rs = $this->insert($data);
        return $rs;
    }

    
    public function updateDetalle($id, $data = array()) {
        $rs = $this->update($data, 'id = ' . (int)$id);
        return $rs;
    }
    
    public function deleteDetalle($id) {
        $rs = $this->delete('id = ' . (int)$id);
        return $rs;
    }

    public function getDetalles($options = array()) {
        
        $select = $this->select()
             ->from(array('e'  => 'hk_pedidos'),array('id','id_cliente','id_user' ,'total','fecha'=>'DATE_FORMAT(fecha, "%d/%m/%Y")','status')  )
             ->order('e.id DESC');
        
       

       
                
        $select->setIntegrityCheck(false);

        return $this->fetchAll($select);
        
    }


    
}