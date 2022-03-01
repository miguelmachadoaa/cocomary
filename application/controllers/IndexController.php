<?php

class IndexController extends Zend_Controller_Action {
    
    
    public function init() {
         $opt=array('layout'=>'layout');

         Zend_Layout::startMvc($opt);

          $Obj= new Application_Model_DbTable_Identidad();
        
                $this->view->identidad=$Obj->get('1');

                       $ObjPortafolio = new Application_Model_DbTable_Portafolios();
        // se envia a la vista todos los registros de usuarios
        $this->view->categorias_menu = $ObjPortafolio->fetchAll("estatus='1'");
    }
    
    public function indexAction() {

    	$ObjSliders = new Application_Model_DbTable_Sliders();
        // se envia a la vista todos los registros de usuarios
        $this->view->sliders = $ObjSliders->fetchAll('estatus=1');


        $ObjPaginas = new Application_Model_DbTable_Paginas();
        // se envia a la vista todos los registros de usuarios
        $this->view->productos_pagina = $ObjPaginas->fetchAll('grupo="productos"');

        $this->view->quienes = $ObjPaginas->fetchRow('grupo="somos"');
        $this->view->inicio = $ObjPaginas->fetchRow('grupo="inicio"');
        

        $this->view->ingresos_pag = $ObjPaginas->fetchRow('grupo="ingresos"');

         $ObjProductos = new Application_Model_DbTable_Productos();

        $limpieza=$ObjProductos->Aleatorio(8);
        $abonos=$ObjProductos->getproductosLimit(4, '1503017036');
        $fertilizantes=$ObjProductos->getproductosLimit(4, '1503016298');
        $aleatorios=$ObjProductos->Aleatorio(8);

        
        $this->view->limpieza=$limpieza;
        $this->view->abonos=$abonos;
        $this->view->fertilizantes=$fertilizantes;
        $this->view->aleatorios=$aleatorios;

         $ObjServicios= new Application_Model_DbTable_Servicios();

      
        $this->view->servicios = $ObjServicios->fetchAll('estatus=1');

        $ObjNoticias = new Application_Model_DbTable_Noticias();
        // se envia a la vista todos los registros de usuarios
        $this->view->noticias = $ObjNoticias->Aleatorio();


        $ObjIdentidad= new Application_Model_DbTable_Identidad();
        
        $identidad=$ObjIdentidad->get('1');

      

        $meta = array(
            'titulo' => 'Tienda Cocomary - Inicio', 
            'descripcion' => $identidad['descripcion'], 
            'key' => strip_tags($identidad['palabras']),
            'imagen' => '',
            'url'=>'https://tiendacocomary.com',
            'menu'=>'inicio'
            );

         $this->view->meta=$meta;
        
    }


}
