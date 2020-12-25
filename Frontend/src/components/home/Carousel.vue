<template>
  <v-row justify="center">
    <v-col cols="12" md="10" lg="8">
      <v-card elevation="4">
        <v-carousel
          continuous
          cycle
          show-arrows-on-hover
          hide-delimiters
          interval="10000"
        >
          <v-carousel-item v-for="product in featured" :key="product.id">
            <v-img :src="product.img" max-height="500" class="fill-height">
              <div class="d-flex justify-end fill-height">
                <div class="product-info pa-3 d-flex flex-column">
                  <div class="text-h4">{{ product.name }}</div>
                  <div class="text-caption my-3">{{ product.description }}</div>
                  <v-spacer></v-spacer>
                  <div class="text-h4 d-flex justify-space-between ">
                    {{ product.price }}€
                    <div>
                      <v-btn
                        icon
                        color="light-green accent-3"
                        large
                        @click="addToCart(product)"
                      >
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                      <v-btn icon dark :to="'product/' + product.id" large>
                        <v-icon>mdi-arrow-right</v-icon>
                      </v-btn>
                    </div>
                  </div>
                </div>
              </div>
            </v-img>
          </v-carousel-item>
        </v-carousel>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  computed: {
    ...mapGetters(["featured"]),
  },
  methods: {
    ...mapActions(["getResource"]),
    ...mapActions("cart", ["addToCart"]),
  },

  created() {
    this.getResource({ resource: "products", mutationName: "SET_PRODUCTS" });
  },
};
</script>

<style lang="scss" scoped>
.product-info {
  width: 250px;
  background: rgba($color: #000000, $alpha: 0.3);
}
</style>
