<?php
class AjaxController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout('layout')->disableLayout();
    }


    public function allproductosAction(){

      $objProductos = new Application_Model_DbTable_Productos();

     # $ObjProductos->Aleatorio(8);

      $this->view->productos=$objProductos->fetchAll()->toArray();

    }




    public function listadoproductosAction(){

      $objProductos = new Application_Model_DbTable_Productos();

     # $ObjProductos->Aleatorio(8);

      $this->view->productos=$objProductos->Aleatorio(8)->toArray();

    }

    public function getcategoriasAction(){

      $objCategorias = new Application_Model_DbTable_Portafolios();

     # $ObjProductos->Aleatorio(8);

      $this->view->categorias=$objCategorias->fetchAll()->toArray();

    }


     public function getproductocategoriaAction(){

      $id = $this->_getParam('id');

      $objProductos = new Application_Model_DbTable_Productos();

      $productos=$objProductos->Categoria($id)->toArray();

      $this->view->productos=$productos;

    }




    public function getproductoAction(){

      $id = $this->_getParam('id');

      $objProductos = new Application_Model_DbTable_Productos();

      $producto=$objProductos->fetchRow('slug="'.$id.'"');

      $this->view->producto=$producto;

      $ObjFotos = new Application_Model_DbTable_Fotos();

      $foto=$ObjFotos->fetchRow('id_solicitud="'.$producto['id'].'"');

      $this->view->foto=$foto;

    }


    //endconexiones vue 

          public function borrarimagenAction()
    {

        $id = $this->_getParam('id');
        $idsolicitud = $this->_getParam('idsolicitud');

        $ObjFotos = new Application_Model_DbTable_Fotos();
        
        if ($id) {
        
          $ObjFotos->del($id);
           
        }

         $this->view->fotos=$ObjFotos->fetchAll('id_solicitud="'.$idsolicitud.'"');
       
    }

    

