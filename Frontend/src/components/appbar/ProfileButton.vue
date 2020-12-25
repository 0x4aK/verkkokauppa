<template>
  <v-menu offset-y>
    <template v-slot:activator="{ on, attrs }">
      <v-btn text v-bind="attrs" v-on="on">
        <v-icon left>mdi-account</v-icon>
        {{ user.fname }}
      </v-btn>
    </template>

    <v-list dense>
      <v-list-item
        v-for="link in profileLinksFiltered"
        :key="link.to"
        :to="link.to"
      >
        <v-list-item-content>
          <v-list-item-title>{{ link.title }}</v-list-item-title>
        </v-list-item-content>
        <v-list-item-icon>
          <v-icon>{{ link.icon }}</v-icon>
        </v-list-item-icon>
      </v-list-item>

      <v-divider />

      <v-list-item @click="doLogout()">
        <v-list-item-content>
          <v-list-item-title>Kirjaudu ulos</v-list-item-title>
        </v-list-item-content>
        <v-list-item-icon>
          <v-icon>mdi-logout</v-icon>
        </v-list-item-icon>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>
import { mapGetters, mapState } from "vuex";

import Logout from "@/mixins/logout";

export default {
  computed: {
    ...mapGetters(["profileLinksFiltered"]),
    ...mapState("auth", ["user"]),
  },

  mixins: [Logout],
};
</script>

<style></style>
