<template>
  <div class="text-center">
    <v-dialog v-model="dialog" width="450" persistent>
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="info" v-bind="attrs" v-on="on">
          Uusi Admin/Owner
        </v-btn>
      </template>

      <v-card>
        <v-expand-transition>
          <div v-if="created.length">
            <v-card-title class="pb-0">Luodut käyttäjät</v-card-title>
            <v-card-text>
              <div
                class="d-flex"
                v-for="(account, index) in created"
                :key="index"
              >
                <v-text-field
                  class="mr-2"
                  readonly
                  hide-details
                  label="Sähköposti"
                  prepend-icon="mdi-account"
                  :value="account.email"
                />
                <v-text-field
                  readonly
                  hide-details
                  prepend-icon="mdi-lock"
                  :append-icon="
                    account.showPassword ? 'mdi-eye' : 'mdi-eye-off'
                  "
                  :type="account.showPassword ? 'text' : 'password'"
                  label="Salasana"
                  @click:append="account.showPassword = !account.showPassword"
                  :value="account.password"
                />
              </div>
            </v-card-text>
          </div>
        </v-expand-transition>
        <v-form @submit.prevent="submit">
          <v-card-title>Luo Admin/Owner käyttäjä</v-card-title>

          <v-card-text>
            <div class="d-flex">
              <v-text-field
                label="Etunimi"
                v-model="user.fname"
                prepend-icon="mdi-account"
              />
              <v-text-field
                class="ml-4"
                label="Sukunimi"
                v-model="user.lname"
              />
            </div>
            <v-text-field
              label="Sähköposti"
              v-model="user.email"
              prepend-icon="mdi-email"
            />
            <v-text-field
              label="Osoite"
              v-model="user.address"
              prepend-icon="mdi-map-marker"
            />
            <v-text-field
              label="Puhelin"
              v-model="user.phone"
              prepend-icon="mdi-phone"
            />
            <v-autocomplete
              color="info"
              label="Ravintola"
              item-text="name"
              item-value="id"
              :items="stores"
              v-model="user.store"
              prepend-icon="mdi-store"
            />

            <v-chip-group
              mandatory
              active-class="info--text"
              v-model="user.role"
            >
              <v-icon class="mr-2">mdi-shield-account-variant</v-icon>
              <v-chip
                label
                v-for="role in roles"
                :key="role.lvl"
                :value="role.lvl"
              >
                {{ role.name }}
              </v-chip>
            </v-chip-group>
          </v-card-text>

          <v-card-actions class="pa-4">
            <v-btn color="success" @click="dialog = false">
              Sulje
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn color="info" type="submit">
              Luo
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import httpClient from "@/mixins/httpClient";

import { mapActions, mapState } from "vuex";

export default {
  mixins: [httpClient],

  data: () => ({
    dialog: false,

    roles: [
      { name: "Owner", lvl: 1 },
      { name: "Admin", lvl: 2 },
    ],

    user: {
      fname: "",
      lname: "",
      address: "",
      phone: "",

      email: "",

      store: 0,
      role: 1,
    },

    created: [],
  }),

  computed: mapState(["stores"]),

  methods: {
    submit() {
      this.http("api/admin/users/", {
        method: "POST",
        body: JSON.stringify(this.user),
      })
        .then((resp) => {
          this.showMessage({ message: resp.message, color: "success" });
          this.created.push({
            email: resp.data.email,
            password: resp.data.password,
            showPassword: false,
          });
          this.$emit("userCreated");
          this.resetUser();
        })
        .catch((err) => {
          console.error(err);
          this.showMessage({ message: err.message, color: "error" });
        });
    },
    resetUser() {
      this.user = {
        fname: "",
        lname: "",
        address: "",
        phone: "",

        email: "",

        store: 0,
        role: 1,
      };
    },
    ...mapActions(["getResource", "showMessage"]),
  },

  created() {
    this.getResource({ resource: "stores", mutationName: "SET_STORES" });
  },
};
</script>
