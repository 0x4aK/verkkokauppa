<template>
  <v-app id="app">
    <AppBarMobile v-if="$vuetify.breakpoint.mobile" />
    <AppBar v-else />

    <v-main>
      <router-view />
    </v-main>

    <Cart v-show="$route.meta.showCart" />
    <Footer />
    <Message />
  </v-app>
</template>

<script>
import AppBar from "@/components/appbar/AppBar";
import AppBarMobile from "@/components/appbar/AppBarMobile";

import Cart from "@/components/cart/Cart";
import Footer from "@/components/Footer";

import Message from "@/components/snackbar/Message";

import { mapActions } from "vuex";

export default {
  name: "App",

  components: { AppBar, AppBarMobile, Cart, Footer, Message },
  created() {
    this.getUserInfo();
  },
  methods: mapActions("auth", ["getUserInfo"]),
};
</script>
