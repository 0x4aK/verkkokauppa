<template>
  <div class="text-center">
    <v-dialog v-model="dialog" width="500" persistent>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="warning"
          v-bind="attrs"
          v-on="on"
          small
          :disabled="disabled"
        >
          Vaihda salasana
        </v-btn>
      </template>

      <v-card>
        <v-form @submit.prevent="submit">
          <v-card-title>
            Vaihda salasana
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" class="py-0">
                  <v-text-field
                    v-model="oldPassword"
                    prepend-icon="mdi-lock"
                    :append-icon="showOldPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showOldPassword ? 'text' : 'password'"
                    name="password"
                    label="Vanha salasana"
                    @click:append="showOldPassword = !showOldPassword"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-divider />

              <v-row>
                <v-col cols="12" class="py-0">
                  <v-text-field
                    v-model="newPassword"
                    prepend-icon="mdi-lock"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    label="Uusi salasana"
                    hint="Vähintään 8 merkkiä"
                    @click:append="showPassword = !showPassword"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" class="py-0 pl-11">
                  <v-text-field
                    v-model="newPasswordConf"
                    :append-icon="showConfPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showConfPassword ? 'text' : 'password'"
                    name="password_confirmation"
                    label="Vahvista salasana"
                    hint="Kirjoita salasana uudestaan"
                    @click:append="showConfPassword = !showConfPassword"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions class="mx-4">
            <v-btn color="error" text @click="dialog = false">
              Peruuta
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn color="info" text type="submit">
              Hyväksy
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

    oldPassword: "",
    newPassword: "",
    newPasswordConf: "",

    showOldPassword: false,
    showPassword: false,
    showConfPassword: false,
  }),

  methods: {
    submit() {
      this.http("api/profile/password", {
        method: "PATCH",
        body: JSON.stringify({
          old_password: this.oldPassword,
          password: this.newPassword,
          password_confirmation: this.newPasswordConf,
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
