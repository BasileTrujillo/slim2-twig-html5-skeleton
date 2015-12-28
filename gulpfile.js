// Requis
var gulp = require('gulp');

// Include plugins
var plugins = require('gulp-load-plugins')(); // Load all plugins from package.json

// Path files
var srcJsPath  = './assets/src/js';
var srcCssPath = './assets/src/css';

//Add each vendor CSS files that should be minified
var vendorCssFiles = [
    'vendor/components/normalize.css/normalize.css'
];

var distJsPath = './assets/dist/js';
var distCssPath = './assets/dist/css';

// "build_css" task = autoprefixer + CSScomb + beautify (source -> destination)
var buildCssTask = function () {
    return gulp.src(srcCssPath + '/*.css')
        .pipe(plugins.cached('building_css'))
        .pipe(plugins.csscomb())
        .pipe(plugins.cssbeautify({indent: '    '}))
        .pipe(plugins.autoprefixer())
        .pipe(gulp.dest(srcCssPath + '/'));
};

// "minify_css" task = Minify CSS + rename to *.min.css (destination -> destination)
var minifyCssTask = function (src, dest) {
    return gulp.src(src)
        .pipe(plugins.cached('minifying_css'))
        .pipe(plugins.csso())
        .pipe(plugins.rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(dest));
};

// "minify_js" task = Minify JS + rename to *.min.js (destination -> destination)
var minifyJsTask = function () {
    return gulp.src(srcJsPath + '/*.js')
        .pipe(plugins.plumber({
            handleError: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(plugins.uglify())
        .pipe(plugins.rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(distJsPath + '/'));
};

//Task function handling
gulp.task('build_css' , buildCssTask);
gulp.task('minify_css', function() {
    return minifyCssTask(srcCssPath + '/*.css', distCssPath + '/')
});
gulp.task('minify_vendor_css', function() {
    return minifyCssTask(vendorCssFiles, function(file) {
        return file.base; //Put each minified file to source file folder
    })
});
gulp.task('minify_js' , minifyJsTask);

//Task names config
gulp.task('build'   , ['build_css']);
gulp.task('prod'    , ['build_css', 'minify_css', 'minify_js']);
gulp.task('default' , ['build']);

// Watch task
gulp.task('watch', function () {
    gulp.watch(srcCssPath + '/*.css', ['build']);
});
