<template>
  <div>
    <v-card :disabled="!loadedOrder">
      <v-card-title>
        Tilauksen {{ loadedOrder ? `#${loadedOrder.id}` : "" }} hallinta
        <v-spacer />

        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              icon
              color="error"
              @click="resetData"
              v-bind="attrs"
              v-on="on"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </template>
          <span>Lopeta hallinta</span>
        </v-tooltip>
      </v-card-title>
      <v-divider />
      <v-card-actions class="pa-4 justify-space-around">
        <v-select
          :items="statusItems"
          item-text="tooltip"
          item-value="id"
          v-model="orderStatus"
          label="Status"
          @change="setStatus"
          class="mr-3"
        />
        <DeleteOrderBtn :id="orderId" @orderDeleted="updateOrders" />
      </v-card-actions>
    </v-card>
    <Orders
      ref="OrderCard"
      class="mt-4"
      type="admin"
      amount="8"
      @orderClicked="loadOrder"
    />
  </div>
</template>

<script>
import httpClient from "@/mixins/httpClient";

import { mapState } from "vuex";

import Orders from "@/components/Orders";
import DeleteOrderBtn from "@/components/admin/DeleteOrderBtn";

export default {
  mixins: [httpClient],
  components: { Orders, DeleteOrderBtn },

  data: () => ({
    loadedOrder: null,
    orderStatus: null,
  }),

  computed: {
    orderId() {
      return this.loadedOrder?.id;
    },
    statusItems() {
      return Object.entries(this.status).map(([key, value]) =>
        Object.assign({ ...value }, { id: Number(key) })
      );
    },
    ...mapState(["status"]),
  },

  methods: {
    loadOrder(order) {
      this.loadedOrder = order;
      this.orderStatus = order.status;
    },
    resetData() {
      this.loadedOrder = null;
      this.orderStatus = null;
    },
    updateOrders() {
      this.$refs.OrderCard.getOrders();
    },
    setStatus() {
      this.http(`api/admin/orders/${this.orderId}`, {
        method: "PATCH",
        body: JSON.stringify({ status: this.orderStatus }),
      }).then((data) => {
        this.loadedOrder = data.data;
        this.$refs.OrderCard.getOrders();
      });
    },
  },
};
</script>

<style></style>
