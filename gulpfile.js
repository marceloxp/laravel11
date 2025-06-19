const os = require('os');
const gulp = require('gulp');
const debug = require('gulp-debug');
const fs = require('fs').promises;
const csso = require('gulp-csso');
const gulpif = require('gulp-if');
const mainYarnFiles = require('main-yarn-files');
const concat = require('gulp-concat');
const autoprefixer = require('gulp-autoprefixer');
const sass = require('gulp-sass')(require('sass'));
const edit = require('gulp-edit');
const terser = require('gulp-terser');
const strnow = require('strnow');
const fkill = require('fkill');
const execa = require('execa');
const sourcemaps = require('gulp-sourcemaps');
const GulpConfig = require('./gulpconfig.js');
const config = new GulpConfig();
const browserSync = require('browser-sync').create();
const BrowserSyncConfig = require('./browsersync.js');

function _task_vendor(p_local) {
    var src_files = mainYarnFiles(config.get('options'));
    const custom_vendors = ['swiper'];
    custom_vendors.forEach(function (vendor) {
        if (config.uselib(vendor)) {
            src_files = src_files.concat(config.getVendorFiles(vendor));
        }
    });
    return (
        gulp
            .src(src_files)
            // JS
            .pipe(config.get('filter.js'))
            .pipe(debug({ title: 'Debug _task_vendor:' }))
            .pipe(terser())
            .pipe(concat('vendor.min.js', { newLine: "\r\n" }))
            .pipe(
                edit(function (src, cb) {
                    src = "/* Last modified: " + strnow.get() + " */\n\n" + src;
                    cb(null, src);
                })
            )
            .pipe(gulp.dest(config.env(p_local, 'js')))
            .pipe(config.get('filter.js.restore'))
            // CSS
            .pipe(config.get('filter.css'))
            .pipe(debug({ title: 'Debug _task_vendor:' }))
            .pipe(autoprefixer())
            .pipe(csso())
            .pipe(concat('vendor.min.css', { newLine: "\r\n" }))
            .pipe(
                edit(function (src, cb) {
                    src = "/* Last modified: " + strnow.get() + " */\n\n" + src;
                    cb(null, src);
                })
            )
            .pipe(gulp.dest(config.env(p_local, 'css')))
            .pipe(config.get('filter.css.restore'))
            .on('end', function () {
                console.log('-- END GULP TASK VENDOR --');
            })
    );
}

function _task_lib(p_local) {
    return (
        gulp
            .src(require("./package.json").br_com_umstudio.libs)
            .pipe(debug({ title: 'Debug _task_lib:' }))
            .pipe(gulp.dest(config.env(p_local, 'libs')))
            .on('end', function () {
                console.log('-- GULP TASK TASK_LIB --');
            })
    );
}

function _task_sass(p_local) {
    const minify = p_local == 'production' ? true : false;
    return gulp
        .src(config.env(p_local, 'sass') + '_init.scss')
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(concat('style.min.css'))
        .pipe(sass({ silenceDeprecations: ['legacy-js-api'] }).on('error', sass.logError))
        // .pipe(urlAdjuster({ replace: ['../', config.env(p_local, 'base') + '/'] }))
        .pipe(gulpif(minify, csso()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(config.env(p_local, 'css')))
        .on('end', function () {
            console.log('-- GULP TASK SASS --');
        });
}

function _task_sass_local() {
    return _task_sass('local');
}

function _task_sass_homolog() {
    return _task_sass('homolog');
}

function _task_sass_production() {
    return _task_sass('production');
}

function _task_vendor_local() {
    return _task_vendor('local');
}

function _task_vendor_homolog() {
    return _task_vendor('homolog');
}

function _task_vendor_production() {
    return _task_vendor('production');
}

function __task_lib_local() {
    return _task_lib('local');
}

function __task_lib_homolog() {
    return _task_lib('homolog');
}

function __task_lib_production() {
    return _task_lib('production');
}

function getPackageHostAndPort() {
    const package_json = require('./package.json');
    const host = package_json.br_com_umstudio.hosts.local.replace('http://', '').replace('https://', '');
    const parts = host.split(':');
    const port = (parts.length > 1) ? parts[1] : 80;
    return { host: parts[0], port: port };
}

function _task_killport() {
    return new Promise(function (resolve, reject) {
        (async () => {
            const host = getPackageHostAndPort();
            await fkill(`:${host.port}`, { force: true, silent: true });
            await fkill(`:${BrowserSyncConfig.port}`, { force: true, silent: true });
            resolve();
        })();
    });
}

// Função para detectar o caminho do PHP baseado na versão do composer.json
async function getPhpPath() {
    if (os.platform() !== 'darwin') {
        return 'php'; // Retorna comando padrão para não-MacOS
    }

    try {
        // Lê o composer.json
        const composerJson = await fs.readFile('www/composer.json', 'utf8');
        const composerConfig = JSON.parse(composerJson);

        // Verifica a versão requerida do PHP
        const phpVersion = composerConfig.require.php;

        // Se requer PHP 8.2 ou superior
        if (phpVersion.includes('^8')) {
            return '/opt/homebrew/bin/php';
        }
        // Se requer PHP 7.x
        if (phpVersion.includes('^7')) {
            return '/Applications/MAMP/bin/php/php7.4.21/bin/php';
        }

        // Default para PHP 8 se não conseguir determinar
        return '/opt/homebrew/bin/php';
    } catch (error) {
        console.error('Erro ao ler composer.json:', error);
        return '/opt/homebrew/bin/php'; // Fallback para PHP 8
    }
}

function executeArtisanServe() {
    return new Promise(function (resolve, reject) {
        (async () => {
            const host = getPackageHostAndPort();
            const phpPath = await getPhpPath();
            const command = `${phpPath} www/artisan serve --host=${host.host} --port=${host.port}`;

            try {
                execa(command, { shell: true, stdio: 'inherit' });
                resolve();
            } catch (error) {
                reject(error);
            }
        })();
    });
}

function watch_and_sync() {
    browserSync.init(BrowserSyncConfig);
    return gulp.watch(config.env('local', 'sass') + '**/*.scss', gulp.parallel(_task_sass_local));
}

exports.sass = _task_sass_local;
exports.vendor = gulp.series(_task_vendor_local, __task_lib_local);
exports.default = gulp.series(_task_killport, executeArtisanServe, _task_vendor_local, __task_lib_local, _task_sass_local, watch_and_sync);
exports.local = gulp.series(_task_killport, executeArtisanServe, _task_vendor_local, __task_lib_local, _task_sass_local);
exports.homolog = gulp.series(_task_vendor_homolog, __task_lib_homolog, _task_sass_homolog);
exports.production = gulp.series(_task_vendor_production, __task_lib_production, _task_sass_production);
