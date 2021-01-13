<template>
  <div class="text-center">
    <v-dialog v-model="dialog" width="400" persistent>
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="error" v-bind="attrs" v-on="on" small>
          Poista tilaus
        </v-btn>
      </template>

      <v-card>
        <v-form @submit.prevent="submit">
          <v-card-title> Poista tilaus #{{ id }} </v-card-title>

          <v-card-text>
            <div class="subtitle-2 text-center error--text">
              Oletko varma, että haluat poistaa tilauksen?
            </div>
          </v-card-text>

          <v-card-actions class="mx-4">
            <v-btn color="info" text @click="dialog = false">
              Peruuta
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn color="error" text type="submit">
              Poista
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import httpClient from "@/mixins/httpClient";

import { mapActions } from "vuex";

export default {
  props: ["id"],
  mixins: [httpClient],
  data: () => ({
    dialog: false,
  }),

  methods: {
    submit() {
      this.http(`api/admin/orders/${this.id}`, {
        method: "DELETE",
      })
        .then((resp) => {
          this.dialog = false;
          this.showMessage({ message: resp.message, color: "success" });
          this.$emit("orderDeleted");
        })
        .catch((err) => console.error(err));
    },
    ...mapActions(["showMessage"]),
  },
};
</script>
