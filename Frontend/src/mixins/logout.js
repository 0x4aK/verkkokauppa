import { mapActions } from "vuex";

export default {
  methods: {
    doLogout(message) {
      this.logout();
      this.showMessage(
        message || {
          message: "Uloskirjautuminen onnistui",
          color: "info",
        }
      );
      if (this.$route.name !== "Home") this.$router.push({ name: "Home" });
    },
    ...mapActions(["showMessage"]),
    ...mapActions("auth", ["logout"]),
  },
};
