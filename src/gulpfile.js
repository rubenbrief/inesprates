var $, browserSync, gulp, merge, paths, reload, fs, gutil, sass, siteUrl;
gulp = require("gulp");
$ = require("gulp-load-plugins")();
fs = require("fs");
browserSync = require('browser-sync').create();
reload = browserSync.reload,
gutil = require('gulp-util');
sass = require('gulp-sass');

// CHANGE THIS TO MATCH YOUR SITE URL
siteUrl = "ip.local/";

paths = {
  "scripts": {
    "src": "js/",
    "assets": "../assets/js/"
  },
  "styles": {
    "src": "sass/",
    "assets": "../assets/css/"
  },
  "fonts": {
    "src": "fonts/",
    "assets": "../assets/fonts/"
  },
  "img": {
    "src": "img/",
    "assets": "../assets/img/"
  },
  "bower": {
    "src": "bower_components/"
  },
  "fonts": {
    "assets": "../assets/fonts/",
    "src": "fonts/"
  }
};


gulp.task('browser-sync', function() {
  browserSync.init({
    proxy: siteUrl
  });
});

gulp.task("styles-ie", function() {
  return gulp.src(paths.styles.src+"ie.css")
  .pipe($.plumber())
  .pipe($.autoprefixer())
  .pipe($.rename("ie.min.css"))
  .pipe($.minifyCss())
  .pipe($.plumber.stop())
  .pipe(gulp.dest(paths.styles.assets))
});

gulp.task("bower-sass", function(){
  return gulp.src(paths.bower.src+"uikit/scss/**/*")
    .pipe(gulp.dest(paths.styles.src+"uikit"));
});

gulp.task("bower-fonts", function(){
  return gulp.src(paths.bower.src+"uikit/src/fonts/*")
    .pipe(gulp.dest(paths.fonts.src));
});

gulp.task("fonts", function(){
  return gulp.src(paths.fonts.src)
    .pipe(gulp.dest(paths.fonts.assets))
    .pipe(reload({stream: true}));
});

gulp.task("styles-login", function() {
  return gulp.src(paths.styles.src+"login.css")
  .pipe($.plumber())
  .pipe($.autoprefixer())
  .pipe($.rename("login.min.css"))
  .pipe($.minifyCss())
  .pipe($.plumber.stop())
  .pipe(gulp.dest(paths.styles.assets));
});

gulp.task("scripts", function() {
  //Add here more paths to include js files
  return gulp.src([
    paths.scripts.src+"main.js"
    ])
    .pipe($.changed(paths.scripts.assets))
    .pipe($.concat("scripts.min.js"))
    .pipe(gulp.dest(paths.scripts.assets))
    .pipe(reload({stream: true}));
});

gulp.task("scripts-prod", function() {
  return gulp.src([
      paths.scripts.assets+"scripts.min.js"
    ])
    .pipe($.uglify())
    .pipe(gulp.dest(paths.scripts.assets));
});


gulp.task("styles", function() {
  return gulp.src([
    paths.styles.src+"app.scss"
    ])
  .pipe($.changed(paths.styles.assets))
  .pipe($.sourcemaps.init({loadMaps: true}))
  .pipe($.plumber())
  .pipe(sass().on('error', sass.logError))
  .pipe($.autoprefixer())
  .pipe($.rename("styles.min.css"))
  .pipe($.plumber.stop())
  .pipe(gulp.dest(paths.styles.assets))
  .pipe($.sourcemaps.write())
  .pipe(gulp.dest(paths.styles.assets))
  .pipe(reload({stream: true}));
});


gulp.task("styles-prod", function() {
  return gulp.src([
    paths.styles.assets+"styles.min.css"
    ])
  .pipe($.minifyCss())
  .pipe(gulp.dest(paths.styles.assets));
});


gulp.task("img", function() {
  return gulp.src(paths.img.src+"*")
  .pipe(gulp.dest(paths.img.assets))
  .pipe(reload({stream: true}));
});

gulp.task("img-prod", function() {
  return gulp.src(paths.img.assets+"*")
  .pipe($.imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
        }))
  .pipe(gulp.dest(paths.img.assets));
});


gulp.task("watch", ['browser-sync'], function() {
  gulp.watch(paths.scripts.src+"**/*.js", ["scripts"]);
  gulp.watch(paths.styles.src+"**/*.scss", ["styles"]);
  gulp.watch(paths.img.src+"**/*", ["img"]);
  gulp.watch(paths.fonts.src+"**/*", ["fonts"]);
  gulp.watch("../*.php").on("change", browserSync.reload);
});

gulp.task("start", ["bower-sass", "bower-fonts"]);
gulp.task("default", [ "styles", "scripts", "img", "fonts", "watch"]);
gulp.task("prod", [ "styles-prod", "scripts-prod", "img-prod"]);
