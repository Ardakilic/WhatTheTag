'use strict';


//WhatTheTag-specific configurations
let less = require('gulp-less'),
    notify = require('gulp-notify'),
    del = require('del'),
    minifyCSS = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    runSequence = require('run-sequence'),
    concat = require('gulp-concat');

let wtt = {
    jsPath            : './resources/assets/js',
    lessPath        : './resources/assets/less',
    nodePath        : './node_modules',
    tempPath        : './temp_dir',
    public: {
        fontPath    : './public/fonts',
        cssPath        : './public/css',
        jsPath        : './public/js' 
    }
};

let cssFiles = [
    '/bootstrap/dist/css/bootstrap.min.css',
    '/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.min.css',
    '/font-awesome/css/font-awesome.min.css',
    '/bootstrap-social/bootstrap-social.css',
    '/bootstrap-tagsinput/dist/bootstrap-tagsinput.css',
    '/dropify/dist/css/dropify.min.css'
];
cssFiles = cssFiles.map(function(el) { 
    return wtt.nodePath + el; 
});

let javaScripts = [
    '/jquery/dist/jquery.min.js',
    '/bootstrap/dist/js/bootstrap.min.js',
    '/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
    '/datatables/media/js/jquery.dataTables.min.js',
    '/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js',
    '/social-share-js/dist/jquery.socialshare.min.js',
    '/dropify/dist/js/dropify.min.js'
];

javaScripts = javaScripts.map(function(el) { 
    return wtt.nodePath + el; 
});
//WhatTheTag-specific configurations END

let config = new sey.config();

config.bundle('global')
    .set({
        babel: {
        },

        eslint: {
        },

        less: {
        }
    });


//Build fonts
config.bundle('main')
    .set({
        clean: {
            beforeBuild: wtt.public.fontPath
        }
    })
    .src([
        wtt.nodePath + '/font-awesome/fonts/**.*', 
        wtt.nodePath + '/bootstrap/fonts/**.*',
        wtt.nodePath + '/dropify/dist/fonts/**.*'
    ])
    .dest(wtt.public.fontPath)
    .exec();
//Build fonts END

//Build JavaScripts

//vendor.min.js
config.bundle('main')
    .set({
        clean: {
            beforeBuild: wtt.tempPath
        }
    })
    .src(wtt.JavaScripts)
    .jsoptimize()
    .dest(wtt.tempPath + 'vendor.min.js')
    .exec();

//app-specific.min.js
config.bundle('main')
    /*.set({
        clean: {
            beforeBuild: wtt.tempPath
        }
    })*/
    .src([
        wtt.lessPath + '/**/*.less',
        wtt.lessPath + '/**/*.js'
    ])
    .jsoptimize()
    .dest(wtt.tempPath + 'app-specific.min.js')
    .exec();

//app.min.js
config.bundle('main')
    .set({
        clean: {
            beforeBuild: wtt.public.jsPath
        }
    })
    .src([
        wtt.tempPath + 'vendor.min.js',
        wtt.tempPath + 'app-specific.min.js'
    ])
    .concat('app.min.js')
    .dest(wtt.public.jsPath)
    .exec();
//Build JavaScripts END


//Build CSS Files

//vendor.css
config.bundle('main')
    .set({
        clean: {
            beforeBuild: wtt.public.cssPath
        }
    })
    .src(cssFiles.concat(wtt.lessPath + '/**/*.less'))
    .less()
    .cssminify()
    .concat('app.min.css')
    .dest(wtt.public.cssPath)
    .exec();
//Build CSS Files END

sey.run(config);