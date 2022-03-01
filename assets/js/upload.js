/*$('#form_mueble').bootstrapValidator({
    message: 'Este campo es requerido',
    feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
  fields: {
        nombres: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        apellidos: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        cedula: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[VEGJvegj0-9-]*$/,
                    message: 'Solo puede contener Numeros y VEGJ Ej: V-12345678'
                }
               
            }
        },
        tlf: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },

        tlf_movil: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },
        email: {
                validators: {
                    emailAddress: {
                        message: 'Debe Ingresar un Email Valido'
                    },
                    notEmpty: {
                      message: 'Este campo no puede estar vacío'
                    }
                }
        },
        estado: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        ciudad: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        direccion: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        tipo_bien: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        cantidad_bien: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9]*$/,
                    message: 'Solo puede contener Numeros'
                }
               
            }
        },
        marca: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        modelo: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        edad_bien: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        conservacion_bien: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        tecnologia_bien: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        mantenimiento_bien: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        trabajo_bien: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        }
    }
});


$('#form_inmueble').bootstrapValidator({
    message: 'Este campo es requerido',
    feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
  fields: {
        nombres: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        apellidos: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        cedula: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[VEGJvegj0-9-]*$/,
                    message: 'Solo puede contener Numeros y VEGJ Ej: V-12345678'
                }
               
            }
        },
        tipo_inmueble: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        tlf: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },

        tlf_movil: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },
        email: {
                validators: {
                    emailAddress: {
                        message: 'Debe Ingresar un Email Valido'
                    },
                    notEmpty: {
                      message: 'Este campo no puede estar vacío'
                    }
                }
        },
        estado: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        ciudad: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        direccion: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        nombre_inmueble: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        edad_inmueble: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        construccion_inmueble: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }               
            }
        },
        terreno_inmueble: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }               
            }
        },
        tenencia_inmueble: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        uso_bien: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        topografia: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        mejoras: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        conservacion: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        condiciones_fisicas: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        acabados: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        pisos: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        habitaciones: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        banos: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        },
        puestos: {
            validators: {
                notEmpty: {
                    message: 'Debe seleccionar un elemento de la lista'
                }
            }
        }
    }
});



$('#form_presupuesto').bootstrapValidator({
    message: 'Este campo es requerido',
    feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
  fields: {
        nombres: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        apellidos: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        tlf: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },

        tlf_movil: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },
        email: {
                validators: {
                    emailAddress: {
                        message: 'Debe Ingresar un Email Valido'
                    },
                    notEmpty: {
                      message: 'Este campo no puede estar vacío'
                    }
                }
        },
        empresa: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        rif: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[VEGJvegj0-9-]*$/,
                    message: 'Solo puede contener Numeros y VEGJ Ej: V-12345678'
                }
               
            }
        },
        ciudad: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        tipo: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
               
            }
        },
        cantidad: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }, 
                regexp: {
                    regexp: /^[0-9]*$/,
                    message: 'Solo puede contener Numeros'
                }
               
            }
        }
    }
});



$('#form_validador').bootstrapValidator({
    message: 'Este campo es requerido',
    feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
  fields: {
        dpt: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
            }
        },
        cvt: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                }
            }
        },
        email: {
                validators: {
                    emailAddress: {
                        message: 'Debe Ingresar un Email Valido'
                    },
                    notEmpty: {
                      message: 'Este campo no puede estar vacío'
                    }
                }
        }
    }
});


$('#form_auditoria').bootstrapValidator({
    message: 'Este campo es requerido',
    feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
  fields: {
        nombres: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        apellidos: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[a-zA-ZáéíóúñÁÉÍÓÚÑ ]*$/,
                    message: 'Este campo solo puede contener letras (A-Z)'
                }
            }
        },
        tlf: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },

        tlf_movil: {
            validators: {
                notEmpty: {
                    message: 'Este campo no puede estar vacío'
                },
                regexp: {
                    regexp: /^[0-9-]*$/,
                    message: 'Solo puede contener Numeros y - Ej: 1234-123-1234'
                }
               
            }
        },
        email: {
                validators: {
                    emailAddress: {
                        message: 'Debe Ingresar un Email Valido'
                    },
                    notEmpty: {
                      message: 'Este campo no puede estar vacío'
                    }
                }
        }
    }
});*/

$(document).ready(function()
{
    
 id=$('#id').val();
 //alert('cargo');

  $("#fileuploader").uploadFile({
  url:"/ajax/upload/id/"+id,
  fileName:"myfile",
  showStatusAfterSuccess:false,
  showAbort:false,
  showDone:false,
    showProgress:false,
    dragDrop:false,

 
  
    onSubmit:function(files)
        {
            
        $(".loader").html("<img title='cargando'  src='/assets/img/loading.gif'>");
        
        },

    onSuccess:function(files,data,xhr)
    {
    $(".carga .row").html(data);
    $(".loader").html("");
          
    },
    onError: function(files,status,errMsg)
        {
           $(".loader").html("");
        }
  
  });



});

