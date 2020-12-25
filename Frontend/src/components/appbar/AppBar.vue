<template>
  <v-app-bar app color="white">
    <v-toolbar-title id="title" class="text-no-wrap mx-5"
      >Mesen Burgeri</v-toolbar-title
    >

    <v-tabs>
      <v-tab :to="link.to" exact v-for="link in links" :key="link.to">
        <v-icon>{{ link.icon }}</v-icon>
        <span class="ml-2">{{ link.title }}</span>
      </v-tab>
    </v-tabs>

    <ProfileButton v-if="isLoggedIn" />
    <LoginButton v-else />
  </v-app-bar>
</template>

<script>
import LoginButton from "./LoginButton";
import ProfileButton from "./ProfileButton";

import { mapGetters, mapState } from "vuex";

export default {
  components: { LoginButton, ProfileButton },

  computed: {
    ...mapState(["links"]),
    ...mapGetters("auth", ["isLoggedIn"]),
  },
};
</script>

<style lang="scss">
#title {
  overflow: initial;
}
</style>
