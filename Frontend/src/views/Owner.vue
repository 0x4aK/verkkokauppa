<template>
  <v-container class="owner">
    <div class="text-h4 my-3" v-if="storeOwner">{{ storeOwner.name }}</div>
    <v-row>
      <v-col cols="12" md="10" lg="8">
        <v-card>
          <v-container v-if="loadedOrder">
            <div class="text-h5 d-flex justify-space-between mb-1">
              Tilaus #{{ loadedOrder.id }}
              <div>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      icon
                      v-bind="attrs"
                      v-on="on"
                      color="info"
                      @click="setStatus(loadedOrder.id, 0)"
                      :disabled="loadedOrder.status <= 1"
                    >
                      <v-icon>mdi-undo</v-icon>
                    </v-btn>
                  </template>
                  <span>Peruuta</span>
                </v-tooltip>

                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      icon
                      v-bind="attrs"
                      v-on="on"
                      color="error"
                      @click="loadedOrder = null"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </template>
                  <span>Lopeta hallinta</span>
                </v-tooltip>
              </div>
            </div>

            <v-divider />

            <v-row class="order-info">
              <v-col cols="6">
                <div class="text-caption">
                  Tilaus luotu:
                  <div class="text-subtitle-2">
                    {{ formatTime(loadedOrder.created_at) }}
                  </div>
                </div>
                <div class="text-caption mt-1">
                  Viimeksi muokattu:
                  <div class="text-subtitle-2">
                    {{ formatTime(loadedOrder.updated_at) }}
                  </div>
                </div>
                <div class="text-caption mt-1">
                  Status:
                  <div class="text-subtitle-2">
                    <v-icon x-small :color="status[loadedOrder.status].color">
                      {{ status[loadedOrder.status].icon }}
                    </v-icon>
                    {{ status[loadedOrder.status].tooltip }}
                  </div>
                </div>
              </v-col>
              <v-col cols="6">
                <div class="text-caption">
                  Tilaaja:
                  <div class="text-subtitle-2">
                    {{ loadedOrder.user.fname }} {{ loadedOrder.user.lname }}
                  </div>
                </div>
                <div class="text-caption mt-1">
                  Puhelinnumero:
                  <div class="text-subtitle-2">
                    {{ loadedOrder.user.phone }}
                  </div>
                </div>
                <div class="text-caption mt-1">
                  Osoite:
                  <div class="text-subtitle-2">
                    {{ loadedOrder.user.address }}
                  </div>
                </div>
              </v-col>
            </v-row>

            <v-row class="order-action">
              <v-col cols="12" sm="4">
                <v-btn
                  block
                  color="info"
                  :disabled="loadedOrder.status !== 1"
                  @click="setStatus(loadedOrder.id, 2)"
                >
                  Aloita valmistus
                </v-btn>
              </v-col>
              <v-col cols="12" sm="4">
                <v-btn
                  block
                  color="success"
                  :disabled="
                    loadedOrder.status !== 2 ||
                      loadedOrder.ordered.length !== productsChecked.length
                  "
                  @click="setStatus(loadedOrder.id, 3)"
                >
                  Tilaus valmis
                </v-btn>
              </v-col>
              <v-col cols="12" sm="4">
                <v-btn
                  block
                  color="info"
                  :disabled="loadedOrder.status !== 3"
                  @click="setStatus(loadedOrder.id, 4)"
                >
                  Tilaus noudettu
                </v-btn>
              </v-col>
            </v-row>

            <v-row class="order-products">
              <v-col>
                <v-list
                  class="products-list"
                  rounded
                  :outlined="loadedOrder.status === 2"
                  :disabled="loadedOrder.status !== 2"
                >
                  <v-list-item-group v-model="productsChecked" multiple>
                    <template v-for="(product, i) in loadedOrder.ordered">
                      <v-list-item
                        :key="i"
                        :value="i"
                        active-class="light-green accent-1"
                      >
                        <template v-slot:default="{ active }">
                          <v-list-item-content>
                            <v-list-item-title>
                              {{ product.quantity }}x {{ product.name }}
                            </v-list-item-title>
                          </v-list-item-content>

                          <v-list-item-action>
                            <v-checkbox
                              :input-value="active"
                              color="green darken-2"
                            ></v-checkbox>
                          </v-list-item-action>
                        </template>
                      </v-list-item>
                    </template>
                  </v-list-item-group>
                </v-list>
              </v-col>
            </v-row>
          </v-container>
          <div v-else class="text-h5 text--disabled text-center py-16">
            Valitse tilaus
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="8" md="6" lg="4">
        <Orders ref="OrderCard" type="owner" @orderClicked="loadOrder" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import httpClient from "@/mixins/httpClient";

import Orders from "@/components/Orders";

import { mapActions, mapGetters, mapState } from "vuex";

export default {
  mixins: [httpClient],

  components: { Orders },

  data: () => ({
    loadedOrder: null,
    productsChecked: [],
  }),

  computed: {
    storeOwner() {
      return this.storesWithOpenHours.filter(
        (store) => store.id === this.user.store
      )[0];
    },
    ...mapState(["status"]),
    ...mapState("auth", ["user"]),
    ...mapGetters(["storesWithOpenHours"]),
  },

  methods: {
    loadOrder(order) {
      this.productsChecked =
        order.status === 3 ? [...Array(order.ordered.length).keys()] : [];
      this.loadedOrder = order;
    },

    formatTime(date) {
      return new Date(date).toLocaleString("fi-FI");
    },

    setStatus(orderId, status) {
      this.http(`api/owner/orders/${orderId}`, {
        method: "PATCH",
        body: JSON.stringify({ status }),
      }).then((data) => {
        this.loadedOrder = data.data;
        this.$refs.OrderCard.getOrders();
      });
    },
    ...mapActions(["getResource"]),
  },

  created() {
    this.getResource({ resource: "stores", mutationName: "SET_STORES" });
  },
};
</script>

<style lang="scss">
.products-list {
  &.v-list--disabled {
    opacity: 50%;
  }
  &.v-sheet--outlined {
    border: thin solid rgba($color: #00a2ff, $alpha: 1);
  }
}
</style>
