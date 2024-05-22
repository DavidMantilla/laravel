import Vuex from "vuex";

const store = Vuex.createStore({
  state() {
    return {
      count: 0,
      token:"",
    };
  },
  mutations: {
    increment(state) {
      state.count++;
    },
    setToken(state,token){
      sessionStorage.setItem("token", token);
        state.token=token;
    },
    getToken(state){
      state.token=  sessionStorage.getItem("token");
    }
  },
});

export default store;
