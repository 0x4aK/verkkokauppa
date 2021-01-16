<template>
  <v-container class="product mt-lg-0 mt-16 mb-lg-8">
    <v-row justify="center">
      <v-col cols="10" md="6">
        <v-card>
          <v-img :src="product.img"></v-img>
        </v-card>
      </v-col>

      <v-col cols="10" md="6">
        <v-container class="pa-0">
          <v-row no-gutters>
            <v-col cols="12">
              <v-card>
                <v-card-title>
                  {{ product.name }}
                </v-card-title>

                <v-card-text>
                  {{ product.description }}
                </v-card-text>

                <v-card-actions class="mx-2 pb-6">
                  <div class="text-h4">{{ product.price }}€</div>

                  <v-spacer />
                  <v-btn
                    fab
                    dark
                    color="light-green"
                    @click="addToCart(product)"
                  >
                    <v-icon>mdi-cart-plus</v-icon>
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-col>

            <v-col cols="12" class="mt-4">
              <v-card>
                <v-slide-group show-arrows>
                  <v-slide-item v-for="rProduct in related" :key="rProduct.id">
                    <v-card
                      :to="`${rProduct.id}`"
                      height="132"
                      width="200"
                      class="ma-2"
                    >
                      <v-img
                        :src="rProduct.img"
                        class="white--text align-end fill-height"
                      >
                        <v-card-title class="product-name py-0">{{
                          rProduct.name
                        }}</v-card-title>
                      </v-img>
                    </v-card>
                  </v-slide-item>
                </v-slide-group>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import httpClient from "@/mixins/httpClient";
import { mapActions } from "vuex";

export default {
  mixins: [httpClient],

  data: () => ({
    product: {},
    related: [],
  }),

  created() {
    this.getProduct();
  },

  watch: {
    $route: "getProduct",
  },

  methods: {
    getProduct() {
      this.http(`api/products/${this.$route.params.id}`)
        .then((resp) => {
          this.product = resp.data.product;
          this.related = resp.data.related;
        })
        .catch(() => {
          this.$router.push({ name: "Menu" });
        });
    },
    ...mapActions("cart", ["addToCart"]),
  },
};
</script>

<style lang="scss">
.v-item-group > div:nth-child(odd) {
  min-width: 32px;
}
</style>
