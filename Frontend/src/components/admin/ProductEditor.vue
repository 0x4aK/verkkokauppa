<template>
  <v-container class="product-editor">
    <v-row class="justify-center">
      <v-col cols="12" md="6" lg="4">
        <v-card>
          <v-form @submit.prevent="submitProduct">
            <v-card-title>
              Tuotteen hallinta
              <v-spacer />

              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    icon
                    color="error"
                    @click="selectedProduct = null"
                    :disabled="!selectedProduct"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon>mdi-close</v-icon>
                  </v-btn>
                </template>
                <span>Lopeta hallinta</span>
              </v-tooltip>
            </v-card-title>

            <v-expand-transition>
              <div v-if="selectedProduct">
                <v-img height="230" :src="selectedProduct.img"></v-img>

                <v-card-text>
                  <v-text-field label="Nimi" v-model="selectedProduct.name" />
                  <div class="d-flex">
                    <v-text-field
                      label="Hinta"
                      v-model="selectedProduct.price"
                    />
                    <v-switch
                      class="mx-5"
                      v-model="selectedProduct.is_featured"
                      label="Esillä"
                      color="success"
                    />
                  </div>
                  <v-textarea
                    label="Tuoteselostus"
                    auto-grow
                    v-model="selectedProduct.description"
                  />

                  <v-select
                    :items="categoryItems"
                    label="Standard"
                    item-text="title"
                    item-value="id"
                    v-model="selectedProduct.category"
                    :prepend-icon="
                      this.categories[selectedProduct.category].icon
                    "
                  />
                </v-card-text>
              </div>
            </v-expand-transition>

            <v-row class="pa-4 justify-space-around" dense>
              <v-col cols="12" sm="4">
                <DeleteProductBtn
                  :product="selectedProduct"
                  :disabled="!selectedProduct || selectedProduct.id === 0"
                  @productDeleted="getProducts"
                />
              </v-col>
              <v-col cols="12" sm="4">
                <v-btn
                  color="success"
                  small
                  block
                  :disabled="!selectedProduct || selectedProduct.id === 0"
                  @click="selectedProduct.id = 0"
                >
                  Kopioi
                </v-btn>
              </v-col>
              <v-col cols="12" sm="4">
                <v-btn
                  color="info"
                  type="submit"
                  small
                  block
                  :disabled="!selectedProduct"
                >
                  {{ addBtnLabel }}
                </v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card>
      </v-col>

      <v-col cols="12" md="6" lg="8">
        <v-card>
          <v-container>
            <v-row dense>
              <v-col cols="6" lg="3">
                <v-card @click="makeEmptyProduct" height="164">
                  <v-img
                    src="/images/menu.jpg"
                    class="white--text align-center fill-height"
                  >
                    <v-card-title class="py-0 justify-center">
                      <v-icon color="light-green accent-3" x-large>
                        mdi-plus
                      </v-icon>
                      Lisää
                    </v-card-title>
                  </v-img>
                </v-card>
              </v-col>

              <v-col
                cols="6"
                lg="3"
                v-for="product in products"
                :key="product.id"
              >
                <v-card @click="selectedProduct = { ...product }" height="164">
                  <v-img
                    :src="product.img"
                    class="white--text align-end fill-height"
                  >
                    <v-card-title class="py-0">
                      {{ product.name }}
                      <v-icon
                        class="featured-star"
                        color="success"
                        v-if="product.is_featured"
                      >
                        mdi-star
                      </v-icon>
                    </v-card-title>
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
import httpClient from "@/mixins/httpClient";

import DeleteProductBtn from "@/components/admin/DeleteProductBtn";

import { mapActions, mapState } from "vuex";

export default {
  mixins: [httpClient],

  components: { DeleteProductBtn },

  data: () => ({
    selectedProduct: null,
  }),

  computed: {
    categoryItems() {
      return this.categories
        .map((category, index) => Object.assign({ ...category }, { id: index }))
        .slice(1);
    },
    addBtnLabel() {
      return this.selectedProduct?.id === 0 ? "Lisää" : "Hyväksy";
    },
    ...mapState(["products", "categories"]),
  },

  methods: {
    submitProduct() {
      this.http(`api/admin/products/${this.selectedProduct.id}`, {
        method: "PATCH",
        body: JSON.stringify({ ...this.selectedProduct }),
      })
        .then((resp) => {
          this.showMessage({ message: resp.message, color: "success" });
          this.getProducts();
        })
        .catch((err) => {
          console.error(err);
          this.showMessage({ message: err.message, color: "error" });
        });
    },

    makeEmptyProduct() {
      this.selectedProduct = {
        id: 0,
        name: "",
        price: "",
        is_featured: false,
        description: "",
        category: 1,
        img: "",
      };
    },

    getProducts() {
      this.selectedProduct = null;
      this.getResource({
        resource: "products",
        mutationName: "SET_PRODUCTS",
        forced: true,
      });
    },
    ...mapActions(["getResource", "showMessage"]),
  },

  created() {
    this.getResource({ resource: "products", mutationName: "SET_PRODUCTS" });
  },
};
</script>

<style lang="scss">
.featured-star {
  position: absolute !important;
  top: 6px;
  right: 6px;
}
</style>
