<template>
  <v-container class="restaurants">
    <v-row class="my-2 px-3">
      <span class="text-h3 mr-10">Ravintolat</span>
      <v-text-field
        class="mt-2 ml-auto d-inline"
        style="max-width:350px"
        prepend-inner-icon="mdi-magnify"
        v-model="search"
        label="Etsi"
        id="search"
        type="text"
        outlined
        dense
        clearable
        hide-details
      >
      </v-text-field>
    </v-row>
    <v-divider />
    <v-row class="justify-center justify-md-start" v-if="storesFiltered.length">
      <v-col
        cols="10"
        md="6"
        lg="4"
        v-for="store in storesFiltered"
        :key="store.id"
      >
        <Store :store="store" />
      </v-col>
    </v-row>
    <v-row justify="center" v-else>
      <div class="text-h5 text--disabled my-16">
        Emme löytäneet sopivaa kauppaa
        <v-icon>mdi-emoticon-confused-outline</v-icon>
      </div>
    </v-row>
  </v-container>
</template>

<script>
import Store from "@/components/Store";

import { mapActions, mapGetters } from "vuex";

export default {
  components: { Store },
  data: () => ({
    search: "",
  }),
  computed: {
    storesFiltered() {
      if (!this.search) return this.storesWithOpenHours;

      return this.storesWithOpenHours.filter((store) =>
        store.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    ...mapGetters(["storesWithOpenHours"]),
  },
  methods: mapActions(["getResource"]),
  created() {
    this.getResource({ resource: "stores", mutationName: "SET_STORES" });
  },
};
</script>
