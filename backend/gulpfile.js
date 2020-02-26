// Gulp.js configuration
'use strict';

const
  // source and build folders
  dir = {
    src     : 'resources/',
    build   : 'public/lib/',
    assets  : 'resources/assets/',
  },

  // Gulp and plugins
  gulp        = require('gulp'),
  noop        = require('gulp-noop'),
  newer       = require('gulp-newer'),
  imagemin    = require('gulp-imagemin'),
  sass        = require('gulp-sass'),
  postcss     = require('gulp-postcss'),
  deporder    = require('gulp-deporder'),
  stripdebug  = require('gulp-strip-debug'),
  uglify      = require('gulp-uglify'),
  plumber     = require('gulp-plumber'),
  del         = require('del')
;

// clean tasks
gulp.task('clean:build', () => {
  return del(dir.build + '**/*', { force:true });
});

gulp.task('clean:build:assets', () => {
  return del(dir.build + 'assets/**/*', { force:true });
});

gulp.task('clean:assets', () => {
  return del(dir.assets + '**/*', { force:true });
});

gulp.task('clean:assets:bootstrap', () => {
  return del(dir.src + 'scss/bootstrap/' + '**/*', { force:true });
});

// image settings
const images = {
  src    : dir.src + 'images/**/*',
  build  : dir.build + 'images/'
};

// image processing
gulp.task('build:images', () => {
  return gulp.src(images.src)
    .pipe(plumber())
    .pipe(newer(images.build))
    .pipe(imagemin())
    .pipe(gulp.dest(images.build));
});

// CSS settings
const css = {
  src               : dir.src + 'scss/style.scss',
  srcCms            : dir.src + 'scss/style-cms.scss',
  watch             : dir.src + 'scss/**/*',
  build             : dir.build,
  sassOpts: {
    outputStyle     : 'compressed',
    imagePath       : images.build,
    precision       : 3,
    errLogToConsole : true
  },
  sassDevOpts: {
    outputStyle     : 'expanded',
    imagePath       : images.build,
    precision       : 3,
    errLogToConsole : true
  },
  processors: [
    require('postcss-assets')({
      loadPaths: ['images/'],
      basePath:  dir.build,
      baseUrl:   '/lib/'
    }),
    require('css-mqpacker'),
    require('cssnano')
  ],
  devProcessors: [
    require('postcss-assets')({
      loadPaths: ['lib/images/'],
      basePath:  dir.build,
      baseUrl:   '/lib/'
    }),
    require('css-mqpacker')
  ]
};

// CSS standalone task
gulp.task('build:css:standalone', () => {
  return gulp.src(css.src)
    .pipe(plumber())
    .pipe(sass(css.sassOpts))
    .pipe(postcss(css.processors))
    .pipe(gulp.dest(css.build))
    .pipe(noop());
});

// DEV: CSS standalone task
gulp.task('build:css:standalone:dev', () => {
  return gulp.src(css.src)
    .pipe(plumber())
    .pipe(sass(css.sassDevOpts))
    .pipe(postcss(css.devProcessors))
    .pipe(gulp.dest(css.build))
    .pipe(noop());
});

// CSS CMS task
gulp.task('build:css-cms', () => {
  return gulp.src(css.srcCms)
    .pipe(plumber())
    .pipe(sass(css.sassOpts))
    .pipe(postcss(css.processors))
    .pipe(gulp.dest(css.build))
    .pipe(noop());
});

// DEV: CSS CMS task
gulp.task('build:css-cms:dev', () => {
  return gulp.src(css.src)
    .pipe(plumber())
    .pipe(sass(css.sassDevOpts))
    .pipe(postcss(css.devProcessors))
    .pipe(gulp.dest(css.build))
    .pipe(noop());
});

// CSS combined tasks
gulp.task('build:css', gulp.series('build:images', 'build:css:standalone', 'build:css-cms'));
gulp.task('build:css:dev', gulp.series('build:images', 'build:css:standalone:dev', 'build:css-cms:dev'));

