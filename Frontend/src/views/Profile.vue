<template>
  <v-container class="profile">
    <v-row class="justify-center justify-md-space-between pa-3">
      <Store
        v-if="favoriteStore"
        :store="favoriteStore"
        class="fav-store mt-3"
      />
      <v-skeleton-loader
        width="400"
        type="card-avatar, article, actions"
        v-else
      />

      <v-col class="py-0 flex-shrink-0">
        <v-container class="pa-0" style="min-width:350px">
          <v-row class="justify-center justify-md-space-between">
            <v-col cols="12" lg="6">
              <Info />
            </v-col>
            <v-col cols="12" lg="6">
              <Orders />
            </v-col>
          </v-row>
        </v-container>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Store from "@/components/Store";
import Info from "@/components/profile/Info";
import Orders from "@/components/Orders";

import { mapActions, mapGetters, mapState } from "vuex";

export default {
  components: { Store, Info, Orders },
  computed: {
    favoriteStore() {
      return this.storesWithOpenHours.find(
        (store) => store.id === this.user.store
      );
    },
    ...mapState("auth", ["user"]),
    ...mapGetters(["storesWithOpenHours"]),
  },
  methods: mapActions(["getResource"]),

  created() {
    this.getResource({ resource: "stores", mutationName: "SET_STORES" });
  },
};
</script>

<style lang="scss">
.fav-store {
  width: 400px;
}
</style>
