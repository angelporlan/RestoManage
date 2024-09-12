import { createStore } from 'vuex';
import createPersistedState from 'vuex-persistedstate';

const store = createStore({
  modules: {
    user: {
      namespaced: true,
      state: {
        user: null
      },
      mutations: {
        SET_USER(state, user) {
          state.user = user;
        }
      },
      actions: {
        setUser({ commit }, user) {
          commit('SET_USER', user);
        }
      },
      getters: {
        getUser: state => state.user
      }
    },
    cesta: {
      namespaced: true,
      state: {
        items: []
      },
      mutations: {
        ADD_TO_CESTA(state, dish) {
          const item = state.items.find(i => i.id === dish.id);
          if (item) {
            item.cantidad += dish.cantidad;
          } else {
            state.items.push(dish);
          }
        },
        REMOVE_FROM_CESTA(state, dishId) {
          state.items = state.items.filter(item => item.id !== dishId);
        },
        CLEAR_CESTA(state) {
          state.items = [];
        },
        REMOVE_ONE_FROM_CESTA(state, dishId) {
          const dishIndex = state.items.findIndex(item => item.id === dishId);
          if (dishIndex !== -1) {
            if (state.items[dishIndex].cantidad > 1) {
              state.items[dishIndex].cantidad--;
            } else {
              state.items.splice(dishIndex, 1);
            }
          }
        }

      },
      actions: {
        addToCesta({ commit }, dish) {
          commit('ADD_TO_CESTA', dish);
        },
        removeFromCesta({ commit }, dishId) {
          commit('REMOVE_FROM_CESTA', dishId);
        },
        clearCesta({ commit }) {
          commit('CLEAR_CESTA');
        },
        removeOneFromCesta({ commit }, dishId) {
          commit('REMOVE_ONE_FROM_CESTA', dishId);
        }
      },
      getters: {
        cesta: state => state.items
      }
    }
  },
  plugins: [createPersistedState({
    storage: window.localStorage
  })]
});

export default store;
