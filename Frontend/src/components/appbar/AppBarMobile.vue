<template>
  <v-bottom-navigation color="primary" app grow>
    <v-btn :to="link.to" exact v-for="link in links" :key="link.to">
      <span>{{ link.title }}</span>
      <v-icon>{{ link.icon }}</v-icon>
    </v-btn>

    <ProfileButtonMobile v-if="isLoggedIn" />
    <LoginButton v-else mobile="1" />
  </v-bottom-navigation>
</template>

<script>
import LoginButton from "./LoginButton";
import ProfileButtonMobile from "./ProfileButtonMobile";

import { mapGetters, mapState } from "vuex";

export default {
  components: { LoginButton, ProfileButtonMobile },

  computed: {
    ...mapState(["links"]),
    ...mapGetters("auth", ["isLoggedIn"]),
  },
};
</script>

<style scoped>
a {
  height: auto !important;
}
</style>
