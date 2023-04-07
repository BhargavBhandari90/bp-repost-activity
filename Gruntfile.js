module.exports = function (grunt) {
	"use strict";

	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),

		addtextdomain: {
			options: {
				textdomain: "bp-repost-activity",
			},
			update_all_domains: {
				options: {
					updateDomains: true,
				},
				src: [
					"*.php",
					"**/*.php",
					"!.git/**/*",
					"!bin/**/*",
					"!node_modules/**/*",
					"!tests/**/*",
				],
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: "/languages",
					exclude: [".git/*", "bin/*", "node_modules/*", "tests/*"],
					mainFile: "bp-repost-activity.php",
					potFilename: "bp-repost-activity.pot",
					potHeaders: {
						poedit: true,
						"x-poedit-keywordslist": true,
					},
					type: "wp-plugin",
					updateTimestamp: true,
				},
			},
		},

		cssmin: {
			target: {
				files: [
					{
						expand: true,
						cwd: "assets/css",
						src: ["*.css", "!*.min.css"],
						dest: "assets/css",
						ext: ".min.css",
					},
				],
			},
		},

		uglify: {
			dev: {
				files: [
					{
						expand: true,
						src: ["assets/js/*.js", "!assets/js/*.min.js"],
						dest: "assets/js",
						cwd: ".",
						rename: function (dst, src) {
							// To keep the source js files and make new files as `*.min.js`:

							return src.replace(".js", ".min.js");
							// Or to override to src:
							// return src;
						},
					},
				],
			},
		},

		phpcbf: {
			options: {
				bin: "phpcbf",
				standard: "WordPress",
				noPatch: false,
			},
			application: {
				src: ["**/*.php", "!node_modules/**/*", "!tests/**/*"],
			},
		},

		watch: {
			scripts: {
				files: ["**/*.js", "**/*.css", "**/*.php"],
				tasks: ["addtextdomain", "makepot", "cssmin", "uglify", "phpcbf"],
				options: {
					spawn: false,
				},
			},
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
					"readme.md": "readme.txt",
				},
			},
		},
	});

	grunt.loadNpmTasks("grunt-wp-i18n");
	grunt.loadNpmTasks("grunt-wp-readme-to-markdown");
	grunt.loadNpmTasks("grunt-contrib-cssmin");
	grunt.loadNpmTasks("grunt-contrib-uglify");
	grunt.registerTask("default", ["i18n", "readme"]);
	grunt.registerTask("i18n", ["addtextdomain", "makepot"]);
	grunt.registerTask("readme", ["wp_readme_to_markdown"]);
	grunt.registerTask("minify", ["cssmin", "uglify"]);

	grunt.util.linefeed = "\n";
};
