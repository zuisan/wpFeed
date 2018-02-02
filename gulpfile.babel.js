import gulp from 'gulp';
import gulpriot from 'gulp-riot';
import babelify from 'babelify';
import browserify from 'browserify';
import riotify from 'riotify';
import source from 'vinyl-source-stream';
import uglify from "gulp-uglify";

//postCss
import postcss from 'gulp-postcss';
import nested from 'postcss-nested';
import vars from 'postcss-simple-vars';
import _import from 'postcss-import';
import autoprefixer from 'autoprefixer';


var fs = require('fs-extra');
var rl = require('readline');

gulp.task('riot', () => {
    gulp
    .src(["./assets/riot/tag/*.tag"])
    .pipe(gulpriot({
        compact: true
    }))   
    .pipe(gulp.dest("./assets/riot/bild/"))
    .on('end',function(){
      return browserify({
        debug: true,
        entries: ['./assets/riot/main.js']
      })
        .transform(riotify)
        .transform(babelify, {presets: ["es2015-riot"]})
        .bundle()
        .pipe(source('main.bundle.js'))
        .pipe(gulp.dest('./public/riot'))
        .on('end',function(){
            gulp.src(["./public/riot/main.bundle.js"])
                .pipe(uglify({mangle: false}))
                .pipe(gulp.dest("./public/riot/min"));
        })
    })
});

   gulp.task('browserify',() => {
        browserify(["./assets/js/main.js"])
        .transform(babelify, { presets: ['es2015'] })
        .on("error", function (err) { console.log("Error : " + err.message); })
            .bundle()
            .pipe(source("main.js"))
            .pipe(gulp.dest("./public/js"))
            .on('end',function(){
                gulp.src(["./public/js/main.js"])
                    .pipe(uglify({mangle: false}))
                    .pipe(gulp.dest("./public/js/min"))
            })
    });


    let mod = [
        nested,
        autoprefixer({ browsers: ['android >= 4.1'] }),
        _import,
        vars
    ];
    gulp.task('css', () => {
      gulp.src( ['./assets/css/*.css'] )
        .pipe(postcss(mod))
       .pipe(gulp.dest('./public/css') );
    });



gulp.task('watch',function() {
    gulp.watch(['./assets/riot/tag/*.tag','./assets/**/*.js'], ['riot']);
    gulp.watch(['./assets/css/*.css','./assets/css/parts/*.css'], ['css']);
    //gulp.watch(['./assets/js/*.js','./assets/js/**/*.js'], ['browserify']);
});


    
  