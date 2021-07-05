const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js("resources/js/app.js", "public/js")
    .vue({ version: 3, publicPath: process.env.MIX_PUBLIC_PATH })
    .postCss("resources/css/app.css", "public/css", [require("tailwindcss")]);
// .webpackConfig({
//     resolve: {
//         fallback: {
//             http: require.resolve("stream-http"),
//             path: require.resolve("path-browserify"),
//             stream: require.resolve("stream-browserify"),
//             zlib: require.resolve("browserify-zlib"),
//             crypto: require.resolve("crypto-browserify"),
//             // http: false,
//             // path: false,
//             // stream: false,
//             // zlib: false,
//             crypto: false,
//             fs: false,
//         },
//     },
// });
