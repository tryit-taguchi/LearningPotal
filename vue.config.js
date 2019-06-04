module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? '/nlp/' : '',
  pwa: {
      themeColor: '#4DBA87', // The Vue color
      msTileColor: '#000000',
      appleMobileWebAppCapable: 'yes',
      appleMobileWebAppStatusBarStyle: 'black',
  },
}
