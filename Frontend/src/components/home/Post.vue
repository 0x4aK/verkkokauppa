<template>
  <v-row class="justify-center justify-md-start">
    <v-col cols="12" sm="8" md="6" lg="4" v-for="post in posts" :key="post.url">
      <v-card :to="post.url" elevation="4">
        <v-img height="230" :src="post.img" class="white--text align-end">
          <v-card-title class="post-title py-1">{{ post.title }}</v-card-title>
        </v-img>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: () => ({
    posts: [
      {
        title: "Selaa tuotteita",
        url: "menu",
        img: "images/menu.jpg",
      },
      {
        title: "Etsi lähin ravintola",
        url: "ravintolat",
        img: "images/restaurant.jpg",
      },
      {
        title: "Tule meille töihin!",
        url: "job",
        img: "images/job.jpg",
      },
    ],
  }),
};
</script>

<style lang="scss" scoped>
.post-title {
  background-color: rgba($color: #000000, $alpha: 0.6);
}
</style>
