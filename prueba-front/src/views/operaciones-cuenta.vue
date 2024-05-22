<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const ruta = useRouter();
const cuenta = ref("");
const infocliente = ref("");
const status = ref("");
const store = useStore();
let token = store.state.token;
const dataCuenta = ref({});
const list_transaccion = ref([]);
const list_cuentas = ref([]);
const cuentas = ref(false);
const dataTransaction = {

    tipo: "",
    valor: "",
    id_cuenta: ""


}
//const image = ref("");
function buscarCliente() {
    cuentas.value = true;

    fetch(
        `http://127.0.0.1:8000/api/cuenta/todos`, {
        method: "post",
        headers: {
            "Content-Type": "application/json",
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Origin': '*'
        },
        body: JSON.stringify({ "cedula": infocliente.value }),
        mode: "cors",
    }).then((res) => {

        status.value = res.status;

        return res.json()

    })
        .catch(error => console.error(error))
        .then((response) => {
            list_cuentas.value = response;
        });

}
function buscarCuenta() {
    
    dataCuenta.value = {};
    if (cuenta.value != "") {
        fetch(
            `http://127.0.0.1:8000/api/cuenta/${cuenta.value}`, {
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
                cuenta.value = "";
                if (status.value == 401) {
                    ruta.push('/');
                }
                if (response.cuenta != undefined) {
                    console.log(response.cuenta);
                    dataCuenta.value = response.cuenta;
                    fetch(
                        `http://127.0.0.1:8000/api/transaccion/${response.cuenta.id}`, {
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

                            list_transaccion.value = response;
                        });
                }
            });
    }

}

function crearTransaccion() {
    
    dataTransaction.id_cuenta = dataCuenta.value.id;

    fetch(
        "http://localhost:8000/api/transaccion",
        {
            method: "post",
            headers: {
                "Content-Type": "application/json",
                'Access-Control-Allow-Origin': '*',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(dataTransaction),
            mode: "cors",
        }).then((res) => {

            status.value = res.status;

            return res.json()

        })
        .then((response) => {

            console.log(response);
            if (status.value == 401) {
                ruta.push('/');
            }
            dataCuenta.value = response.cuenta;
            list_transaccion.value.push(response.transacciones);


        });


}

function value(id){
    infocliente.value="";
    cuenta.value=id.cuenta;

    buscarCuenta();
}
</script>

<template>
    <div class="container-cuenta">
        <div class="buscar-cliente">
            buscar cuenta <br>
            <input type="text" v-model="cuenta">
            <button @click="buscarCuenta">
                Buscar
            </button>
        </div>
        <div class="buscar-cliente">
            buscar cliente <br>
            <input type="text" v-model="infocliente">
            <button @click="buscarCliente">
                Buscar
            </button>
        </div>
        <div class="buscar-cliente" style="width: 90%;overflow-x: auto; " v-if="cuentas">
            cuentas
            <div style=" width: max-content; height: 170px;">

                <div class=" cuentas-button" v-for=" cuent in list_cuentas" :key="cuent.id" @click="value(cuent)">
                    <p> Numero cuenta: {{ cuent.cuenta }}</p>
                    <p> Tipo de cuenta: {{ cuent.tipo_cuenta }}</p>
                    <p>Saldo: {{ cuent.saldo }}</p>
                </div>
            </div>
        </div>
        <div class="buscar-cliente" style="width: 90%;" v-if="dataCuenta.id != undefined">
            <br>
            <div style=" width: max-content; ">

                <div style="width: max-content;">
                    <p> <b>Numero cuenta: </b>{{ dataCuenta.cuenta }} </p>
                    <p> <b>Tipo de cuenta: </b>{{ dataCuenta.tipo_cuenta }}</p>
                    <p><b>Saldo: </b> ${{ dataCuenta.saldo }}</p>
                    <br>
                </div>
            </div>


            <h3>Transacción</h3>
            <div class="transaccion">
                <div class="nueva-transaccion">
                    nueva transaccion
                    <form @submit.prevent="crearTransaccion" class="cuenta-nueva"
                        style="display: flex; flex-wrap: nowrap; justify-content: center; align-items: center;">
                        Tipo:&nbsp;
                        <select name="" id="" class="cuenta-input" v-model="dataTransaction.tipo">
                            <option value="ingreso">ingreso</option>
                            <option value="salida">salida</option>
                        </select>
                        Valor:
                        <input type="text" class="cuenta-input" v-model="dataTransaction.valor">
                        <button class="cuenta-button" style="margin-top:0px;"> Guardar</button>
                    </form>
                </div>

            </div>

            <b>Transacciones</b> <br>
            <div class="transacciones">

                <div v-for=" trans in list_transaccion" :key=trans.id>

                    <div class="transac"> <img
                            :src="trans.tipo == 'ingreso' ? '../img/income.png' : '../img/salida.png'" alt=""
                            width="20px"> <b> valor:</b> {{ trans.valor }} <br> {{ trans.created_at }}</div>
                </div>

            </div>
        </div>
    </div>
</template>
