module.exports = function (grunt) {

    grunt.initConfig({

        clean: {
            fork: ["../fork/*", "!../fork/keyring/**", "!../fork/server_files/**"],
            css: ["assets/images"],
            assets:["assets/*min*","EcomB2B/assets/**"]

        },
        terser: {
            ecom_desktop_in: {
                options: {
                    sourceMap: true,
                },
                src: ['js_libs/autobahn.v1.js','EcomB2B/js/aurora/web_sockets.js','EcomB2B/js/libs/jquery.hoverIntent.js', 'EcomB2B/js/au_header/menu.js', 'EcomB2B/js/au_header/search.js'],
                dest: 'EcomB2B/assets/desktop.in.min.js'
            }, ecom_desktop_out: {
                options: {
                    sourceMap: true,
                },
                src: ['EcomB2B/js/libs/jquery.js', 'EcomB2B/js/libs/jquery.hoverIntent.js', 'EcomB2B/js/au_header/menu.js', 'EcomB2B/js/au_header/search.js'],
                dest: 'EcomB2B/assets/desktop.out.min.js'
            }, ecom_desktop_logged_in: {
                options: {
                    sourceMap: true,},
                src: ['EcomB2B/js/aurora/validation.EcomB2B.js', 'EcomB2B/js/aurora/aurora.logged_in.js', 'EcomB2B/js/aurora/ordering.js','EcomB2B/js/aurora/logout.js',
                ], dest: 'EcomB2B/assets/desktop.logged_in.min.js',
            }, ecom_dropshipping_logged_in: {
                options: {
                    sourceMap: true,},
                src: ['EcomB2B/js/dropshipping/portfolio.js','EcomB2B/js/dropshipping/notifications.js','EcomB2B/js/dropshipping/logout.js','EcomB2B/js/dropshipping/dropshipping.js'
                ], dest: 'EcomB2B/assets/dropshipping.logged_in.min.js',
            }, ecom_image_gallery: {
                options: {
                    sourceMap: true,
                }, src: [
                    'EcomB2B/js/images/photoswipe.js', 'EcomB2B/js/images/photoswipe-ui-default.js',
                ], dest: 'EcomB2B/assets/image_gallery.min.js',
            }, ecom_datatables: {
                options: {
                    sourceMap: true,

                }, src: [
                    'js_libs/underscore.min.js', 'js_libs/backbone.min.js', 'js_libs/backbone.paginator.js', 'js_libs/backgrid.js', 'js_libs/backgrid-filter.js','js/table.js'
                ], dest: 'EcomB2B/assets/datatables.min.js',
            }, ecom_desktop_forms: {
                options: {
                    sourceMap: true,
                },
                src: ['EcomB2B/js/libs/jquery-ui.js', 'EcomB2B/js/libs/jquery.form.min.js', 'EcomB2B/js/libs/jquery.validate.min.js', 'EcomB2B/js/libs/additional-methods.min.js', 'EcomB2B/js/libs/sweetalert.min.js', 'EcomB2B/js/libs/sha256.js', 'EcomB2B/js/au_forms/aurora_forms.js'],
                dest: 'EcomB2B/assets/desktop.forms.min.js'
            }, ecom_desktop_client_basket: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/client_basket.js'],
                dest: 'EcomB2B/assets/desktop.client_basket.min.js'
            }, ecom_desktop_basket: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/basket.js', 'EcomB2B/js/basket_checkout/order_totals.js',],
                dest: 'EcomB2B/assets/desktop.basket.min.js'
            }, ecom_desktop_checkout: {
                options: {
                    sourceMap: true,
                }, src: [
                    'bower_components/braintree-web/paypal-checkout.js', 'bower_components/braintree-web/client.js', 'bower_components/braintree-web//hosted-fields.js', 'EcomB2B/js/basket_checkout/checkout.js', 'EcomB2B/js/basket_checkout/order_totals.js',
                ], dest: 'EcomB2B/assets/desktop.checkout.min.js'
            }, ecom_desktop_profile: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/order_totals.js',], dest: 'EcomB2B/assets/desktop.profile.min.js'
            }, ecom_mobile_in: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/aurora_mobile/validation.EcomB2B.js', 'EcomB2B/js/aurora_mobile/aurora.logged_in.mobile.js', 'EcomB2B/js/aurora_mobile/ordering.touch.js'

                ], dest: 'EcomB2B/assets/mobile.logged_in.min.js',

            }, ecom_mobile_forms: {
                options: {
                    sourceMap: true,
                },
                src: ['EcomB2B/js/libs/jquery-ui.js', 'EcomB2B/js/libs/jquery.form.min.js', 'EcomB2B/js/libs/jquery.validate.min.js', 'EcomB2B/js/libds/additional-methods.min.js', 'EcomB2B/js/libs/sweetalert.min.js', 'EcomB2B/js/libs/sha256.js', 'EcomB2B/js/au_forms/aurora_forms.js'],
                dest: 'EcomB2B/assets/mobile.forms.min.js',

            }, ecom_mobile_basket: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/basket.js', 'EcomB2B/js/basket_checkout/order_totals.js'], dest: 'EcomB2B/assets/mobile.basket.min.js'
            }, ecom_mobile_profile: {
                options: {
                    sourceMap: true,
                }, src: ['EcomB2B/js/basket_checkout/order_totals.js'], dest: 'EcomB2B/assets/mobile.profile.min.js'
            }, ecom_mobile_checkout: {
                options: {
                    sourceMap: true,
                },
                src: [
                    'bower_components/braintree-web/paypal-checkout.js', 'bower_components/braintree-web/client.js','bower_components/braintree-web//hosted-fields.js', 'EcomB2B/js/basket_checkout/checkout.js', 'EcomB2B/js/basket_checkout/order_totals.js',],
                dest: 'EcomB2B/assets/mobile.checkout.min.js'

            }, ecom_mobile: {
                options: {

                    sourceMap: true,
                }, src: [ 'EcomB2B/js/libs/mobile_plugins.js', 'EcomB2B/js/au_header/search.js'],
                dest: 'EcomB2B/assets/mobile.min.js',

            }, ecom_mobile_custom: {
                options: {

                    sourceMap: true,
                }, src: [

                    'EcomB2B/js/libs/mobile_custom.js'],
                dest: 'EcomB2B/assets/mobile_custom.min.js',

            }, aurora_libs: {
                options: {

                    sourceMap: true,
                }, src: ['bower_components/jquery/dist/jquery.js', 'js_libs/jquery-migrate-3.0.1.js', 'js_libs/jquery-ui.1.12.1.js', 'js_libs/jquery.nice-select.js',


                    'bower_components/moment/min/moment-with-locales.js', 'bower_components/moment-timezone/builds/moment-timezone-with-data-2012-2022.js', 'bower_components/select2/dist/js/select2.js', //'js_libs/moment-with-locales.js',
                    //'js_libs/moment-timezone-with-data.js',

                    'js_libs/chrono.js', 'js_libs/sha256.js',
                    'js_libs/underscore.min.js', 'js_libs/backbone.min.js', 'js_libs/backbone.paginator.js', 'js_libs/backgrid.js', 'js_libs/backgrid-filter.js',
                    'js_libs/snap.svg.js', 'js_libs/svg-dial.js', 'js_libs/countrySelect.js', 'js_libs/intlTelInput-jquery.14.0.6.js',

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

                    'js/mixed_recipients.edit.js', 'js/search.js', 'js/table.js', 'js/validation.js', 'js/pdf.js', 'js/edit_webpage_edit.js', 'js/new.js',
                    'js/order.common.js', 'js/order_collection.js', 'js/location_parts.js','js/dropshipping.js',

                    'js/email_campaign.common.js',
                    'js/new_marketing_mailshot.js',
                    'js/inline_editing.js','js/dashboard.js',
                    'js/supplier.order.js', 'js/supplier.delivery.js','js/supplier.delivery.costing.js',
                    'js/part_locations.edit.js', 'js/part_locations.edit_locations.js', 'js/part_locations.stock_check.js', 'js/part_locations.move_stock.js', 'js/fast_track_packing.js', 'js/sticky_notes.js', 'js/picking_and_packing.js', 'js/app.js', 'js/real_time.js', 'js/customers.js', 'js/customer_orders.js', 'js/customer_client.js', 'js/customer_client_orders.js',
                    'js/add_item_to_order.js','js/upload.js','js/islands.js'


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
                    'css/staging/app.css': 'sass/au/app.scss',
                    'css/staging/app.mobile.css': 'sass/au/app.mobile.scss',

                }
            }, ecom_css: {
                files: {
                    'EcomB2B/css/staging/style.theme_1.EcomB2B.desktop.css': 'sass/EcomB2B/style.theme_1.EcomB2B.scss',
                    'EcomB2B/css/staging/style.theme_1.EcomB2B.tablet.css': 'sass/EcomB2B/style.theme_1.EcomB2B.tablet.scss',
                    'EcomB2B/css/staging/style.theme_1.EcomB2B.mobile.css': 'sass/EcomB2B/style.theme_1.EcomB2B.mobile.scss',
                }
            }, login: {
                files: {
                    'css/staging/login.css': 'sass/au/login.scss'
                }
            }
        },
        cssmin: {
            options: {
                 sourceMap: true
            },

            ecom_css: {
                files: {
                    'EcomB2B/assets/desktop.min.css': [
                        'node_modules/@fortawesome/fontawesome-pro/css/all.css', 'EcomB2B/css/staging/style.theme_1.EcomB2B.desktop.css'],
                    'EcomB2B/assets/datatables.min.css': [
                        'css/backgrid.css', 'css/backgrid-filter.css'],
                    'EcomB2B/assets/forms.min.css': [
                        'EcomB2B/css/sweetalert.css', 'EcomB2B/css/sky-forms.css', 'EcomB2B/css/sky_forms.aurora.css'],
                    'EcomB2B/assets/image_gallery.min.css': [
                        'EcomB2B/css/photoswipe.css', 'EcomB2B/css/photoswipe/default-skin.css'],
                    'EcomB2B/assets/mobile.min.css': [
                        'node_modules/@fortawesome/fontawesome-pro/css/all.css', 'EcomB2B/css/mobile_style.css', 'EcomB2B/css/mobile_skin.css', 'EcomB2B/css/mobile_framework.css', 'EcomB2B/css/staging/style.theme_1.EcomB2B.mobile.css'],
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




            au_css: {
                files: [{
                    expand: true, dot: true, cwd: 'assets', dest: 'assets/', src: [
                        'au_app.min.css','login.min.css'
                    ], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },



            au_js: {
                files: [{
                    expand: true, dot: true, cwd: 'assets', dest: 'assets/', src: [
                        'login.min.js','aurora.min.js'
                    ], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },

            au_libs: {
                files: [{
                    expand: true, dot: true, cwd: 'assets', dest: 'assets/', src: [
                        'login_libs.min.js','aurora_libs.min.js'
                    ], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },

            ecom_desktop_in: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['desktop.in.min.js'], rename: function (dest, src) {
                            return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                        }
                    }]
            },
            ecom_desktop_out: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['desktop.out.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_datatables: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['datatables.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_desktop_forms: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['desktop.forms.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_mobile_forms: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['mobile.forms.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_mobile: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['mobile.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_mobile_custom: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['mobile_custom.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_desktop_logged_in: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['desktop.logged_in.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_dropshipping_logged_in: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['dropshipping.logged_in.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_image_gallery: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['image_gallery.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_basket_checkout: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: [
                        'desktop.basket.min.js', 'desktop.client_basket.min.js','desktop.checkout.min.js','desktop.profile.min.js','mobile.basket.min.js','mobile.checkout.min.js','mobile.profile.min.js'
                    ], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },
            ecom_mobile_in: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: ['mobile.logged_in.min.js'], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
                    }
                }]
            },

            ecom_css: {
                files: [{
                    expand: true, dot: true, cwd: 'EcomB2B/assets', dest: 'EcomB2B/assets/', src: [
                        'desktop.min.css','forms.min.css','image_gallery.min.css','mobile.min.css','tablet.min.css','datatables.min.css'
                    ], rename: function (dest, src) {
                        return dest + src.replace('.min', '.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min');
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
                    expand: true, src: ['templates/stop_junk_email*.tpl'], dest: '../fork/'
                }, {
                    expand: true, src: ['templates/notification_emails/*.tpl'], dest: '../fork/'
                }

                ],
            },


        },
        replace: {
            aurora_version: {
                src: ['templates/app.tpl','templates/login.tpl','utils/sentry.php','EcomB2B/sentry.php','EcomB2B/templates/theme_1/_head.theme_1.EcomB2B*'], overwrite: true, replacements: [{
                    from: /<div class="aurora_version full">(.*)<\/div>/g,
                    to: '<div class="aurora_version full">v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '</div>'
                }, {
                    from: /<div class="aurora_version small">(.*)<\/div>/g,
                    to: '<div class="aurora_version small"><div>v' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '</div><div>' + grunt.option('au_version_patch') + '</div></div>'
                }, {
                    from: /__AURORA_RELEASE__/g,
                    to: grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch')
                }

                ]
            },
            au_css: {
                src: ['templates/app.tpl','templates/login.tpl'], overwrite: true, replacements: [
                    {
                        from: /au_app.\.*min.css"/g, to: 'au_app.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                    },
                    {
                        from: /login.\.*min.css"/g, to: 'login.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
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
                    from: /desktop.in.\.*min.js"/g, to: 'desktop.in.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, ecom_desktop_logged_in: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /desktop.logged_in.\.*min.js"/g, to: 'desktop.logged_in.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            },ecom_dropshipping_logged_in: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /dropshipping.logged_in.\.*min.js"/g, to: 'dropshipping.logged_in.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, ecom_desktop_out: {
                src: ['EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /desktop.out.\.*min.js"/g, to: 'desktop.out.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, ecom_image_gallery: {
                src: ['EcomB2B/templates/theme_1/webpage_blocks.theme_1.*tpl'], overwrite: true, replacements: [{
                    from: /image_gallery.\.*min.js"/g, to: 'image_gallery.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, ecom_datatables: {
                src: ['EcomB2B/templates/theme_1/webpage_blocks.theme_1.*tpl'], overwrite: true, replacements: [{
                    from: /datatables.\.*min.js"/g, to: 'datatables.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            },ecom_desktop_forms: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.tpl'], overwrite: true, replacements: [{
                    from: /desktop.forms.\.*min.js"/g, to: 'desktop.forms.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, ecom_basket_checkout: {
                src: ['EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /desktop.basket.\.*min.js"/g, to: 'desktop.basket.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                },{
                    from: /desktop.client_basket.\.*min.js"/g, to: 'desktop.client_basket.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }, {
                    from: /mobile.basket.\.*min.js"/g, to: 'mobile.basket.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }, {
                    from: /desktop.checkout.\.*min.js"/g, to: 'desktop.checkout.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }, {
                    from: /mobile.checkout.\.*min.js"/g, to: 'mobile.checkout.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }, {
                    from: /desktop.profile.\.*min.js"/g, to: 'desktop.profile.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }, {
                    from: /mobile.profile.\.*min.js"/g, to: 'mobile.profile.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }

                ]
            }, ecom_mobile_in: {
                src: ['templates/theme_1/website.header.mobile.theme_1.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /mobile.logged_in.\.*min.js"/g, to: 'mobile.logged_in.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            }, ecom_mobile_forms: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.*tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /mobile.forms.\.*min.js"/g, to: 'mobile.forms.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            },
            ecom_mobile_custom: {
                src: ['templates/theme_1/website.header.mobile.theme_1.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /mobile_custom.\.*min.js"/g, to: 'mobile_custom.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            },
            ecom_mobile: {
                src: ['templates/theme_1/website.header.mobile.theme_1.tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl'], overwrite: true, replacements: [{
                    from: /mobile.\.*min.js"/g, to: 'mobile.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.js"'
                }]
            },
            ecom_css: {
                src: ['EcomB2B/templates/theme_1/_head.theme_1.EcomB2B.*tpl', 'EcomB2B/templates/theme_1/webpage_blocks.theme_1.EcomB2B.*tpl','theme_1/_head.theme_1.mobile.tpl','theme_1/_head.theme_1.tpl'], overwrite: true, replacements: [
                    {
                        from: /desktop.\.*min.css"/g, to: 'desktop.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                    },
                    {
                        from: /datatables.\.*min.css"/g, to: 'datatables.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                    },
                    {
                        from: /forms.\.*min.css"/g, to: 'forms.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                    },
                    {
                        from: /image_gallery.\.*min.css"/g, to: 'image_gallery.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                    },
                    {
                        from: /mobile.\.*min.css"/g, to: 'mobile.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                    },
                    {
                        from: /tablet.\.*min.css"/g, to: 'tablet.' + grunt.option('au_version_major') + '.' + grunt.option('au_version_minor') + '.' + grunt.option('au_version_patch') + '.min.css"'
                    },


                ]
            },




        }

    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-terser');

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-text-replace');



    grunt.registerTask('ecom_desktop_in', ['terser:ecom_desktop_in', 'copy:ecom_desktop_in', 'replace:ecom_desktop_in']);
    grunt.registerTask('ecom_desktop_out', ['terser:ecom_desktop_out', 'copy:ecom_desktop_out', 'replace:ecom_desktop_out']);
    grunt.registerTask('ecom_desktop_forms', ['terser:ecom_desktop_forms', 'copy:ecom_desktop_forms', 'replace:ecom_desktop_forms']);
    grunt.registerTask('ecom_mobile_forms', ['terser:ecom_mobile_forms', 'copy:ecom_mobile_forms', 'replace:ecom_mobile_forms']);
    grunt.registerTask('ecom_mobile', ['terser:ecom_mobile', 'copy:ecom_mobile', 'replace:ecom_mobile']);
    grunt.registerTask('ecom_mobile_custom', ['terser:ecom_mobile_custom', 'copy:ecom_mobile_custom', 'replace:ecom_mobile_custom']);
    grunt.registerTask('ecom_datatables', ['terser:ecom_datatables', 'copy:ecom_datatables', 'replace:ecom_datatables']);



    grunt.registerTask('ecom_libs_headers', ['ecom_desktop_in','ecom_desktop_out','ecom_desktop_forms','ecom_mobile_forms','ecom_mobile','ecom_mobile_custom','ecom_datatables']);

    grunt.registerTask('ecom_libs_headers_replace', ['replace:ecom_desktop_in','replace:ecom_desktop_out','replace:ecom_desktop_forms','replace:ecom_mobile_forms','replace:ecom_mobile','replace:ecom_mobile_custom','replace:ecom_datatables']);


    grunt.registerTask('ecom_desktop_logged_in', ['terser:ecom_desktop_logged_in', 'copy:ecom_desktop_logged_in', 'replace:ecom_desktop_logged_in']);
    grunt.registerTask('ecom_dropshipping_logged_in', ['terser:ecom_dropshipping_logged_in', 'copy:ecom_dropshipping_logged_in', 'replace:ecom_dropshipping_logged_in']);


    grunt.registerTask('ecom_image_gallery', ['terser:ecom_image_gallery', 'copy:ecom_image_gallery', 'replace:ecom_image_gallery']);

    grunt.registerTask('ecom_basket_checkout', ['terser:ecom_desktop_basket', 'terser:ecom_desktop_client_basket', 'terser:ecom_desktop_checkout', 'terser:ecom_desktop_profile',
        'terser:ecom_mobile_basket', 'terser:ecom_mobile_profile', 'terser:ecom_mobile_checkout','copy:ecom_basket_checkout', 'replace:ecom_basket_checkout'

    ]);

    grunt.registerTask('ecom_mobile_in', ['terser:ecom_mobile_in', 'copy:ecom_mobile_in', 'replace:ecom_mobile_in']);


    grunt.registerTask('ecom_css', ['cssmin:ecom_css',  'copy:ecom_css', 'replace:ecom_css']);

    grunt.registerTask('au_sass', ['sass:aurora', 'sass:login']);
    grunt.registerTask('cssmin_au_css', ['cssmin:au', 'cssmin:au_login']);

    grunt.registerTask('au_css', ['cssmin_au_css',  'copy:au_css', 'replace:au_css']);


    grunt.registerTask('au_libs', ['terser:aurora_libs', 'terser:login_libs','copy:au_libs', 'replace:js_libs', 'replace:js_login_libs']);
    grunt.registerTask('au_js', ['terser:aurora', 'terser:login','copy:au_js', 'replace:js', 'replace:js_login']);



    grunt.registerTask('fork', ['copy:fork_stones', 'copy:fork']);
    grunt.registerTask('qfork', ['copy:fork']);




    grunt.registerTask('au', ['copy:fa_webfonts', 'sass:aurora', 'sass:login', 'cssmin:au', 'cssmin:au_login', 'terser:aurora_libs', 'terser:login_libs', 'terser:login', 'terser:aurora']);


    grunt.registerTask('pweb', ['copy:fa_webfonts', 'sass:ecom_css', 'cssmin:ecom_css',
            'terser:ecom_desktop_in','terser:ecom_desktop_out','terser:ecom_desktop_forms','terser:ecom_mobile_forms','terser:ecom_mobile','terser:ecom_mobile_custom','terser:ecom_datatables',
            'terser:ecom_desktop_logged_in', 'terser:ecom_dropshipping_logged_in','terser:ecom_image_gallery','terser:ecom_desktop_basket','terser:ecom_desktop_client_basket', 'terser:ecom_desktop_checkout', 'terser:ecom_desktop_profile',
        'terser:ecom_mobile_basket', 'terser:ecom_mobile_profile', 'terser:ecom_mobile_checkout','terser:ecom_mobile_in'

    ]);

};
