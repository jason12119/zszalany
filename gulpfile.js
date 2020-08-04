const gulp = require('gulp');
const concat = require('gulp-concat');
const clean = require('gulp-clean');
const cleanCSS = require('gulp-clean-css');
const gulpless = require('gulp-less');
const minify = require('gulp-minify');
const stripCssComments = require('gulp-strip-css-comments');
const googleWebFonts = require('gulp-google-webfonts');


// Smaže framework build
gulp.task('remove-build', function () {
    return gulp
        .src([
            './dist/js/*',
            './dist/css/*'
        ])
        .pipe(clean({force: true}))
});


// Vytvoří build fontů pro frameworks
gulp.task('frameworks-fonts', function () {
    return gulp
        .src([
            './node_modules/font-awesome/fonts/*',

        ])
        .pipe(gulp.dest('./dist/fonts/'));
});


// Smaže frameworks build
gulp.task('remove-build', function () {
    return gulp
        .src([
            './dist/*',
        ])
        .pipe(clean({force: true}))
});

// Vytvoří build css frameworks
gulp.task('frameworks-css', function () {
    return gulp
        .src([
            './node_modules/bootstrap3/dist/css/bootstrap.min.css',
            './node_modules/font-awesome/css/font-awesome.min.css',
        ])
        .pipe(stripCssComments({
            preserve: false
        }))
        .pipe(concat('frameworks.css'))
        .pipe(cleanCSS())
        .pipe(gulp.dest('./dist/css'));
});

// Vytvoří build js frameworks
gulp.task('frameworks-js', function () {
    return gulp.src([
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/bootstrap3/dist/js/bootstrap.min.js',

    ])
        .pipe(concat('frameworks.js'))
        .pipe(minify())
        .pipe(gulp.dest('./dist/js'));
});

// Vytvoří build js frameworks
gulp.task('app-js', function () {
    return gulp.src([
        './js/functions.js',
        './js/matchHeight.js',
        './js/formValidator.js',

    ])
        .pipe(concat('app.bundle.js'))
        .pipe(minify())
        .pipe(gulp.dest('./dist/js'));
});

// Vytvoří build css stylů aplikace
gulp.task('app-css', function () {

    return gulp
        .src([
            './css/style.less', './css/responsive.less'
        ])
        .pipe(gulpless())
        .pipe(concat('app.bundle.css'))
        .pipe(cleanCSS())
        .pipe(gulp.dest('./css/'));
});

gulp.task('default', gulp.series(['remove-build', 'frameworks-css', 'frameworks-js', 'frameworks-fonts', 'app-css', 'app-js']));
gulp.task('frameworks', gulp.series(['remove-build', 'frameworks-css', 'frameworks-js', 'frameworks-fonts']));
gulp.task('app', gulp.series(['app-css', 'app-js']));

exports.default
exports.frameworks;
exports.app;



