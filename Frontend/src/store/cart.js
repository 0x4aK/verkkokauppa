const state = {
  cart: JSON.parse(localStorage.getItem("cart") || "{}"),
};

const mutations = {
  SET_CART: (state, payload) => {
    localStorage.setItem("cart", JSON.stringify(payload));
    state.cart = payload;
  },
};

const actions = {
  addToCart({ commit, state }, productObj) {
    const tempCart = { ...state.cart };
    if (!tempCart[productObj.id])
      tempCart[productObj.id] = {
        name: productObj.name,
        quantity: 0,
        price: Number(productObj.price),
      };

    tempCart[productObj.id].quantity++;

    commit("SET_CART", tempCart);
  },
  removeFromCart({ commit, state }, productId) {
    const tempCart = { ...state.cart };

    if (!tempCart[productId]) return;

    tempCart[productId].quantity--;

    if (!tempCart[productId].quantity) delete tempCart[productId];
    commit("SET_CART", tempCart);
  },
};

const getters = {};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
