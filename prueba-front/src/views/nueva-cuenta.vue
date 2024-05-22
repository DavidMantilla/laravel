<script setup>
import { onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const nuevoCliente = ref(false);
const nuevaCuenta = ref(false);
const nCuenta = ref(false);
const cedulaCliente = ref('');
const status = ref(0);
const error= ref("");
const data = ref({
   cedula: "",
   nombre: "",
   email: "",
});
const datacuenta = ref({
   cuenta: "",
   saldo: "",
   tipo_cuenta: "ahorros",
   cliente: ""
});
const router = useRouter();
let token = store.state.token;


onMounted(() => {



});

function cuenta() {

   if (cedulaCliente.value != "") {


      fetch(

         "http://127.0.0.1:8000/api/cliente/" + cedulaCliente.value,
         {
            method: "get",
            headers: {
               "Content-Type": "application/json",
               'Authorization': `Bearer ${token}`,
               'Access-Control-Allow-Origin': '*'
            },
            mode: "cors",
         }).then((res) => {

            status.value = res.status;

            return res.json()

         })
         .catch(error => console.error(error))
         .then((response) => {
            console.log(response);
            if (response.cedula != undefined) {

               nuevaCuenta.value = true;
               nuevoCliente.value = false;
               cedulaCliente.value = "";

               data.value = response
               datacuenta.value.cliente = data.value.cedula
            }
            if (status.value == "401") {

               store.commit("setToken", "");
               router.push('/');

            }
            if (status.value == "404") {
               nuevaCuenta.value = false;
               nuevoCliente.value = true;
               data.value = {};
            }



         });
   }


}

function cliente() {
   nuevoCliente.value = !nuevoCliente.value;
   nuevaCuenta.value = false;
   nCuenta.value = false;
   data.value = {};
}


function creacliente() {

   fetch(

      "http://127.0.0.1:8000/api/cliente",
      {
         method: "post",
         headers: {
            "Content-Type": "application/json",
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Origin': '*'
         },
         body: JSON.stringify({

            "cedula": data.value.cedula,
            "nombre": data.value.nombre,
            "email": data.value.email,
         }),
         mode: "cors",
      }).then((res) => {

         status.value = res.status;

         return res.json()

      })
      .catch(error => console.error(error))
      .then((response) => {
         console.log(response);
         if (response.cedula != undefined) {

            nuevaCuenta.value = true;
            nuevoCliente.value = false;
            cedulaCliente.value = "";

            data.value = response
         }
         if (status.value == "401") {

            store.commit("setToken", "");
            router.push('/');

         }
         if (status.value == "404") {
            nuevaCuenta.value = false;
            nuevoCliente.value = true;
            data.value = {};
         }



      });


}

function crear_cuenta() {

   fetch(

      "http://127.0.0.1:8000/api/cuenta",
      {
         method: "post",
         headers: {
            "Content-Type": "application/json",
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Origin': '*'
         },
         body: JSON.stringify({
            cuenta: datacuenta.value.cuenta,
            saldo: datacuenta.value.saldo,
            tipo_cuenta: datacuenta.value.tipo_cuenta,
            cliente: datacuenta.value.cliente
         }),
         mode: "cors",
      })
      .then((res) => {

      
         if(res.status!=304){
           
            return res.json();
         }
         return res;
      })
      .catch(error => console.log(error))
      .then((response) => {
         console.log(response);
         if (response.cuenta!=undefined) {

            console.log(response);
            nCuenta.value = false;
            datacuenta.value = {
               tipo_cuenta: "ahorros",
               cliente: data.value.cedula

            };

            error.value="";
         }
         else{
            error.value="ya existe";
         }
         
      });



}
</script>


<template>
   <div class="container-cuenta">
      <div class="buscar-cliente">
         buscar cliente <br>
         <input type="text" v-model="cedulaCliente">
         <button @click="cuenta">
            Buscar
         </button>

         <button class="nuevo cliente" @click="cliente">
            Nuevo
         </button>
         <div class="nueva-cuenta" v-if="nuevaCuenta">


            <p> cedula: {{ data.cedula }}</p>
            <p> nombre: {{ data.nombre }}</p>
            <br> agregar cuenta
            <button @click="nCuenta = !nCuenta"> nueva cuenta</button>

         </div>
      </div>
      <div class="buscar-cliente" v-if="nuevoCliente">


         <div class="nuevo-form">
            <h3 style="text-align: center;">Nuevo cliente</h3>
            <form action="" @submit.prevent="creacliente">
               Cedula <input type="number" v-model="data.cedula" class="cedula-cuenta">
               Nombre <input type="text" v-model="data.nombre" class="nombre-cuenta">
               Email <input type="email" v-model="data.email" class="email-cuenta">

               <button> Guardar </button>
            </form>
         </div>
      </div>

      <div class="buscar-cliente" v-if="nCuenta">

         Crear cuenta
         <form @submit.prevent="crear_cuenta" class="cuenta-nueva">
            cuenta <br>
            <p style="color: red; font-size: 14px;">{{ error}}</p>
            <input type="text" class="cuenta-input" v-model="datacuenta.cuenta" required>
            saldo <br>
            <input type="text" class="cuenta-input" v-model="datacuenta.saldo">
            tipo_cuenta <br>
            <input type="text" class="cuenta-input" value="ahorro" v-model="datacuenta.tipo_cuenta" disabled>

            <button class="cuenta-button"> guardar</button>
         </form>
      </div>
   </div>


</template>