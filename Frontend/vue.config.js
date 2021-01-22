module.exports = {
  publicPath:
    process.env.NODE_ENV === "production" ? "/~karjyr/verkkokauppa/" : "/",

  lintOnSave: process.env.NODE_ENV !== "production",

  devServer: {
    proxy: {
      "^/api": {
        target: "http://localhost:8000",
        changeOrigin: true,
        pathRewrite: { "^/api/": "/api/" },
        logLevel: "debug",
      },
      "^/images": {
        target: "http://localhost:8000",
      },
    },
  },

  transpileDependencies: ["vuetify"],
};
