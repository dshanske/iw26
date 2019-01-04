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
    sass: {                              // Task
      dev: {                            // Target
        options: {                       // Target options
          style: 'expanded'
        },
        files: {                         // Dictionary of files
          'style.css': 'sass/style.scss'       // 'destination': 'source'
        }
      },
      dist: {                            // Target
        options: {                       // Target options
          style: 'compressed'
        },
        files: {                         // Dictionary of files
          'style.min.css': 'sass/style.scss',       // 'destination': 'source'

        }
      }
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

  // Default task(s).
  grunt.registerTask('default', ['wp_readme_to_markdown', 'makepot', 'sass']);

};
