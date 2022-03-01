 
Vue.component('hola-mundo', {
    template: '<h1> Hola mundo </h1>'
  });

Vue.component('articulos', {
    props:['datos'],
    template: `<div>
                 <table class="table table-responsive" >
                 <tr><td>Código.</td><td>Descripción.</td><td>Precio.</td></tr>
                 <tr v-for="art in datos"><td>{{art.codigo}}</td><td>{{art.descripcion}}</td><td>{{art.precio}}</td></tr>
                 </table>
               </div>`
  });


Vue.component('ventas', {
    props:['datos'],
    template: `<div>
                 <table class="table table-responsive" >
                 <tr>
                   <td>Código</td>
                   <td>Telefono</td>
                   <td>Nombre</td>
                   <td>Direccion</td>
                   <td>Total</td>
                   <td>Costo</td>
                   <td>Fecha</td>
                 </tr>
                 <tr v-for="dato in datos">
                 <td>{{dato.id}}</td>
                 <td>{{dato.tlf}}</td>
                 <td>{{dato.nombre}}</td>
                 <td>{{dato.direccion}}</td>
                 <td>{{dato.total}}</td>
                 <td>{{dato.costo}}</td>
                 <td>{{dato.fecha}}</td>
                 </tr>
                 </table>
               </div>`
  });





Vue.component('selector-numerico', {
    props:['valor', 'minimo', 'maximo'],
    template: `<div><span style="width:1rem;display:inline-block;background:#ff0">{{cant}}</span>
                 <button v-on:click="incrementar()">+</button>
                 <button v-on:click="decrementar()">-</button>
               </div>`,
    data: function() {
      return {
        cant: this.valor
      }
    },
    methods: {
      incrementar: function() {
        if (this.cant<this.maximo)
          this.cant++;
      },
      decrementar: function() {
        if (this.cant>this.minimo)
          this.cant--;
      }      
    }
  });



 var app=new Vue({
      el: '#aplicacion',
      data:{ 
        actividad: '',
        actividades: [],
        edad : 0,
        contador: 0,
        lenguajes: ['C', 'C++', 'C#', 'Java', 'Ruby', 'Kotlin'],
        articulos: [{
                    codigo: 1, 
                    descripcion: 'papas',
                    precio: 12.52
                   },{
                    codigo: 2, 
                    descripcion: 'naranjas',
                    precio: 21
                   },{
                    codigo: 3, 
                    descripcion: 'peras',
                    precio: 18.20
                   }],
        codigo: '',
        descripcion: '',
        precio: '',
        nombre: '',
        apellido: '',
         tamañoFuente: 20,
         colorParrafo: '#000',
         ventas:[],
         enviados:[]
      },
      methods: {
        actualizarLista: function() {
          this.actividades.push(this.actividad);
          this.actividad = '';
          this.contador=this.contador+2;
          this.edad=this.contador;
        },
        reiniciar:function(){
            this.actividades=[];
            this.contador=0;
        },
        agregarArticulo: function() {
          this.articulos.push({
                                codigo: this.codigo,
                                descripcion: this.descripcion,
                                precio: this.precio
                              });
          this.codigo = '';
          this.descripcion = '';
          this.precio = '';
        },
        recuperarVentas: function () {
          this.$http.get('http://cocomary.site/ajax/getRecibidos').then(function (respuesta) {
                 this.ventas=respuesta.body              
          })
        }
      },
       computed:{
        nombreCompleto: function () {
           return this.nombre + ' ' + this.apellido;
        }
       },
       created: function () {
            this.recuperarVentas();
            this.$http.get('http://cocomary.site/ajax/getenviados').then(function (respuesta) {
                 this.enviados=respuesta.body              
          });
        }

    });