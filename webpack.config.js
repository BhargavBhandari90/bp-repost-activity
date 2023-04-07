const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,
		"bp-repost-activity": ["./assets/js/index.js", "./assets/css/style.css"],
	},
};
