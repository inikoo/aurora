module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'), secret: grunt.file.readJSON('deployment.secret.json'),

        clean: {
            fork: ["../fork/*", "!../fork/keyring/**", "!../fork/server_files/**"],
        },
        uglify: {
            pweb_common_desktop_logged_in: {
                options: {
                    sourceMap: true,
                },

                src: [
                    'EcomB2B/js/jquery.hoverIntent.js',
                    'EcomB2B/js/menu.js',
                    'EcomB2B/js/search.js',
                ], dest: 'EcomB2B/js/desktop.in.min.js'
            },pweb_common_desktop_logged_out: {
                options: {
                    sourceMap: true,
                },

                src: [
                    'EcomB2B/theme_1/local/jquery.js',
                    'EcomB2B/js/jquery.hoverIntent.js',
                    'EcomB2B/js/menu.js',
                    'EcomB2B/js/search.js',
                ], dest: 'EcomB2B/js/desktop.out.min.js'
            }, pweb_desktop_logged_in: {
                options: {

                    sourceMap: true,
                },

                src: [
                    'EcomB2B/js/validation.EcomB2B.js',
                    'EcomB2B/js/aurora.logged_in.js',
                    'EcomB2B/js/ordering.js',


                ], dest: 'EcomB2B/js/desktop.logged_in.min.js',
            }, pweb_desktop_image_gallery: {
                src: [

                    'EcomB2B/js/photoswipe.js',
                    'EcomB2B/js/photoswipe-ui-default.js',


                ], dest: 'EcomB2B/js/image_gallery.min.js',
            }, pweb_desktop_forms: {
                options: {
                    sourceMap: true,
                },
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
                options: {
                    sourceMap: true,
                },
                src: [
                    'EcomB2B/js/basket.js',
                    'EcomB2B/js/order_totals.js',
                ], dest: 'EcomB2B/js/desktop.basket.min.js'
            }, pweb_desktop_checkout: {
                options: {
                    sourceMap: true,
                },
                src: [

                    'EcomB2B/js/braintree.3.40.0.paypal-checkout.min.js',
                    'EcomB2B/js/braintree.3.40.0.min.js',
                    'EcomB2B/js/braintree.3.40.0.hosted-fields.min.js',
                    'EcomB2B/js/checkout.js',
                    'EcomB2B/js/order_totals.js',

                ], dest: 'EcomB2B/js/desktop.checkout.min.js'
            }, pweb_desktop_profile: {
                options: {
                    sourceMap: true,
                },
                src: [
                    'EcomB2B/js/order_totals.js',
                ], dest: 'EcomB2B/js/desktop.profile.min.js'
            }, pweb_mobile_logged_in: {
                options: {
                    sourceMap: true,
                },
                src: [
                    'EcomB2B/js/validation.EcomB2B.js',
                    'EcomB2B/js/aurora.logged_in.mobile.js',
                    'EcomB2B/js/ordering.touch.js'

                ], dest: 'EcomB2B/js/mobile.logged_in.min.js',

            }, pweb_mobile_forms: {
                options: {
                    sourceMap: true,
                },
                src: [
                    'EcomB2B/theme_1/local/jquery-ui.js',
                    'EcomB2B/theme_1/sky_forms/js/jquery.form.min.js',
                    'EcomB2B/theme_1/sky_forms/js/jquery.validate.min.js',
                    'EcomB2B/theme_1/sky_forms/js/additional-methods.min.js',
                    'EcomB2B/js/sweetalert.min.js',
                    'EcomB2B/js/sha256.js',
                    'EcomB2B/js/aurora_forms.js',
                ], dest: 'EcomB2B/js/mobile.forms.min.js',

            }, pweb_mobile_basket: {
                options: {
                    sourceMap: true,
                },
                src: [
                    'EcomB2B/js/basket.js',
                    'EcomB2B/js/order_totals.js',
                ], dest: 'EcomB2B/js/mobile.basket.min.js'
            }, pweb_mobile_profile: {
                options: {
                    sourceMap: true,
                },
                src: [
                    'EcomB2B/js/order_totals.js',
                ], dest: 'EcomB2B/js/mobile.profile.min.js'
            }, pweb_mobile_checkout: {
                options: {
                    sourceMap: true,
                },
                src: [

                    'EcomB2B/js/braintree.3.40.0.paypal-checkout.min.js',
                    'EcomB2B/js/braintree.3.40.0.min.js',
                    'EcomB2B/js/braintree.3.40.0.hosted-fields.min.js',
                    'EcomB2B/js/checkout.js',
                    'EcomB2B/js/order_totals.js',
                ], dest: 'EcomB2B/js/mobile.checkout.min.js'

            },
            pweb_tablet: {
                options: {

                    sourceMap: true,
                },
                src: [
                    'EcomB2B/theme_1/local/jquery.js',
                    'EcomB2B/theme_1/tablet/plugins.js',
                    'EcomB2B/js/search.js',
                ], dest: 'EcomB2B/js/mobile.190304.min.js',

            }, pweb_tablet_custom: {
                options: {

                    sourceMap: true,
                },
                src: [

                    'EcomB2B/theme_1/tablet/custom.js',
                ], dest: 'EcomB2B/js/tablet.custom.min.js',

            }, aurora_libs: {
                options: {

                    sourceMap: true,
                },
                src: [
                    'js/libs/jquery-3.3.1.min.js',
                    'js/libs/jquery-migrate-3.0.1.js',
                    'js/libs/jquery-ui.1.12.1.js',
                    'js/libs/jquery.nice-select.js',


                    'bower_components/moment/min/moment-with-locales.js',
                    'bower_components/moment-timezone/builds/moment-timezone-with-data-2012-2022.js',
                    'bower_components/select2/dist/js/select2.js',
                    //'js/libs/moment-with-locales.js',
                    //'js/libs/moment-timezone-with-data.js',

                    'js/libs/chrono.js',
                    'js/libs/sha256.js',
                    'js/libs/underscore.min.js',
                    'js/libs/backbone.min.js',
                    'js/libs/backbone.paginator.js',
                    'js/libs/backgrid.js',
                    'js/libs/backgrid-filter.js',
                    'js/libs/snap.svg.js',
                    'js/libs/svg-dial.js',
                    'js/libs/countrySelect.js',
                    'js/libs/intlTelInput-jquery.14.0.6.js',

                    //'js/libs/d3.js',
                    //'js/libs/d3fc.layout.js',
                    //'js/libs/d3fc.js',


                    'js/libs/sweetalert2.all.min.js',
                    'js/libs/tooltipster.bundle.min.js',
                    'js/libs/jquery-qrcode-0.14.0.min.js',
                    'js/alert_dial.js',
                    'js/libs/editor_v1/froala_editor.min.js',
                    //'js/libs/editor_v1/codemirror.js',
                    //'js/libs/editor_v1/codemirror.xml.js',
                    //'js/libs/editor_v1/codemirror_active-line.js',
                    'js/libs/editor_v1/plugins/align.min.js',
                    'js/libs/editor_v1/plugins/draggable.min.js',
                    'js/libs/editor_v1/plugins/char_counter.min.js',
                    'js/libs/editor_v1/plugins/code_beautifier.min.js',
                    'js/libs/editor_v1/plugins/code_view.min.js',
                    'js/libs/editor_v1/plugins/colors.min.js',
                    'js/libs/editor_v1/plugins/emoticons.min.js',
                    'js/libs/editor_v1/plugins/entities.min.js',
                    'js/libs/editor_v1/plugins/file.min.js',
                    'js/libs/editor_v1/plugins/font_family.min.js',
                    'js/libs/editor_v1/plugins/font_size.min.js',
                    'js/libs/editor_v1/plugins/fullscreen.min.js',
                    'js/libs/editor_v1/plugins/image.min.js',
                    'js/libs/editor_v1/plugins/image_manager.min.js',
                    'js/libs/editor_v1/plugins/inline_style.min.js',
                    'js/libs/editor_v1/plugins/line_breaker.min.js',
                    'js/libs/editor_v1/plugins/link.min.js',
                    'js/libs/editor_v1/plugins/lists.min.js',
                    'js/libs/editor_v1/plugins/paragraph_format.min.js',
                    'js/libs/editor_v1/plugins/paragraph_style.min.js',
                    'js/libs/editor_v1/plugins/quick_insert.min.js',
                    'js/libs/editor_v1/plugins/quote.min.js',
                    'js/libs/editor_v1/plugins/table.min.js',
                    'js/libs/editor_v1/plugins/save.min.js',
                    'js/libs/editor_v1/plugins/url.min.js',
                    'js/libs/editor_v1/plugins/video.min.js',
                    'js/libs/amcharts/amcharts.js',
                    'js/libs/amcharts/serial.js',
                    'js/libs/amcharts/amstock.js',
                    'js/libs/amcharts/plugins/dataloader/dataloader.min.js',
                    'js/libs/amcharts/plugins/export/export.min.js',
                    'js/libs/jquery.fancybox.min.js',
                    'js/libs/jquery.awesome-cursor.min.js',
                    'js/libs/base64.js',
                    //'js/libs/jquery.formatCurrency-1.4.0.min.js',
                    'js/libs/jquery.formatCurrency.js',
                    'js/libs/autobahn.v1.js',


                ], dest: 'assets/aurora_libs.min.js',

            }, aurora: {
                options: {

                    sourceMap: true,
                },
                src: [
                    'js/common.js',
                    'js/help.js',
                    'js/keyboard_shortcuts.js',
                    'js/barcode_scanner.js',
                    'js/edit.js',

                    'js/mixed_recipients.edit.js',
                    'js/search.js',
                    'js/table.js',
                    'js/validation.js',
                    'js/pdf.js',
                    'js/edit_webpage_edit.js',
                     'js/new.js',
                    'js/order.common.js',
                    'js/email_campaign.common.js',
                    'js/supplier.order.js',
                    'js/supplier.delivery.js',
                    'js/part_locations.edit.js',
                    'js/part_locations.edit_locations.js',
                    'js/part_locations.stock_check.js',
                    'js/part_locations.move_stock.js',
                    'js/fast_track_packing.js',
                    'js/sticky_notes.js',
                    'js/picking_and_packing.js',
                    'js/app.js',
                    'js/real_time.js',
                    'js/customers.js'





                ], dest: 'assets/aurora.min.js',

            }, login: {
                options: {

                    sourceMap: true,
                },
                src: [
                    'js/libs/jquery-3.3.1.min.js',
                    'js/libs/jquery-migrate-3.0.1.js',
                    'bower_components/moment/min/moment.min.js',
                    'bower_components/moment-timezone/builds/moment-timezone-with-data-10-year-range.min.js',
                    'js/libs/sha256.js',
                    'js/libs/aes.js',
                    'js/libs/base64.js',

                    'js/login/login.js',
                    'js/libs/jquery.backstretch.min.js',





                ], dest: 'assets/login.min.js',

            }, setup: {
                options: {

                    sourceMap: true,
                },
                src: [
                    'js/common.js',
                    'js/help.js',
                    'js/keyboard_shortcuts.js',
                    'js/edit.js',
                    'js/search.js',
                    'js/table.js',
                    'js/validation.js',

                    'js/setup/setup.js'




                ], dest: 'assets/aurora_setup.min.js',

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
            },

            pweb: {
                files: {
                    'EcomB2B/css/desktop.min.css': [

                        'css/fontawesome-all.css',
                        'EcomB2B/css/style.theme_1.EcomB2B.desktop.css'],

                    'EcomB2B/css/forms.min.css': [
                        'EcomB2B/css/sweetalert.css',
                        'EcomB2B/theme_1/sky_forms/css/sky-forms.css',
                        'EcomB2B/css/sky_forms.aurora.css'
                    ],


                    'EcomB2B/css/image_gallery.min.css': [
                        'EcomB2B/css/photoswipe.css',
                        'EcomB2B/css/photoswipe/default-skin.css'],




                    'EcomB2B/css/mobile.min.css': [
                        'css/fontawesome-all.css',
                        'EcomB2B/theme_1/mobile/style.css',
                        'EcomB2B/theme_1/mobile/skin.css',
                        'EcomB2B/theme_1/mobile/framework.css',
                        'EcomB2B/css/style.theme_1.EcomB2B.mobile.css',
                    ],



                'EcomB2B/css/tablet.min.css': [

                        'css/fontawesome-all.css',
                        'EcomB2B/theme_1/tablet/style.css',
                        'EcomB2B/theme_1/tablet/skin.css',
                        'EcomB2B/theme_1/tablet/framework.css',

                        'EcomB2B/theme_1/css/swiper.css',
                        'EcomB2B/css/style.theme_1.EcomB2B.tablet.css'
                    ],




                }
            },

            au: {
                files: {
                    'assets/au_app.min.css':
                        [
                            'css/jquery-ui.css',
                            'css/fontawesome-all.css',
                            'css/intlTelInput.css',
                            'css/countrySelect.css',
                            'css/d3fc.css',
                            'css/backgrid.css',
                            'css/backgrid-filter.css',
                            'bower_components/select2/dist/css/select2.css',

                            'css/editor_v1/froala_editor.css',
                            'css/editor_v1/froala_style.css',
                            'css/editor_v1/codemirror.css',
                            'css/editor_v1/codemirror_dracula.css',

                            'css/editor_v1/plugins/char_counter.css',
                            'css/editor_v1/plugins/code_view.css',
                            'css/editor_v1/plugins/colors.css',
                            'css/editor_v1/plugins/emoticons.css',
                            'css/editor_v1/plugins/file.css',
                            'css/editor_v1/plugins/fullscreen.css',
                            'css/editor_v1/plugins/image.css',
                            'css/editor_v1/plugins/image_manager.css',
                            'css/editor_v1/plugins/line_breaker.css',
                            'css/editor_v1/plugins/quick_insert.css',
                            'css/editor_v1/plugins/table.css',
                            'css/editor_v1/plugins/video.css',
                            'css/editor_v1/plugins/draggable.css',
                            'css/amcharts/style.css',
                            'css/jquery.fancybox.min.css',
                            'css/tooltipster.bundle.min.css',
                            'css/app.css'
                        ]

                }
            },

            au_login: {
                files: {
                    'css/login.min.css': [
                        'css/fontawesome-all.css',
                        'css/login.css'
                    ]

                }
            }
        },
        copy: {





            fork_stones: {
                files: [{
                    expand: true, src: ['external_libs/**'], dest: '../fork/'
                }, {
                    expand: true, src: ['locale/**'], dest: '../fork/'
                }, {
                    expand: true, src: ['smarty_plugins/**'], dest: '../fork/'
                }, {
                    expand: true, cwd: 'fork/', src: ['*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['composer.json'], dest: '../fork/composer'
                }

                ]
            },

            fork: {
                files: [{
                    expand: true, cwd: 'fork/', src: ['tmp/*.txt'], dest: '../fork/'
                }, {
                    expand: true, src: ['class.*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['image.php'], dest: '../fork/'
                },{
                    expand: true, src: ['trait.*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['conf/*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['nano_services/*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['conf/fields/*.php'], dest: '../fork/'
                },{
                    expand: true, src: ['node/*.js'], dest: '../fork/'
                }, {
                    expand: true, src: ['utils/*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['widgets/*.php'], dest: '../fork/'
                }, {
                    expand: true, cwd: 'fork/', src: ['*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['templates/unsubscribe*.tpl'], dest: '../fork/'
                }, {
                    expand: true, src: ['templates/notification_emails/*.tpl'], dest: '../fork/'
                }

                ],
            },


        },

        environments: {

            options: {

                current_symlink: 'current', zip_deploy: true, max_buffer: 200 * 1024 * 1024
            }, fork: {
                options: {
                    local_path: '../fork/',
                    deploy_path: '/home/fork/fork',
                    host: '<%= secret.fork.host %>',
                    username: '<%= secret.fork.username %>',
                    password: '<%= secret.fork.password %>',
                    port: '<%= secret.fork.port %>',
                    debug: true,
                    releases_to_keep: '3',
                    exclude: ['keyring', 'external_libs', 'server_files','vendor','base_dirs','img_*','node_modules'],
                    before_deploy: 'cd /home/fork/composer && /usr/bin/php7.2 /usr/local/bin/composer install',
                    after_deploy: 'cd /home/fork/fork/current && mv /home/fork/composer/vendor . && ln -s /home/fork/node/node_modules . &&  mkdir server_files &&  mkdir server_files/tmp  &&  ln -s /home/fork/external_libs/current/ external_libs && ln -s /home/fork/keyring/ keyring  && ln -s /home/fork/base_dirs/ base_dirs && cp -av  /home/fork/img/* . '
                }
            }, fork_external_libs: {
                options: {
                    local_path: '../fork/external_libs',
                    deploy_path: '/home/fork/external_libs',
                    host: '<%= secret.fork.host %>',
                    username: '<%= secret.fork.username %>',
                    password: '<%= secret.fork.password %>',
                    port: '<%= secret.fork.port %>',
                    debug: true,
                    releases_to_keep: '3'
                }
            }


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
    grunt.registerTask('fork', [ 'copy:fork_stones', 'copy:fork']);
    grunt.registerTask('qfork', ['copy:fork']);

    grunt.registerTask('ws', ['clean:websocket','copy:websocket', 'copy:websocket_stones']);
    grunt.registerTask('qws', ['copy:websocket']);


    grunt.registerTask('au', ['sass:aurora','sass:aurora_public','sass:login', 'cssmin:au', 'cssmin:au_login','uglify:aurora_libs','uglify:login','uglify:aurora']);
    grunt.registerTask('qau', ['uglify:aurora']);


    grunt.registerTask('pweb', ['sass:aurora_public', 'cssmin:pweb',

        'uglify:pweb_mobile_logged_in',
        'uglify:pweb_mobile_forms',
        'uglify:pweb_mobile_profile',
        'uglify:pweb_mobile_basket',
        'uglify:pweb_mobile_checkout',
        'uglify:pweb_tablet',
        'uglify:pweb_tablet_custom',
        'uglify:pweb_common_desktop_logged_in',
        'uglify:pweb_common_desktop_logged_out',
        'uglify:pweb_desktop_logged_in',
        'uglify:pweb_desktop_forms',
        'uglify:pweb_desktop_profile',
        'uglify:pweb_desktop_basket',
        'uglify:pweb_desktop_checkout',
        'uglify:pweb_desktop_image_gallery'
    ]);
    grunt.registerTask('deploy_fork_stones', [ 'copy:fork_stones', 'copy:fork', 'ssh_deploy:fork_external_libs']);
    grunt.registerTask('deploy_fork_composer', [ 'copy:fork', 'ssh_deploy:fork_composer']);

    grunt.registerTask('deploy_qfork', ['copy:fork', 'ssh_deploy:fork']);
    grunt.registerTask('deploy_websocket_composer', ['clean:websocket',  'copy:websocket', 'ssh_deploy:websocket_composer']);
    grunt.registerTask('deploy_websocket', ['clean:websocket',  'copy:websocket', 'ssh_deploy:websocket']);
    grunt.registerTask('ecom', ['ssh_deploy:ecom']);

};
