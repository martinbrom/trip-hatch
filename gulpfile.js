var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');

var scripts = [
    'node_modules/jquery/dist/jquery.js',
    'node_modules/bootstrap3/dist/js/bootstrap.js',
    'resources/assets/js/*.js'
];

var styles = [
    'node_modules/bootstrap3/dist/css/bootstrap.css',
    'resources/assets/scss/app.scss'
];

var fonts = [
    'node_modules/font-awesome/fonts/fontawesome-webfont.ttf',
    'node_modules/font-awesome/fonts/fontawesome-webfont.woff',
    'node_modules/font-awesome/fonts/fontawesome-webfont.woff2'
];

function handleError(error) {
    console.log(error.toString());
    this.emit('end');
}

gulp.task('fonts', function() {
    gulp.src(fonts)
        .pipe(gulp.dest('public/fonts/'));
});

gulp.task('js', function() {
    gulp.src(scripts)
        .pipe(concat('app.js'))
        .pipe(gulp.dest('public/js/'));
});

gulp.task('scss', function() {
    gulp.src(styles)
        .pipe(concat('app.scss'))
        .pipe(sass())
        .on('error', handleError)
        .pipe(cleanCSS())
        .pipe(gulp.dest('public/css/'));
});

gulp.task('watch', ['js', 'scss'], function() {
    gulp.watch(['resources/assets/js/*.js'], ['js']);
    gulp.watch(['resources/assets/scss/*.scss'], ['scss']);
});

gulp.task('bundle', ['fonts', 'js','scss'], function() {
});

gulp.task('default', ['fonts', 'js','scss'], function() {
});
