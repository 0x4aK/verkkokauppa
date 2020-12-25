const state = {
  user: {},
  access_token: localStorage.getItem("access_token"),
};

const mutations = {
  SET_ACCESS_TOKEN: (state, payload) => (state.access_token = payload),
  SET_USER: (state, payload) => (state.user = payload),
};

const actions = {
  login({ commit, dispatch }, data) {
    if (!data.token || !data.user) return dispatch("logout");

    commit("SET_ACCESS_TOKEN", data.token);
    localStorage.setItem("access_token", data.token);

    commit("SET_USER", data.user);
  },
  logout({ commit }) {
    commit("SET_ACCESS_TOKEN", null);
    localStorage.removeItem("access_token");

    commit("SET_USER", {});
  },
  async getUserInfo({ dispatch, commit, getters, state }) {
    if (!getters.isLoggedIn) return;

    const res = await fetch("http://192.168.1.64:8000/api/profile", {
      headers: { Authorization: `Bearer ${state.access_token}` },
    });

    if (!res.ok) return dispatch("logout");

    const json = await res.json();
    commit("SET_USER", json.user);
  },
  setUser({ commit }, user) {
    commit("SET_USER", user);
  },
};

const getters = {
  isLoggedIn: (state) => !!state.access_token,
  getUserRole: (state) =>
    JSON.parse(
      atob(state.access_token?.split(".")[1] || "") ||
        '{"role":{"lvl":0,"name":"quest"}}'
    ).role,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
