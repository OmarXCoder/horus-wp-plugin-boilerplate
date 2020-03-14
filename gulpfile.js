const gulp = require('gulp');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss')
const cleanCSS = require('gulp-clean-css');
const autoprefixer = require('autoprefixer');

function minifyCSS() {
    return gulp.src(['src/css/**/*.css'], { base: '.' })
        .pipe(postcss([autoprefixer()]))
        .pipe(cleanCSS({ compatibility: 'ie11' }))
        .pipe(gulp.dest('dist'))
}

function minifyJS() {
    return gulp.src(['src/js/**/*.js'], { base: '.' })
        .pipe(uglify())
        .pipe(gulp.dest('dist'))
}


exports.minifyJS = minifyJS;
exports.minifyCSS = minifyCSS;
exports.build = gulp.series(minifyCSS, minifyJS);
