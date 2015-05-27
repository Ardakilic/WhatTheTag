var gulp = require('gulp'),
	sass = require('gulp-ruby-sass'),
	notify = require('gulp-notify'),
	del = require('del'),
	uglify = require('gulp-uglify'),
	runSequence = require('run-sequence'),
	concat = require('gulp-concat');

var config = {
	jsPath			: './resources/assets/js',
	sassPath		: './resources/assets/sass',
	nodePath		: './node_modules',
	tempPath		: './temp_dir',
	sassCachePath	: './.sass-cache',
	public: {
		fontPath	: './public/fonts',
		cssPath		: './public/css',
		jsPath		: './public/js' 
	}
};

var cssFiles = [
	'/bootstrap/dist/css/bootstrap.min.css',
	'/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.min.css',
	'/font-awesome/css/font-awesome.min.css',
	'/bootstrap-social/bootstrap-social.css'
];
cssFiles = cssFiles.map(function(el) { 
	return config.nodePath + el; 
});

var javaScripts = [
	'/jquery/dist/jquery.min.js',
	'/bootstrap/dist/js/bootstrap.min.js',
	'/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
	'/datatables/media/js/jquery.dataTables.min.js',
	'/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js',
	'/social-share-js/dist/jquery.socialshare.min.js'
];
javaScripts = javaScripts.map(function(el) { 
	return config.nodePath + el; 
});


gulp.task('build-fonts', function() {
	return gulp.src([config.nodePath + '/font-awesome/fonts/**.*', config.nodePath + '/bootstrap/fonts/**.*'])
		.on('error', notify.onError(function(error) {
				return 'Error: ' + error.message;
			})
		)
		.pipe(gulp.dest(config.public.fontPath));
});

gulp.task('vendor-js', function(){
	return gulp.src(javaScripts)
		.on('error', notify.onError(function (error) {
				return 'Error: ' + error.message;
			})
		)
		.pipe(concat('vendor.min.js'))
		.pipe(gulp.dest(config.tempPath));
});

gulp.task('app-js', function(){
	return gulp.src(config.jsPath + '/**.*')
		.on('error', notify.onError(function (error) {
				return 'Error: ' + error.message;
			})
		)
		.pipe(concat('app-specific.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(config.tempPath));
});

gulp.task('merge-scripts', function(){
	return gulp.src([config.tempPath + '/vendor.min.js', config.tempPath + '/app-specific.min.js']) //To make sure they are set in order we give paths, not wildcards
		.pipe(concat('app.min.js'))
		.on('error', notify.onError(function (error) {
				return 'Error: ' + error.message;
			})
		)
		.pipe(gulp.dest(config.public.jsPath));
});

gulp.task('build-scripts', function(){
	return runSequence('vendor-js', 'app-js', 'merge-scripts');
});

gulp.task('vendor-css', function(){
	return gulp.src(cssFiles)
		.on('error', notify.onError(function(error) {
				return 'Error: ' + error.message;
			})
		)
		.pipe(concat('vendor.min.css'))
		.pipe(gulp.dest(config.tempPath));
});

gulp.task('app-css', function(){
	return sass(config.sassPath + '/app.scss', {
			container	: config.tempPath,
			style		: 'compressed',
			stopOnError	: true
		})
		.on('error', notify.onError(function (error) {
				return 'Error: ' + error.message;
			})
		)
		.pipe(concat('app-specific.min.css'))
		.pipe(gulp.dest(config.tempPath));
});

gulp.task('merge-css', function(){
	return gulp.src([config.tempPath + '/vendor.min.css', config.tempPath + '/app-specific.min.css'])
		.on('error', notify.onError(function(error) {
				return 'Error: ' + error.message;
			})
		)
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(config.public.cssPath));
});

gulp.task('build-css', function(){
	return runSequence('vendor-css', 'app-css', 'merge-css');
});

gulp.task('watch', function() {
	gulp.watch(config.appFiles.sass, ['build-css']);
	gulp.watch(config.appFiles.js, ['build-js']);
});

gulp.task('clean', function(cb) {
	del([
		config.public.cssPath + '/*', config.public.jsPath + '/*', 
		config.public.fontPath + '/*', config.tempPath + '/', 
		config.sassCachePath + '/'
		], cb
	);
});

gulp.task('default', function(){
	return runSequence(['clean', 'build-fonts', 'build-css', 'build-scripts']);
});