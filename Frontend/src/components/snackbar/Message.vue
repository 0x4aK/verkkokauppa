<template>
  <v-snackbar
    v-model="show"
    :timeout="timeout"
    :color="color"
    :top="$vuetify.breakpoint.mobile"
  >
    {{ text }}

    <template v-slot:action="{ attrs }">
      <v-btn text v-bind="attrs" @click="show = false">
        Sulje
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
  data: () => ({
    show: false,
    text: "",
    color: "",
    timeout: 5000,
  }),

  created() {
    this.$store.subscribe((mutation, state) => {
      if (mutation.type === "SET_MESSAGE") {
        this.text = state.snackbarMessage;
        this.color = state.snackbarColor;
        this.show = true;
      }
    });
  },
};
</script>

<style></style>
