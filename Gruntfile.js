module.exports = function(grunt) {

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        concat: {
            scripts: {
                src: [
                "template/js/vendor/jquery-1.10.1.min.js",
                "template/js/main.js"
                ],
                dest: 'template/build/production.min.js',
            },
            styles: {
                src: [
                'template/css/normalize.min.css',
                'template/css/main.css'
                ],
                dest: 'template/build/production.min.css',
            }
        },
        uglify: {
            build: {
                options: {
                    banner: "/* Glav.in scripts */"
                },
                src: 'template/build/production.min.js',
                dest: 'template/build/production.min.js'
            }
        },
        cssmin: {
            production: {
                options: {
                    banner: '/* Glav.in styles */ '
                },
                files: {
                    'template/build/production.min.css': ['template/build/production.min.css']
                }
            }
        },

        jslint: {
            client: {
                directives: {
                    browser: true,
                    predef: [
                    'jQuery'
                    ]
                },
                src: 'template/js/main.js'
            }
        },



        watch: {
            scripts: {
                files: ['template/js/**/*.js'],
                tasks: ['concat:scripts', 'uglify'],
                options: {
                    livereload: true,
                    spawn: false,
                },
            },
            styles: {
                files: ['template/css/**/*.css'],
                tasks: ['concat:styles', 'cssmin'],
                options: {
                    livereload: true,
                    spawn: false,
                },
            }

        }

    });

grunt.loadNpmTasks('grunt-contrib-concat');
grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-cssmin');
grunt.loadNpmTasks('grunt-jslint');
grunt.loadNpmTasks('grunt-contrib-watch');


grunt.registerTask('default', ['watch']);

};