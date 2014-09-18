module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		
		notify: {
			notify_hooks: {
	   			options: {
	      			enabled: true,
	      			max_jshint_notifications: 5, // maximum number of notifications from jshint output
	      			title: "City Happs" // defaults to the name in package.json, or will use project directory's name
	    		}
	  		}
	  	},
		sass: {
			dist: {
				files: {
					'public/css/style.css' : 'public/css/style.scss'
				}
			}
		},
		watch: {
			css: {
				files: 'public/css/style.scss',
				tasks: ['sass'], 
			}, 
			options: {
				title: 'All Done',
				message: 'Nice Work'
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.registerTask('default',['watch']);
}