function enviarCorreo(){

    nombre=$('#nombre').val();
    email=$('#email').val();
    tel=$('#tel').val();
    mensaje=$('#mensaje').val();

    
       if(nombre=="" || email=="" || tel=="" || mensaje==""){

        $('#respuesta').html('<div class="alert alert-danger" role="alert">Debe llenar todos los campos del formulario.</div>'); 
          
        
        }else{

        $.ajax({
            type: "POST",
            data:{nombre, email,  mensaje, tel},
            url: "http://www.bysatlantico.com/assets/correo.php",
                
            complete: function(datos2){
                //alert(id);
                //$("#deleteModal").modal('hide');

                if (datos2.responseText=='false') {
                    
                     $('#respuesta').html('<div class="alert alert-danger" role="alert">Hubo un error al enviar su mensaje, porfavor intenta de nuevo.</div>'); 
              
                }else{
                     
                    $('#respuesta').html('<div class="alert alert-success" role="alert">Su mensaje ha sido enviado, en breve nos pondremos en contacto con Usted.</div>'); 

                    $('#nombre').val('');
                    $('#email').val('');
                    $('#mensaje').val('');
                    $('#tel').val('');
                }

            
            }
        });

        $.ajax({
            type: "POST",
            data:{nombre, email,  mensaje, tel},
            url:"/ajax/savecontacto",
                
            
        });


        }



}



function borrarimagen(id, idsolicitud){

        $.ajax({
            type: "POST",
            data:{id, idsolicitud},
            url: "/ajax/borrarimagen",
                
            complete: function(datos){
                

                $(".carga").html(datos.responseText);

            
            }
        });

}



$('.btnComprar').on('click', function(){

    img=$(this).data('img');
    producto=$(this).data('producto');
    precio=$(this).data('precio');
    url=$(this).data('url');

    $('.enviarCorreoCompra').data('url', url);

    $('#imgpro').attr('src', img);
    $('#nombrepro').html(producto);
    $('#preciopro').html(precio);
    $('#producto_compra').val(producto+' '+precio);



    $('#ComprarModal').modal('show');

});



$('.enviarCorreoCompra').on('click', function(){

    //alert('click');
    //
        url=$(this).data('url');

        //console.log(url);

        producto=$('#producto_compra').val();

        //alert(producto);

        nombre=$('#nombre_compra').val();
        telefono=$('#telefono_compra').val();
        direccion=$('#direccion_compra').val();
        barrio=$('#barrio_compra').val();
        ciudad=$('#ciudad_compra').val();

        email='Compra en cocomary';

        tel=telefono;

        mensaje=direccion+' - '+barrio+' -  '+ciudad+' Producto: '+producto;


         $.ajax({
            type: "POST",
            data:{nombre, email,  mensaje, tel},
            url:"/ajax/savecontacto",
        });

    
       if(nombre=="" || direccion=="" || telefono=="" || barrio=="" || ciudad==""){

        $('.respuestacompra').html('<div class="alert alert-danger" role="alert">Debe llenar todos los campos del formulario.</div>'); 
          
        
        }else{

            mensaje=direccion+' - '+barrio+' -  '+ciudad+' Producto: '+producto;

        $.ajax({
            type: "POST",
            data:{nombre, email,  mensaje, tel},
            url: "http://www.tiendacocomary.com/assets/correocompra.php",
                
            complete: function(datos2){

                if (datos2.responseText=='false') {
                    
                     $('.respuestacompra').html('<div class="alert alert-danger" role="alert">Hubo un error al enviar su mensaje, porfavor intenta de nuevo.</div>'); 
              
                }else{

                    $.ajax({
                        type: "POST",
                        data:{nombre, email,  mensaje, tel},
                        url:"/ajax/savecontacto",
                            
                        
                    });



                    $('#ComprarModal').modal('hide');

                    window.location.href = 'https://tiendacocomary.com/'+url;
                    //$(location).attr('href', url);
                     
                    $('.respuestacompra').html('<div class="alert alert-success" role="alert">Su mensaje ha sido enviado, en breve nos pondremos en contacto con Usted.</div>'); 

                    $('#nombre_compra').val('');
                    $('#telefono_compra').val('');
                    $('#direccion_compra').val('');
                    $('#barrio_compra').val('');
                    $('#ciudad_compra').val('');

                }

            
            }
        });

       


        }





});





