'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
 
gulp.task('sass', function () {
  gulp.src('./webroot/sass/styles.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./webroot/css'));
});
 
gulp.task('sass:watch', function () {
  gulp.watch('./webroot/sass/**/*.scss', ['sass']);
});