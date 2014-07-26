# Asset Manager

This is a little helper package for [Laravel](http://laravel.com/) to use in combination with e.g. [Gulp](http://gulpjs.com).

The advantage of this package is that it **automatically handles versioned assets**. With versioned assets, you can benefit from **cache busting** when an asset changes. This way your clients will always use the correct assets and not an old cached version.

For example the `gulpfile.js` below will produce a `public/css/styles-{hash}.css` and `public/js/scripts-{hash}.js` file where `{hash}` is the MD5 hash of the built file.

By using the `Asset` facade, you'll be able to include these assets without having to take care of those hashes.

For example `Asset::scripts('frontend')` would result in `<script scr="public/js/scripts-627d37eb.js"></script>`.

## Installation

1. Add `"modbase/asset-manager": "0.1.*"` to your `composer.json` file.
2. Run `composer update`
3. Add `'Modbase\AssetManager\AssetManagerServiceProvider'` to your service providers in `app/config/app.php`.

## Example gulpfile.js

```javascript
var gulp = require('gulp');
var styl = require('gulp-styl');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var rev = require('gulp-rev');
var clean = require('gulp-clean');
var filename = require('gulp-asset-manifest');

// Paths to your asset files
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
    // Cleanup old assets
    gulp.src('public/css/styles-*.css', { read: false }).pipe(clean());

    // Prefix, compress and concat the CSS assets
    // Afterwards add the MD5 hash to the filename
    gulp.src(paths.frontend.styles)
        .pipe(styl({ compress: true }))
        .pipe(concat('styles.css'))
        .pipe(rev())
        .pipe(filename({ bundleName: 'frontend.styles' })) // This will create/update the assets.json file
        .pipe(gulp.dest('public/css'));
});

// JavaScript task
gulp.task('js', function() {
    // Cleanup old assets
    gulp.src('public/js/scripts-*.js', { read: false }).pipe(clean());

    // Concat and uglify the JavaScript assets
    // Afterwards add the MD5 hash to the filename
    gulp.src(paths.frontend.scripts)
        .pipe(concat('scripts.js'))
        .pipe(uglify())
        .pipe(rev())
        .pipe(filename({ bundleName: 'frontend.scripts' })) // This will create/update the assets.json file
        .pipe(gulp.dest('public/js'));
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['css', 'js']);
```

## Laravel Facade

Use this in your views: `Asset::styles('frontend')` and `Asset::scripts('frontend')`.

The arguments of the `styles()` and `scripts()` methods correspond to the `bundleName` prefix you've given in the *gulpfile.js*. In the example above, it was `frontend`. Always end the bundleName with `.scripts` or `.styles` so that the AssetManager knows what to fetch.

## Example workflow

1. Use the above example *gulpfile.js* and update whatever you want, but be careful with the bundleName option!
2. In your master.blade.php (assuming you're using the awesome Blade template engine and have a master layout) add `{{ Asset::styles('frontend') }}` to your head section. Add `{{ Asset::scripts('frontend') }}` at the bottom of your HTML (you should put your JavaScript at the bottom, just before the closing body tag to speed up loading).
3. Add your assets to `app/assets/css` and `app/assets/js` (according to the *gulpfile.js* paths).
4. Run `gulp` or `gulp css` or `gulp js` everytime you update your assets (or simply use a gulp watch).
5. Enjoy!
