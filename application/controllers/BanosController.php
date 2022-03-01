<?php

class BanosController extends Zend_Controller_Action {
    
    protected $_flashMessenger = null;
    
    public function init() {
        
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');

        $Obj= new Application_Model_DbTable_Identidad();
        
                $this->view->identidad=$Obj->get('1');
        
    }

     public function indexAction(){

         $opt=array('layout'=>'layout');

         Zend_Layout::startMvc($opt);

         $ObjPaginas = new Application_Model_DbTable_Paginas();
        $this->view->pagina = $ObjPaginas->fetchRow('grupo="banos"');



        $ObjSliders = new Application_Model_DbTable_Sliders();
        // se envia a la vista todos los registros de usuarios
        $this->view->sliders = $ObjSliders->fetchAll();
        
       $ObjBanos= new Application_Model_DbTable_Banos();

      
        $this->view->elementos = $ObjBanos->fetchAll('estatus=1');

        
          $ObjNoticias = new Application_Model_DbTable_Noticias();
        // se envia a la vista todos los registros de usuarios
        $this->view->noticias = $ObjNoticias->Aleatorio();
        
        // se envia a la vista los mensajes de acciones
        $this->view->messages = $this->_flashMessenger->getMessages();
     
        
    }


    public function listAction(){
        
       $ObjBanos= new Application_Model_DbTable_Banos();

      
        $this->view->elementos = $ObjBanos->fetchAll();
        
        // se envia a la vista los mensajes de acciones
        $this->view->messages = $this->_flashMessenger->getMessages();
    }

  
     public function verAction(){

        $id = $this->_getParam('id', 1);
        
        // se instancia el modelo users
        $ObjModulos= new Application_Model_DbTable_Modulos();

        $ObjEtiquetas = new Application_Model_DbTable_Etiquetas();
        // se envia a la vista todos los registros de usuarios
        $this->view->noticias = $ObjModulos->fetchAll($id);

        //var_dump($ObjNoticias->getNoticiaId($id))  ;      
       
        $this->view->etiquetas = $ObjEtiquetas->getEtiquetaID($id);

        $tags=$ObjEtiquetas->getEtiquetaID($id);

        $data = array();

        foreach ($tags as $key ) {
            $data[]=$key['descripcion'];
        }

        $related=$ObjNoticias->getNoticiasTags($data);

        $this->view->related=$related; 
        
        // se envia a la vista los mensajes de acciones
        $this->view->messages = $this->_flashMessenger->getMessages();
        
       
        
    }

     private function getFileExtension($filename)
        {
            $fext_tmp = explode('.',$filename);
            return $fext_tmp[(count($fext_tmp) - 1)];
        }

     public function addAction(){


        $auth = Zend_Auth::getInstance();

        $this->view->auth = $auth;
        
        if ($this->getRequest()->isPost()) {
            
            $formData = $this->getRequest()->getPost();
            

        $dest_dir = "assets/img/";
            
            /* Uploading Document File on Server */
            $upload = new Zend_File_Transfer_Adapter_Http();
            $upload->setDestination($dest_dir)
                         ->addValidator('Count', false, 1)
                         ->addValidator('Size', false, 5242880)
                         ->addValidator('Extension', false, 'jpg,png,gif');
            $files = $upload->getFileInfo();
                  
            if($upload->receive()) {
        
            
            $mime_type = $upload->getMimeType('doc_path');
            $fname = $upload->getFileName('doc_path');
            $size = $upload->getFileSize('doc_path');
            $file_ext = $this->getFileExtension($fname);            
            $new_file = $dest_dir.md5(mktime()).'.'.$file_ext;
            
            $filterFileRename = new Zend_Filter_File_Rename(
                array(
                    'target' => $new_file, 'overwrite' => true
            ));
            
            $filterFileRename->filter($fname);

            if (file_exists($new_file))
            {
                $request = $this->getRequest();
                $caption = $request->getParam('caption');
                
                $html = 'Orig Filename: '.$fname.'<br />';
                $html .= 'New Filename: '.$new_file.'<br />';
                $html .= 'File Size: '.$size.'<br />';
                $html .= 'Mime Type: '.$mime_type.'<br />';
                $html .= 'Caption: '.$caption.'<br />';
            }
            else
            {
                $html = 'Unable to upload the file!';
            }

        }else{
            $new_file='assets/img/default.jpg';
        }

        unset($formData['controller'],$formData['action'],$formData['module'],$formData['MAX_FILE_SIZE'],$formData['btn_submit']);


            $formData['imagen']=$new_file;

            $data = array(
            'id' => $formData['id'],
            'titulo' => $formData['titulo'],
            'descripcion' => $formData['descripcion'],
            'imagen'=>$new_file
            );
                
        $ObjBanos= new Application_Model_DbTable_Banos();
                $ObjBanos->add($formData);

                $this->_flashMessenger->addMessage(array('success' => 'Se ha registrado con éxito!'));
                
                $this->_redirect('/banos/list');
                

            
        }
        
    }

    public function editAction() {
        
        $id = $this->_getParam('id', 0);
        
        

        if ($this->getRequest()->isPost()){
            
            $formData = $this->getRequest()->getPost();
            

        $dest_dir = "assets/img/";
            
            /* Uploading Document File on Server */
            $upload = new Zend_File_Transfer_Adapter_Http();
            $upload->setDestination($dest_dir)
                         ->addValidator('Count', false, 1)
                         ->addValidator('Size', false, 5242880)
                         ->addValidator('Extension', false, 'jpg,png,gif');
            $files = $upload->getFileInfo();
         
           
               if($upload->receive()) {
          
            
            $mime_type = $upload->getMimeType('doc_path');
            $fname = $upload->getFileName('doc_path');
            $size = $upload->getFileSize('doc_path');
            $file_ext = $this->getFileExtension($fname);            
            $new_file = $dest_dir.md5(mktime()).'.'.$file_ext;
            
            $filterFileRename = new Zend_Filter_File_Rename(
                array(
                    'target' => $new_file, 'overwrite' => true
            ));
            
            $filterFileRename->filter($fname);

            
 unset($formData['controller'],$formData['action'],$formData['module'],$formData['MAX_FILE_SIZE'],$formData['btn_submit']);


            $formData['imagen']=$new_file;


        }else{
           

           unset($formData['controller'],$formData['action'],$formData['module'],$formData['MAX_FILE_SIZE'],$formData['btn_submit']);

        }

        $id=$formData['id'];
           
        $ObjBanos= new Application_Model_DbTable_Banos();
                
                $ObjBanos->upd($id, $formData);

                $this->_flashMessenger->addMessage(array('success' => 'Se ha Actualizado con éxito!'));
                
                $this->_redirect('/banos/list');
            
            // se agrega validator para campo username
            
            
            
        } else {
            
            if ($id > 0) {
                
               $ObjBanos= new Application_Model_DbTable_Banos();
        
                $this->view->elemento=$ObjBanos->get($id);



            } else {
                throw new Exception('No se encontró el registro');
            }
        }
    }









}
