<script setup>
import { ref } from "vue";
import {  useRouter } from 'vue-router';
import { useStore } from 'vuex'

const usuario = ref("");
const password = ref("");
const router = useRouter();
const store= useStore();
let status = ref("");

function submit() {
  
  fetch(
    "http://localhost:8000/api/login",
    {
      method: "post",
      headers: {
        "Content-Type": "application/json",
        'Access-Control-Allow-Origin': '*'
      },
      body: JSON.stringify({
        "email": usuario.value,
        "password": password.value
      }),
      mode: "cors",
    }).then((res)=> res.json())
    .then((response)=>{
    
      console.log(response);
      if (response.token) {
        store.commit("setToken",response.token);      
        router.push('/operaciones')

      } else {

        status.value = response.message;

      }

    })



}


/*
 */
</script>

<template>

  <div class="bg">

    <div class="panel">
      <h3>Login</h3>
      <div class="error">
        {{ status }}
      
      </div>

      <form @submit.prevent="submit" method="post">
        <label for="">Usuario</label>
        <input type="email" v-model="usuario" required> <br>
        <label for="">Clave</label>
        <input type="password" v-model="password" required> <br>
        <button type="submit"> Ingresar</button>


      </form>
    </div>
  </div>

</template>
