<script setup>
import sidebar from "./sidebar-Dash.vue";
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue'

const store = useStore();
const router = useRouter();


onMounted(() => {
  store.commit("getToken");
  if (store.state.token == "") {
    router.push('/');
  }
})
</script>

<template>
  <div class="pagina">

    <sidebar />
    <div class="container">

      <router-view></router-view>
    </div>

  </div>
</template>
