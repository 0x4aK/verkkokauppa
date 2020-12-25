import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import cart from "./cart";

Vue.use(Vuex);

const state = {
  links: [
    { title: "Koti", to: "/", icon: "mdi-home" },
    { title: "Menu", to: "/menu", icon: "mdi-food" },
    { title: "Ravintolat", to: "/ravintolat", icon: "mdi-store" },
  ],

  profileLinks: [
    { title: "Profiili", to: "/profile", icon: "mdi-account-details", lvl: 0 },
    { title: "Owner", to: "/owner", icon: "mdi-store", lvl: 1 },
    { title: "Admin", to: "/admin", icon: "mdi-cog", lvl: 2 },
  ],

  categories: [
    { title: "Kaikki", icon: "mdi-food" },
    { title: "Burgerit", icon: "mdi-hamburger" },
    { title: "Lisukkeet", icon: "mdi-food-drumstick" },
    { title: "Juomat", icon: "mdi-cup" },
  ],
  selectedCategory: 1,
  products: [],

  stores: [],

  days: { ma: 1, ti: 2, ke: 3, to: 4, pe: 5, la: 6, su: 0 },
  status: {
    0: { tooltip: "Avoin", icon: "mdi-circle", color: "red" },
    1: { tooltip: "Hyväksytty", icon: "mdi-circle", color: "info" },
    2: { tooltip: "Valmistetaan", icon: "mdi-circle", color: "yellow" },
    3: { tooltip: "Valmis", icon: "mdi-circle", color: "green" },
    4: { tooltip: "Suljettu", icon: "mdi-circle", color: "gray" },
  },

  snackbarMessage: "",
  snackbarColor: "",
};

const mutations = {
  SET_SELECTED_CATEGORY: (state, payload) => (state.selectedCategory = payload),
  SET_PRODUCTS: (state, payload) => (state.products = payload),
  SET_STORES: (state, payload) => (state.stores = payload),
  SET_MESSAGE: (state, payload) => {
    state.snackbarMessage = payload.message;
    state.snackbarColor = payload.color;
  },
};

const actions = {
  setSelectedCategory({ commit }, category) {
    commit("SET_SELECTED_CATEGORY", category || 0);
  },
  showMessage({ commit }, { message, color }) {
    commit("SET_MESSAGE", { message, color });
  },
  async getResource({ commit, state }, { resource, mutationName }) {
    if (state[resource].length) return;

    console.debug(`Getting ${resource}`);

    const resp = await fetch(`http://192.168.1.64:8000/api/${resource}/`); //TODO: replace url in production
    if (!resp.ok) throw Error("Error while fetching products");

    const data = await resp.json();
    commit(mutationName, data.data);
  },
};

const getters = {
  featured: (state) => state.products.filter((product) => product.is_featured),
  profileLinksFiltered: (state, getters) => {
    const userLvl = getters["auth/getUserRole"].lvl;
    return state.profileLinks.filter((link) => link.lvl <= userLvl);
  },
  storesWithOpenHours: (state) => {
    const stores = state.stores;

    if (!stores) return [];

    const range = (
      start,
      end,
      openHour,
      closeHour,
      length = end - start + 1
    ) => {
      return Array.from({ length }, (_, i) => {
        const day = new Date();
        day.setDate(day.getDate() - day.getDay() + start + i);

        const close = new Date(day.getTime());
        close.setHours(closeHour, 0, 0, 0);

        const open = new Date(day.getTime());
        open.setHours(openHour, 0, 0, 0);

        return { open, close };
      });
    };

    return stores.map((store) => {
      const openArray = Array(7);

      for (const [key, value] of Object.entries(store.open)) {
        const [fromDay, toDay = fromDay] = key
          .split("-")
          .map((day) => state.days[day]);
        const [openHour, closeHour] = value
          .split("-")
          .map((hour) => Number(hour));

        const openRange = range(fromDay, toDay, openHour, closeHour);

        openArray.splice(fromDay, openRange.length, ...openRange);
      }

      return { ...store, openArray };
    });
  },
};

export default new Vuex.Store({
  modules: {
    auth,
    cart,
  },

  state,
  mutations,
  actions,
  getters,
});