public function uploadAction(){

      $ObjFotos = new Application_Model_DbTable_Fotos();


      $id = $this->_getParam('id');

        $auth = Zend_Auth::getInstance();
        $this->view->auth = $auth;
        $id_user=$auth->getIdentity()->uid;
    
        $output_dir = "assets/images/";

        $fileName='';

            if(isset($_FILES["myfile"]))
            {
                $ret = array();

                $error =$_FILES["myfile"]["error"];
                //You need to handle  both cases
                //If Any browser does not support serializing of multiple files using FormData() 
                if(!is_array($_FILES["myfile"]["name"])) //single file
                {
                    $fileName = $_FILES["myfile"]["name"];



                    //$ext=explode(".", $fileName);

                    //$ext = array_shift($ext);

                    $code = md5(uniqid(rand(), true));

                    $nombre=substr($code, 0, 8);

                    //$nombreArchivo=$id_user.$nombre.'.'.$ext;

                    $file=$nombre.$fileName;
  

                    move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$file);


                    $ret[]= $file;
                }
                else  //Multiple files, file[]
                {
                  $fileCount = count($_FILES["myfile"]["name"]);
                  for($i=0; $i < $fileCount; $i++)
                  {

                    $code = md5(uniqid(rand(), true));

                    $nombre=substr($code, 0, 8);


                    $fileName = $_FILES["myfile"]["name"][$i];

                    $file=$nombre.$fileName;


                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$file);

                    
                    $ret[]= $file;
                  }
                
                }
                //echo json_encode($ret);
                $data = array('id_solicitud' =>$id , 'foto' =>$file  );
                $ObjFotos->add($data);
                 $this->view->fotos=$ObjFotos->fetchAll('id_solicitud="'.$id.'"');
            }
            
    }


        public function eliminarAction()
    {

        $id = $this->_getParam('id');
        $modelo = $this->_getParam('modelo');

        
        if ($id) {

          switch ($modelo) {
            case 'auditoria':
                $Obj = new Application_Model_DbTable_Auditoria();
              # code...
              break;

          case 'inmueble':
                $Obj = new Application_Model_DbTable_Inmueble();
              # code...
              break;

          case 'mueble':
                $Obj = new Application_Model_DbTable_Mueble();
              # code...
              break;

          case 'validador':
                $Obj = new Application_Model_DbTable_Validador();
              # code...
              break;

          case 'presupuesto':
                $Obj = new Application_Model_DbTable_Presupuesto();
              # code...
              break;

              case 'cocina':
                $Obj = new Application_Model_DbTable_Cocinas();
              # code...
              break; 

              case 'topes':
                $Obj = new Application_Model_DbTable_Topes();
              # code...
              break;   

              case 'mobiliario':
                $Obj = new Application_Model_DbTable_Mobiliario();
              # code...
              break; 

              case 'dormitorios':
                $Obj = new Application_Model_DbTable_Dormitorios();
              # code...
              break;  

              case 'banos':
                $Obj = new Application_Model_DbTable_Banos();
              # code...
              break;  

               case 'closets':
                $Obj = new Application_Model_DbTable_Closets();
              # code...
              break;          
            default:
              # code...
              break;
          }

        // se envia a la vista todos los registros de usuarios
        
           $res=$Obj->del($id);

           echo $res;

        }

        

       
    }



    public function estatusAction()
    {

        $id = $this->_getParam('id');
        $modelo = $this->_getParam('modelo');
        $estatus = $this->_getParam('estatus');

        
        if ($id) {

          switch ($modelo) {
            case 'auditoria':
                $Obj = new Application_Model_DbTable_Auditoria();
              # code...
              break;

              case 'aerolinea':
                $Obj = new Application_Model_DbTable_Aerolineas();
              # code...
              break;

          case 'inmueble':
                $Obj = new Application_Model_DbTable_Inmueble();
              # code...
              break;

          case 'mueble':
                $Obj = new Application_Model_DbTable_Mueble();
              # code...
              break;

          case 'validador':
                $Obj = new Application_Model_DbTable_Validador();
              # code...
              break;

          case 'presupuesto':
                $Obj = new Application_Model_DbTable_Presupuesto();
              # code...
              break;

          case 'pagina':
                $Obj = new Application_Model_DbTable_Paginas();
              # code...
              break;

          case 'hoteles':
                $Obj = new Application_Model_DbTable_Hoteles();
              # code...
              break;     

               case 'cocina':
                $Obj = new Application_Model_DbTable_Cocinas();
              # code...
              break; 

              case 'topes':
                $Obj = new Application_Model_DbTable_Topes();
              # code...
              break;   

              case 'mobiliario':
                $Obj = new Application_Model_DbTable_Mobiliario();
              # code...
              break; 

              case 'dormitorios':
                $Obj = new Application_Model_DbTable_Dormitorios();
              # code...
              break;  

              case 'banos':
                $Obj = new Application_Model_DbTable_Banos();
              # code...
              break;  

               case 'closets':
                $Obj = new Application_Model_DbTable_Closets();
              # code...
              break;               
            default:
              # code...
              break;
          }

          $data = array('estatus' => $estatus );


       
        // se envia a la vista todos los registros de usuarios
        
           $res=$Obj->upd($id, $data);

           $this->view->modelo=$modelo;
           $this->view->id=$id;
           $this->view->estatus=$estatus;

           

        }

        

       
    }

     public function savecontactoAction()
    {

        $nombre = $this->_getParam('nombre');

        $email = $this->_getParam('email');
        $mensaje = $this->_getParam('mensaje');
        $tel = $this->_getParam('tel');
        
        if ($email) {

        $ObjContacto = new Application_Model_DbTable_Contacto();
        // se envia a la vista todos los registros de usuarios
        

        $data = array(
          'nombre' =>$nombre, 
          'email' =>$email, 
          'mensaje' =>$mensaje, 
          'telefono' =>$tel, 
          );

           $res=$ObjContacto->add($data);

        }

        // $this->view->etiquetas=$ObjEtiquetas->getEtiquetasIdNoticia($id);

       
    }



    public function eliminartagAction()
    {

        $id = $this->_getParam('id');

        $tag = $this->_getParam('tag');
        
        if ($id) {

        $ObjEtiquetas = new Application_Model_DbTable_Etiquetas();
        // se envia a la vista todos los registros de usuarios
        
           $res=$ObjEtiquetas->deleteEtiqueta($tag);

        }

         $this->view->etiquetas=$ObjEtiquetas->getEtiquetasIdNoticia($id);

       
    }


    public function agregartagsAction()
    {

        $id = $this->_getParam('id');
        $etiqueta = $this->_getParam('etiqueta');
            if ($id) {

        $ObjEtiquetas = new Application_Model_DbTable_Etiquetas();
        // se envia a la vista todos los registros de usuarios
       
        
        $tags=explode(',', $etiqueta);

        foreach ($tags as $tag) {
            
             $data = array(
            'id_noticia' => $id,
            'descripcion' => trim($tag),
            'estatus' => '1'
            );

           $res=$ObjEtiquetas->addEtiqueta($data);

        }


         $this->view->etiquetas=$ObjEtiquetas->getEtiquetasIdNoticia($id);

        }
    }