// JavaScript settings
const js = {
  src       : dir.src + 'js/**/*',
  build     : dir.build + 'scripts/'
};

// JavaScript processing
gulp.task('build:js', () => {
  return gulp.src(js.src)
    .pipe(plumber())
    .pipe(deporder())
    .pipe(stripdebug())
    .pipe(uglify())
    .pipe(gulp.dest(js.build))
    .pipe(noop());
});

// DEV: JavaScript processing
gulp.task('build:js:dev', () => {
  return gulp.src(js.src)
    .pipe(plumber())
    .pipe(deporder())
    .pipe(gulp.dest(js.build))
    .pipe(noop());
});

// assets settings
const assets = {
  packages  : 'node_modules/',
  custom    : dir.src + 'assets-custom/',
  src       : dir.assets,
  build     : dir.build + 'assets/',
}

// assets copy from node_modules to project src
gulp.task('copy:assets', () => {
  return (
    // jQuery
    gulp.src(assets.packages + 'jquery/dist/jquery.min.js').pipe(gulp.dest(assets.src + 'jquery/')),
    // jQuery Cookie
    gulp.src(assets.packages + 'jquery-validation/dist/jquery.validate.min.js').pipe(gulp.dest(assets.src + 'jquery-validation/')),
    // jQuery Validation
    gulp.src(assets.packages + 'jquery.cookie/jquery.cookie.js').pipe(gulp.dest(assets.src + 'jquery.cookie/')),
    // jQuery Match Height
    gulp.src(assets.packages + 'jquery-match-height/dist/jquery.matchHeight-min.js').pipe(gulp.dest(assets.src + 'jquery-match-height/')),
    // jQuery Masked Input
    gulp.src(assets.packages + 'jquery.maskedinput/src/jquery.maskedinput.js').pipe(gulp.dest(assets.src + 'jquery-masked-input/')),
    // Popper
    gulp.src(assets.packages + 'popper.js/dist/umd/popper.min.js').pipe(gulp.dest(assets.src + 'popper/')),
    // Countdown
    gulp.src(assets.packages + 'countdown/countdown.js').pipe(gulp.dest(assets.src + 'countdown/')),
    // slick carousel
    gulp.src(assets.packages + 'slick-carousel/slick/slick.min.js').pipe(gulp.dest(assets.src + 'slick/')),
    gulp.src(assets.packages + 'slick-carousel/slick/slick.css').pipe(gulp.dest(assets.src + 'slick/')),
    // custom scrollbar
    gulp.src(assets.packages + 'malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js').pipe(gulp.dest(assets.src + 'custom-scrollbar/')),
    gulp.src(assets.packages + 'malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css').pipe(gulp.dest(assets.src + 'custom-scrollbar/')),
    // dropzone
    gulp.src(assets.packages + 'dropzone/dist/min/dropzone.min.js').pipe(gulp.dest(assets.src + 'dropzone/')),
    // jquery suggestions
    gulp.src(assets.packages + 'suggestions-jquery/dist/js/jquery.suggestions.min.js').pipe(gulp.dest(assets.src + 'suggestions-jquery/')),
    gulp.src(assets.packages + 'suggestions-jquery/dist/css/suggestions.min.css').pipe(gulp.dest(assets.src + 'suggestions-jquery/')),
    // select2
    gulp.src(assets.packages + 'select2/dist/js/select2.min.js').pipe(gulp.dest(assets.src + 'select2/')),
    gulp.src(assets.packages + 'select2/dist/css/select2.min.css').pipe(gulp.dest(assets.src + 'select2/')),
    // tinymce
    gulp.src(assets.packages + 'tinymce/tinymce.min.js').pipe(gulp.dest(assets.src + 'tinymce/')),
    gulp.src(assets.packages + 'tinymce/themes/silver/theme.min.js').pipe(gulp.dest(assets.src + 'tinymce/themes/silver/')),
    gulp.src(assets.packages + 'tinymce/plugins/image/plugin.min.js').pipe(gulp.dest(assets.src + 'tinymce/plugins/image/')),
    gulp.src(assets.packages + 'tinymce/plugins/media/plugin.min.js').pipe(gulp.dest(assets.src + 'tinymce/plugins/media/')),
    gulp.src(assets.packages + 'tinymce/plugins/code/plugin.min.js').pipe(gulp.dest(assets.src + 'tinymce/plugins/code/')),
    gulp.src(assets.packages + 'tinymce/plugins/link/plugin.min.js').pipe(gulp.dest(assets.src + 'tinymce/plugins/link/')),
    gulp.src(assets.packages + 'tinymce/skins/ui/oxide/skin.min.css').pipe(gulp.dest(assets.src + 'tinymce/skins/ui/oxide/')),
    gulp.src(assets.packages + 'tinymce/skins/ui/oxide/content.min.css').pipe(gulp.dest(assets.src + 'tinymce/skins/ui/oxide/')),
    gulp.src(assets.packages + 'tinymce/skins/content/default/content.min.css').pipe(gulp.dest(assets.src + 'tinymce/skins/content/default/')),
    gulp.src(assets.packages + 'tinymce-i18n/langs5/ru.js').pipe(gulp.dest(assets.src + 'tinymce/langs/')),
    // bootstrap
    gulp.src(assets.packages + 'bootstrap/dist/js/bootstrap.min.js').pipe(gulp.dest(assets.src + 'bootstrap/')),
    gulp.src(assets.packages + 'bootstrap/dist/js/bootstrap.min.js.map').pipe(gulp.dest(assets.src + 'bootstrap/')),
    gulp.src(assets.packages + 'bootstrap/scss/**/*').pipe(gulp.dest(dir.src + 'scss/bootstrap/')),
    // Trumbowyg
    gulp.src(assets.packages + 'trumbowyg/dist/trumbowyg.min.js').pipe(gulp.dest(assets.src + 'trumbowyg/')),
    gulp.src(assets.packages + 'trumbowyg/dist/ui/trumbowyg.min.css').pipe(gulp.dest(assets.src + 'trumbowyg/ui/')),
    gulp.src(assets.packages + 'trumbowyg/dist/ui/icons.svg').pipe(gulp.dest(assets.src + 'trumbowyg/ui/')),
    gulp.src(assets.packages + 'trumbowyg/dist/langs/ru.min.js').pipe(gulp.dest(assets.src + 'trumbowyg/'))
  )
});

