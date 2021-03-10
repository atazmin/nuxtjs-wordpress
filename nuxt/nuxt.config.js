
export default {
  /*
  ** Nuxt rendering mode
  ** See https://nuxtjs.org/api/configuration-mode
  */
  mode: 'universal',
  /*
  ** Nuxt target
  ** See https://nuxtjs.org/api/configuration-target
  */
  target: 'server',
  /*
  ** Headers of the page
  ** See https://nuxtjs.org/api/configuration-head
  */
  head: {
    title: 'Placeholder Page Title',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'Site Name' },
      { name: 'twitter:card', content: 'summary' },
      { name: 'twitter:creator', content: '@PLACEHOLDER_TWITTER_HANDLE' },
      { name: 'twitter:site', content: 'Site Name' },
    ],
    link: [
      { rel: 'icon', type: 'image/png', href: '/icon.png' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700&family=Roboto+Condensed:wght@300;400;700&family=Source+Serif+Pro:wght@400;700&display=swap' }
    ]
  },
  /*
  ** Customize the progress-bar color
  */
 loading: '~/components/Loading.vue',
  /*
  ** Global CSS
  */
  css: [
    '~/assets/scss/main.scss',
  ],
  styleResources: {
    scss: [
      '~/assets/scss/_variables-overrides.scss',
      '~/node_modules/bootstrap/scss/_functions.scss',
      '~/node_modules/bootstrap/scss/_variables.scss',
      '~/node_modules/bootstrap/scss/_mixins.scss',
      '~/node_modules/bootstrap/scss/_containers.scss',
      '~/node_modules/bootstrap/scss/_grid.scss',
      '~/assets/scss/mixins/_mixins.scss',
      '~/assets/scss/_variables.scss',
    ]
  },
  /*
  ** Plugins to load before mounting the App
  ** https://nuxtjs.org/guide/plugins
  */
  plugins: [
    { src: '~/plugins/click-outside.js' },
    { src: '~/plugins/lightgallery.js',  mode: 'client' },
    { src: '~/plugins/detect-touchscreen-device.js' },
    { src: '~/plugins/truncate-text.js' },
    { src: '~/plugins/howler.js' },
  ],
  /*
  ** Auto import components
  ** See https://nuxtjs.org/api/configuration-components
  */
  components: true,
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
    '@nuxtjs/moment',
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/style-resources',
    ['nuxt-mq', {
        defaultBreakpoint: 'sm',
        breakpoints: {
          sm: 576,
          md: 768,
          lg: 992,
          xl: 1200,
          xxl: Infinity
        }
      }
    ],
  ],
  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  publicRuntimeConfig: {
    baseURL: process.env.BASE_URL || 'http://localhost/graphql',
    publicURL: process.env.PUBLIC_URL || 'http://localhost:3000',
    twitterHandle: process.env.TWITTER_HANDLE || 'PLACEHOLDER_TWITTER_HANDLE'
  },

  privateRuntimeConfig: {
  },
  /*
  ** Build configuration
  ** See https://nuxtjs.org/api/configuration-build/
  */
  build: {
    extractCSS: {
      allChunks: true
    },
    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {

    }
  },
  router: {
    linkActiveClass: 'active'
  },
  serverMiddleware: [
    { path: "/api", handler: "~/api/newsletter.js" },
  ],
  axios: {
    credentials: true
  }
}
