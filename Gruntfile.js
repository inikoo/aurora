module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),



        concat: {
            js_libs: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/jquery-ui.js', 'js/libs/moment-with-locales.js', 'js/libs/chrono.js', 'js/libs/sha256.js', 'js/libs/underscore.js', 'js/libs/backbone.js', 'js/libs/backbone.paginator.js', 'js/libs/backgrid.js', 'js/libs/backgrid-filter.js', 'js/libs/intlTelInput.js', 'js/libs/d3.js', 'js/libs/d3fc.layout.js', 'js/libs/d3fc.js'],
                dest: 'build/js/libs.js',
            },
            js_aurora: {
                src: ['js/app.js', 'js/keyboard_shorcuts.js', 'js/search.js', 'js/table.js', 'js/validation.js', 'js/edit.js', 'js/new.js'],
                dest: 'build/js/aurora.js',
            }




        },
        uglify: {
            libs: {
                src: 'build/js/libs.js',
                dest: 'build/js/libs.min.js',
            },
            aurora: {
                src: 'build/js/aurora.js',
                dest: 'build/js/aurora.min.js',
            },
            injections: {
                files: [{
                    expand: true,
                    src: 'js/injections/*.js',
                    dest: 'build/',
                    ext: '.min.js'
                }]
            },



            login: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/sha256.js', 'js/libs/aes.js', 'js/login/login.js'],
                dest: 'build/js/login.min.js'
            },
            login_setup: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/sha256.js', 'js/libs/aes.js', 'js/setup/login.setup.js'],
                dest: 'build/js/login.setup.min.js'
            },
            setup: {
                src: ['js/setup/setup.js'],
                dest: 'build/js/setup.min.js'
            }


        },
        sass: {
            aurora: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'build/css/app.min.css': 'sass/app.scss'
                }
            },
            login: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'build/css/login.min.css': 'sass/login.scss'
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1,
            },
            target: {
                files: {
                    'build/css/libs.min.css': ['css/jquery-ui.css', 'css/font-awesome.css', 'css/intlTelInput.css', 'css/d3fc.css', 'css/backgrid.css', 'css/backgrid-filter.css']

                }
            }
        },
        copy: {
            main: {
                files: [

                {
                    expand: true,
                    src: ['fonts/*'],
                    dest: 'build/'
                }, {
                    expand: true,
                    src: ['css/images/*'],
                    dest: 'build/'
                },


                ],
            },
        },
        watch: {
            scripts: {
                files: ['js/*.js'],
                tasks: ['concat:js_aurora', 'uglify:aurora'],
                options: {
                    spawn: false,
                },
            },
            injections: {
                files: ['js/injections/*.js'],
                tasks: ['uglify:injections'],
                options: {
                    spawn: false,
                },
            },
            sass: {
                files: ['sass/*.scss'],
                tasks: ['sass:aurora'],
                options: {
                    spawn: false,
                },
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['concat', 'uglify', 'sass', 'cssmin', 'copy']);

};
