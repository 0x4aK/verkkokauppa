<template>
  <v-container class="menu mt-16 mt-md-0">
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
            <v-divider />

            <v-row>
              <v-col
                cols="6"
                lg="3"
                v-for="product in filteredProducts"
                :key="product.id"
              >
                <!-- TODO: Maybe add way to addToCart somehow -->
                <v-card :to="`product/${product.id}`">
                  <v-img
                    :src="'/images/menu.jpg'"
                    class="white--text align-end fill-height"
                  >
                    <v-card-title class="product-name py-0">{{
                      product.name
                    }}</v-card-title>
                  </v-img>
                </v-card>
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
      if (!this.selectedCategory)
        return [...this.products].sort((a, b) => a.category - b.category);

      return this.products.filter(
        (product) => product.category === this.selectedCategory
      );
    },

    ...mapState(["products", "categories", "selectedCategory"]),
  },

  methods: {
    ...mapActions(["setSelectedCategory", "addToCart", "getResource"]),
  },

  created() {
    this.getResource({ resource: "products", mutationName: "SET_PRODUCTS" });
  },
};
</script>

<style lang="scss" scoped>
.product-name {
  background-color: rgba($color: #000000, $alpha: 0.4);
}
</style>
