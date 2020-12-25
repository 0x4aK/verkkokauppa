<template>
  <div class="text-center">
    <v-dialog v-model="dialog" width="500" persistent>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="error"
          v-bind="attrs"
          v-on="on"
          small
          :disabled="disabled"
        >
          Poista tili
        </v-btn>
      </template>

      <v-card>
        <v-form @submit.prevent="submit">
          <v-card-title>
            Poista tili
          </v-card-title>

          <v-card-text>
            <div class="subtitle-2 text-center error--text">
              Oletko varma, että haluat poistaa käyttäjätilisi?
            </div>
            <v-divider />

            <v-container>
              <v-row>
                <v-col cols="12" class="py-0">
                  <v-text-field
                    v-model="password"
                    prepend-icon="mdi-lock"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    label="Salasana"
                    @click:append="showPassword = !showPassword"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
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
import Logout from "@/mixins/logout";

export default {
  props: ["disabled"],
  mixins: [httpClient, Logout],
  data: () => ({
    dialog: false,

    password: "",
    showPassword: false,
  }),

  methods: {
    submit() {
      this.http("api/profile/", {
        method: "DELETE",
        body: JSON.stringify({
          password: this.password,
        }),
      })
        .then((resp) => {
          this.doLogout({
            message: resp.message,
            color: "success",
          });
          this.dialog = false;
        })
        .catch((err) => console.error(err));
    },
  },
};
</script>
