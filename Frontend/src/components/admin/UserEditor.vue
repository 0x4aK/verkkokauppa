<template>
  <v-card>
    <v-container>
      <v-row>
        <v-col cols="12" lg="6">
          <v-card-title class="pa-0">
            Käyttäjä{{ selectedUser ? `n #${selectedUser.id}` : "" }} hallinta
            <v-spacer />

            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  icon
                  color="error"
                  @click="selectedUser = null"
                  :disabled="!selectedUser"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-icon>mdi-close</v-icon>
                </v-btn>
              </template>
              <span>Lopeta hallinta</span>
            </v-tooltip>
          </v-card-title>

          <v-divider class="my-2" />

          <v-expand-transition>
            <v-row v-if="selectedUser" dense>
              <v-col cols="12" class="text-caption">
                Nimi:
                <div class="text-subtitle-2">
                  {{ selectedUser.fname }} {{ selectedUser.lname }}
                </div>
              </v-col>
              <v-col cols="6" class="text-caption mt-1">
                Käyttäjä luotu:
                <div class="text-subtitle-2">
                  {{ formatTime(selectedUser.created_at) }}
                </div>
              </v-col>
              <v-col cols="6" class="text-caption mt-1">
                Viimeksi muokattu:
                <div class="text-subtitle-2">
                  {{ formatTime(selectedUser.updated_at) }}
                </div>
              </v-col>
              <v-col cols="6" class="text-caption mt-1">
                Sähköposti:
                <div class="text-subtitle-2">
                  {{ selectedUser.email }}
                </div>
              </v-col>
              <v-col cols="6" class="text-caption mt-1">
                Puhelin:
                <div class="text-subtitle-2">
                  {{ selectedUser.phone }}
                </div>
              </v-col>
              <v-col cols="12" class="text-caption mt-1">
                Taso:
                <div class="text-subtitle-2">
                  {{ selectedUser.role.name }}
                </div>
              </v-col>
            </v-row>
          </v-expand-transition>

          <div class="d-flex mt-5">
            <DeleteUserAdminBtn
              :user="selectedUser"
              :disabled="!selectedUser"
              @userDeleted="getUsers"
            />
            <v-spacer />
            <CreateUserAdminBtn @userCreated="getUsers" />
          </div>
        </v-col>

        <v-col cols="12" lg="6">
          <v-virtual-scroll :items="users" item-height="50" height="350">
            <template v-slot:default="{ item }">
              <v-list-item
                dense
                :key="item.id"
                @click="selectedUser = item"
                style="height: 49px"
              >
                <v-list-item-content>
                  <div class="text-caption text--disabled">
                    Käyttäjä #{{ item.id }}
                  </div>
                  <div class="text-body-2">
                    {{ item.fname }} {{ item.lname }}
                  </div>
                </v-list-item-content>
              </v-list-item>
              <v-divider />
            </template>
          </v-virtual-scroll>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>

<script>
import httpClient from "@/mixins/httpClient";

import DeleteUserAdminBtn from "./DeleteUserAdminBtn";
import CreateUserAdminBtn from "./CreateUserAdminBtn";

export default {
  mixins: [httpClient],
  components: { DeleteUserAdminBtn, CreateUserAdminBtn },

  data: () => ({
    selectedUser: null,
    users: [],
  }),

  methods: {
    getUsers() {
      this.selectedUser = null;
      this.http("api/admin/users/all")
        .then((resp) => (this.users = resp.data))
        .catch((err) => console.error(err));
    },
    formatTime(date) {
      return new Date(date).toLocaleString("fi-FI");
    },
  },

  beforeMount() {
    this.getUsers();
  },
};
</script>
