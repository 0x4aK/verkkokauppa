<template>
  <v-card>
    <v-container>
      <v-row>
        <v-col cols="12" lg="6">
          <v-card-title class="pa-0">
            Ravintolan hallinta
            <v-spacer />

            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  icon
                  color="error"
                  @click="selectedStore = null"
                  :disabled="!selectedStore"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-icon>mdi-close</v-icon>
                </v-btn>
              </template>
              <span>Lopeta hallinta</span>
            </v-tooltip>
          </v-card-title>

          <v-divider class="my-2" />

          <v-form @submit.prevent="submitStore">
            <v-expand-transition>
              <div v-if="selectedStore">
                <v-img
                  height="200"
                  :src="selectedStore.img"
                  class="rounded-t-lg"
                />
                <div class="pa-2">
                  <v-text-field label="Nimi" v-model="selectedStore.name" />
                  <v-text-field
                    label="Osoite"
                    v-model="selectedStore.address"
                  />
                  <v-text-field label="Puhelin" v-model="selectedStore.phone" />

                  <RestaurantTimeTable ref="timeTable" :store="selectedStore" />
                </div>
              </div>
            </v-expand-transition>

            <div class="d-flex mt-2">
              <DeleteRestaurantBtn
                :store="selectedStore"
                :disabled="!selectedStore"
                @storeDeleted="getStores"
              />
              <v-spacer />
              <v-btn color="info" type="submit" :disabled="!selectedStore">
                {{ addBtnLabel }}
              </v-btn>
            </div>
          </v-form>
        </v-col>

        <v-col cols="12" lg="6">
          <v-container class="pa-0">
            <v-row dense>
              <v-col cols="6">
                <v-card height="164" @click="makeEmptyStore">
                  <v-img
                    src="/images/restaurant.jpg"
                    class="white--text align-center fill-height"
                  >
                    <v-card-title
                      class="store-add ma-8 pa-0 rounded-lg justify-center"
                    >
                      <v-icon color="light-green accent-3" x-large>
                        mdi-plus
                      </v-icon>
                      Lisää
                    </v-card-title>
                  </v-img>
                </v-card>
              </v-col>

              <v-col cols="6" v-for="store in stores" :key="store.id">
                <v-card height="164" @click="selectedStore = { ...store }">
                  <v-img
                    :src="store.img"
                    class="white--text align-end fill-height"
                  >
                    <div class="store-name px-1">
                      {{ store.name }}
                    </div>
                  </v-img>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>

<script>
import httpClient from "@/mixins/httpClient";

import DeleteRestaurantBtn from "./DeleteRestaurantBtn";
import RestaurantTimeTable from "./RestaurantTimeTable";

import { mapActions, mapState } from "vuex";

export default {
  mixins: [httpClient],

  components: { DeleteRestaurantBtn, RestaurantTimeTable },

  data: () => ({
    selectedStore: null,
  }),

  computed: {
    addBtnLabel() {
      return this.selectedStore?.id === 0 ? "Lisää" : "Hyväksy";
    },

    ...mapState(["stores"]),
  },
  methods: {
    submitStore() {
      this.http(`api/admin/stores/${this.selectedStore.id}`, {
        method: "PATCH",
        body: JSON.stringify({
          ...this.selectedStore,
          open: this.$refs.timeTable.getTimeObj(),
        }),
      })
        .then((resp) => {
          this.showMessage({ message: resp.message, color: "success" });
          this.getStores();
        })
        .catch((err) => {
          console.error(err);
          this.showMessage({ message: err.message, color: "error" });
        });
    },

    makeEmptyStore() {
      this.selectedStore = {
        id: 0,
        name: "",
        address: "",
        phone: "",
        open: null,
        img: "",
      };
    },

    getStores() {
      this.selectedStore = null;
      this.getResource({
        resource: "stores",
        mutationName: "SET_STORES",
        forced: true,
      });
    },
    ...mapActions(["getResource", "showMessage"]),
  },

  created() {
    this.getResource({ resource: "stores", mutationName: "SET_STORES" });
  },
};
</script>

<style lang="scss">
.store-name {
  background: rgba($color: #000000, $alpha: 0.8);
}
.store-add {
  background: rgba($color: #000000, $alpha: 0.6);
}
</style>
