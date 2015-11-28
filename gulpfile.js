var gulp = require('gulp');
var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');
var source = require('vinyl-source-stream');
var rename = require('gulp-rename');
var util = require('gulp-util');
var notifier = require('node-notifier');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');

var paths = {
    sass: ['./lib/sass/**/*.scss']
}

var standardHandler = function (err){
  notifier.notify({ message: 'Error: ' + err.message });
  util.log(util.colors.red('Error'), err);
  this.emit('end');
}

gulp.task('default', ['sass', 'pot']);

gulp.task('sass', function (done) {
    gulp.src('./lib/sass/srr-settings.scss')
        .pipe(sass())
        .on('error', standardHandler)
        .pipe(gulp.dest('./lib/css/'))
        .pipe(minifyCss({
            keepSpecialComments: 0
        }))
        .on('error', standardHandler)
        .pipe(rename({ extname: '.min.css' }))
        .pipe(gulp.dest('./ge-rss-reader/assets/css/'))
        .on('end', done);
});

gulp.task('pot', function () {
    return gulp.src('ge-rss-reader/*.php')
        .pipe(sort())
        .pipe(wpPot( {
            domain: 'super-rss-reader',
            destFile:'srr.pot',
            package: 'ge-rss-reader',
            lastTranslator: 'David Raison <david@tentwentyfour.lu>'
        }))
        .pipe(gulp.dest('ge-rss-reader/languages/'));
});

gulp.task('watch', function () {
    gulp.watch(paths.sass, ['sass']);
});
