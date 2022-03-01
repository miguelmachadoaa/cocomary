<?php

class ContactoController extends Zend_Controller_Action {
    
    
    public function init() {
         $opt=array('layout'=>'layout');

         Zend_Layout::startMvc($opt);

          $Obj= new Application_Model_DbTable_Identidad();
        
             $this->view->identidad=$Obj->get('1');

             $ObjPortafolio = new Application_Model_DbTable_Portafolios();
        // se envia a la vista todos los registros de usuarios
        $this->view->categorias_menu = $ObjPortafolio->fetchAll();

        $ObjProductos = new Application_Model_DbTable_Productos();

        $semillas=$ObjProductos->getproductosLimit(3, '1503016404');
        $abonos=$ObjProductos->getproductosLimit(3, '1503017036');
        $fertilizantes=$ObjProductos->getproductosLimit(3, '1503016298');
        $aleatorios=$ObjProductos->Aleatorio(4);

        
        $this->view->semillas=$semillas;
        $this->view->abonos=$abonos;
        $this->view->fertilizantes=$fertilizantes;
        $this->view->aleatorios=$aleatorios;
    }
    
    public function indexAction() {

    	
        $ObjPaginas = new Application_Model_DbTable_Paginas();
      

        $this->view->pagina = $ObjPaginas->fetchRow('grupo="contacto"');

   

        
        $ObjNoticias = new Application_Model_DbTable_Noticias();
        // se envia a la vista todos los registros de usuarios
        $this->view->noticias = $ObjNoticias->Aleatorio();



        $meta = array(
            'titulo' => 'Contacto -  Tienda Cocomary', 
            'descripcion' => 'Su mensaje es importante para nosotros. ', 
            'imagen' => '',
            'url'=>'',
            'key' => '',
            'menu'=>'contacto'
            );

         $this->view->meta=$meta;

        
        
    }

        public function verAction(){

        $id = $this->_getParam('id', 0);

        
         $opt=array('layout'=>'gentelella');

         Zend_Layout::startMvc($opt);

        $ObjContacto = new Application_Model_DbTable_Contacto();
        // se envia a la vista todos los registros de usuarios
        $this->view->contacto = $ObjContacto->get($id);
        
        
       
        
   
        
    }

    public function listAction(){

        $opt=array('layout'=>'gentelella');

         Zend_Layout::startMvc($opt);

     

        $ObjContacto= new Application_Model_DbTable_Contacto();

      
        $this->view->contactos = $ObjContacto->fetchAll('estatus=1');
        
    }


}
