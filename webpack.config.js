const path                    = require( 'path' );
const defaultConfig           = require( "@wordpress/scripts/config/webpack.config" );
const MiniCssExtractPlugin    = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin      = require( "css-minimizer-webpack-plugin" );
const { hasBabelConfig }      = require( '@wordpress/scripts/utils' );
const TerserPlugin            = require( 'terser-webpack-plugin' );
const UnminifiedWebpackPlugin = require( 'unminified-webpack-plugin' );
const RemoveEmptyScriptsPlugin  = require( 'webpack-remove-empty-scripts' )

const isProduction = process.env.NODE_ENV === 'production';
const mode         = isProduction ? 'production' : 'development';

const BUILD_DIR = path.resolve( __dirname, 'assets' );

const filename = ext => isProduction ? ext + '/[name].min.' + ext : ext + '/[name].min.' + ext;

module.exports = {
	...defaultConfig,
	mode,
	//devtool:      ! isProduction ? 'source-map' : false,
	devtool:      ! isProduction ? false : false,
	entry:        {
		"afb-scripts": path.resolve( process.cwd(), 'src/js', 'afb-scripts.js' ),
		//"jquery.mask":  path.resolve( process.cwd(), 'node_modules/jquery-mask-plugin/dist', 'jquery.mask.js' ),
		//"micromodal":  path.resolve( process.cwd(), 'node_modules/micromodal/dist', 'micromodal.js' ),
		"afb-styles":  path.resolve( process.cwd(), 'src/scss', 'afb-styles.scss' ),
	},
	output:       {
		filename: filename( 'js' ),
		path:     BUILD_DIR,
		clean:    true
	},
	optimization: {
		minimize:  true,
		minimizer: [
			new CssMinimizerPlugin( {
				minimizerOptions: {
					preset: [
						"default",
						{ "discardComments": { "removeAll": true } }
					]
				},
			} ),
			new TerserPlugin( {
				extractComments: false,
			} ),
		]
	},
	module:       {
		rules: [
			{
				test:    /\.js$/,
				exclude: /node_modules/,
				use:     [
					require.resolve( 'thread-loader' ),
					{
						loader:  require.resolve( 'babel-loader' ),
						options: {
							// Babel uses a directory within local node_modules
							// by default. Use the environment variable option
							// to enable more persistent caching.
							cacheDirectory: process.env.BABEL_CACHE_DIRECTORY || true,

							// Provide a fallback configuration if there's not
							// one explicitly available in the project.
							...(
								! hasBabelConfig() && {
									babelrc:    false,
									configFile: false,
									presets:    [ require.resolve( '@wordpress/babel-preset-default' ) ],
								}
							),
						},
					},
				],
			},
			{
				test: /\.css$/i,
				use:  [ "style-loader", "css-loader" ],
			},
			{
				test:    /\.s[ac]ss$/i,
				exclude: /node_modules/,
				use:     [
					{
						loader: MiniCssExtractPlugin.loader,
					},
					{
						loader:  'css-loader',
						options: {
							sourceMap: ! isProduction,
						},
					},
					{
						loader:  'postcss-loader',
						options: {
							sourceMap: ! isProduction,
						},
					},
					{
						loader:  'sass-loader',
						options: {
							sourceMap: ! isProduction,
						},
					},
				],
			},
		],
	},
	plugins:      [
		new RemoveEmptyScriptsPlugin(),
		new MiniCssExtractPlugin( {
			filename: filename( 'css' ),
		} ),
		new UnminifiedWebpackPlugin( )
	],

	externals: {
		jquery: 'jQuery'
	}
}
