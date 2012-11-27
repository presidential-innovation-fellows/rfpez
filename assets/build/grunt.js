module.exports = function(grunt) {

  var tasks = 'stylus jader coffee concat cssmin min';

  var path = require('path');
  var exec = require('child_process').exec;

  grunt.loadNpmTasks('grunt-css');
  grunt.loadNpmTasks('grunt-coffee');
  grunt.loadNpmTasks('grunt-contrib-stylus');

  grunt.registerTask('jader', 'Compiles jade templates to PHP.', function() {
    var cb = this.async();

    var child = exec('php ../../artisan jader', function (error, stdout, stderr) {
      console.log(error ? error : "Done");
      cb();
    });
  });


  grunt.initConfig({

    pkg: '<json:package.json>',

    coffee: {
      all: {
        src: ['../coffee/**/*.coffee'],
        dest: '../js',
        options: {bare: true}
      }
    },

    stylus: {
      compile: {
        files: {
          '../css/compiled_styl.css': ['../styl/main.styl']
        }
      }
    },


    concat: {
      css: {
        src: [
          '../css/bootstrap.css',
          '../css/bootstrap-responsive.css',
          '../css/bootstrap-wysihtml5.css',
          '../css/datepicker.css',
          '../css/compiled_styl.css'
        ],
        dest: '../../public/css/all.css'
      },

      js_global: {
        src: [
          '../js/vendor/bootstrap.js',
          '../js/vendor/jquery.validate.js',
          '../js/vendor/jquery.validate_rfpez.js',
          '../js/vendor/jquery.timeago.js',
          '../js/vendor/jquery.placeholder.js',
          '../js/vendor/jquery.form.js',
          '../js/vendor/jquery.pjax.js',
          '../js/flash-button.js',
          '../js/main.js',
          '../js/question-and-answer.js',
          '../js/validation.js',
          '../js/filter-projects.js',
          '../js/notifications.js',
          '../js/dsbs-lookup.js',
          '../js/infinite-vendor-scroll.js',
          '../js/vendor/underscore.js',
          '../js/vendor/backbone.js',
          '../js/vendor-image-preview.js',
        ],
        dest: '../../public/js/global.js'
      },

      js_vendor: {
        src: [
          '../js/new-bid.js',
          '../js/save-bid-draft.js'
        ],
        dest: '../../public/js/vendor.js'
      },

      js_officer: {
        src: [
          '../js/vendor/bootstrap-datepicker.js',
          '../js/vendor/wysihtml5.min.js',
          '../js/vendor/bootstrap-wysihtml5.js',
          '../js/vendor/jquery.sortable.js',
          '../js/vendor/autogrow-input.js',
          '../js/vendor/jquery.hotkeys.js',
          '../js/collaborators.js',
          '../js/sow-composer.js',
          '../js/comments-backbone.js',
          '../js/collaborators-backbone.js',
          '../js/sow-deliverables-backbone.js',
          '../js/bid-review.js'
        ],
        dest: '../../public/js/officer.js'
      },

      js_admin: {
        src: [
          '../js/admin-officers-backbone.js',
          '../js/admin-projects-backbone.js'
        ],
        dest: '../../public/js/admin.js'
      }

    },

    cssmin: {
      all: {
        src: ['../../public/css/all.css'],
        dest: '../../public/css/all.min.css'
      }
    },

    min: {
      js_global: {
        src: ['../../public/js/global.js'],
        dest: '../../public/js/global.min.js'
      },
      js_vendor: {
        src: ['../../public/js/vendor.js'],
        dest: '../../public/js/vendor.min.js'
      },
      js_officer: {
        src: ['../../public/js/officer.js'],
        dest: '../../public/js/officer.min.js'
      },
      js_admin: {
        src: ['../../public/js/admin.js'],
        dest: '../../public/js/admin.min.js'
      }
    },


    watch: {
      app: {
        files: ['../coffee/**/*.coffee', '../styl/**/*.styl', '../css/**/*.css', '../js/**/*.js', '../../application/views/**/**/*.jade'],
        tasks: tasks
      }
    }
  });

  grunt.registerTask('default', tasks);

};
