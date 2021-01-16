<template>
  <div>
    <v-card>
      RESTAURANT EDITOR
    </v-card>

    <v-card class="mt-4">
      RESTAURANTS
    </v-card>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  methods: {
    ...mapActions(["getResource", "showMessage"]),
  },
  created() {
    this.getResource({ resource: "products", mutationName: "SET_PRODUCTS" });
  },
};
</script>

<style></style>
