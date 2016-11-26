const gulp = require('gulp');
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const browserify = require('browserify');
const babelify = require('babelify');
const source = require('vinyl-source-stream');
const stylus = require('gulp-stylus');

gulp.task('scripts', () => {
    //noinspection JSUnresolvedFunction
    browserify({
        entries: [
            './src/ui/router.js'
        ],
        transform: [babelify.configure()]
    })
        .bundle()
        .pipe(source('app.js'))
        .on('error', (e) => { console.log(e) })
        .pipe(gulp.dest('./static'))
        .pipe(rename({dirname: ''}))
        .pipe(gulp.dest('./../backend/public'));
});

gulp.task('styles', () => {
    gulp.src('./src/ui/pages/layout.styl')
        .pipe(stylus({
            url: { name: 'url', limit: false }
        }))
        .pipe(rename('app.css'))
        .pipe(gulp.dest('./static'))
        .pipe(rename({dirname: ''}))
        .pipe(gulp.dest('./../backend/public'));
});

gulp.task('watch', ['scripts', 'styles'], () => {
    gulp.watch('./src/**/*.js', ['scripts']);
    gulp.watch('./src/**/*.styl', ['styles']);
});