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

elixir.config.js.uglify.options = { mangle: false }; //angular dosen't like to be mangled :(

elixir(function (mix) {
  var project_root = '../../../';

  mix.scripts(
    [
      project_root + 'bower_components/semantic/dist/semantic.min.js',
      '**/**/*.js',
    ],
    'public/js/main-bundle.js'
  );

  mix.sass('app.scss');
  mix.sass('admin/index.scss', 'public/adminpanel/src/app/stylesheets');

  mix.copy(
    'bower_components/semantic/dist/themes/default/**',
    'public/build/css/themes/default'
  );

  /**
   * Turn on cache busting for css and js files
   * https://laravel.com/docs/5.2/elixir#versioning-and-cache-busting
   */
  mix.version(['css/app.css', 'js/main-bundle.js']);
});

function s3() {
  var s3 = new AWS.S3();

  var json = JSON.parse(fs.readFileSync('public/build/rev-manifest.json'));

  _.mapValues(json, function (file) {
    var type = file.split('.')[1];
    var contentType = type == 'css' ? 'text/css' : 'application/javascript';

    var params = {
      Bucket: 'cdn.snugfeed.com',
      Key: 'build/' + file,
      Body: fs.readFileSync('public/build/' + file),
      ACL: 'public-read',
      ContentType: contentType,
    };
    s3.putObject(params, function (err, data) {
      if (err) console.log(err);
      else console.log('Successfully uploaded build');
    });

    return file;
  });
}

gulp.task('s3', function () {
  s3();
});
