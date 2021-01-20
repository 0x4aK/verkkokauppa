import httpClient from "./httpClient";

import { mapActions } from "vuex";

export default {
  mixins: [httpClient],
  methods: {
    doLogout() {
      this.http("api/auth/logout", {
        method: "POST",
      })
        .then((resp) => {
          this.logout();
          this.showMessage({
            message: resp.message,
            color: "info",
          });
        })
        .catch((err) => console.log(err));

      if (this.$route.name !== "Home") this.$router.push({ name: "Home" });
    },
    ...mapActions(["showMessage"]),
    ...mapActions("auth", ["logout"]),
  },
};
