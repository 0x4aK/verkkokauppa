<template>
  <v-container class="home mt-16 mt-md-0">
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
