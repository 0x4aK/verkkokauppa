import { mapState, mapGetters, mapActions } from "vuex";

export default {
  methods: {
    async http(url, params = {}) {
      const headers = params.headers || { "Content-Type": "application/json" };

      const fetchParams = {
        headers,
        ...params,
      };

      if (this.isLoggedIn) {
        fetchParams.headers["Authorization"] = `Bearer ${this.access_token}`;
      }

      console.debug(
        `Fetching "%c${url}%c" with params: %o`,
        "color: #75BFFF",
        "color: white",
        fetchParams
      );

      const res = await fetch(url, fetchParams); //TODO: replace url in production
      if (res.status === 403) {
        this.logout();
        if (this.$route.name === "Login") return false;
        this.$router.push({ name: "Login" });
      }

      let json;
      try {
        json = await res.json();
      } catch (err) {
        throw Error(res.statusText);
      }
      if (!res.ok) throw Error(json.message);
      console.debug("JSON resp:", json);
      return json;
    },
    ...mapActions("auth", ["logout"]),
  },

  computed: {
    ...mapState("auth", ["access_token"]),
    ...mapGetters("auth", ["isLoggedIn"]),
  },
};
