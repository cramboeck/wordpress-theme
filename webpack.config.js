/**
 * WordPress Dependencies
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

/**
 * Block entry points
 */
const blockEntries = {
	'hero/index': './blocks/hero/index.js',
	'services/index': './blocks/services/index.js',
	'testimonials/index': './blocks/testimonials/index.js',
	'cta/index': './blocks/cta/index.js',
	'faq/index': './blocks/faq/index.js',
	'pricing/index': './blocks/pricing/index.js',
	'team/index': './blocks/team/index.js',
	'features/index': './blocks/features/index.js',
	'contact/index': './blocks/contact/index.js',
};

module.exports = {
	...defaultConfig,
	entry: blockEntries,
	output: {
		...defaultConfig.output,
		path: path.resolve(process.cwd(), 'build'),
		filename: '[name].js',
	},
};
