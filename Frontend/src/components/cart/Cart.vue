<template>
  <div :class="['cart ma-2 ma-lg-4', { mobile: $vuetify.breakpoint.mobile }]">
    <div style="position: absolute">
      <v-expansion-panels accordion popout>
        <v-expansion-panel>
          <v-expansion-panel-header :class="{ flash: isFlashing }">
            <div>
              <v-icon>mdi-cart</v-icon>
              Cart
            </div>
            <div class="text-caption success--text">{{ totalPrice }}€</div>
          </v-expansion-panel-header>
          <v-expansion-panel-content v-for="(details, id) in cart" :key="id">
            <div class="d-flex align-center">
              <div class="text-caption">
                {{ details.name }} x
                <span class="text-subtitle-2">{{ details.quantity }}</span>
              </div>
              <div class="ml-auto mr-1 text-body-2 success--text">
                {{ (details.quantity * details.price).toFixed(2) }}€
              </div>
              <v-btn icon color="red" @click="removeFromCart(id)">
                <v-icon>mdi-minus</v-icon>
              </v-btn>
            </div>
          </v-expansion-panel-content>
          <v-expansion-panel-content>
            <div class="d-flex align-center">
              <v-btn class="ml-auto" text to="/kassa" color="black">
                Siirry kassalle
                <v-icon class="ml-1">mdi-arrow-right</v-icon>
              </v-btn>
            </div>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  data: () => ({
    isFlashing: false,
  }),

  computed: {
    ...mapState("cart", ["cart"]),

    totalPrice() {
      return Object.values(this.cart)
        .reduce((acc, cartObj) => acc + cartObj.quantity * cartObj.price, 0)
        .toFixed(2);
    },
  },
  methods: {
    flash() {
      this.isFlashing = true;
      setTimeout(() => (this.isFlashing = false), 500);
    },

    ...mapActions("cart", ["removeFromCart"]),
  },

  created() {
    this.$store.subscribe((mutation) => {
      if (mutation.type === "cart/SET_CART") {
        this.flash();
      }
    });
  },
};
</script>

<style lang="scss">
.cart {
  position: sticky;
  z-index: 10;
  bottom: 16px;

  &.mobile {
    position: fixed;
    top: 16px;
    bottom: auto;
    right: 300px;

    & > div {
      top: 0;
      bottom: auto;
    }
  }

  & > div {
    width: 300px;
    bottom: 0;
  }
}

.flash {
  background-color: rgba($color: #ffe817, $alpha: 0.2);
}
</style>
