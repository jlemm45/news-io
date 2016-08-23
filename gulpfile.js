var elixir = require('laravel-elixir');
var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var _ = require('lodash');
var AWS = require('aws-sdk');
var fs = require('fs');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    mix.sass('admin/index.scss', 'public/adminpanel/src/app/stylesheets');
});

//holds files to be bundled. key is bundle name.
var js = {
    'main': [
        'public/semantic/dist/semantic.min.js',
        'public/js/Directives/Login.js',
        'public/js/Directives/Register.js',
        'public/js/Directives/ManageFeeds.js',
        'public/js/Services/UserService.js',
        'public/js/Services/FeedService.js',
        'public/js/Directives/Modal.js',
        'public/js/Directives/Dropdown.js',
        'public/js/Functions.js'
    ],
    'feeds': [
        'public/js/Controllers/FeedsController.js',
        'public/js/Directives/Article.js',
        'public/js/Directives/NewFeed.js',
        'public/js/Services/ArticleService.js',
        'public/js/Directives/ReadArticle.js',
        'public/js/Directives/ToggleView.js',
        'public/js/Services/PreferenceService.js'
    ],
    'login': [
        'public/js/Controllers/LoginController.js'
    ],
    'me': [
        'public/js/Controllers/MeController.js'
    ],
    'register': [
        'public/js/Controllers/RegisterController.js'
    ],
    'welcome': [
        'public/js/Controllers/WelcomeController.js'
    ],
    'article': [
        'public/js/Controllers/ArticleController.js'
    ]
};

function bundle() {
    _.forEach(js, function(v, k) {
        gulp.src(v)
            .pipe(concat(k+'-bundle.js'))
            .pipe(uglify({mangle: false}))
            .pipe(gulp.dest('public/js'))
    });
}

function s3() {
    var s3 = new AWS.S3();

    _.forEach(js, function(v, k) {
        var params = {Bucket: 'cdn.snugfeed.com', Key: 'js/'+k+'-bundle.js', Body: fs.readFileSync('public/js/'+k+'-bundle.js'), ACL: 'public-read'};
        s3.putObject(params, function(err, data) {
            if (err) console.log(err);
            else     console.log('Successfully uploaded '+k+'-bundle.js');
        });
    });
}

gulp.task('uglify', function() {
    bundle();
});

gulp.task('s3', function() {
    s3();
});

gulp.task('buildprod', function() {
    bundle();
    console.log('bundled');
    s3();
    console.log('uploaded');
});