<template>
  <v-container class="admin" fluid>
    <v-row class="px-3">
      <v-tabs grow v-model="tab">
        <v-tab>Hallinta panelit</v-tab>
        <v-tab>Tuote paneli</v-tab>
      </v-tabs>
    </v-row>

    <v-divider class="my-2" />

    <v-tabs-items v-model="tab">
      <v-tab-item>
        <v-container>
          <v-row class="justify-center justify-md-start">
            <v-col cols="12" sm="10" md="6" lg="4">
              <OrderEditor />
            </v-col>

            <v-col cols="12" sm="10" md="6" lg="8" class="pa-0">
              <v-row>
                <v-col cols="12">
                  <RestaurantEditor />
                </v-col>

                <v-col cols="12">
                  <UserEditor />
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>
      <v-tab-item>
        <ProductEditor />
      </v-tab-item>
    </v-tabs-items>
  </v-container>
</template>

<script>
import OrderEditor from "@/components/admin/OrderEditor";
import RestaurantEditor from "@/components/admin/RestaurantEditor";
import UserEditor from "@/components/admin/UserEditor";
import ProductEditor from "@/components/admin/ProductEditor";

export default {
  components: { OrderEditor, RestaurantEditor, UserEditor, ProductEditor },

  data: () => ({
    tab: 0,
  }),
};
</script>
