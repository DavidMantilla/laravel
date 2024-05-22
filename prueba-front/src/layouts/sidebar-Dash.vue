<script setup>
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';


const store = useStore();
const router = useRouter();
function salir() {
    store.commit("setToken", "");
    router.push('/');
}


</script>

<template>

    <div class="sidebar">

        <div class="cuenta">
            <ul class="lista">
                <li>
                    <RouterLink to="/operaciones/crear_cuenta"> nueva cuenta</RouterLink>
                </li>
                <li>
                    <RouterLink to="/operaciones/operaciones_cuenta"> operaciones de cuenta</RouterLink>
                </li>
                <li @click="salir">
                    salir
                </li>
            </ul>
        </div>
    </div>
</template>