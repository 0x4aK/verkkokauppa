<template>
  <div class="text-center">
    <v-dialog v-model="dialog" width="400" persistent>
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="error" v-bind="attrs" v-on="on" :disabled="disabled">
          Poista
        </v-btn>
      </template>

      <v-card>
        <v-form @submit.prevent="submit">
          <v-card-title> Poista käyttäjä</v-card-title>

          <v-card-text>
            <div class="subtitle-2 text-center error--text">
              Oletko varma, että haluat poistaa tämän käyttäjän?
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
  props: ["user", "disabled"],
  mixins: [httpClient],
  data: () => ({
    dialog: false,
  }),

  methods: {
    submit() {
      this.http(`api/admin/users/${this.user.id}`, {
        method: "DELETE",
      })
        .then((resp) => {
          this.showMessage({ message: resp.message, color: "success" });
          this.$emit("userDeleted");
        })
        .catch((err) => {
          console.error(err);
          this.showMessage({ message: err.message, color: "error" });
        })
        .finally(() => (this.dialog = false));
    },
    ...mapActions(["showMessage"]),
  },
};
</script>
