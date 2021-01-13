<template>
  <v-container class="home mt-lg-0 mt-16 mb-lg-8">
    <Carousel />
    <Post />
  </v-container>
</template>

<script>
import Carousel from "@/components/home/Carousel";
import Post from "@/components/home/Post";

export default {
  components: {
    Carousel,
    Post,
  },
};
</script>
