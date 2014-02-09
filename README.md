# Asset Manager

This is a little helper package for Laravel to use in combination with e.g. Gulp.

## Example gulpfile.js

```
var gulp = require('gulp');
var styl = require('gulp-styl'); 
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var rev = require('gulp-rev');
var clean = require('gulp-clean');
var filename = require('gulp-filename');

var paths = {
    frontend: {
        scripts: [
            'app/assets/javascript/vendor/**/*.js', 
            'app/assets/javascript/*.js'
        ],
        styles: [
            'app/assets/css/vendor/**/*.css',
            'app/assets/css/*.css'
        ]
    }
}

// CSS task
gulp.task('css', function() {
    gulp.src('public/css/styles-*.css', { read: false }).pipe(clean());

    gulp.src(paths.frontend.styles)
        .pipe(styl({ compress: true }))
        .pipe(concat('styles.css'))
        .pipe(rev())
        .pipe(filename({ bundleName: 'frontend.styles' }))
        .pipe(gulp.dest('public/css'));
});

// JavaScript task
gulp.task('js', function() {
    gulp.src('public/js/scripts-*.js', { read: false }).pipe(clean());

    gulp.src(paths.frontend.scripts)
        .pipe(concat('scripts.js'))
        .pipe(uglify())
        .pipe(rev())
        .pipe(filename({ bundleName: 'frontend.scripts' }))
        .pipe(gulp.dest('public/js'));
});

// Watch files for changes and re-run tasks
gulp.task('watch', function() {
    gulp.run('default');

    gulp.watch('app/assets/css/**/*.css', function(event) {
        gulp.run('css');
    });

    gulp.watch('app/assets/javascript/**/*.js', function(event) {
        gulp.run('js');
    });
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['css', 'js']);
```

## Laravel Facade

Use this in your views: `Asset::styles('frontend')` and `Asset::scripts('frontend')`.

The arguments of the `styles()` and `scripts()` methods are correspond to the `bundleName` prefix you've given in the *gulpfile.js*. In the example above, it was `frontend`. Always end the bundleName with `.scripts` or `.styles` so that the AssetManager knows what to fetch.
