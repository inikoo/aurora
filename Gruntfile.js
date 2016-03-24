module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        clean: {
            build: ["build/*", "!build/keyring/**", "!build/server_files/**"],
        },

        concat: {
            js_libs: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/jquery-ui.js', 'js/libs/moment-with-locales.js', 'js/libs/chrono.js', 'js/libs/sha256.js', 'js/libs/underscore.js', 'js/libs/backbone.js', 'js/libs/backbone.paginator.js', 'js/libs/backgrid.js', 'js/libs/backgrid-filter.js', 'js/libs/intlTelInput.js', 'js/libs/d3.js', 'js/libs/d3fc.layout.js', 'js/libs/d3fc.js'],
                dest: 'build/js/libs.js',
            },
            js_aurora: {
                src: ['js/app.js', 'js/keyboard_shorcuts.js', 'js/search.js', 'js/table.js', 'js/validation.js', 'js/edit.js', 'js/new.js', 'js/help.js'],
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
                   // style: 'compressed'
                },
                files: {
                    'css/app.css': 'sass/app.scss'
                }
            },
            login: {
                options: {
                   // style: 'compressed'
                },
                files: {
                    'css/login.css': 'sass/login.scss'
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1,
            },
            libs: {
                files: {
                    'build/css/libs.min.css':
                     ['css/jquery-ui.css', 'css/font-awesome.css', 'css/intlTelInput.css', 'css/d3fc.css', 'css/backgrid.css', 'css/backgrid-filter.css']

                }
            },
            aurora: {
                files: {
                    'build/css/app.min.css':
                     ['css/app.css']

                }
            },
              login: {
                files: {
                    'build/css/login.min.css':
                     ['css/login.css']

                }
            }
        },
        copy: {
            css_aux_files: {
                files: [

                {
                    expand: true,
                    src: ['fonts/*'],
                    dest: 'build/'
                }, {
                    expand: true,
                    src: ['css/images/*'],
                    dest: 'build/'
                }],
            },
            php: {
                files: [



                {
                    expand: true,
                    src: ['*.php'],
                    dest: 'build/'
                }],
            },
            php_libs: {
                files: [


                {
                    expand: true,
                    src: ['external_libs/**'],
                    dest: 'build/'
                },

                ],
            },
            locale: {
                files: [


                {
                    expand: true,
                    src: ['locale/**'],
                    dest: 'build/'
                },

                ],
            },
            conf: {
                files: [{
                    expand: true,
                    src: ['conf/*.php'],
                    dest: 'build/'
                },

                ],
            },
            cron: {
                files: [{
                    expand: true,
                    src: ['cron/*.php'],
                    dest: 'build/'
                },

                ],
            },
            navigation: {
                files: [{
                    expand: true,
                    src: ['navigation/*.php'],
                    dest: 'build/'
                },

                ],
            },
            pdf: {
                files: [{
                    expand: true,
                    src: ['pdf/*.php'],
                    dest: 'build/'
                },

                ],
            },
            prepare_table: {
                files: [{
                    expand: true,
                    src: ['prepare_table/*.php'],
                    dest: 'build/'
                },

                ],
            },

        },

        imagemin: {
            aurora: {
                options: {
                    optimizationLevel: 3,

                },
                files: [{
                    expand: true,
                    cwd: 'art/',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'build/art/'
                }]
            }
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
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-imagemin');

    grunt.registerTask('default', ['clean', 'imagemin', 'concat', 'uglify', 'sass', 'cssmin', 'copy']);

};
