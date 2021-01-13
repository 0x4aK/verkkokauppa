import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";
import Home from "../views/Home.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
    meta: { showCart: true },
  },
  {
    path: "/ravintolat",
    name: "Restaurant",
    component: () =>
      import(/* webpackChunkName: "restaurants" */ "../views/Restaurants.vue"),
  },
  {
    path: "/menu",
    name: "Menu",
    component: () => import(/* webpackChunkName: "menu" */ "../views/Menu.vue"),
    meta: { showCart: true },
  },
  {
    path: "/kirjaudu",
    name: "Login",
    component: () =>
      import(/* webpackChunkName: "signin" */ "../views/Login.vue"),
    beforeEnter: (_to, _from, next) => {
      if (store.getters["auth/isLoggedIn"]) next({ name: "Profile" });
      else next();
    },
  },
  {
    path: "/product/:id",
    name: "Product",
    component: () =>
      import(/* webpackChunkName: "product" */ "../views/Product.vue"),
    meta: { showCart: true },
  },
  {
    path: "/profile",
    name: "Profile",
    component: () =>
      import(/* webpackChunkName: "profile" */ "../views/Profile.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/admin",
    name: "Admin",
    component: () =>
      import(/* webpackChunkName: "admin" */ "../views/Admin.vue"),
    meta: { requiresAuth: true, lvlNeeded: 2 },
  },
  {
    path: "/owner",
    name: "Owner",
    component: () =>
      import(/* webpackChunkName: "owner" */ "../views/Owner.vue"),
    meta: { requiresAuth: true, lvlNeeded: 1 },
  },

  {
    path: "/:catchAll(.*)",
    name: "NotFound",
    component: () =>
      import(/* webpackChunkName: "not-found" */ "../views/NotFound.vue"),
  },
];

const router = new VueRouter({
  mode: "history",
  routes,

  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { x: 0, y: 0 };
    }
  },
});

router.beforeEach((to, _from, next) => {
  if (to.name === "Login") next();
  else if (to.meta.requiresAuth && !store.getters["auth/isLoggedIn"])
    next({ name: "Login" });
  else if (
    to.meta.lvlNeeded &&
    to.meta.lvlNeeded > store.getters["auth/getUserLvl"]
  )
    next({ name: "Home" });
  else next();
});

export default router;
