<template>
  <div>
    Avoinna:
    <div
      class="d-flex align-center"
      v-for="openTime in openTimes"
      :key="openTime.id"
    >
      <v-text-field
        class="mr-1"
        label="Mistä"
        single-line
        hide-details
        outlined
        dense
        v-model="openTime.from"
      />
      <v-text-field
        label="Auki"
        single-line
        hide-details
        outlined
        dense
        v-model="openTime.fromTime"
      />
      <div class="ma-3">-</div>
      <v-text-field
        label="Mihin"
        single-line
        hide-details
        outlined
        dense
        v-model="openTime.to"
      />
      <v-text-field
        class="ml-1 mr-1"
        label="Kiinni"
        single-line
        hide-details
        outlined
        dense
        v-model="openTime.toTime"
      />
      <v-btn
        v-if="openTime.id === 0"
        color="success"
        icon
        @click="addTime(openTime)"
      >
        <v-icon>mdi-plus</v-icon>
      </v-btn>
      <v-btn v-else color="error" icon @click="delTime(openTime.id)">
        <v-icon>mdi-minus</v-icon>
      </v-btn>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  props: ["store"],

  data: () => ({
    openTimes: [],
  }),

  computed: mapState(["days"]),

  methods: {
    addTime(time) {
      if (this.openTimes.length > 6) {
        this.showMessage({ message: "Aikoja voi olla vain 7", color: "error" });
        return;
      }

      const error = this.validateTime(time);
      if (error)
        return this.showMessage({
          message: error,
          color: "error",
        });

      this.openTimes.push({
        id: this.openTimes.length,
        from: time.from,
        to: time.to,
        fromTime: time.fromTime,
        toTime: time.toTime,
      });

      [time.from, time.to, time.fromTime, time.toTime] = ["", "", "", ""];
    },

    delTime(id) {
      this.openTimes = this.openTimes.filter((time) => time.id !== id);
    },

    validateTime(time) {
      const weekdays = Object.keys(this.days);
      if (!weekdays.includes(time.from))
        return `Tarkista päivät (${weekdays.join(", ")})`;

      const [fromTime, toTime] = [Number(time.fromTime), Number(time.toTime)];

      if (
        [fromTime, toTime].some((time) => {
          return isNaN(time) || time < 6 || time > 24;
        })
      )
        return "Tarkista ajat (6 < aika < 24)";

      if (fromTime >= toTime)
        return "Kiinnimenoajan tulee olla suurempi kuin aukeamisaika";
    },

    getTimeObj() {
      const openTimes = this.openTimes.filter((time) => time.id !== 0);

      if (openTimes.some(this.validateTime)) return false;

      return openTimes.reduce((acc, time) => {
        const key = `${time.from}${time.to ? `-${time.to}` : ""}`;
        return {
          ...acc,
          [key]: `${time.fromTime}-${time.toTime}`,
        };
      }, {});
    },
    ...mapActions(["showMessage"]),
  },

  watch: {
    store: {
      immediate: true,
      handler() {
        this.openTimes = [
          {
            id: 0,
            from: "",
            to: "",
            fromTime: "",
            toTime: "",
          },
        ];
        Object.entries(this.store.open || {}).forEach(([date, time]) => {
          const [from, to] = date.split("-");
          const [fromTime, toTime] = time.split("-");

          this.openTimes.push({
            id: this.openTimes.length,
            from,
            to,
            fromTime,
            toTime,
          });
        });
      },
    },
  },
};
</script>
