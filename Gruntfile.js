module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'), secret: grunt.file.readJSON('deployment.secret.json'),

        clean: {
            app: ["build/app/*", "!build/app/keyring/**", "!build/app/server_files/**"],
            fork: ["build/fork/*", "!build/fork/keyring/**", "!build/fork/server_files/**"],
            websocket: ["build/websocket/*"],
        }, concat: {
            js_libs: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/jquery-ui.js', 'js/libs/moment-with-locales.js', 'js/libs/chrono.js', 'js/libs/sha256.js', 'js/libs/underscore.js', 'js/libs/backbone.js', 'js/libs/backbone.paginator.js', 'js/libs/backgrid.js', 'js/libs/backgrid-filter.js', 'js/libs/intlTelInput.js', 'js/libs/d3.js', 'js/libs/d3fc.layout.js', 'js/libs/d3fc.js'],
                dest: 'build/app/js/libs.js',
            }, js_aurora: {
                src: ['js/app.js', 'js/keyboard_shorcuts.js', 'js/search.js', 'js/table.js', 'js/validation.js', 'js/edit.js', 'js/new.js', 'js/order.js', 'js/supplier.order.js', 'js/supplier.delivery.js', 'js/help.js'],
                dest: 'build/app/js/app.js',
            }


        },
        uglify: {
            libs: {
                src: 'build/app/js/libs.js', dest: 'build/app/js/libs.min.js',
            }, aurora: {
                src: 'build/app/js/app.js', dest: 'build/app/js/app.min.js',
            }, injections: {
                files: [{
                    expand: true, src: 'js/injections/*.js', dest: 'build/app/', ext: '.min.js'
                }]
            },


            login: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/sha256.js', 'js/libs/aes.js', 'js/login/login.js'], dest: 'build/app/js/login.min.js'
            }, login_setup: {
                src: ['js/libs/jquery-2.2.1.js', 'js/libs/sha256.js', 'js/libs/aes.js', 'js/setup/login.setup.js'], dest: 'build/app/js/login.setup.min.js'
            }, setup: {
                src: ['js/setup/setup.js'], dest: 'build/app/js/setup.min.js'
            }, pweb_desktop: {
                sourceMap: true,

                src: [
                    'EcomB2B/theme_1/local/jquery.js',


                    'EcomB2B/js/jquery.hoverIntent.js',
                    'EcomB2B/js/menu.js',
                    'EcomB2B/js/search.js',


                ], dest: 'EcomB2B/js/desktop.min.js'
            }, pweb_desktop_logged_in: {
                options: {

                    sourceMap: true,
                },

                src: [

                    'EcomB2B/js/aurora.logged_in.js',
                    'EcomB2B/js/ordering.js',


                ], dest: 'EcomB2B/js/desktop.logged_in.min.js',
            }, pweb_desktop_image_gallery: {
                src: [

                    'EcomB2B/js/photoswipe.js',
                    'EcomB2B/js/photoswipe-ui-default.js',


                ], dest: 'EcomB2B/js/image_gallery.min.js',
            }, pweb_desktop_forms: {
                sourceMap: true,
                src: [
                    'EcomB2B/theme_1/local/jquery-ui.js',
                    'EcomB2B/theme_1/sky_forms/js/jquery.form.min.js',
                    'EcomB2B/theme_1/sky_forms/js/jquery.validate.min.js',
                    'EcomB2B/theme_1/sky_forms/js/additional-methods.min.js',
                    'EcomB2B/js/sweetalert.min.js',
                    'EcomB2B/js/sha256.js',
                    'EcomB2B/js/aurora_forms.js',
                ], dest: 'EcomB2B/js/desktop.forms.min.js'
            }, pweb_desktop_basket: {
                src: [
                    'EcomB2B/js/basket.js',
                ], dest: 'EcomB2B/js/desktop.basket.min.js'
            }, pweb_desktop_checkout: {
                src: [
                    'EcomB2B/js/braintree.js',
                    'EcomB2B/js/checkout.js',
                ], dest: 'EcomB2B/js/desktop.checkout.min.js'
            }, pweb_mobile: {
                src: [
                    'EcomB2B/theme_1/local/jquery.js',
                    'EcomB2B/theme_1/mobile/plugins.js',
                    'EcomB2B/theme_1/mobile/custom.js',
                    'EcomB2B/js/search.js',
                ], dest: 'EcomB2B/js/mobile.min.js',

            }, pweb_mobile_logged_in: {
                options: {

                    sourceMap: true,
                },
                src: [
                    'EcomB2B/js/aurora.logged_in.mobile.js',
                    'EcomB2B/js/ordering.touch.js'

                ], dest: 'EcomB2B/js/mobile.logged_in.min.js',

            }, pweb_mobile_forms: {
                src: [
                    'EcomB2B/theme_1/local/jquery-ui.js',
                    'EcomB2B/theme_1/sky_forms/js/jquery.form.min.js',
                    'EcomB2B/theme_1/sky_forms/js/jquery.validate.min.js',
                    'EcomB2B/theme_1/sky_forms/js/additional-methods.min.js',
                    'EcomB2B/js/sweetalert.min.js',
                    'EcomB2B/js/sha256.js',
                    'EcomB2B/js/aurora_forms.js',
                ], dest: 'EcomB2B/js/mobile.forms.min.js',

            }, pweb_mobile_checkout: {
                src: [
                    'EcomB2B/js/braintree.js',
                    'EcomB2B/js/checkout.js',
                ], dest: 'EcomB2B/js/mobile.checkout.min.js'

            },
            pweb_tablet: {
                options: {

                    sourceMap: true,
                },
                src: [
                    'EcomB2B/theme_1/local/jquery.js',
                    'EcomB2B/theme_1/tablet/plugins.js',
                   // 'EcomB2B/js/swiper.js',
                  //  'EcomB2B/theme_1/tablet/custom.js',
                    'EcomB2B/js/search.js',
                ], dest: 'EcomB2B/js/tablet.min.js',

            }, pweb_tablet_custom: {
                options: {

                    sourceMap: true,
                },
                src: [

                    'EcomB2B/theme_1/tablet/custom.js',
                ], dest: 'EcomB2B/js/tablet.custom.min.js',

            }

        },
        sass: {
            aurora: {
                options: {
                    // style: 'compressed'
                }, files: {
                    'css/app.css': 'sass/app.scss', 'css/app.mobile.css': 'sass/app.mobile.scss',

                }
            }, aurora_public: {
                options: {
                    // style: 'compressed'
                }, files: {
                    'EcomB2B/css/style.theme_1.EcomB2B.desktop.css': 'sass/style.theme_1.EcomB2B.scss',
                    'EcomB2B/css/style.theme_1.EcomB2B.tablet.css': 'sass/style.theme_1.EcomB2B.tablet.scss',
                    'EcomB2B/css/style.theme_1.EcomB2B.mobile.css': 'sass/style.theme_1.EcomB2B.mobile.scss',


                }
            },
/*
            web: {
                options: {
                    // style: 'compressed'
                }, files: {
                    'web/css/aurora.css': 'web/sass/aurora.scss'
                }
            },
            */
            login: {
                options: {
                    // style: 'compressed'
                }, files: {
                    'css/login.css': 'sass/login.scss'
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false, roundingPrecision: -1, sourceMap: true,
            }, libs: {
                files: {
                    'build/app/css/libs.min.css': ['css/jquery-ui.css', 'css/font-awesome.css', 'css/intlTelInput.css', 'css/d3fc.css', 'css/backgrid.css', 'css/backgrid-filter.css']

                }
            },

            pweb: {
                files: {
                    'EcomB2B/css/desktop.min.css': [

                      'EcomB2B/css/fontawesome-all.css',

                        'EcomB2B/css/style.theme_1.EcomB2B.desktop.css'],

                    'EcomB2B/css/forms.min.css': [
                        'EcomB2B/css/sweetalert.css',

                        'EcomB2B/theme_1/sky_forms/css/sky-forms.css'
                    ],


                    'EcomB2B/css/image_gallery.min.css': [
                        'EcomB2B/css/photoswipe.css',
                        'EcomB2B/css/photoswipe/default-skin.css'],




                    'EcomB2B/css/mobile.min.css': [
                        'EcomB2B/css/fontawesome-all.min.css',
                        'EcomB2B/theme_1/mobile/style.css',
                        'EcomB2B/theme_1/mobile/skin.css',
                        'EcomB2B/theme_1/mobile/framework.css',
                        'EcomB2B/css/style.theme_1.EcomB2B.mobile.css',
                    ],



                'EcomB2B/css/tablet.min.css': [

                        'EcomB2B/css/fontawesome-all.min.css',
                        'EcomB2B/theme_1/tablet/style.css',
                        'EcomB2B/theme_1/tablet/skin.css',
                        'EcomB2B/theme_1/tablet/framework.css',

                        'EcomB2B/theme_1/css/swiper.css',
                        'EcomB2B/css/style.theme_1.EcomB2B.tablet.css'
                    ],




                }
            },

            aurora: {
                files: {
                    'build/app/css/app.min.css': ['css/app.css']

                }
            }, login: {
                files: {
                    'build/app/css/login.min.css': ['css/login.css']

                }
            }
        },
        copy: {

            app: {
                files: [{
                    expand: true, src: ['fonts/*'], dest: 'build/app/'
                }, {
                    expand: true, src: ['css/images/*'], dest: 'build/app/'
                },

                    {
                        expand: true, src: ['*.php'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['external_libs/**'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['locale/**'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['conf/*.php'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['utils/*.php'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['cron/*.php'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['navigation/*.php'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['pdf/*.php'], dest: 'build/app/'
                    }, {
                        expand: true, src: ['prepare_table/*.php'], dest: 'build/app/'
                    },

                ],
            },



            websocket: {
                files: [{
                    expand: true, cwd: 'websocket_server/', src: ['app/**'], dest: 'build/websocket/'
                },{
                    expand: true,cwd: 'websocket_server/',  src: ['*.php'], dest: 'build/websocket/'
                },{
                    expand: true,cwd: 'websocket_server/',  src: ['composer.json'], dest: 'build/websocket/'
                }


                ]
            },

            fork_stones: {
                files: [{
                    expand: true, src: ['external_libs/**'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['locale/**'], dest: 'build/fork/'
                }, {
                    expand: true, cwd: 'fork/', src: ['*.php'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['composer.json'], dest: 'build/fork/composer'
                }

                ]
            },

            fork: {
                files: [{
                    expand: true, cwd: 'fork/', src: ['tmp/*.txt'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['class.*.php'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['trait.*.php'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['conf/*.php'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['conf/fields/*.php'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['utils/*.php'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['widgets/*.php'], dest: 'build/fork/'
                }, {
                    expand: true, cwd: 'fork/', src: ['*.php'], dest: 'build/fork/'
                }, {
                    expand: true, src: ['templates/unsubscribe*.tpl'], dest: 'build/fork/'
                }

                ],
            },


        }, imagemin: {
            aurora: {
                options: {
                    optimizationLevel: 3,

                }, files: [{
                    expand: true, cwd: 'art/', src: ['**/*.{png,jpg,gif}'], dest: 'build/app/art/'
                }]
            }
        },


        environments: {

            options: {

                current_symlink: 'current', zip_deploy: true, max_buffer: 200 * 1024 * 1024
            }, fork: {
                options: {
                    local_path: 'build/fork/',
                    deploy_path: '/home/fork/fork',
                    host: '<%= secret.fork.host %>',
                    username: '<%= secret.fork.username %>',
                    password: '<%= secret.fork.password %>',
                    port: '<%= secret.fork.port %>',
                    debug: true,
                    releases_to_keep: '3',
                    exclude: ['keyring', 'external_libs', 'server_files','vendor'],
                    after_deploy: 'cd /home/fork/fork/current && ln -s /home/fork/composer/current/vendor vendor && ln -s /home/fork/external_libs/current/ external_libs && ln -s /home/fork/keyring/ keyring '
                }
            }, fork_external_libs: {
                options: {
                    local_path: 'build/fork/external_libs',
                    deploy_path: '/home/fork/external_libs',
                    host: '<%= secret.fork.host %>',
                    username: '<%= secret.fork.username %>',
                    password: '<%= secret.fork.password %>',
                    port: '<%= secret.fork.port %>',
                    debug: true,
                    releases_to_keep: '3'
                }
            }, fork_composer: {
                options: {
                    local_path: 'build/fork/composer',
                    deploy_path: '/home/fork/composer',
                    host: '<%= secret.fork.host %>',
                    username: '<%= secret.fork.username %>',
                    password: '<%= secret.fork.password %>',
                    port: '<%= secret.fork.port %>',
                    debug: true,
                    after_deploy: 'cd /home/fork/composer/current && composer update',

                    releases_to_keep: '3'
                }
            }, websocket_composer: {
                options: {
                    local_path: 'build/websocket_composer',
                    deploy_path: '/home/fork/websocket_composer',
                    host: '<%= secret.fork.host %>',
                    username: '<%= secret.fork.username %>',
                    password: '<%= secret.fork.password %>',
                    port: '<%= secret.fork.port %>',
                    debug: true,
                    after_deploy: 'cd /home/fork/websocket_composer/current && composer install',

                    // after_deploy: 'cd /home/fork/websocket_composer/current && composer install && sudo kill $(ps -aef | grep "push_server.v1.php" | grep -v "grep" | awk \'{print $2}\')',
                    releases_to_keep: '1'
                }
            },websocket: {
                options: {
                    local_path: 'build/websocket/',
                    deploy_path: '/home/fork/websocket/',
                    host: '<%= secret.fork.host %>',
                    username: '<%= secret.fork.username %>',
                    password: '<%= secret.fork.password %>',
                    port: '<%= secret.fork.port %>',
                    debug: true,
                    releases_to_keep: '3',
                    //exclude: ['keyring', 'external_libs', 'server_files'],
                    after_deploy: 'cd /home/fork/websocket/current && composer install '
                }
            }, ecom: {
                options: {
                    local_path: 'ecom/',
                    deploy_path: '/home/inikoo/ecom',
                    host: '<%= secret.ecom.host %>',
                    username: '<%= secret.ecom.username %>',
                    password: '<%= secret.ecom.password %>',
                    port: '<%= secret.ecom.port %>',
                    debug: true,
                    releases_to_keep: '3'

                }
            }, b2becom: {
                options: {
                    local_path: 'ecom/',
                    deploy_path: '/home/inikoo/ecom',
                    host: '<%= secret.ecom.host %>',
                    username: '<%= secret.ecom.username %>',
                    password: '<%= secret.ecom.password %>',
                    port: '<%= secret.ecom.port %>',
                    debug: true,
                    releases_to_keep: '3'

                }
            },


        },

        watch: {

            sass: {
                files: ['sass/*.scss'], tasks: ['sass:aurora'], options: {
                    spawn: false,
                },
            }, fork: {
                files: ['fork/*.php', 'conf/*.php', 'conf/fields/*.php', 'utils/*', 'class.*.php', 'trait.*.php'], tasks: ['copy:fork'], options: {
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
    grunt.loadNpmTasks('grunt-ssh-deploy');


    grunt.registerTask('default', ['sass']);

    grunt.registerTask('app', ['clean:app', 'imagemin', 'sass', 'concat', 'uglify', 'cssmin', 'copy:app']);
    grunt.registerTask('fork', ['clean:fork', 'copy:fork_stones', 'copy:fork']);
    grunt.registerTask('qfork', ['copy:fork']);

    grunt.registerTask('ws', ['clean:websocket','copy:websocket', 'copy:websocket_stones']);
    grunt.registerTask('qws', ['copy:websocket']);


    grunt.registerTask('pweb', ['sass:aurora_public', 'cssmin:pweb','uglify:pweb_mobile',
        'uglify:pweb_mobile',
        'uglify:pweb_mobile_logged_in',
        'uglify:pweb_mobile_forms',
        'uglify:pweb_mobile_checkout',
        'uglify:pweb_tablet',
        'uglify:pweb_tablet_custom',
        'uglify:pweb_desktop',
        'uglify:pweb_desktop_logged_in',
        'uglify:pweb_desktop_forms',
        'uglify:pweb_desktop_basket',
        'uglify:pweb_desktop_checkout',
        'uglify:pweb_desktop_image_gallery'
    ]);
    grunt.registerTask('deploy_fork_stones', ['clean:fork', 'copy:fork_stones', 'copy:fork', 'ssh_deploy:fork_external_libs']);
    grunt.registerTask('deploy_fork_composer', ['clean:fork', 'copy:fork_stones', 'copy:fork', 'ssh_deploy:fork_composer']);

    grunt.registerTask('deploy_qfork', ['copy:fork', 'ssh_deploy:fork']);
    grunt.registerTask('deploy_websocket_composer', ['clean:websocket',  'copy:websocket', 'ssh_deploy:websocket_composer']);
    grunt.registerTask('deploy_websocket', ['clean:websocket',  'copy:websocket', 'ssh_deploy:websocket']);
    grunt.registerTask('ecom', ['ssh_deploy:ecom']);

};
