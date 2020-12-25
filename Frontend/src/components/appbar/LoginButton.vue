<template>
  <v-btn v-if="mobile" to="/kirjaudu" exact>
    <span>Kirjaudu</span>
    <v-icon>mdi-login</v-icon>
  </v-btn>

  <v-btn v-else text to="/kirjaudu" exact>
    <v-icon left>mdi-login</v-icon>
    Kirjaudu
  </v-btn>
</template>

<script>
export default {
  props: ["mobile"],
};
</script>

<style></style>
