import { mapState, mapGetters, mapActions } from "vuex";

export default {
  methods: {
    async http(url, params = {}) {
      const fetchParams = Object.assign({}, params, {
        headers: Object.assign(
          {
            "Content-Type": "application/json",
          },
          params.headers
        ),
      });

      if (this.isLoggedIn) {
        fetchParams.headers["Authorization"] = `Bearer ${this.access_token}`;
      }

      console.debug(
        `Fetching "%c${url}%c" with params: %o`,
        "color: #75BFFF",
        "color: white",
        fetchParams
      );

      const res = await fetch(`http://192.168.1.64:8000/${url}`, fetchParams); //TODO: replace url in production
      if (res.status === 403) {
        this.logout();
        this.$router.push({
          name: "Login",
        });
      }

      const json = await res.json();

      console.debug("JSON resp:", json);

      if (!res.ok) throw Error(json.message);
      return json;
    },
    ...mapActions("auth", ["logout"]),
  },

  computed: {
    ...mapState("auth", ["access_token"]),
    ...mapGetters("auth", ["isLoggedIn"]),
  },
};
