module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            dist: {
                src: ['js/*.js', ],
                dest: 'build/js/aurora.js',
            }
        },
        uglify: {
            build: {
                src: 'build/js/aurora.js',
                dest: 'build/js/aurora.min.js',
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['concat','uglify']);

};
