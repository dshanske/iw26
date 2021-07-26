module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    wp_readme_to_markdown: {
      target: {
        files: {
          'readme.md': 'readme.txt'
        }
      },
    },
    copy: {
	    main: {
		    files: [ {expand: true, cwd: 'node_modules/genericons-neue/icon-font/', src: ['**'], dest: 'genericons-neue/'}, ],
		},
    },
    sass: {                              // Task
      dev: {                            // Target
        options: {                       // Target options
          style: 'expanded'
        },
        files: {                         // Dictionary of files
          'css/default.css': 'sass/default.scss'       // 'destination': 'source'
        }
      },
      dist: {                            // Target
        options: {                       // Target options
          style: 'compressed'
        },
        files: {                         // Dictionary of files
          'css/default.min.css': 'sass/default.scss',       // 'destination': 'source'

        }
      }
    },
    stylelint: {
	    	"extends": "@wordpress/stylelint-config",
	        all: ['sass/**/*.scss']
    },
    makepot: {
      target: {
        options: {
          mainFile: 'iw26.php', // Main project file.
          domainPath: '/languages',                   // Where to save the POT file.
          potFilename: 'iw26.pot',
          type: 'wp-theme',                // Type of project (wp-plugin or wp-theme).
          exclude: [
            'vendor/.*'
          ],
          updateTimestamp: false             // Whether the POT-Creation-Date should be updated without other changes.
            	}
      }
    }
  });

  grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-stylelint' );

  // Default task(s).
  grunt.registerTask('default', ['wp_readme_to_markdown', 'makepot', 'sass', 'copy']);

};
