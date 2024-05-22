import { createWebHistory, createRouter } from "vue-router";

import login from '../pages/home-Producto.vue'
import layout from '../layouts/layout-principal.vue' 
import hello from '../views/nueva-cuenta.vue'
import operaciones from '../views/operaciones-cuenta.vue'


const routes = [
  { path: "/", component: login },
  { path: "/operaciones", component: layout, children:[{path: "crear_cuenta", component: hello},{path: "operaciones_cuenta", component: operaciones}] },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});


export default router