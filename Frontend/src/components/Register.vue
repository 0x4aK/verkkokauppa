<template>
  <v-dialog v-model="dialog" persistent max-width="600px">
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

            <v-divider />

            <v-stepper-step :complete="page > 2" step="2"
              >Kirjautuminen
            </v-stepper-step>

            <v-divider />

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
                      v-model="user.fname"
                      label="Etunimi"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0 pl-11 pl-sm-3">
                    <v-text-field
                      v-model="user.lname"
                      label="Sukunimi"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0">
                    <v-text-field
                      prepend-icon="mdi-map-marker"
                      v-model="user.address"
                      label="Osoite"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0">
                    <v-text-field
                      prepend-icon="mdi-phone"
                      v-model="user.phone"
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
                      v-model="user.email"
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
                      v-model="user.password"
                      prepend-icon="mdi-lock"
                      :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                      :type="showPassword ? 'text' : 'password'"
                      label="Salasana"
                      hint="Vähintään 8 merkkiä"
                      @click:append="showPassword = !showPassword"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" class="py-0 pl-11 pl-sm-3">
                    <v-text-field
                      v-model="user.password_confirmation"
                      :append-icon="
                        showConfPassword ? 'mdi-eye' : 'mdi-eye-off'
                      "
                      :type="showConfPassword ? 'text' : 'password'"
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
                <v-row class="mx-6">
                  <v-autocomplete
                    color="info"
                    label="Lähin ravintola"
                    item-text="name"
                    item-value="id"
                    :items="stores"
                    v-model="user.store"
                  />
                </v-row>
                <v-row class="justify-center">
                  <v-checkbox v-model="user.accepted">
                    <template v-slot:label>
                      <div>
                        Olen lukenut
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on }">
                            <a
                              target="_blank"
                              href="ehdot"
                              @click.stop
                              v-on="on"
                            >
                              Tietosuoja ja käyttöehdot
                            </a>
                          </template>
                          Aukeaa uudessa välilehdessä
                        </v-tooltip>
                        ja hyväksyn ne
                      </div>
                    </template>
                  </v-checkbox>
                </v-row>
                <v-row class="mt-3">
                  <v-btn text @click="page = 2">
                    Takaisin
                  </v-btn>
                  <v-spacer />
                  <v-btn
                    color="success"
                    type="submit"
                    :disabled="!user.accepted"
                  >
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
import { mapActions, mapState } from "vuex";

export default {
  mixins: [httpClient],

  data: () => ({
    dialog: false,
    page: 1,

    showPassword: false,
    showConfPassword: false,

    user: {
      fname: "",
      lname: "",
      address: "",
      phone: "",

      email: "",
      password: "",
      password_confirmation: "",

      store: 0,

      accepted: false,
    },
  }),

  computed: mapState(["stores"]),

  methods: {
    close: function() {
      this.dialog = false;
      this.page = 1;
    },
    submit: function() {
      this.http("api/auth/register", {
        method: "POST",
        body: JSON.stringify(this.user),
      })
        .then((resp) => {
          this.showMessage({ message: resp.message, color: "success" });
          this.dialog = false;
        })
        .catch((err) => {
          console.error(err);
          this.showMessage({ message: err.message, color: "error" });
        });
    },
    ...mapActions(["getResource", "showMessage"]),
  },

  created() {
    this.getResource({ resource: "stores", mutationName: "SET_STORES" });
  },
};
</script>
