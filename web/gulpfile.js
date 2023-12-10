var gulp = require('gulp');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var postcss =  require('gulp-postcss');
var autoprefixer =  require('autoprefixer');
var assets =  require('postcss-assets');
var cssdeclsort = require('css-declaration-sorter');
var mqpacker =  require('css-mqpacker');

gulp.task('sass', function() {
  return gulp.src('./sass/**/*.scss')
    .pipe(plumber({errorHandler: notify.onError("Error: <%= error.message %>")}))
    .pipe(sass({outputStyle: 'expanded'}))
    .pipe(postcss([autoprefixer()]))
    .pipe(postcss([assets({
      loadPaths: ['images/'],
      relative: true
    })]))
    .pipe(postcss([cssdeclsort({order: 'smacss'})]))
    .pipe(postcss([mqpacker()]))
    .pipe(gulp.dest('./css'))
});

gulp.task('sass:watch', function() {
  gulp.watch('./sass/**/*.scss', ['sass']);
});
