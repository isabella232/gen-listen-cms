module.exports = function(grunt) {

// load all grunt tasks
require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),

    /* ==========================================================================
       Set project object
       ========================================================================== */

    project: {
        assets: 'assets',
        css:    'css',
        js:     'js'
    },

    /* ==========================================================================
       SASS
       ========================================================================== */

    sass: {
        dev: {
            options: {
                style: 'compressed',
                compass: true,
                sourcemap: 'none'
            },
            files: [{
                expand: true,
                cwd: '<%= project.assets %>/scss/',
                src: ['style.scss'],
                dest: '<%= project.css %>',
                ext: '.css'
            }]
        }
    },

    /* ==========================================================================
       Concatenate JS
       ========================================================================== */

    concat: {
        js : {
            src : [
                '<%= project.assets %>/js/*'
            ],
            dest : '<%= project.js %>/main.js'
        }
    },

    /* ==========================================================================
       Minify JS
       ========================================================================== */

    uglify : {
        js: {
            files: {
                '<%= project.js %>/main.js' : [ '<%= project.js %>/main.js' ]
            }
        }
    },

    /* ==========================================================================
       Watch
       ========================================================================== */

    watch: {
        sass: {
            files: '<%= project.assets %>/scss/{,*/}*.{scss,sass}',
            tasks: ['sass:dev']
        },
        concat: {
            files: '<%= project.assets %>/js/*',
            tasks: ['concat:js']
        },
        uglify: {
            files: '<%= project.assets %>/js/*',
            tasks: ['uglify:js']
        }
    }

});

/* ==========================================================================
   Default task Run `grunt` on the command line
   ========================================================================== */

grunt.registerTask('default',['sass:dev','watch']);

};