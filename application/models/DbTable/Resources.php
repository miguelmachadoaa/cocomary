<?php

class Application_Model_DbTable_Resources extends Zend_Db_Table_Abstract
{

    protected $_name = 'hk_resources';

    
    public function add($data = array()) {
        $rs = $this->insert($data);
        return $rs;
    }

}

