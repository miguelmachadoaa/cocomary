$(document).ready(function(){

    $('#tb_pedidos').dataTable();

    $('.fecha').datepicker({
        format:"dd/mm/yyyy"
    });

    $('#tb_estudiantes').dataTable({
        "order":[[0, "desc"]]
    });

});




function agregarPedido(){
	$('#addCajaModal').modal('show');
}

 function agregardetalle(pedido, producto, cantidad, precio){     

    //alert('hola');

    url="/ajax/agregardetalle";

         $.post(url, {pedido, producto, cantidad, precio}, function(data) {

           /* res=JSON.parse(data);*/

            if (data) {
               
                
                $('#contenido_caja').html(data);

                     
            } else {

              
            }
         });

 }


 function cerrarcaja(id){     

    url="/ajax/cerrarcaja";

         $.post(url, {id}, function(data) {

           /* res=JSON.parse(data);*/

            if (data) {
               
                
                $('#contenido_caja').html(data);

                     
            } else {

              
            }
         });

 }

  function caja(){     

    //alert('hola');

    url="/ajax/caja";

         $.post(url, {}, function(data) {

           /* res=JSON.parse(data);*/

            if (data) {
               
                
                $('#contenido_caja').html(data);

                     
            } else {

              
            }
         });

 }

 function crearCaja(){     

    nombre=$('#nombre_modal').val();

    cliente=$('#cliente').val();

    vendedor=$('#vendedor').val();

    url="/ajax/crearcaja";

         $.post(url, {nombre, vendedor, cliente}, function(data) {

            if (data) {
                
                ///alertify.success(res2.contenido).dismissOthers();
                
                $('#contenido_caja').html(data);

               // $('.mensaje').html('<div class="alert alert-success alert-dismissable">Solicitud procesada correctamente</div>');
                
                $('#addCajaModal').modal('hide');
                     
            } else {

                $('.mensaje').html('<div class="alert alert-danger alert-dismissable">Error al procesar solicitud</div>');
                //alertify.error(res2.contenido).dismissOthers(); 

                $('#addCajaModal').modal('hide');
                    
            }

         });

 }


 function detalleCaja(id){     

    //alert('hola');

    url="/ajax/detallecaja";

         $.post(url, {id}, function(data) {

           /* res=JSON.parse(data);*/

            if (data) {
                
                ///alertify.success(res2.contenido).dismissOthers();
                
                $('#contenido_caja').html(data);

               /* $('.mensaje').html('<div class="alert alert-success alert-dismissable">Solicitud procesada correctamente</div>');
                
                $('#addCajaModal').modal('hide');*/
                     
            } else {

               /* $('.mensaje').html('<div class="alert alert-danger alert-dismissable">Error al procesar solicitud</div>');
                //alertify.error(res2.contenido).dismissOthers(); 

                $('#addCajaModal').modal('hide');*/
                     
            }




         });

 }

 function eliminardetalle(id, pedido){     

    //alert('hola');

    url="/ajax/eliminardetalle";

         $.post(url, {id, pedido}, function(data) {

           /* res=JSON.parse(data);*/

            if (data) {
               
                
                $('#contenido_caja').html(data);

                     
            } else {

              
            }
         });

 }


function pagar(id, monto){     

    $('#id_pedido').val(id);
    $('#monto_modal').val(monto);

    $('#addPagoModal').modal('show');


 }


 function pagarCaja(){     

    
    id=$('#id_pedido').val();
    monto=$('#monto_modal').val();
    tipo_pago=$('#tipo_pago').val();



    url="/ajax/pagarcaja";

         $.post(url, {id, monto, tipo_pago}, function(data) {

           /* res=JSON.parse(data);*/

            if (data) {
               
                
                $('#contenido_caja').html(data);

                $('#addPagoModal').modal('hide');

                     
            } else {

              
            }
         });

 }




function desactivarMarca(id){   

    url="/ajax/desactivarmarca";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function activarMarca(id){   

    url="/ajax/activarmarca";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function del(id){
    $('#del_id').val(id);
    $('#deleteModal').modal('show');
 }

  function deleteMarca(){   

    id=$('#del_id').val();

    url="/ajax/deletemarca";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }

  function deleteServicios(){   

    id=$('#del_id').val();

    url="/ajax/deleteservicios";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }



function desactivarServicio(id){   

    url="/ajax/desactivarservicios";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function activarServicio(id){   

    url="/ajax/activarservicios";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

   function deleteModulo(){   

    id=$('#del_id').val();

    url="/ajax/deletemodulo";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }



function desactivarModulo(id){   

    url="/ajax/desactivarmodulo";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function activarModulo(id){   

    url="/ajax/activarmodulo";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }


   function deletePortafolio(){   

    id=$('#del_id').val();

    url="/ajax/deleteportafolio";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }



function desactivarPortafolio(id){   

    url="/ajax/desactivarportafolio";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function activarPortafolio(id){   

    url="/ajax/activarportafolio";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

  function deleteValidador(){   

    id=$('#del_id').val();

    url="/ajax/deletevalidador";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }

 function deleteAuditoria(){   

    id=$('#del_id').val();

    url="/ajax/deleteauditoria";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }



 



 function deletePagina(){   

    id=$('#del_id').val();

    url="/ajax/deletepagina";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }



function desactivarPagina(id){   

    url="/ajax/desactivarpagina";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function activarPagina(id){   

    url="/ajax/activarpagina";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }





 function deleteNoticia(){   

    id=$('#del_id').val();

    url="/ajax/deletenoticia";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }



function desactivarNoticia(id){   

    url="/ajax/desactivarnoticia";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function activarNoticia(id){   

    url="/ajax/activarnoticia";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }





 function deleteSlider(){   

    id=$('#del_id').val();

    url="/ajax/deleteslider";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

 }



function desactivarSlider(id){   

    url="/ajax/desactivarslider";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function activarSlider(id){   

    url="/ajax/activarslider";

         $.post(url, {id}, function(data) {

            if (data) {
                
                $('#'+id).html(data);
                     
            } else {
        
            }

         });

 }

 function agregarTags(){

        id=$('#id').val();
        etiqueta=$('#etiqueta').val();

       url="/ajax/agregartags";

         $.post(url, {id, etiqueta}, function(data) {

            if (data) {
                
                $('.contenido').html(data);
                $('#etiqueta').val('');
                     
            } else {
        
            }

         });

 }

 function eliminarTag(tag){   

    id=$('#id').val();

    
    url="/ajax/eliminartag";

         $.post(url, {tag, id}, function(data) {

            if (data) {

                $('.contenido').html(data);
                $('#etiqueta').val('');
               
                
                     
            } else {

                $('.contenido').html(data);
                $('#etiqueta').val('');
        
            }

         });

 }




function borrar(id, modelo){

    $('#del_id').val(id);
    $('#del_modelo').val(modelo);

    $('#deleteModal').modal('show');

}

function eliminar(){

    id=$('#del_id').val();
    modelo=$('#del_modelo').val();

    url="/ajax/eliminar";

     $.post(url, {id, modelo}, function(data) {

            if (data) {
                
                $('#'+id).remove();
                $('#deleteModal').modal('hide');
                     
            } else {
        
            }

         });

}

function estatus(id, modelo, estatus){   

    url="/ajax/estatus";

         $.post(url, {id, modelo, estatus}, function(data) {

            if (data) {
                
                $('#'+id+' .estatus').html(data);
                     
            } else {
        
            }

         });

 }