/*inicio de funciones noticias */

        public function desactivarnoticiaAction()
    {

        $id = $this->_getParam('id');
            if ($id) {

                 $ObjNoticias = new Application_Model_DbTable_Noticias();
             


              $data = array(
                'estatus' => '0'
                );

              $ObjNoticias->updateNoticia($id, $data);

              $this->view->noticia=$ObjNoticias->getNoticiaUn($id);

        }
    }

      public function activarnoticiaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                 $ObjNoticias = new Application_Model_DbTable_Noticias();
                
             


              $data = array(
                'estatus' => '1'
                );

              $ObjNoticias->updateNoticia($id, $data);

              $this->view->noticia=$ObjNoticias->getNoticiaUn($id);

        }
      
    }

     public function deletenoticiaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                $ObjNoticias = new Application_Model_DbTable_Noticias();
                

              $res=$ObjNoticias->del($id);

              echo $res;

        }
      
    }

/*fin de funciones noticias */






    public function desactivarsliderAction()
    {

        $id = $this->_getParam('id');
            if ($id) {

                 $ObjSliders = new Application_Model_DbTable_Sliders();
             


              $data = array(
                'estatus' => '0'
                );

              $ObjSliders->updateSliders($id, $data);

              $this->view->slider=$ObjSliders->getSliders($id);

        }
    }

      public function activarsliderAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                 $ObjSliders = new Application_Model_DbTable_Sliders();
             


              $data = array(
                'estatus' => '1'
                );

              $ObjSliders->updateSliders($id, $data);

              $this->view->slider=$ObjSliders->getSliders($id);

        }
      
    }

     public function deletesliderAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                  $ObjSliders = new Application_Model_DbTable_Sliders();
                
             
                

              $res=$ObjSliders->deleteSliders($id);

              echo $res;

        }
      
    }

     public function deletesvalidadorAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                  $ObjValidador = new Application_Model_DbTable_Validador();
                
              $res=$ObjValidador->del($id);

              echo $res;
        }
      

    }

     public function deletesauditoriaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                  $Obj = new Application_Model_DbTable_Auditoria();
                

              $res=$Obj->del($id);

              echo $res;

        }
      
    }



    

         public function desactivarpaginaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                $ObjPaginas = new Application_Model_DbTable_Paginas();
             


              $data = array(
                'estatus' => '0'
                );

              $ObjPaginas->updatePagina($id, $data);

              $this->view->pagina=$ObjPaginas->getPagina($id);

        }
      
    }

      public function activarpaginaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                $ObjPaginas = new Application_Model_DbTable_Paginas();
             


              $data = array(
                'estatus' => '1'
                );

              $ObjPaginas->updatePagina($id, $data);

              $this->view->pagina=$ObjPaginas->getPagina($id);

        }
      
    }

     public function deletepaginaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                 $ObjPaginas = new Application_Model_DbTable_Paginas();
                
             
                

              $res=$ObjPaginas->deletePagina($id);

              echo $res;

        }
      
    }



        public function desactivarportafolioAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                $ObjPortafolios = new Application_Model_DbTable_Portafolios();
             


              $data = array(
                'estatus' => '0'
                );

              $ObjPortafolios->updatePortafolios($id, $data);

              $this->view->portafolio=$ObjPortafolios->getPortafolios($id);

        }
      
    }

      public function activarportafolioAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                 $ObjPortafolios = new Application_Model_DbTable_Portafolios();
             
              $data = array(
                'estatus' => '1'
                );

              $ObjPortafolios->updatePortafolios($id, $data);

              $this->view->portafolio=$ObjPortafolios->getPortafolios($id);

        }
      
    }

     public function deleteportafolioAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                 $ObjPortafolios = new Application_Model_DbTable_Portafolios();
                
             
                

              $res=$ObjPortafolios->deletePortafolios($id);

              echo $res;

        }
      
    }

    public function desactivarmoduloAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                $ObjModulos = new Application_Model_DbTable_Modulos();
             


              $data = array(
                'estatus' => '0'
                );

              $ObjModulos->updateModulos($id, $data);

              $this->view->modulo=$ObjModulos->getModulos($id);

        }
      
    }

      public function activarmoduloAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                $ObjModulos = new Application_Model_DbTable_Modulos();
             
              $data = array(
                'estatus' => '1'
                );

              $ObjModulos->updateModulos($id, $data);

              $this->view->modulo=$ObjModulos->getModulos($id);

        }
      
    }

     public function deletemoduloAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

                $ObjModulos = new Application_Model_DbTable_Modulos();
             
                

              $res=$ObjModulos->deleteModulos($id);

              echo $res;

        }
      
    }


