module.exports = function(grunt) {
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		makepot: {
			target: {
				options: {
					domainPath: '/languages/',
					mainFile: 'vamtam-sermons.php',
					potFilename: 'vamtam-sermons.pot',
					type: 'wp-plugin',
				}
			}
		},
	});

	require('matchdep').filterDev('grunt-*').forEach( grunt.loadNpmTasks );
};