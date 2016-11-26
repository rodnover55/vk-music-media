const gulp = require('gulp');
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const browserify = require('browserify');
const babelify = require('babelify');
const source = require('vinyl-source-stream');

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
        .pipe(gulp.dest('./static'));
});

gulp.task('watch', ['scripts'], () => {
    gulp.watch('./src/**/*.js', ['scripts']);
});