<template>
  <v-card class="fill-height" elevation="3">
    <v-img height="250" :src="store.img" />
    <v-card-title>{{ store.name }}</v-card-title>

    <v-card-text>
      <v-list dense>
        <v-list-item v-for="slot in info" :key="slot.name">
          <v-list-item-icon class="mr-1">
            <v-icon>{{ slot.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ store[slot.name] }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item>
          <v-list-item-icon class="mr-1">
            <v-icon>mdi-store</v-icon>
          </v-list-item-icon>

          <v-list dense class="py-0">
            <v-list-item
              v-for="(hours, days, index) in store.open"
              :key="index"
            >
              <v-list-item-icon class="mr-1 align-self-center">
                <v-icon small :color="isOpen(days, store.openArray)">
                  mdi-circle
                </v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <div>{{ days }}:</div>
                <div>{{ hours }}</div>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-list-item>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: ["store"],
  computed: mapState(["days"]),

  data: () => ({
    info: [
      { name: "address", icon: "mdi-map-marker" },
      { name: "phone", icon: "mdi-phone" },
    ],
    now: new Date(),
  }),

  methods: {
    isOpen(days, openArray) {
      const [fromDay, toDay = fromDay] = days
        .split("-")
        .map((day) => this.days[day]);

      const today = this.now.getDay();

      if (today >= fromDay && today <= toDay) {
        if (
          this.now.getHours() >= openArray[today].open.getHours() &&
          this.now.getHours() < openArray[today].close.getHours()
        )
          return "green";

        return "red";
      }
    },
  },
};
</script>
