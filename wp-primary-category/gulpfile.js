/**
 * Configuration.
 */

var project             = 'jss-primary-category'; // Name

//var styleSRC            = './assets/css/style.scss'; // Path to main .scss file
//var styleDestination    = './'; // Path to place the compiled CSS file

var adminstyleSRC = 'admin/sass/jss-category-metabox.scss';
var adminstyleDestination = './admin/css/output/';

var adminjsSRC = 'admin/js/jss-category-metabox.js';
var adminjsDestination = './admin/js/output/';
var adminjsFile = 'jss-category-metabox';


/**
 * Load Plugins.
 */
var gulp         = require('gulp'); // Gulp of-course

// CSS related plugins.
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var minifycss    = require('gulp-uglifycss');

// JS related plugins.
var concat       = require('gulp-concat');
var uglify       = require('gulp-uglify');

// Utility related plugins.
var rename       = require('gulp-rename');
var sourcemaps   = require('gulp-sourcemaps');
var notify       = require('gulp-notify');


/**
 * Task: admin styles
 */
gulp.task('adminstyles', function () {
	return gulp.src( adminstyleSRC )
		.pipe( sourcemaps.init() )
		.pipe( sass( {
			errLogToConsole: true,
			outputStyle: 'compact',
			//outputStyle: 'compressed',
			// outputStyle: 'nested',
			// outputStyle: 'expanded',
			precision: 10
		} ) )
		.pipe( sourcemaps.write( { includeContent: false } ) )
		.pipe( sourcemaps.init( { loadMaps: true } ) )
		.pipe( autoprefixer(
			'last 2 version',
			'> 1%',
			'safari 5',
			'ie 8',
			'ie 9',
			'opera 12.1',
			'ios 6',
			'android 4' ) )

		.pipe( sourcemaps.write ( './' ) )
		//.pipe( gulp.dest( adminstyleDestination ) )


		.pipe( rename( { suffix: '.min' } ) )
		.pipe( minifycss( {
			maxLineLen: 10
		}))
		.pipe( gulp.dest( adminstyleDestination ) )
		.pipe( notify( { message: 'TASK: "styles" Completed!', onLast: true } ) )
});

/**
 * Task: admin js
 */
gulp.task( 'adminjs', function() {
	return gulp.src( adminjsSRC )
		.pipe( concat( adminjsFile + '.js' ) )
		.pipe( gulp.dest( adminjsDestination ) )
		.pipe( rename( {
			basename: adminjsFile,
			suffix: '.min'
		}))
		.pipe( uglify() )
		.pipe( gulp.dest( adminjsDestination ) )
		.pipe( notify( { message: 'TASK: "adminjs" Completed!', onLast: true } ) );
});

gulp.task( 'default', (done) => (
	gulp.series( 'adminstyles', 'adminjs' )(done)
));