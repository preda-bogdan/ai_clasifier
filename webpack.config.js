const path = require( 'path' )
const webpack = require( 'webpack' )
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' )

const extractSass = new ExtractTextPlugin({
	filename: "css/lala.bundle.css",
	allChunks: true,
});

module.exports = {
	entry: [ './src/js/main.js', './src/scss/main.scss' ],
	output: {
		path: path.resolve( __dirname, 'build' ),
		filename: 'js/lala.bundle.js'
	},
	module: {
		loaders: [
			{
				test: /\.js$/,
				loader: 'babel-loader',
				query: {
					presets: ['es2015']
				}
			},
			{
				enforce: 'pre',
				test: /\.vue$/,
				loader: 'eslint-loader',
				exclude: /node_modules/
			},
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			{
				test: /\.scss$/,
				use: extractSass.extract({
					use: [
						{
							loader: "css-loader"
						},
						{
							loader: "sass-loader"
						}
					],
					fallback: "style-loader"
				})
			}
		]
	},
	plugins: [
		extractSass
	],
	resolve: {
		alias: {
			'vue$': 'vue/dist/vue.esm.js'
		}
	},
	stats: {
		colors: true
	},
	devtool: 'source-map'
}