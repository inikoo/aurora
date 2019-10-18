module.exports = function (grunt) {

    grunt.initConfig({

        clean: {
            fork: ["../fork/*", "!../fork/keyring/**", "!../fork/server_files/**"],
            css: ["assets/images"],
            pweb_deployment_step2: ["EcomB2B/assets/*.min.js", "EcomB2B/assets/*.min.css"],
            pweb_deployment_step4: ["EcomB2B/assets/*.tmp.*"],
            au_deployment_step2: ["assets/*.min.js", "assets/*.min.css"],
            au_deployment_step4: ["assets/*.tmp.*"],
            assets:["assets/*min*","EcomB2B/assets/**"]

        },
        uglify: {
            pweb_common_desktop_logged_in: {
                options: {
                    sourceMap: true,
                },

                src: ['EcomB2B/js/libs/jquery.hoverIntent.js', 'EcomB2B/js/au_header/menu.js', 'EcomB2B/js/au_header/search.js',], dest: 'EcomB2B/assets/desktop.in.min.js'
            }, pweb_common_desktop_logged_out: {
                options: {
                    sourceMap: true,
                },

                src: ['EcomB2B/js/libs/jquery.js', 'EcomB2B/js/libs/jquery.hoverIntent.js', 'EcomB2B/js/au_header/menu.js', 'EcomB2B/js/au_header/search.js',], dest: 'EcomB2B/assets/desktop.out.min.js'
            }, pweb_desktop_logged_in: {
                options: {

                    sourceMap: true,
                },

                src: ['EcomB2B/js/aurora/validation.EcomB2B.js', 'EcomB2B/js/aurora/aurora.logged_in.js', 'EcomB2B/js/aurora/ordering.js',


                ], dest: 'EcomB2B/assets/desktop.logged_in.min.js',
            }, pweb_desktop_image_gallery: {
                options: {
                    sourceMap: true,
                }, src: [

                    'EcomB2B/js/images/photoswipe.js', 'EcomB2B/js/images/photoswipe-ui-default.js',


                ], dest: 'EcomB2B/assets/image_gallery.min.js',
            }, pweb_desktop_forms: {
                options: {
                    sourceMap: true,
                },
                src: ['EcomB2B/js/libs/jquery-ui.js', 'EcomB2B/js/libs/jquery.form.min.js', 'EcomB2B/js/libs/jquery.validate.min.js', 'EcomB2B/js/libs/additional-methods.min.js', 'EcomB2B/js/libs/sweetalert.min.js', 'EcomB2B/js/libs/sha256.js', 'EcomB2B/js/au_forms/aurora_forms.js'],
                dest: 'EcomB2B/assets/desktop.forms.min.js'
            }, pweb_desktop_basket: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/basket.js', 'EcomB2B/js/basket_checkout/order_totals.js',], dest: 'EcomB2B/assets/desktop.basket.min.js'
            }, pweb_desktop_checkout: {
                options: {
                    sourceMap: true,
                }, src: [

                    'EcomB2B/js/basket_checkout/braintree.3.40.0.paypal-checkout.min.js', 'EcomB2B/js/basket_checkout/braintree.3.40.0.min.js', 'EcomB2B/js/basket_checkout/braintree.3.40.0.hosted-fields.min.js', 'EcomB2B/js/basket_checkout/checkout.js', 'EcomB2B/js/basket_checkout/order_totals.js',

                ], dest: 'EcomB2B/assets/desktop.checkout.min.js'
            }, pweb_desktop_profile: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/order_totals.js',], dest: 'EcomB2B/assets/desktop.profile.min.js'
            }, pweb_mobile_logged_in: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/aurora_mobile/validation.EcomB2B.js', 'EcomB2B/js/aurora_mobile/aurora.logged_in.mobile.js', 'EcomB2B/js/aurora_mobile/ordering.touch.js'

                ], dest: 'EcomB2B/assets/mobile.logged_in.min.js',

            }, pweb_mobile_forms: {
                options: {
                    sourceMap: true,
                },
                src: ['EcomB2B/js/libs/jquery-ui.js', 'EcomB2B/js/libs/jquery.form.min.js', 'EcomB2B/js/libs/jquery.validate.min.js', 'EcomB2B/js/libds/additional-methods.min.js', 'EcomB2B/js/libs/sweetalert.min.js', 'EcomB2B/js/lbs/sha256.js', 'EcomB2B/js/au_forms/aurora_forms.js',],
                dest: 'EcomB2B/assets/mobile.forms.min.js',

            }, pweb_mobile_basket: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/basket.js', 'EcomB2B/js/basket_checkout/order_totals.js'], dest: 'EcomB2B/assets/mobile.basket.min.js'
            }, pweb_mobile_profile: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/order_totals.js'], dest: 'EcomB2B/assets/mobile.profile.min.js'
            }, pweb_mobile_checkout: {
                options: {
                    sourceMap: true,
                },
                src: [

                    'EcomB2B/js/basket_checkout/braintree.3.40.0.paypal-checkout.min.js', 'EcomB2B/js/basket_checkout/braintree.3.40.0.min.js', 'EcomB2B/js/basket_checkout/braintree.3.40.0.hosted-fields.min.js', 'EcomB2B/js/checkout.js', 'EcomB2B/js/basket_checkout/order_totals.js',],
                dest: 'EcomB2B/assets/mobile.checkout.min.js'

            }, pweb_tablet: {
                options: {

                    sourceMap: true,
                }, src: ['EcomB2B/js/libs/jquery.js', 'EcomB2B/js/libs/mobile_plugins.js', 'EcomB2B/js/au_header/search.js'], dest: 'EcomB2B/assets/mobile.min.js',

            }, pweb_tablet_custom: {
                options: {

                    sourceMap: true,
                }, src: [

                    'EcomB2B/js/libs/mobile_custom.js'], dest: 'EcomB2B/assets/mobile_custom.min.js',

            }, aurora_libs: {
                options: {

                    sourceMap: true,
                }, src: ['js_libs/jquery-3.3.1.min.js', 'js_libs/jquery-migrate-3.0.1.js', 'js_libs/jquery-ui.1.12.1.js', 'js_libs/jquery.nice-select.js',


                    'bower_components/moment/min/moment-with-locales.js', 'bower_components/moment-timezone/builds/moment-timezone-with-data-2012-2022.js', 'bower_components/select2/dist/js/select2.js', //'js_libs/moment-with-locales.js',
                    //'js_libs/moment-timezone-with-data.js',

                    'js_libs/chrono.js', 'js_libs/sha256.js', 'js_libs/underscore.min.js', 'js_libs/backbone.min.js', 'js_libs/backbone.paginator.js', 'js_libs/backgrid.js', 'js_libs/backgrid-filter.js', 'js_libs/snap.svg.js', 'js_libs/svg-dial.js', 'js_libs/countrySelect.js', 'js_libs/intlTelInput-jquery.14.0.6.js',

                    //'js_libs/d3.js',
                    //'js_libs/d3fc.layout.js',
                    //'js_libs/d3fc.js',


                    'js_libs/sweetalert2.all.min.js', 'js_libs/tooltipster.bundle.min.js', 'js_libs/jquery-qrcode-0.14.0.min.js', 'js/alert_dial.js', 'js_libs/editor_v1/froala_editor.min.js', //'js_libs/editor_v1/codemirror.js',
                    //'js_libs/editor_v1/codemirror.xml.js',
                    //'js_libs/editor_v1/codemirror_active-line.js',
                    'js_libs/editor_v1/plugins/align.min.js', 'js_libs/editor_v1/plugins/draggable.min.js', 'js_libs/editor_v1/plugins/char_counter.min.js', 'js_libs/editor_v1/plugins/code_beautifier.min.js', 'js_libs/editor_v1/plugins/code_view.min.js', 'js_libs/editor_v1/plugins/colors.min.js', 'js_libs/editor_v1/plugins/emoticons.min.js', 'js_libs/editor_v1/plugins/entities.min.js', 'js_libs/editor_v1/plugins/file.min.js', 'js_libs/editor_v1/plugins/font_family.min.js', 'js_libs/editor_v1/plugins/font_size.min.js', 'js_libs/editor_v1/plugins/fullscreen.min.js', 'js_libs/editor_v1/plugins/image.min.js', 'js_libs/editor_v1/plugins/image_manager.min.js', 'js_libs/editor_v1/plugins/inline_style.min.js', 'js_libs/editor_v1/plugins/line_breaker.min.js', 'js_libs/editor_v1/plugins/link.min.js', 'js_libs/editor_v1/plugins/lists.min.js', 'js_libs/editor_v1/plugins/paragraph_format.min.js', 'js_libs/editor_v1/plugins/paragraph_style.min.js', 'js_libs/editor_v1/plugins/quick_insert.min.js', 'js_libs/editor_v1/plugins/quote.min.js', 'js_libs/editor_v1/plugins/table.min.js', 'js_libs/editor_v1/plugins/save.min.js', 'js_libs/editor_v1/plugins/url.min.js', 'js_libs/editor_v1/plugins/video.min.js', 'js_libs/amcharts/amcharts.js', 'js_libs/amcharts/serial.js', 'js_libs/amcharts/amstock.js', 'js_libs/amcharts/plugins/dataloader/dataloader.min.js', 'js_libs/amcharts/plugins/export/export.min.js', 'js_libs/jquery.fancybox.min.js', 'js_libs/jquery.awesome-cursor.min.js', 'js_libs/base64.js', //'js_libs/jquery.formatCurrency-1.4.0.min.js',
                    'js_libs/jquery.formatCurrency.js', //'bower_components/autobahn/autobahn.js',
                    'js_libs/autobahn.v1.js',

                ], dest: 'assets/aurora_libs.min.js',

            }, aurora: {
                options: {

                    sourceMap: true,
                }, src: ['js/common.js', 'js/help.js', 'js/keyboard_shortcuts.js', 'js/barcode_scanner.js', 'js/edit.js',

                    'js/mixed_recipients.edit.js', 'js/search.js', 'js/table.js', 'js/validation.js', 'js/pdf.js', 'js/edit_webpage_edit.js', 'js/new.js', 'js/order.common.js', 'js/email_campaign.common.js', 'js/supplier.order.js', 'js/supplier.delivery.js', 'js/part_locations.edit.js', 'js/part_locations.edit_locations.js', 'js/part_locations.stock_check.js', 'js/part_locations.move_stock.js', 'js/fast_track_packing.js', 'js/sticky_notes.js', 'js/picking_and_packing.js', 'js/app.js', 'js/real_time.js', 'js/customers.js', 'js/customer_orders.js', 'js/customer_client.js', 'js/customer_client_orders.js'


                ], dest: 'assets/aurora.min.js',

            }, login_libs: {
                options: {

                    sourceMap: true,
                },
                src: ['js_libs/jquery-3.3.1.min.js', 'js_libs/jquery-migrate-3.0.1.js', 'bower_components/moment/min/moment.min.js', 'bower_components/moment-timezone/builds/moment-timezone-with-data-10-year-range.min.js', 'js_libs/sha256.js', 'js_libs/aes.js', 'js_libs/base64.js',

                    'js_libs/jquery.backstretch.min.js',


                ],
                dest: 'assets/login_libs.min.js',

            }, login: {
                options: {

                    sourceMap: true,
                }, src: [

                    'js/login/login.js'


                ], dest: 'assets/login.min.js',

            }, setup: {
                options: {

                    sourceMap: true,
                }, src: ['js/common.js', 'js/help.js', 'js/keyboard_shortcuts.js', 'js/edit.js', 'js/search.js', 'js/table.js', 'js/validation.js',

                    'js/setup/setup.js'


                ], dest: 'assets/aurora_setup.min.js',

            }

        },
        sass: {
            aurora: {
                files: {
                    'css/staging/app.css': 'sass/app.scss', 'css/staging/app.mobile.css': 'sass/app.mobile.scss',

                }
            }, aurora_public: {
                files: {
                    'EcomB2B/css/staging/style.theme_1.EcomB2B.desktop.css': 'sass/EcomB2B/style.theme_1.EcomB2B.scss',
                    'EcomB2B/css/staging/style.theme_1.EcomB2B.tablet.css': 'sass/EcomB2B/style.theme_1.EcomB2B.tablet.scss',
                    'EcomB2B/css/staging/style.theme_1.EcomB2B.mobile.css': 'sass/EcomB2B/style.theme_1.EcomB2B.mobile.scss',
                }
            }, login: {
                files: {
                    'css/staging/login.css': 'sass/login.scss'
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false, roundingPrecision: -1, sourceMap: true,
            },

            pweb: {
                files: {
                    'EcomB2B/assets/desktop.min.css': [

                        'node_modules/@fortawesome/fontawesome-pro/css/all.css', 'EcomB2B/css/staging/style.theme_1.EcomB2B.desktop.css'],

                    'EcomB2B/assets/forms.min.css': ['EcomB2B/css/sweetalert.css', 'EcomB2B/css/sky-forms.css', 'EcomB2B/css/sky_forms.aurora.css'],


                    'EcomB2B/assets/image_gallery.min.css': ['EcomB2B/css/photoswipe.css', 'EcomB2B/css/photoswipe/default-skin.css'],


                    'EcomB2B/assets/mobile.min.css': ['node_modules/@fortawesome/fontawesome-pro/css/all.css', 'EcomB2B/css/mobile_style.css', 'EcomB2B/css/mobile_skin.css', 'EcomB2B/css/mobile_framework.css', 'EcomB2B/css/staging/style.theme_1.EcomB2B.mobile.css'],


                    'EcomB2B/assets/tablet.min.css': [

                        'node_modules/@fortawesome/fontawesome-pro/css/all.css', 'EcomB2B/css/mobile_style.css', 'EcomB2B/css/mobile_skin.css', 'EcomB2B/css/mobile_framework.css',

                        'EcomB2B/css/staging/style.theme_1.EcomB2B.tablet.css'],


                }
            },

            au: {
                files: {
                    'assets/au_app.min.css': ['css/jquery-ui.css', 'node_modules/@fortawesome/fontawesome-pro/css/all.css', 'css/intlTelInput.css', 'css/countrySelect.css', 'css/d3fc.css', 'css/backgrid.css', 'css/backgrid-filter.css', 'bower_components/select2/dist/css/select2.css',

                        'css/editor_v1/froala_editor.css', 'css/editor_v1/froala_style.css', 'css/editor_v1/codemirror.css', 'css/editor_v1/codemirror_dracula.css',

                        'css/editor_v1/plugins/char_counter.css', 'css/editor_v1/plugins/code_view.css', 'css/editor_v1/plugins/colors.css', 'css/editor_v1/plugins/emoticons.css', 'css/editor_v1/plugins/file.css', 'css/editor_v1/plugins/fullscreen.css', 'css/editor_v1/plugins/image.css', 'css/editor_v1/plugins/image_manager.css', 'css/editor_v1/plugins/line_breaker.css', 'css/editor_v1/plugins/quick_insert.css', 'css/editor_v1/plugins/table.css', 'css/editor_v1/plugins/video.css', 'css/editor_v1/plugins/draggable.css', 'css/amcharts/style.css', 'css/jquery.fancybox.min.css', 'css/tooltipster.bundle.min.css', 'css/staging/app.css']

                }
            },

            au_login: {
                files: {
                    'assets/login.min.css': ['node_modules/@fortawesome/fontawesome-pro/css/all.css', 'css/staging/login.css']

                }
            }
        },
        copy: {

            fa_webfonts: {
                files: [{
                    expand: true, cwd: 'node_modules/@fortawesome/fontawesome-pro/webfonts/',
                    src: ['*'],
                    dest: 'webfonts/'
                }

                ]
            },


            pweb_deployment_step1: {
                files: [
                    {
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['{,*/}*.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.tmp');
                    }
                },
                    {
                        expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['{,*/}*.min.css'], rename: function (dest, src) {
                            return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.tmp');
                        }
                    }

                ]
            }, pweb_deployment_step3: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['{,*/}*.tmp.*'], rename: function (dest, src) {
                        return dest + src.replace('.tmp.', '.min.');
                    }
                }]
            },

            au_deployment_step1: {
                files: [
                    {
                    expand: true, dot: true, cwd: 'assets', dest: 'assets/', src: ['{,*/}*.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.tmp');
                    }
                },
                    {
                    expand: true, dot: true, cwd: 'assets', dest: 'assets/', src: ['{,*/}*.min.css'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.tmp');
                    }
                }
                ]
            }, au_deployment_step3: {
                files: [{
                    expand: true, dot: true, cwd: 'assets', dest: 'assets/', src: ['{,*/}*.tmp.*'], rename: function (dest, src) {
                        return dest + src.replace('.tmp.', '.min.');
                    }
                }]
            },

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
                }, {
                    expand: true, src: ['trait.*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['conf/*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['nano_services/*.php'], dest: '../fork/'
                }, {
                    expand: true, src: ['conf/fields/*.php'], dest: '../fork/'
                }, {
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
        replace: {
            aurora_version: {
                src: ['templates/app.tpl'], overwrite: true, replacements: [{
                    from: /<div class="aurora_version full">(.*)<\/div>/g,
                    to: '<div class="aurora_version full">v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '</div>'
                }, {
                    from: /<div class="aurora_version small">(.*)<\/div>/g,
                    to: '<div class="aurora_version small"><div>v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '</div><div>' + grunt.option('au_version_patch') + '</div></div>'
                }

                ]
            },
            css: {
                src: ['templates/app.tpl'], overwrite: true, replacements: [{
                    from: /au_app.\.*min.css"/g, to: 'au_app.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                }

                ]
            }, js_libs: {
                src: ['templates/app.tpl'], overwrite: true, replacements: [{
                    from: /aurora_libs.\.*min.js"/g, to: 'aurora_libs.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, js_login_libs: {
                src: ['templates/login.tpl'], overwrite: true, replacements: [{
                    from: /login_libs.\.*min.js"/g, to: 'login_libs.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, js: {
                src: ['templates/app.tpl'], overwrite: true, replacements: [{
                    from: /aurora.\.*min.js"/g, to: 'aurora.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, js_login: {
                src: ['templates/login.tpl'], overwrite: true, replacements: [{
                    from: /login.\.*min.js/g, to: 'login.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }

            , ecom_desktop_in: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /desktop.in.min.js\.*"/g, to: 'desktop.in.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
            }, ecom_desktop_logged_in: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /desktop.logged_in.min.js\.*"/g, to: 'desktop.logged_in.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
            }, ecom_desktop_out: {
                src: ['EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /desktop.out.min.js\.*"/g, to: 'desktop.out.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
            }, ecom_images: {
                src: ['EcomB2B/templates/theme_1/webpage_blocks.theme_1.*tpl'], overwrite: true, replacements: [{
                    from: /image_gallery.min.js\.*"/g, to: 'image_gallery.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
            }, ecom_desktop_forms: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /desktop.forms.min.js\.*"/g, to: 'desktop.forms.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
            }, ecom_desktop_basket_checkout: {
                src: ['EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /desktop.basket.min.js\.*"/g, to: 'desktop.basket.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }, {
                    from: /mobile.basket.min.js\.*"/g, to: 'mobile.basket.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }, {
                    from: /desktop.checkout.min.js\.*"/g, to: 'desktop.checkout.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }, {
                    from: /mobile.checkout.min.js\.*"/g, to: 'mobile.checkout.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }, {
                    from: /desktop.profile.min.js\.*"/g, to: 'desktop.profile.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }, {
                    from: /mobile.profile.min.js\.*"/g, to: 'mobile.profile.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }

                ]
            }, ecom_mobile_in: {
                src: ['templates/theme_1/website.header.mobile.theme_1.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /mobile.logged_in.min.js\.*"/g, to: 'mobile.logged_in.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
            }, ecom_mobile_forms: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.*tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /mobile.forms.min.js\.*"/g, to: 'mobile.forms.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
            }, mobile_custom: {
                src: ['templates/theme_1/website.header.mobile.theme_1.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /mobile_custom.min.js\.*"/g, to: 'mobile_custom.min.js?=v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '"'
                }]
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
    grunt.loadNpmTasks('grunt-text-replace');


    grunt.registerTask('pweb_deployment', ['copy:pweb_deployment_step1', 'clean:pweb_deployment_step2', 'copy:pweb_deployment_step3', 'clean:pweb_deployment_step4',]);
    grunt.registerTask('au_deployment', ['copy:au_deployment_step1', 'clean:au_deployment_step2', 'copy:au_deployment_step3', 'clean:au_deployment_step4',]);


    grunt.registerTask('fork', ['copy:fork_stones', 'copy:fork']);
    grunt.registerTask('qfork', ['copy:fork']);


    grunt.registerTask('au_sass', ['sass:aurora', 'sass:aurora_public', 'sass:login']);
    grunt.registerTask('au_css', ['cssmin:au', 'cssmin:au_login']);
    grunt.registerTask('au_js', ['uglify:aurora_libs', 'uglify:login', 'uglify:aurora']);

    grunt.registerTask('au', ['copy:fa_webfonts', 'sass:aurora', 'sass:login', 'cssmin:au', 'cssmin:au_login', 'uglify:aurora_libs', 'uglify:login_libs', 'uglify:login', 'uglify:aurora']);


    grunt.registerTask('pweb', ['copy:fa_webfonts', 'sass:aurora_public', 'cssmin:pweb',

        'uglify:pweb_mobile_logged_in', 'uglify:pweb_mobile_forms', 'uglify:pweb_mobile_profile', 'uglify:pweb_mobile_basket', 'uglify:pweb_mobile_checkout', 'uglify:pweb_tablet', 'uglify:pweb_tablet_custom', 'uglify:pweb_common_desktop_logged_in', 'uglify:pweb_common_desktop_logged_out', 'uglify:pweb_desktop_logged_in', 'uglify:pweb_desktop_forms', 'uglify:pweb_desktop_profile', 'uglify:pweb_desktop_basket', 'uglify:pweb_desktop_checkout', 'uglify:pweb_desktop_image_gallery']);

};
