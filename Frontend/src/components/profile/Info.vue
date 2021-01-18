<template>
  <div v-if="user && stores">
    <v-card>
      <v-card-title>
        Profiili
        <v-spacer />
        <v-btn icon color="info" @click="editProfile = !editProfile">
          <v-icon v-text="editProfile ? 'mdi-cog-off' : 'mdi-cog'" />
        </v-btn>
      </v-card-title>
      <v-divider />
      <v-card-actions class="mx-4">
        <v-form
          :disabled="!editProfile"
          class="flex-grow-1 d-flex flex-column"
          @submit.prevent="submit()"
        >
          <v-text-field
            class="mb-1"
            label="Etunimi"
            hide-details
            v-model="fname"
          />
          <v-text-field
            class="mb-1"
            label="Sukunimi"
            hide-details
            v-model="lname"
          />
          <v-text-field
            class="mb-1"
            label="Osoite"
            hide-details
            v-model="address"
          />
          <v-text-field
            class="mb-1"
            label="Puhelin"
            hide-details
            v-model="phone"
          />
          <v-autocomplete
            color="info"
            label="Lähin ravintola"
            item-text="name"
            item-value="id"
            :items="stores"
            v-model="store"
          />

          <div class="d-flex mt-3">
            <v-spacer />
            <v-expand-transition>
              <v-btn v-show="editProfile" color="info" type="submit" text>
                Hyväksy
              </v-btn>
            </v-expand-transition>
          </div>
        </v-form>
      </v-card-actions>
    </v-card>
    <v-card class="mt-3">
      <v-card-title>
        Profiilin hallinta
        <v-spacer />
        <v-btn icon color="info" @click="modifyProfile = !modifyProfile">
          <v-icon v-text="modifyProfile ? 'mdi-cog-off' : 'mdi-cog'" />
        </v-btn>
      </v-card-title>
      <v-divider />
      <v-card-actions class="pa-4 justify-space-around">
        <NewPasswordBtn :disabled="!modifyProfile" />
        <DeleteUserBtn :disabled="!modifyProfile" />
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import NewPasswordBtn from "./NewPasswordBtn";
import DeleteUserBtn from "./DeleteUserBtn";

import httpClient from "@/mixins/httpClient";

import { mapActions, mapState } from "vuex";

export default {
  mixins: [httpClient],
  components: { NewPasswordBtn, DeleteUserBtn },

  data: () => ({
    editProfile: false,
    modifyProfile: false,

    fname: null,
    lname: null,
    address: null,
    phone: null,
    store: null,
  }),
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState(["stores"]),
  },
  methods: {
    submit() {
      this.editProfile = false;

      this.http("api/profile", {
        method: "PATCH",
        body: JSON.stringify({
          fname: this.fname,
          lname: this.lname,
          address: this.address,
          phone: this.phone,
          store: this.store,
        }),
      })
        .then((resp) => {
          this.setUser(resp.user);
          this.showMessage({ message: resp.message, color: "success" });
        })
        .catch((err) => console.error(err));
    },

    setInfo(user = this.user) {
      this.fname = user.fname;
      this.lname = user.lname;
      this.address = user.address;
      this.phone = user.phone;

      this.store = this.stores.filter((store) => store.id == user.store)[0];
    },
    ...mapActions(["showMessage"]),
    ...mapActions("auth", ["setUser"]),
  },
  watch: {
    user: function(val) {
      this.setInfo(val);
    },
    stores: function() {
      this.setInfo();
    },
  },
  beforeMount() {
    this.setInfo();
  },
};
</script>
