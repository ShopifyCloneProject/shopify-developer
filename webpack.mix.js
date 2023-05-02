const mix = require('laravel-mix')
const exec = require('child_process').exec
require('dotenv').config()

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const glob = require('glob')
const path = require('path')

function mixAssetsDir(query, cb) {
  ;(glob.sync('resources/' + query) || []).forEach(f => {
    f = f.replace(/[\\\/]+/g, '/')
    cb(f, f.replace('resources', 'public'))
  })
}

const sassOptions = {
  precision: 5,
  includePaths: ['node_modules', 'resources/assets/']
}

// plugins Core stylesheets
mixAssetsDir('sass/base/plugins/**/!(_)*.scss', (src, dest) =>
  mix.sass(src, dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), {sassOptions})
)

// pages Core stylesheets
mixAssetsDir('sass/base/pages/**/!(_)*.scss', (src, dest) =>
  mix.sass(src, dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), {sassOptions})
)

// Core stylesheets
mixAssetsDir('sass/base/core/**/!(_)*.scss', (src, dest) =>
  mix.sass(src, dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), {sassOptions})
)

// script js
mixAssetsDir('js/scripts/**/*.js', (src, dest) => mix.scripts(src, dest))

/*
 |--------------------------------------------------------------------------
 | Application assets
 |--------------------------------------------------------------------------
 */

mixAssetsDir('vendors/js/**/*.js', (src, dest) => mix.scripts(src, dest))
mixAssetsDir('vendors/css/**/*.css', (src, dest) => mix.copy(src, dest))
mixAssetsDir('vendors/**/**/images', (src, dest) => mix.copy(src, dest))
mixAssetsDir('vendors/css/editors/quill/fonts/', (src, dest) => mix.copy(src, dest))
mixAssetsDir('fonts', (src, dest) => mix.copy(src, dest))
mixAssetsDir('fonts/**/**/*.css', (src, dest) => mix.copy(src, dest))
mix.copyDirectory('resources/images', 'public/images')
mix.copyDirectory('resources/data', 'public/data')
mix.copyDirectory('resources/assets', 'public/assets')
mixAssetsDir('adminassets/js/**/*.js', (src, dest) => mix.scripts(src, dest))
mixAssetsDir('adminassets/css/**/*.css', (src, dest) => mix.copy(src, dest))
mix.copyDirectory('resources/adminassets/images', 'public/adminassets/images')

mix
  .js('resources/js/core/app-menu.js', 'public/js/core')
  .js('resources/js/core/app.js', 'public/js/core')
  .sass('resources/sass/core.scss', 'public/css', {sassOptions})
  .sass('resources/sass/overrides.scss', 'public/css', {sassOptions})
  .sass('resources/sass/common.scss', 'public/css', {sassOptions})
  .sass('resources/sass/base/custom-rtl.scss', 'public/css', {sassOptions})
  .sass('resources/assets/scss/style-rtl.scss', 'public/css', {sassOptions})
  .sass('resources/assets/scss/style.scss', 'public/css', {sassOptions})
  .sass('resources/assets/scss/common.scss', 'public/css', {sassOptions})

mix.then(() => {
  if (process.env.MIX_CONTENT_DIRECTION === 'rtl') {
    let command = `node ${path.resolve('node_modules/rtlcss/bin/rtlcss.js')} -d -e ".css" ./public/css/ ./public/css/`
    exec(command, function(err, stdout, stderr) {
      if (err !== null) {
        console.log(err)
      }
    })
  }
})

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css');

let jspath = "resources/js/";

mix.js(jspath + 'admin/product/app.js', 'public/js/admin/product/app.js').vue();
mix.js(jspath + 'admin/collection/app.js', 'public/js/admin/collection/app.js').vue();
mix.js(jspath + 'admin/customer/app.js', 'public/js/admin/customer/app.js').vue();
mix.js(jspath + 'admin/location/app.js', 'public/js/admin/location/app.js').vue();
mix.js(jspath + 'admin/generalsettings/app.js', 'public/js/admin/generalsettings/app.js').vue();
mix.js(jspath + 'admin/giftcardsettings/app.js', 'public/js/admin/giftcardsettings/app.js').vue();
mix.js(jspath + 'admin/plansettings/app.js', 'public/js/admin/plansettings/app.js').vue();
mix.js(jspath + 'admin/paymentsettings/app.js', 'public/js/admin/paymentsettings/app.js').vue();
mix.js(jspath + 'admin/notificationsettings/app.js', 'public/js/admin/notificationsettings/app.js').vue();
mix.js(jspath + 'admin/accountsettings/app.js', 'public/js/admin/accountsettings/app.js').vue();
mix.js(jspath + 'admin/checkoutsettings/app.js', 'public/js/admin/checkoutsettings/app.js').vue();
mix.js(jspath + 'admin/languagesettings/app.js', 'public/js/admin/languagesettings/app.js').vue();
mix.js(jspath + 'admin/shippingsettings/app.js', 'public/js/admin/shippingsettings/app.js').vue();
mix.js(jspath + 'admin/filessettings/app.js', 'public/js/admin/filessettings/app.js').vue();
mix.js(jspath + 'admin/billingsettings/app.js', 'public/js/admin/billingsettings/app.js').vue();
mix.js(jspath + 'admin/taxessettings/app.js', 'public/js/admin/taxessettings/app.js').vue();
mix.js(jspath + 'admin/channelsettings/app.js', 'public/js/admin/channelsettings/app.js').vue();
mix.js(jspath + 'admin/legalsettings/app.js', 'public/js/admin/legalsettings/app.js').vue();
mix.js(jspath + 'admin/dashboard/app.js', 'public/js/admin/dashboard/app.js').vue();
mix.js(jspath + 'admin/order/app.js', 'public/js/admin/order/app.js').vue();
mix.js(jspath + 'admin/pagessettings/app.js', 'public/js/admin/pagessettings/app.js').vue();
mix.js(jspath + 'admin/xmlfeed/app.js', 'public/js/admin/xmlfeed/app.js').vue();
mix.js(jspath + 'admin/customsettings/app.js', 'public/js/admin/customsettings/app.js').vue();
mix.js(jspath + 'admin/themes/app.js', 'public/js/admin/themes/app.js').vue();
mix.js(jspath + 'admin/cart/app.js', 'public/js/admin/cart/app.js').vue();
mix.js(jspath + 'admin/abandonecheckout/app.js', 'public/js/admin/abandonecheckout/app.js').vue();
mix.js(jspath + 'admin/themesettings/app.js', 'public/js/admin/themesettings/app.js').vue();
mix.js(jspath + 'admin/shippingproducts/app.js', 'public/js/admin/shippingproducts/app.js').vue();
mix.js(jspath + 'admin/shippingdetails/app.js', 'public/js/admin/shippingdetails/app.js').vue();
mix.js(jspath + 'admin/returnorders/app.js', 'public/js/admin/returnorders/app.js').vue();
mix.js(jspath + 'admin/returnshippingproducts/app.js', 'public/js/admin/returnshippingproducts/app.js').vue();
mix.js(jspath + 'admin/exchangeorders/app.js', 'public/js/admin/exchangeorders/app.js').vue();
mix.js(jspath + 'admin/discounts/app.js', 'public/js/admin/discounts/app.js').vue();
