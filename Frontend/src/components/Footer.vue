<template>
  <v-footer dark padless class="mb-14 mb-lg-0">
    <v-card class="flex" flat tile>
      <v-card-title class="blue-grey darken-4">
        <strong class="subheading">Hyvässä seurassa, Mesen Burgeri</strong>
        <v-spacer />
        <div>
          <v-btn v-for="icon in icons" :key="icon" class="mx-4" dark icon>
            <v-icon size="24px">
              {{ icon }}
            </v-icon>
          </v-btn>
        </div>
      </v-card-title>

      <v-card-text class="py-2 white--text text-center">
        {{ new Date().getFullYear() }} — <strong>Mesen Burgeri Oy</strong>
      </v-card-text>
    </v-card>
  </v-footer>
</template>

<script>
export default {
  data: () => ({
    icons: ["mdi-facebook", "mdi-instagram"],
  }),
};
</script>
