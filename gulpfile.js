var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');

var scripts = [
    'bower_components/jquery/dist/jquery.slim.js',
    'bower_components/bootstrap/dist/js/bootstrap.js',
    'resources/assets/js/*.js'
];

var styles = [
    'bower_components/bootstrap/dist/css/bootstrap.css',
    'resources/assets/sass/*.scss'
];

gulp.task('js', function(){
    gulp.src(scripts)
        .pipe(concat('app.js'))
        .pipe(gulp.dest('public/js/'));
});

gulp.task('scss', function(){
    gulp.src(styles)
        .pipe(concat('app.scss'))
        .pipe(sass())
        .pipe(cleanCSS())
        .pipe(gulp.dest('public/css/'));
});

gulp.task('watch', ['js', 'scss'], function() {
    gulp.watch(['application/assets/js/*.js'], ['js']);
    gulp.watch(['application/assets/sass/*.scss'], ['css']);
});

gulp.task('bundle', ['js','scss'], function() {
});

gulp.task('default', ['js','scss'], function(){
});