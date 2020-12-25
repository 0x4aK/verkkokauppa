TODO: Finish register portion and logic

<template>
  <v-dialog v-model="dialog" persistent max-width="800px">
    <template v-slot:activator="{ on, attrs }">
      <div class="register d-flex flex-column text-center">
        <span>Etkö ole vielä jäsen?</span>
        <v-btn color="info" dark v-bind="attrs" v-on="on" outlined>
          Rekisteröidy
        </v-btn>
      </div>
    </template>

    <v-card>
      <v-form @submit.prevent="submit">
        <v-stepper v-model="page">
          <v-stepper-header>
            <v-stepper-step :complete="page > 1" step="1">
              Perustiedot
            </v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="page > 2" step="2"
              >Kirjautuminen
            </v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step step="3">Sijainti</v-stepper-step>

            <v-btn
              fab
              small
              color="grey lighten-5"
              @click="close"
              class="my-auto mr-4"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-stepper-header>

          <v-stepper-items class="singin__items">
            <v-stepper-content step="1">
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6" class="py-0">
                    <v-text-field
                      prepend-icon="mdi-account"
                      v-model="firstName"
                      name="fname"
                      label="Etunimi"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0 pl-11 pl-sm-3">
                    <v-text-field
                      v-model="lastName"
                      name="lname"
                      label="Sukunimi"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0">
                    <v-text-field
                      prepend-icon="mdi-map-marker"
                      v-model="address"
                      name="address"
                      label="Osoite"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0">
                    <v-text-field
                      prepend-icon="mdi-phone"
                      v-model="phone"
                      name="phone"
                      label="Puhelinnumero"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row class="mt-3">
                  <v-btn text color="error" @click="close">
                    Peruuta
                  </v-btn>
                  <v-spacer />
                  <v-btn color="info" @click="page = 2">
                    Seuraava
                  </v-btn>
                </v-row>
              </v-container>
            </v-stepper-content>

            <v-stepper-content step="2">
              <v-container>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      prepend-icon="mdi-email"
                      v-model="email"
                      name="email"
                      label="Sähköposti"
                      type="email"
                      required
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="12" sm="6" class="py-0">
                    <v-text-field
                      v-model="password"
                      prepend-icon="mdi-lock"
                      :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                      :type="showPassword ? 'text' : 'password'"
                      name="password"
                      label="Salasana"
                      hint="Vähintään 8 merkkiä"
                      @click:append="showPassword = !showPassword"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0 pl-11 pl-sm-3">
                    <v-text-field
                      v-model="confPassword"
                      :append-icon="
                        showConfPassword ? 'mdi-eye' : 'mdi-eye-off'
                      "
                      :type="showConfPassword ? 'text' : 'password'"
                      name="password_confirmation"
                      label="Vahvista salasana"
                      hint="Kirjoita salasana uudestaan"
                      @click:append="showConfPassword = !showConfPassword"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row class="mt-3">
                  <v-btn text @click="page = 1">
                    Takaisin
                  </v-btn>
                  <v-spacer />
                  <v-btn color="info" @click="page = 3">
                    Seuraava
                  </v-btn>
                </v-row>
              </v-container>
            </v-stepper-content>

            <v-stepper-content step="3">
              <v-container>
                <v-row class="mt-3">
                  <v-btn text @click="page = 2">
                    Takaisin
                  </v-btn>
                  <v-spacer />
                  <v-btn color="success" type="submit">
                    Rekisteröidy
                  </v-btn>
                </v-row>
              </v-container>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script>
import httpClient from "@/mixins/httpClient";

export default {
  mixins: [httpClient],

  data: () => ({
    dialog: false,
    page: 1,

    showPassword: false,
    showConfPassword: false,

    firstName: "",
    lastName: "",
    address: "",
    phone: "",

    email: "",
    password: "",
    confPassword: "",

    closestShop: "",
    wantsNews: false,
    hasAccepted: false,
  }),

  methods: {
    close: function() {
      this.dialog = false;
      this.page = 1;
    },
    submit: function() {
      this.http("api/auth/register", {
        method: "POST",
        body: JSON.stringify({
          fname: this.firstName,
          lname: this.lastName,
          address: this.address,
          phone: this.phone,
          email: this.email,
          password: this.password,
          password_confirmation: this.confPassword,
          closest: this.closestShop,
          news: this.wantsNews,
        }),
      })
        .then((resp) => {
          console.log(resp);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style lang="scss">
.singin__items {
  min-height: 300px;
}
</style>
