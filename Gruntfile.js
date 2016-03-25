module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        clean: {
            app: ["build/app/*", "!build/app/keyring/**", "!build/app/server_files/**"],
            fork: ["build/fork/*", "!build/fork/keyring/**", "!build/fork/server_files/**"],

        },

        concat: {
            js_libs: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/jquery-ui.js', 'js/libs/moment-with-locales.js', 'js/libs/chrono.js', 'js/libs/sha256.js', 'js/libs/underscore.js', 'js/libs/backbone.js', 'js/libs/backbone.paginator.js', 'js/libs/backgrid.js', 'js/libs/backgrid-filter.js', 'js/libs/intlTelInput.js', 'js/libs/d3.js', 'js/libs/d3fc.layout.js', 'js/libs/d3fc.js'],
                dest: 'build/app/js/libs.js',
            },
            js_aurora: {
                src: ['js/app.js', 'js/keyboard_shorcuts.js', 'js/search.js', 'js/table.js', 'js/validation.js', 'js/edit.js', 'js/new.js', 'js/help.js'],
                dest: 'build/app/js/app.js',
            }




        },
        uglify: {
            libs: {
                src: 'build/app/js/libs.js',
                dest: 'build/app/js/libs.min.js',
            },
            aurora: {
                src: 'build/app/js/app.js',
                dest: 'build/app/js/app.min.js',
            },
            injections: {
                files: [{
                    expand: true,
                    src: 'js/injections/*.js',
                    dest: 'build/app/',
                    ext: '.min.js'
                }]
            },



            login: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/sha256.js', 'js/libs/aes.js', 'js/login/login.js'],
                dest: 'build/app/js/login.min.js'
            },
            login_setup: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/sha256.js', 'js/libs/aes.js', 'js/setup/login.setup.js'],
                dest: 'build/app/js/login.setup.min.js'
            },
            setup: {
                src: ['js/setup/setup.js'],
                dest: 'build/app/js/setup.min.js'
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
                    'build/app/css/libs.min.css': ['css/jquery-ui.css', 'css/font-awesome.css', 'css/intlTelInput.css', 'css/d3fc.css', 'css/backgrid.css', 'css/backgrid-filter.css']

                }
            },
            aurora: {
                files: {
                    'build/app/css/app.min.css': ['css/app.css']

                }
            },
            login: {
                files: {
                    'build/app/css/login.min.css': ['css/login.css']

                }
            }
        },
        copy: {

            app: {
                files: [{
                    expand: true,
                    src: ['fonts/*'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['css/images/*'],
                    dest: 'build/app/'
                },

                {
                    expand: true,
                    src: ['*.php'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['external_libs/**'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['locale/**'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['conf/*.php'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['utils/*.php'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['cron/*.php'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['navigation/*.php'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['pdf/*.php'],
                    dest: 'build/app/'
                }, {
                    expand: true,
                    src: ['prepare_table/*.php'],
                    dest: 'build/app/'
                },

                ],
            },

            fork_stones:{
             files: [ {
                    expand: true,
                    src: ['external_libs/**'],
                    dest: 'build/fork/'
                }, {
                    expand: true,
                    src: ['locale/**'],
                    dest: 'build/fork/'
                }, {
                    expand: true,
                    cwd: 'fork/',
                    src: ['*.php'],
                    dest: 'build/fork/'
                }

                ]
            },

            fork: {
                files: [{
                    expand: true,
                    src: ['class.*.php'],
                    dest: 'build/fork/'
                },{
                    expand: true,
                    src: ['conf/*.php'],
                    dest: 'build/fork/'
                }, {
                    expand: true,
                    src: ['utils/*.php'],
                    dest: 'build/fork/'
                }, {
                    expand: true,
                    cwd: 'fork/',
                    src: ['*.php'],
                    dest: 'build/fork/'
                }

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
                    dest: 'build/app/art/'
                }]
            }
        },

        watch: {

            sass: {
                files: ['sass/*.scss'],
                tasks: ['sass:aurora'],
                options: {
                    spawn: false,
                },
            },
             fork: {
                files: ['fork/*.php'],
                tasks: ['copy:fork'],
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

    grunt.registerTask('default', ['sass']);

    grunt.registerTask('app', ['clean:app', 'imagemin', 'sass', 'concat', 'uglify', 'cssmin', 'copy:app']);
    grunt.registerTask('fork', ['clean:fork', 'copy:fork_stones', 'copy:fork']);
    grunt.registerTask('qfork', [ 'copy:fork']);

};