// assets build
gulp.task('build:assets', () => {
  return gulp.src(assets.src + '**/*')
    .pipe(plumber())
    .pipe(newer(assets.build))
    .pipe(gulp.dest(assets.build));
});

// custom assets build
gulp.task('build:assets:custom', () => {
  return gulp.src(assets.custom + '**/*')
    .pipe(plumber())
    .pipe(newer(assets.build))
    .pipe(gulp.dest(assets.build));
});

// watch for file changes
gulp.task('watch', () => {
  gulp.watch(images.src, gulp.series('build:images'));
  gulp.watch(css.watch, gulp.series('build:css'));
  gulp.watch(js.src, gulp.series('build:js'));
});

// DEV: watch for file changes
gulp.task('watch:dev', () => {
  gulp.watch(images.src, gulp.series('build:images'));
  gulp.watch(css.watch, gulp.series('build:css:dev'));
  gulp.watch(js.src, gulp.series('build:js:dev'));
});

// combined tasks
gulp.task('build', gulp.series('clean:build', gulp.parallel('build:assets', 'build:assets:custom', 'build:css', 'build:js')));
gulp.task('build:dev', gulp.series('clean:build', gulp.parallel('build:assets', 'build:assets:custom', 'build:css:dev', 'build:js:dev')));

gulp.task('assets', gulp.series(gulp.parallel('clean:assets', 'clean:assets:bootstrap', 'clean:build:assets'), 'copy:assets', gulp.parallel('build:assets', 'build:assets:custom')));

// development task
gulp.task('dev', gulp.series('build:dev', 'watch:dev'));

// default task
gulp.task('default', gulp.series('build', 'watch'));