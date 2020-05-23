import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

// export const store = new Vuex.Store({
//   state: {},
//   getters: {},
//   mutations: {},
//   actions: {}
// });


export const store = new Vuex.Store({
  state: {
    todos: null,
  },

  getters: {
    TODOS: state => {
      return state.todos;
    },
  },

  mutations: {
    SET_TODO: (state, payload) => {
      state.todos = payload;
    },

    ADD_TODO: (state, payload) => {
      state.todos.push(payload);
    },
  },

  actions: {
    GET_TODO: async (context, payload) => {
      let {data} = await Axios.get('http://yourwebsite.com/api/todo');
      context.commit('SET_TODO', data);
    },

    SAVE_TODO: async (context, payload) => {
      let {data} = await Axios.post('http://yourwebsite.com/api/todo');
      context.commit('ADD_TODO', payload);
    },
  },
});