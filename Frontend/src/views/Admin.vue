<template>
  <v-container class="admin">
    <v-row class="justify-center justify-md-start">
      <v-col cols="12" sm="10" md="6" lg="4">
        <OrderEditor />
      </v-col>

      <v-col cols="12" sm="10" md="6" lg="4">
        <v-card>
          RESTAURANT EDITOR
        </v-card>

        <v-card class="mt-4">
          RESTAURANTS
        </v-card>
      </v-col>

      <v-col cols="12" sm="10" md="6" lg="4">
        <v-card>
          USER EDITOR
        </v-card>

        <v-card class="mt-4">
          USERS
        </v-card>
      </v-col>

      <v-col cols="12" sm="10" md="6" lg="4">
        <v-card>
          PRODUCTS
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import OrderEditor from "@/components/admin/OrderEditor";

export default {
  components: { OrderEditor },
};
</script>
