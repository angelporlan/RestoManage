const state = {
    user: null
  };
  
  const getters = {
    getUser: state => state.user
  };
  
  const actions = {
    setUser({ commit }, user) {
      commit('SET_USER', user);
    }
  };
  
  const mutations = {
    SET_USER(state, user) {
      state.user = user;
    }
  };
  
  export default {
    state,
    getters,
    actions,
    mutations
  };
  