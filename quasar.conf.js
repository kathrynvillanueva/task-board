// Configuration for your app
const
  path = require('path'),
  CopyWebpackPlugin = require('copy-webpack-plugin')

module.exports = function (ctx) {
  return {
    // app plugins (/src/plugins)
    plugins: [
      'i18n',
      'axios',
      'lodash',
      'common',
      'element',
      'app'
    ],
    css: [
      'app.styl'
    ],
    extras: [
      'roboto-font',
      'material-icons', // optional, you are not bound to it
      // 'ionicons',
      // 'mdi',
      'fontawesome'
    ],
    supportIE: true,
    build: {
      distDir: 'public',
      scopeHoisting: true,
      vueRouterMode: 'history',
      // vueCompiler: true,
      // gzip: true,
      // analyze: true,
      // extractCSS: false,
      extendWebpack (cfg) {
        cfg.module.rules.push({
          enforce: 'pre',
          test: /\.vue$/, include: /src/, loader: 'vue-loader', options: { loaders: { js: 'awesome-typescript-loader?silent=true' } },
          loader: 'eslint-loader',
          exclude: /node_modules/
        })
        if (ctx.prod) {
          cfg.plugins.push(new CopyWebpackPlugin([{
            from: path.resolve(__dirname, 'public_files'),
          to: '',
          }]))
        }
      },
      env: {
        appUrl: JSON.stringify('http://task-board-quasar.test')
        // appUrl: JSON.stringify('http://localhost:8081')
      },
    },
    devServer: {
      // https: true,
      port: 8081,
      open: false // opens browser window automatically
    },
    // framework: 'all' --- includes everything; for dev only!
    framework: {
      cssAddon: true,
      iconSet: 'fontawesome',
      components: [
        'QLayout',
        'QLayoutHeader',
        'QLayoutDrawer',
        'QPageContainer',
        'QPage',
        'QToolbar',
        'QToolbarTitle',
        'QBtn',
        'QIcon',
        'QList',
        'QListHeader',
        'QItem',
        'QItemMain',
        'QItemSide',
        'QItemTile',
        'QCard',
        'QCardTitle',
        'QCardMain',
        'QCardMedia',
        'QCardSeparator',
        'QCardActions',
        'QPageSticky',
        'QSpinner',
        'QSpinnerRings',
        'QSpinnerDots',
        'QSpinnerOval',
        'QSpinnerGears',
        'QInput',
        'QField',
        'QTable',
        'QSelect',
        'QSearch',
        'QModal',
        "QModalLayout",
        'QTh',
        'QTr',
        'QTd',
        'QTableColumns',
        'QPopupEdit',
        'QPopover',
        'QCheckbox',
        'QDatetime',
        'QDatetimePicker',
        'QUploader',
        'QBtnDropdown',
        'QCollapsible',
        'QChip',
        'QColorPicker',
        'QColor',
        'QScrollArea',
        'QTooltip',
        'QPagination',
        'QRadio',
        'QInnerLoading',
        'QSlideTransition',
        'QDialog',
        'QTabs',
        'QTab',
        'QTabPane',
        'QCarousel',
        'QCarouselSlide',
        'QCarouselControl',
        'QAutocomplete',
        'QVideo',
        'QSlider',
        'QEditor',
        'QKnob',
        'QOptionGroup',
        'QAjaxBar',
        'QChipsInput',
        'QProgress'
      ],
      directives: [
        'Ripple',
        'CloseOverlay',
        'BackToTop'
      ],
      // Quasar plugins
      plugins: [
        'Notify',
        'Dialog',
        'Loading'
      ],
      // i18n: 'de' // Quasar language
    },
    // animations: 'all',
    animations: [
      'slideInLeft',
      'slideOutLeft',
      'slideInUp',
      'slideInDown',
      'slideOutUp',
      'slideOutDown',
      'slideInRight',
      'slideOutRight',
      'bounceInLeft',
      'bounceOutRight'
    ],
    ssr: {
      pwa: false
    },
    pwa: {
      // workboxPluginMode: 'InjectManifest',
      // workboxOptions: {},
      manifest: {
        // name: 'Quasar App',
        // short_name: 'Quasar-PWA',
        // description: 'Best PWA App in town!',
        display: 'standalone',
        orientation: 'portrait',
        background_color: '#ffffff',
        theme_color: '#027be3',
        icons: [
          {
            'src': 'statics/icons/icon-128x128.png',
            'sizes': '128x128',
            'type': 'image/png'
          },
          {
            'src': 'statics/icons/icon-192x192.png',
            'sizes': '192x192',
            'type': 'image/png'
          },
          {
            'src': 'statics/icons/icon-256x256.png',
            'sizes': '256x256',
            'type': 'image/png'
          },
          {
            'src': 'statics/icons/icon-384x384.png',
            'sizes': '384x384',
            'type': 'image/png'
          },
          {
            'src': 'statics/icons/icon-512x512.png',
            'sizes': '512x512',
            'type': 'image/png'
          }
        ]
      }
    },
    cordova: {
      // id: 'org.cordova.quasar.app'
    },
    electron: {
      // bundler: 'builder', // or 'packager'
      extendWebpack (cfg) {
        // do something with Electron process Webpack cfg
      },
      packager: {
        // https://github.com/electron-userland/electron-packager/blob/master/docs/api.md#options

        // OS X / Mac App Store
        // appBundleId: '',
        // appCategoryType: '',
        // osxSign: '',
        // protocol: 'myapp://path',

        // Window only
        // win32metadata: { ... }
      },
      builder: {
        // https://www.electron.build/configuration/configuration

        // appId: 'quasar-app'
      }
    }
  }
}
