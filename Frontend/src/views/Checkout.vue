<template>
  <v-container class="checkout">
    <v-row class="justify-center">
      <v-col cols="10" md="4">
        <v-card>
          <v-card-title>Kori</v-card-title>

          <v-progress-linear active indeterminate v-if="!products" />
          <v-expand-transition>
            <v-simple-table v-if="products">
              <thead>
                <tr>
                  <th class="text-left">
                    Määrä
                  </th>
                  <th class="text-left">
                    Nimi
                  </th>
                  <th class="text-left">
                    Hinta
                  </th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="product in products" :key="product.id">
                  <td>{{ product.quantity }}</td>
                  <td>{{ product.name }}</td>
                  <td>{{ (product.quantity * product.price).toFixed(2) }}€</td>
                </tr>
                <tr>
                  <td>Yhteensä</td>
                  <td></td>
                  <td>{{ getTotal }}€</td>
                </tr>
              </tbody>
            </v-simple-table>
          </v-expand-transition>
        </v-card>
      </v-col>
      <v-col cols="10" md="5">
        <v-card>
          <v-card-title>Tilaustiedot</v-card-title>
          <v-form @submit.prevent="submitOrder">
            <v-card-text>
              <v-autocomplete
                color="info"
                label="Valitse ravintola"
                item-text="name"
                item-value="id"
                :items="stores"
                v-model="store"
                prepend-icon="mdi-store"
              />

              <v-text-field
                label="Luottokortti"
                prepend-icon="mdi-credit-card"
              />
              <div class="d-flex">
                <v-text-field
                  label="Voimassaoloaika"
                  prepend-icon="mdi-calendar"
                  class="mr-10"
                />
                <v-text-field label="Turvakoodi" prepend-icon="mdi-lock" />
              </div>
            </v-card-text>
            <v-card-actions class="px-5 pb-5">
              <v-btn type="submit" class="ml-auto px-6" color="success">
                Tilaa
              </v-btn>
            </v-card-actions>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import httpClient from "@/mixins/httpClient";

import { mapActions, mapGetters, mapState } from "vuex";

export default {
  mixins: [httpClient],

  data: () => ({
    store: null,
    products: null,
  }),

  computed: {
    getTotal() {
      return this.products
        .reduce((acc, item) => acc + item.quantity * item.price, 0)
        .toFixed(2);
    },
    ...mapState(["stores"]),
    ...mapState("auth", ["user"]),
    ...mapState("cart", ["cart"]),
    ...mapGetters("cart", ["getProductIds"]),
  },

  methods: {
    getProducts() {
      if (!this.getProductIds.length) {
        this.products = [];
        return;
      }

      this.http("api/products/list", {
        method: "POST",
        body: JSON.stringify(this.getProductIds),
      })
        .then((resp) => {
          this.products = resp.data.map((product) => {
            return {
              ...product,
              quantity: this.cart[product.id].quantity,
            };
          });
        })
        .catch((err) => console.error(err));
    },
    setInfo(user = this.user) {
      this.store = this.stores.filter((store) => store.id == user.store)[0]?.id;
    },
    submitOrder() {
      this.http("api/order/", {
        method: "POST",
        body: JSON.stringify({
          products: this.products.map((product) => {
            return { product_id: product.id, quantity: product.quantity };
          }),
          store: this.store,
        }),
      })
        .then((resp) => {
          this.showMessage({ message: resp.message, color: "success" });
          this.emptyCart();
          this.$router.push({ name: "Profile" });
        })
        .catch((err) =>
          this.showMessage({ message: err.message, color: "error" })
        );
    },
    ...mapActions(["getResource", "showMessage"]),
    ...mapActions("cart", ["emptyCart"]),
  },
  watch: {
    user: function(val) {
      this.setInfo(val);
    },
    stores: function() {
      this.setInfo();
    },
  },
  created() {
    this.getResource({ resource: "stores", mutationName: "SET_STORES" });
    this.getProducts();
  },
  beforeMount() {
    this.setInfo();
  },
};
</script>

<style></style>
