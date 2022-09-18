const gulp = require ('gulp');
const cleanCSS = require('gulp-clean-css');
const uglify = require('gulp-uglify');
const uglyfly = require('gulp-uglyfly');

var pipeline = require('readable-stream').pipeline;
const autoprefixer = require('gulp-autoprefixer');
const del = require('del');
const browserSync = require('browser-sync').create();

const sass = require ('gulp-sass')(require('sass'));
const rename = require ('gulp-rename');

gulp.task('clone-js', function() {
  return gulp.src('app/views/js/*.js')
      .pipe(rename({suffix: '.min'}))
      .pipe(gulp.dest('public/js/'));
});

  gulp.task('deleteFilesCSS', function() {
    return del(['public/css/*.*']);
  });

  gulp.task('sassToCSS', function() {
    return gulp.src('app/views/scss/styles.scss')
      .pipe(sass({
        errorLogToConsole: true
        // ,outputStyle: 'compressed'
      }))
      .on('error', console.error.bind(console))
      .pipe(autoprefixer({
        overrideBrowserslist: ['last 5 versions'],
        cascade: false
      }))
      // .pipe(cleanCSS())
      .pipe(rename({suffix: '.min'}))
      .pipe(gulp.dest('public/css/'));
  });

gulp.task('build_css', gulp.series('deleteFilesCSS', 'sassToCSS'));

gulp.task('watchAll', function() {
    gulp.watch(['app/views/scss/*.scss',
          'app/views/js/*.js',
        ]
    , gulp.series('build_css'));
})