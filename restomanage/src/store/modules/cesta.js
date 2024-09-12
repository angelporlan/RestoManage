const state = {
    cesta: []
};

const mutations = {
    addToCesta(state, dish) {
        const existingDish = state.cesta.find(item => item.id === dish.id);
        if (existingDish) {
            existingDish.cantidad += dish.cantidad;
        } else {
            state.cesta.push(dish);
        }
    },
    removeFromCesta(state, dishId) {
        state.cesta = state.cesta.filter(item => item.id !== dishId);
    },
    clearCesta(state) {
        state.cesta = [];
    },
    removeOneFromCesta(state, dishId) {
        const dishIndex = state.cesta.findIndex(item => item.id === dishId);
        if (dishIndex !== -1) {
            if (state.cesta[dishIndex].cantidad > 1) {
                state.cesta[dishIndex].cantidad--;
            } else {
                state.cesta.splice(dishIndex, 1);
            }
        }
    }
};

const actions = {
    addToCesta({ commit }, dish) {
        commit('addToCesta', dish);
    },
    removeFromCesta({ commit }, dishId) {
        commit('removeFromCesta', dishId);
    },
    clearCesta({ commit }) {
        commit('clearCesta');
    },
    removeOneFromCesta({ commit }, dishId) {
        commit('removeOneFromCesta', dishId);
    }
};

const getters = {
    cesta: state => state.cesta
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
};
