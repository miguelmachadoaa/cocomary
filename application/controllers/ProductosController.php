<?php


class ProductosController extends Zend_Controller_Action {
    
    
     protected $_flashMessenger = null;
    
    public function init() {
        
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');

         $Obj= new Application_Model_DbTable_Identidad();
        
               $this->view->identidad=$Obj->get('1');

                      $ObjPortafolio = new Application_Model_DbTable_Portafolios();
        // se envia a la vista todos los registros de usuarios
        $this->view->categorias_menu = $ObjPortafolio->fetchAll("estatus='1'");


         $ObjNoticias = new Application_Model_DbTable_Noticias();
        // se envia a la vista todos los registros de usuarios
        $this->view->noticias = $ObjNoticias->Aleatorio();

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


     public function categoriaAction(){

         $id = $this->_getParam('id', 0);

        $opt=array('layout'=>'layout');

         Zend_Layout::startMvc($opt);
        
        $params = $this->_getAllParams();
        
        $auth = Zend_Auth::getInstance();
        $this->view->auth = $auth;
       
        
        $this->view->params = $params;
        
        $ObjProductos = new Application_Model_DbTable_Productos();

        $relacionados=$ObjProductos->getproductosLimit(100, $id);


        $this->view->relacionados = $relacionados;

        $ObjPortafolios = new Application_Model_DbTable_Portafolios();

        $this->view->categoria=$ObjPortafolios->fetchRow('id="'.$id.'"');

        $categoria=$ObjPortafolios->fetchRow('id="'.$id.'"');

        $this->view->categorias=$ObjPortafolios->fetchAll();
        

        //consulta estado y envio de objeto

        $this->view->messages = $this->_flashMessenger->getMessages();

         $ObjProductos = new Application_Model_DbTable_Productos();

        $aleatorios=$ObjProductos->Aleatorio(4);

        $this->view->aleatorios=$aleatorios;


        $meta = array(
            'titulo' => $categoria['titulo'].' -  Tienda Cocomary', 
            'descripcion' => '', 
            'key' => '',
            'imagen' => '',
            'url'=>'',
            'menu'=>'productos'
            );

         $this->view->meta=$meta;

        
  
    }

    public function verAction(){

         $id = $this->_getParam('id', 0);

        $opt=array('layout'=>'layout');

         Zend_Layout::startMvc($opt);
        
        $params = $this->_getAllParams();
        
        $auth = Zend_Auth::getInstance();

        $this->view->auth = $auth;

        
        $ObjProductos = new Application_Model_DbTable_Productos();

        $detalles=$ObjProductos->getProducto($id);

        $vistas= $ObjProductos->updateProductoVistas($id);

        $this->view->detalles = $detalles;


        $relacionados=$ObjProductos->getproductosLimit(4, $detalles['categoria']);


        $this->view->relacionados = $relacionados;

        $ObjPortafolios = new Application_Model_DbTable_Portafolios();

        $this->view->categoria=$ObjPortafolios->fetchRow('id="'.$detalles['categoria'].'"');
        


        $ObjFotos = new Application_Model_DbTable_Fotos();
        
        $this->view->fotos=$ObjFotos->fetchAll('id_solicitud="'.$id.'"');

        $foto=$ObjFotos->fetchRow('id_solicitud="'.$id.'"');

        //consulta estado y envio de objeto

        $this->view->messages = $this->_flashMessenger->getMessages();


         $ObjProductos = new Application_Model_DbTable_Productos();

        $aleatorios=$ObjProductos->Aleatorio(4);
        
        $this->view->aleatorios=$aleatorios;



        $meta = array(
            'titulo' => $detalles['nombre'].' -  Tienda Cocomary', 
            'descripcion' => strip_tags($detalles['descripcion']), 
            'imagen' => 'http://tiendacocomary.com/assets/images/'.$foto['foto'],
            'url'=>'http://tiendacocomary.com/productos/ver/id/'.$id,
            'key' => '',
            'menu'=>'productos'
            );

         $this->view->meta=$meta;
        
  
        
    }

        public function indexAction(){
        
        $params = $this->_getAllParams();
        
        $auth = Zend_Auth::getInstance();
        $this->view->auth = $auth;
       
        
        $this->view->params = $params;
        
        $ObjProductos = new Application_Model_DbTable_Productos();

        $semillas=$ObjProductos->getproductosLimit(3, '1503016404');

        $this->view->semillas=$semillas;

          $objClientes = new Application_Model_DbTable_Productos();
        $Productos = $objClientes->getProductos($params);
        $this->view->productos = $Productos;

        //consulta estado y envio de objeto

        $this->view->messages = $this->_flashMessenger->getMessages();
        
  
        
    }



        public function listAction(){
        
        $params = $this->_getAllParams();
        
        $auth = Zend_Auth::getInstance();
        $this->view->auth = $auth;
        
        if ($auth->getIdentity()->role != 'administrador') {
            $params['f_state'] = $auth->getIdentity()->state;
        }
        
        $this->view->params = $params;
        
        $objClientes = new Application_Model_DbTable_Productos();
        $Productos = $objClientes->getProductos($params);
        $this->view->productos = $Productos;

      
        //consulta estado y envio de objeto

        $objStates = new Application_Model_DbTable_States();
        $this->view->objStates = $objStates;
        
        $states = $objStates->fetchAll();
        $this->view->states = $states;


        $this->view->messages = $this->_flashMessenger->getMessages();
        

        
    }

        //mostrar   estudiantes 


    //agregar  estudiantes 

    private function getFileExtension($filename)
        {
            $fext_tmp = explode('.',$filename);
            return $fext_tmp[(count($fext_tmp) - 1)];
        }

     public function addAction(){


        $auth = Zend_Auth::getInstance();
        $this->view->auth = $auth;
        
        //$EstForm = new Application_Form_Estudiantes();
        //$this->view->EstForm = $EstForm;

        $est = new Application_Model_DbTable_Productos();


        $ObjPortafolios = new Application_Model_DbTable_Portafolios();

        $this->view->categorias=$ObjPortafolios->fetchAll();
        // se envia a la vista todos los registros de usuarios




        
        if ($this->getRequest()->isPost()) {
            
            $formData = $this->getRequest()->getPost();
            
           

                $data = array(
                    'id' => $formData['id'],
                    'codigo' => $formData['codigo'],
                    'categoria' => $formData['categoria'],
                    'nombre' => $formData['nombre'],
                    'descripcion' => $formData['descripcion'],
                    'unidad' => $formData['unidad'],
                    'costo' => $formData['costo'],
                    'precio' => $formData['precio'],
                    'oferta' => $formData['oferta'],
                    'estatus' => '1'
                    
                );

               
                
                $est = new Application_Model_DbTable_Productos();
                try {
                 
                  $est->addProducto($data);

                  $this->_flashMessenger->addMessage(array('success' => 'Se ha registrado con éxito!'));
                
                $this->_redirect('/productos/');

                } catch (Exception $e) {
                
                $this->_flashMessenger->addMessage(array('success' => 'Ha Ocurrido un Error!'.$e));
                
                $this->_redirect('/productos/add');

                }

                

                
                

            
        }
        
    }

    //editar  estudiantes 
    public function editAction() {


        $auth = Zend_Auth::getInstance();
        $this->view->auth = $auth;  
        
        $id = $this->_getParam('id', 0);

        $ObjPortafolios = new Application_Model_DbTable_Portafolios();

        $this->view->categorias=$ObjPortafolios->fetchAll();
        // se envia a la vista todos los registros de usuarios

        
        

        
       // $estForm = new Application_Form_Estudiantes();
       // $this->view->estForm = $estForm;




        if ($this->getRequest()->isPost()) {
            
            $formData = $this->getRequest()->getPost();
            
            // se agrega validator para campo username
           
                              

            $id=$formData['id'];

            $slug = preg_replace('/[^A-Za-z0-9-]+/','-',$formData['nombre']);
            $slug = strtolower($slug);

                $data = array(
                    'codigo' => $formData['codigo'],
                    'categoria' => $formData['categoria'],
                    'nombre' => $formData['nombre'],
                    'slug' => $slug,
                    'descripcion_corta' => $formData['descripcion_corta'],
                    'descripcion' => $formData['descripcion'],
                    'unidad' => $formData['unidad'],
                    'costo' => $formData['costo'],
                    'oferta' => $formData['oferta'],
                    'precio' => $formData['precio']
                );
                

                $est = new Application_Model_DbTable_Productos();
                $est->updateProducto($id, $data);

         $this->_flashMessenger->addMessage(array('success' => 'Se ha Editado con éxito! $id'));

                
                $this->_redirect('productos/index');

                        

            
        } else {
            
            if ($id > 0) {
                $est = new Application_Model_DbTable_Productos();
                $res=$est->getProducto($id);

                $ObjFotos = new Application_Model_DbTable_Fotos();
                $this->view->fotos=$ObjFotos->fetchAll('id_solicitud="'.$id.'"');

               // $data=array(
                    $this->view->id=$id;
                    $this->view->dcodigo=$res['codigo'];
                    $this->view->dunidad=$res['unidad'];
                    $this->view->dcategoria=$res['categoria'];
                    $this->view->dnombre=$res['nombre'] ;
                    $this->view->ddescripcion_corta=$res['descripcion_corta'] ;
                    $this->view->ddescripcion=$res['descripcion'] ;
                    $this->view->dcosto=$res['costo'];
                    $this->view->dprecio=$res['precio'];
                    $this->view->dunidad=$res['unidad'];
                    $this->view->doferta=$res['oferta'];
                                    //    );
                //$this->view->data=$data;
            } else {
                throw new Exception('No se encontró el registro');
            }
        }
    }

    //eliminar  estudiantes 

        public function deleteAction() {

                    if ($this->getRequest()->isPost()) {
            
            $request = $this->getRequest()->getPost();
            
            if (isset($request['id']) && $request['id'] > 0) {
                
                $id = $request['id'];
                
                $objUsers = new Application_Model_DbTable_Clientes();
                
                
			try {
				$objUsers->deleteCliente($id);
				$mensaje="Se ha Eliminado el Registro Con Éxito";
				
			} catch (Exception $e) {
				$mensaje="No Se Ha Podido Eliminar el Registro. ";
			} 
				
				$this->_flashMessenger->addMessage(array('success' => $mensaje));
					
				  $this->_redirect('clientes/');
				
				
			
                
            } else {
                throw new Exception('No se encontró el registro');
            }
            
        } else {
            $this->_redirect('/clientes');
        }
    }

    //mostart cursos



//listado de asignados 

 



    /*nuevo Consulta Estudiantes 
    ----------------------------- 
    para probar las funciones 
    que ya estan hehas aqui 

    -----------------------------
    -----------------------------
    -----------------------------
    */



    //export a pdf de los miembros

    public function exportAction() {

        $this->_helper->layout->disableLayout();
        
        $params = $this->_getAllParams();
        $this->view->params = $params;
        
        $objMembers = new Application_Model_DbTable_Productos();
        $members = $objMembers->getProductos();
        $this->view->members = $members;

      
        //consulta estado y envio de objeto

        $objStates = new Application_Model_DbTable_States();
        $this->view->objStates = $objStates;
        
       $objProfession = new Application_Model_DbTable_Professions();
        $this->view->objProfession = $objProfession;

        //consulta estado y envio de objeto

        $objCities = new Application_Model_DbTable_Cities();
        $this->view->objCities = $objCities;
        
      

        //consulta de grado y evio de objeto y datos a la vista 
        
        $objgrado = new Application_Model_DbTable_Grado();
        $this->view->objgrado = $objgrado;
        
        $grado = $objgrado->fetchAll();
        $this->view->grado = $grado;
        
        $html = $this->view->render('/productos/export.phtml');
        
        require_once('assets/tcpdf/tcpdf.php');

        // create new PDF document
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heureka');
        $pdf->SetTitle('Reporte');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // add a page
        $pdf->AddPage();
        
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();

        //Close and output PDF document
        $pdf->Output('export_members.pdf', 'I');

        exit();
    }


     public function detailAction() {

        $this->_helper->layout->disableLayout();
        
        //$id = $this->_getParam('member');
        
        $id = $this->_getParam('id');

        $objMembers=new Application_Model_DbTable_Estudiantes();
        $member=$objMembers->getMemberId($id); //pdf detail 
        $this->view->mrow=$member;

        foreach ($member as $mem) {
            $foto=$mem->foto;
                $len=strlen("$foto")-3;
                $ext=substr($foto,$len,3);
        }



        $ObjAsignado=new Application_Model_DbTable_Asignado();
        $asignado=$ObjAsignado->getAsignadoUser($id); //pdf detail 
        $this->view->asignado=$asignado;


        
        $html=$this->view->render('estudiantes/detail.phtml');
        
        require_once('assets/tcpdf/tcpdf.php');

        $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heureka');
        $pdf->SetTitle('Detalles de Registro');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

#$pdf->Image($foto, 160, 45, 30, 40, $ext, '', '', true, 150, '', false, false, 1, false, false, false);

        $pdf->lastPage();
        $pdf->Output('detail_member.pdf', 'I');
        exit();
    }















        public function graciasAction(){

         $id = $this->_getParam('id', 0);

        $opt=array('layout'=>'layout');

         Zend_Layout::startMvc($opt);
        
        $params = $this->_getAllParams();
        
        $auth = Zend_Auth::getInstance();

        $this->view->auth = $auth;

        
        $ObjProductos = new Application_Model_DbTable_Productos();

        $detalles=$ObjProductos->getProducto($id);

        $vistas= $ObjProductos->updateProductoVistas($id);

        $this->view->detalles = $detalles;


        $relacionados=$ObjProductos->getproductosLimit(4, $detalles['categoria']);


        $this->view->relacionados = $relacionados;

        $ObjPortafolios = new Application_Model_DbTable_Portafolios();

        $this->view->categoria=$ObjPortafolios->fetchRow('id="'.$detalles['categoria'].'"');
        


        $ObjFotos = new Application_Model_DbTable_Fotos();
        
        $this->view->fotos=$ObjFotos->fetchAll('id_solicitud="'.$id.'"');

        $foto=$ObjFotos->fetchRow('id_solicitud="'.$id.'"');

        //consulta estado y envio de objeto

        $this->view->messages = $this->_flashMessenger->getMessages();


         $ObjProductos = new Application_Model_DbTable_Productos();

        $aleatorios=$ObjProductos->Aleatorio(4);
        
        $this->view->aleatorios=$aleatorios;



        $meta = array(
            'titulo' => $detalles['nombre'].' -  Tienda Cocomary', 
            'descripcion' => strip_tags($detalles['descripcion']), 
            'imagen' => 'http://tiendacocomary.com/assets/images/'.$foto['foto'],
            'url'=>'http://tiendacocomary.com/productos/ver/id/'.$id,
            'key' => '',
            'menu'=>'productos'
            );

         $this->view->meta=$meta;
        
  
        
    }

}

