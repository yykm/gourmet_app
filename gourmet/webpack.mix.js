const mix = require('laravel-mix');

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

// Hot reloadingを有効にする (npm run watchで実行してください)
mix
// .browserSync({
//   files: [
//       "resources/views/**/*.blade.php",
//       "public/**/*.*"     // 公開フォルダを指定しないとリロードが効きません。注意
//   ],
//   proxy: {
//       target: "ip-10-0-1-10.ap-northeast-1.compute.internal",
//   }
// })
  .js('resources/js/app.js', 'public/js')
  .version();
//  .sass('resources/sass/app.scss', 'public/css');
