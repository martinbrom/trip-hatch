var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');

var scripts = [
    'bower_components/jquery/dist/jquery.js',
    'bower_components/bootstrap/dist/js/bootstrap.js',
    'resources/assets/js/*.js'
];

var styles = [
    'bower_components/bootstrap/dist/css/bootstrap.css',
    'resources/assets/scss/*.scss'
];

function handleError(error) {
    console.log(error.toString());
    this.emit('end');
}

gulp.task('js', function(){
    gulp.src(scripts)
        .pipe(concat('app.js'))
        .pipe(gulp.dest('public/js/'));
});

gulp.task('scss', function(){
    gulp.src(styles)
        .pipe(concat('app.scss'))
        .pipe(sass())
        .on('error', handleError)
        .pipe(cleanCSS())
        .pipe(gulp.dest('public/css/'));
});

gulp.task('watch', function() {
    gulp.watch(['resources/assets/js/*.js'], ['js']);
    gulp.watch(['resources/assets/scss/*.scss'], ['scss']);
});

gulp.task('bundle', ['js','scss'], function() {
});

gulp.task('default', ['js','scss'], function() {
});