public function desactivarserviciosAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

             $objServicios = new Application_Model_DbTable_Servicios();


              $data = array(
                'estatus' => '0'
                );

              $objServicios->upd($id, $data);

              $this->view->servicio=$objServicios->get($id);

        }
      
    }

      public function activarserviciosAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

             $objServicios = new Application_Model_DbTable_Servicios();

              $data = array(
                'estatus' => '1'
                );

              $objServicios->upd($id, $data);

              $this->view->servicio=$objServicios->get($id);

        }
      
    }

     public function deleteserviciosAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

             $objServicios = new Application_Model_DbTable_Servicios();
                

              $res=$objServicios->del($id);

              echo $res;

        }
      
    }


    public function desactivarmarcaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

             $objMarcas = new Application_Model_DbTable_Marcas();

              $data = array(
                'estatus' => '0'
                );

              $objMarcas->upd($id, $data);

              $this->view->marca=$objMarcas->get($id);

        }
      
    }

      public function activarmarcaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

             $objMarcas = new Application_Model_DbTable_Marcas();

              $data = array(
                'estatus' => '1'
                );

              $objMarcas->upd($id, $data);

              $this->view->marca=$objMarcas->get($id);

        }
      
    }

     public function deletemarcaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {

             $objMarcas = new Application_Model_DbTable_Marcas();

              $res=$objMarcas->del($id);

              echo $res;

        }
      
    }




    
     public function pagarcajaAction()
    {

        $id = $this->_getParam('id');

        $monto = $this->_getParam('monto');

         $tipo_pago = $this->_getParam('tipo_pago');

        if ($id) {


              $objPago = new Application_Model_DbTable_Pago();

              $data = array(
                'id_pedido' => $id, 
                'tipo_pago' => $tipo_pago,
                'monto' => $monto

                );

              $objPago->add($data);

            
            $this->view->pagado=$objPago->getPagado($id);


             $objProductos = new Application_Model_DbTable_Productos();

             $this->view->productos=$objProductos->fetchAll();

             $objDetalles = new Application_Model_DbTable_Detalle();
          

             $detalles=$objDetalles->getDetallePedido($id);

             $this->view->detalles=$detalles;


              $objPedidos = new Application_Model_DbTable_Pedidos();

              $total=$objPedidos->updatePedidoTotal($id);

           

             $this->view->pedido=$objPedidos->fetchRow('id="'.$id.'"');

        }
      
    }

    public function eliminardetalleAction()
    {

        $detalle = $this->_getParam('id');

        $pedido = $this->_getParam('pedido');


        if ($detalle) {



             $objPedidos = new Application_Model_DbTable_Pedidos();

             $this->view->pedido=$objPedidos->fetchRow('id="'.$pedido.'"');


             $objProductos = new Application_Model_DbTable_Productos();

             $this->view->productos=$objProductos->fetchAll();

             $objDetalles = new Application_Model_DbTable_Detalle();
          

             $objDetalles->deleteDetalle($detalle);

             $detalles=$objDetalles->getDetallePedido($pedido);

             $this->view->detalles=$detalles;


              $objPedidos = new Application_Model_DbTable_Pedidos();

              $total=$objPedidos->updatePedidoTotal($pedido);

              


             $this->view->pedido=$objPedidos->fetchRow('id="'.$pedido.'"');

        }
      
    }




    public function agregardetalleAction()
    {

        $pedido = $this->_getParam('pedido');
        $producto = $this->_getParam('producto');
        $cantidad = $this->_getParam('cantidad');
        $precio = $this->_getParam('precio');


        if ($pedido) {



             $objPedidos = new Application_Model_DbTable_Pedidos();

             $this->view->pedido=$objPedidos->fetchRow('id="'.$pedido.'"');


             $objProductos = new Application_Model_DbTable_Productos();

             $this->view->productos=$objProductos->fetchAll();

             $objDetalles = new Application_Model_DbTable_Detalle();

             $total=$cantidad*$precio;

             $data = array(
                'id_pedido' =>$pedido , 
                'id_producto' =>$producto , 
                'cantidad' =>$cantidad , 
                'precio' => $precio, 
                'total' => $total, 

                );

             $objDetalles->addDetalle($data);

             $detalles=$objDetalles->getDetallePedido($pedido);

             $this->view->detalles=$detalles;




              $objPedidos = new Application_Model_DbTable_Pedidos();

              $total=$objPedidos->updatePedidoTotal($pedido);

              $objPago = new Application_Model_DbTable_Pago();

            $this->view->pagado=$objPago->getPagado($pedido);


             $this->view->pedido=$objPedidos->fetchRow('id="'.$pedido.'"');

        }
      
    }

    public function detallecajaAction()
    {

        $id = $this->_getParam('id');

        if ($id) {



             $objPedidos = new Application_Model_DbTable_Pedidos();

             $this->view->pedido=$objPedidos->fetchRow('id="'.$id.'"');


             $objProductos = new Application_Model_DbTable_Productos();

             $this->view->productos=$objProductos->fetchAll();

             $objDetalles = new Application_Model_DbTable_Detalle();

             $detalles=$objDetalles->getDetallePedido($id);

             $this->view->detalles=$detalles;

              $objPago = new Application_Model_DbTable_Pago();

            $this->view->pagado=$objPago->getPagado($id);

        }
      
    }


    public function crearcajaAction()
    {
        $nombre = $this->_getParam('nombre');

        $vendedor = $this->_getParam('vendedor');

        $cliente = $this->_getParam('cliente');
        
        
         $auth = Zend_Auth::getInstance();
        
        $user_id = $auth->getIdentity()->uid;

        if ($vendedor) {
           
            
            $objPedidos = new Application_Model_DbTable_Pedidos();

            $data = array(
                'id_user' =>$user_id,
                'id_cliente' =>$cliente,
                'id_empleado' =>$vendedor,
                'total' =>'0',
                'descripcion'=>$nombre
                );
           
            $addPedido=$objPedidos->addPedido($data);
            

            //$estatus=$objEstatus->fetchAll();

            $html='';
            
            $pedido=$objPedidos->fetchAll('status="1"');

                $this->view->pedido=$pedido;               
           

        }
    }


    private function generaCaja(){

         $objPedidos = new Application_Model_DbTable_Pedidos();

         $pedido=$objPedidos->fetchAll('status="1"');

         $this->view->pedido=$pedido;

 
    }


     public function cajaAction(){

         $objPedidos = new Application_Model_DbTable_Pedidos();

         $pedido=$objPedidos->fetchAll('status="1"');

         $this->view->pedido=$pedido;

    }

         public function cerrarcajaAction(){

        $id = $this->_getParam('id');

         
         $objPedidos = new Application_Model_DbTable_Pedidos();


         $data = array('status' => '2' );

         $objPedidos->updatePedido($id, $data);

         $pedido=$objPedidos->fetchAll('status="1"');

         $this->view->pedido=$pedido;

    }


      public function hakayAction()
    {

        

          $ObjResources = new Application_Model_DbTable_Resources();
                
             
              $data = array(
                'resource' => 'index'
                );
             
             $ObjResources->add($data);

       
    }

    public function getrecibidosAction(){

      $this->getHelper('Layout')
         ->disableLayout();

    $this->getHelper('ViewRenderer')
         ->setNoRender();

    $this->getResponse()
         ->setHeader('Content-Type', 'application/json');

      $objPedidos = new Application_Model_DbTable_Pedidos();
      
      $recibidos = $objPedidos->getPedidosEstado(1);
        
      echo  json_encode($recibidos->toArray());

      return;
    }


     public function getenviadosAction(){

      $this->getHelper('Layout')
         ->disableLayout();

    $this->getHelper('ViewRenderer')
         ->setNoRender();

    $this->getResponse()
         ->setHeader('Content-Type', 'application/json');

      $objPedidos = new Application_Model_DbTable_Pedidos();
      
      $recibidos = $objPedidos->getPedidosEstado(5);
        
      echo  json_encode($recibidos->toArray());

      return;
    }




}