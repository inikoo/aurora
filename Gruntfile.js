module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),



        concat: {
            js_libs: {
                src: ['js/jquery-2.2.1.js', 'js/jquery-ui.js', 'js/moment-with-locales.js', 'js/chrono.js', 'js/sha256.js', 'js/underscore.js', 'js/backbone.js', 'js/backbone.paginator.js', 'js/backgrid.js', 'js/backgrid-filter.js', 'js/intlTelInput.js', 'js/d3.js', 'js/d3fc.layout.js', 'js/d3fc.js'],
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
            injection1: {
                src: ['js/employee.new.js'],
                dest: 'build/js/employee.new.js'
            },
            injection2: {
                src: ['js/billingregion_taxcategory.js'],
                dest: 'build/js/billingregion_taxcategory.min.js'
            },
            injection3: {
                src: ['js/customer.details.js'],
                dest: 'build/js/customer.details.min.js'
            },
            injection4: {
                src: ['js/fire.js'],
                dest: 'build/js/fire.min.js'
            },
            injection5: {
                src: ['js/edit_images.js'],
                dest: 'build/js/edit_images.min.js'
            },
            injection6: {
                src: ['js/supplier.details.js'],
                dest: 'build/js/supplier.details.min.js'
            },
            injection7: {
                src: ['js/timesheet.records.js'],
                dest: 'build/js/timesheet.records.min.js'
            },
            injection8: {
                src: ['js/timesheets.days.js'],
                dest: 'build/js/timesheets.days.min.js'
            },


            login: {
                src: ['js/jquery-2.2.1.js', 'js/sha256.js', 'js/aes.js', 'js/login.js'],
                dest: 'build/js/login.min.js'
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


    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['concat', 'uglify', 'sass', 'cssmin', 'copy']);

};
