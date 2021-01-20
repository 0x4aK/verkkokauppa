<template>
  <v-container class="login" fill-height>
    <v-row justify="center" align="center">
      <v-col cols="12" sm="10" lg="6">
        <v-card>
          <v-form @submit.prevent="submitLogin" ref="loginForm">
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    prepend-icon="mdi-email"
                    v-model="email"
                    name="email"
                    label="Sähköposti"
                    id="email"
                    type="email"
                    required
                    autofocus
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12">
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

              <v-row class="mx-2">
                <v-col cols="6" sm="5" md="4">
                  <Register />
                </v-col>
                <v-spacer />
                <v-col cols="5" sm="5" md="4" class="d-flex align-end">
                  <v-btn color="success" type="submit" block :loading="loading">
                    Kirjaudu
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";

import httpClient from "@/mixins/httpClient";

import Register from "@/components/Register";

export default {
  components: {
    Register,
  },

  mixins: [httpClient],

  data: () => ({
    loading: false,

    email: "",
    password: "",

    showPassword: false,

    from: {},
  }),

  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.from = from;
    });
  },

  methods: {
    submitLogin() {
      this.loading = true;

      this.http("api/auth/login", {
        method: "POST",
        body: JSON.stringify({
          email: this.email,
          password: this.password,
        }),
      })
        .then((resp) => {
          if (resp.code === 400) return;

          this.login(resp);
          this.showMessage({ message: "Kirjauduit sisään", color: "success" });

          this.$router.push(this.from);
        })
        .catch((error) => {
          console.error(error);
          this.logout();
          this.$refs.loginForm.reset();
        })
        .finally(() => {
          this.loading = false;
        });
    },
    ...mapActions(["showMessage"]),
    ...mapActions("auth", ["login", "logout"]),
  },
};
</script>
