<template>
  <v-container class="menu mt-lg-0 mt-16 mb-lg-8">
    <v-row>
      <v-col cols="12" sm="4" md="3">
        <v-card elevation="5">
          <v-list>
            <v-list-item-group
              :value="selectedCategory"
              @change="(value) => setSelectedCategory(value)"
              color="primary"
            >
              <v-list-item v-for="(item, i) in categories" :key="i">
                <v-list-item-icon>
                  <v-icon v-text="item.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="item.title"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-col>

      <v-col cols="12" sm="8" md="9">
        <v-card elevation="5">
          <v-container>
            <div class="text-h4">{{ categories[selectedCategory].title }}</div>
            <v-divider class="my-2" />

            <v-row dense>
              <v-col
                cols="6"
                lg="3"
                v-for="product in filteredProducts"
                :key="product.id"
                class="product-item"
              >
                <v-hover v-slot="{ hover }">
                  <div>
                    <v-card :to="`product/${product.id}`" height="164">
                      <v-img
                        :src="product.img"
                        class="white--text align-end fill-height"
                      >
                        <div
                          class="product-name pa-2 text-h6 d-flex align-center"
                        >
                          {{ product.name }}
                        </div>
                        <v-expand-transition>
                          <div
                            v-if="hover"
                            class="product-price d-flex info darken-2 text-h4 white--text"
                          >
                            {{ product.price }}€
                          </div>
                        </v-expand-transition>
                      </v-img>
                    </v-card>

                    <v-tooltip bottom>
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          class="product-cart-add--btn"
                          icon
                          color="light-green accent-3"
                          @click="addToCart(product)"
                          v-bind="attrs"
                          v-on="on"
                        >
                          <v-icon>mdi-plus</v-icon>
                        </v-btn>
                      </template>
                      <span>Lisää koriin</span>
                    </v-tooltip>
                  </div>
                </v-hover>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  computed: {
    filteredProducts() {
      if (!this.selectedCategory) return this.products;

      return this.products.filter(
        (product) => product.category === this.selectedCategory
      );
    },

    ...mapState(["products", "categories", "selectedCategory"]),
  },

  methods: {
    ...mapActions(["setSelectedCategory", "getResource"]),
    ...mapActions("cart", ["addToCart"]),
  },

  created() {
    this.getResource({ resource: "products", mutationName: "SET_PRODUCTS" });
  },
};
</script>

<style lang="scss">
.product-item {
  position: relative;

  & .product-price {
    align-items: center;
    top: 0;
    justify-content: center;
    opacity: 0.9;
    position: absolute;
    width: 100%;
    height: 33%;
  }

  & .product-name {
    background-color: rgba($color: #000000, $alpha: 0.4);
  }
  & .product-cart-add--btn {
    position: absolute;
    z-index: 2;
    bottom: 12px;
    right: 12px;
  }
}
</style>
