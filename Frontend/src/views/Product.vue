<template>
  <v-container class="product mt-16 mt-md-0">
    <v-row justify="center">
      <v-col cols="10" md="6">
        <v-card>
          <v-img :src="product.img"></v-img>
        </v-card>
      </v-col>

      <v-col cols="10" md="6">
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
            <v-btn fab dark color="light-green " @click="addToCart(product)">
              <v-icon>mdi-cart-plus</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
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
  }),

  created() {
    this.http(`api/products/${this.$route.params.id}`)
      .then((data) => (this.product = data.data))
      .catch(() => {
        this.$router.push({ name: "Menu" });
      });
  },

  methods: {
    ...mapActions("cart", ["addToCart"]),
  },
};
</script>
