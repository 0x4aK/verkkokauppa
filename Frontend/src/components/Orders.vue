<template>
  <v-card>
    <v-card-title>
      Tilaukset
      <v-spacer />
      <v-btn icon color="info" :loading="!orders" @click="getOrders">
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </v-card-title>
    <v-divider />
    <v-card-text>
      <v-divider />
      <v-virtual-scroll
        :items="orders"
        :height="getHeight"
        item-height="70"
        v-if="orders"
      >
        <template v-slot:default="{ item }">
          <v-list-item
            :key="item.id"
            @click="$emit('orderClicked', item)"
            style="height: 69px"
            class="px-0"
          >
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-list-item-icon
                  class="mr-0 my-0 py-3 px-2 align-self-center"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-icon small :color="status[item.status].color">
                    {{ status[item.status].icon }}
                  </v-icon>
                </v-list-item-icon>
              </template>

              <span>{{ status[item.status].tooltip }}</span>
            </v-tooltip>
            <v-list-item-content class="align-self-start py-0 fill-height">
              <div class="text-caption text--disabled">
                Tilaus #{{ item.id }}
              </div>
              <div class="text-caption text-truncate">
                <v-icon small>mdi-hamburger</v-icon>
                {{ joinProducts(item.ordered) }}
              </div>
              <div class="text-caption d-flex justify-space-between">
                <div>
                  <v-icon small>mdi-store</v-icon>
                  {{ item.store.name }}
                </div>
                <div class="text--disabled">
                  {{ sinceLastUpdate(item.updated_at) }}
                </div>
              </div>
            </v-list-item-content>
          </v-list-item>
          <v-divider />
        </template>
      </v-virtual-scroll>
    </v-card-text>
  </v-card>
</template>

<script>
import httpClient from "@/mixins/httpClient";
import { mapState } from "vuex";

export default {
  mixins: [httpClient],

  props: ["type"],

  data: () => ({
    orders: null,
  }),

  computed: {
    getHeight() {
      const maxOrders = this.type !== undefined ? 8 : 5;
      const multiplier =
        this.orders.length >= maxOrders ? maxOrders : this.orders.length;
      return multiplier * 70;
    },
    ...mapState(["status"]),
  },

  methods: {
    getOrders() {
      this.http(`api/${this.type !== undefined ? this.type : "profile"}/orders`)
        .then((data) => (this.orders = data.data))
        .catch(() => {
          this.orders = [];
        });
    },

    joinProducts(ordered) {
      return ordered
        .map((product) => `${product.quantity}x ${product.name}`)
        .join(", ");
    },

    sinceLastUpdate(date) {
      const now = new Date();
      const lastUpdate = new Date(date);
      const diffSec = Math.floor((now.getTime() - lastUpdate.getTime()) / 1000);

      if (diffSec < 60) return `${diffSec}s`;

      return `${Math.floor(diffSec / 60)}m ${diffSec % 60}s`;
    },
  },

  beforeMount() {
    this.getOrders();
  },
};
</script>

<style></style>
