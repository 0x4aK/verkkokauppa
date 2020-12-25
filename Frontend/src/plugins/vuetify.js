import Vue from "vue";
import Vuetify from "vuetify/lib";

import fi from "vuetify/es5/locale/fi";

Vue.use(Vuetify);

export default new Vuetify({
  lang: {
    locales: { fi },
    current: "fi",
  },
});
