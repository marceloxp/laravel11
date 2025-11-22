const custom_config = require('./package.json').br_com_umstudio;
const filter = require('gulp-filter');
const config = {
    'env': {
        'local': {
            'base': custom_config.hosts.local,
            'css': 'www/public/css',
            'js': 'www/public/js',
            'libs': 'www/public/libs',
            'sass': 'sass/',
            'img': {
                'source': 'www/public/img/',
                'min': 'www/public/img/',
                'webp': 'www/public/webp/'
            }
        },
        'homolog': {
            'base': custom_config.hosts.homolog,
            'css': 'www/public/css',
            'js': 'www/public/js',
            'libs': 'www/public/libs',
            'sass': 'sass/',
            'img': {
                'source': 'www/public/img/',
                'min': 'www/public/img/',
                'webp': 'www/public/webp/'
            }
        },
        'production': {
            'base': custom_config.hosts.production,
            'css': 'www/public/css',
            'js': 'www/public/js',
            'libs': 'www/public/libs',
            'sass': 'sass/',
            'img': {
                'source': 'www/public/img/',
                'min': 'www/public/img/',
                'webp': 'www/public/webp/'
            }
        }
    },
    'filter': {
        'js': filter('**/*.js', { restore: true }),
        'css': filter('**/*.css', { restore: true })
    },
    'options': {
        'overrides': {
            'jsbaseclass': {
                'ignore': true
            },
            'swiper': {
                'ignore': true
            },
            'gsap': {
                'ignore': true
            }
        },
        'paths': {
            'modulesFolder': 'node_modules',
            'jsonFile': 'package.json'
        },
        'debugging': false
    }
}

module.exports = class GulpConfig {
    constructor() {
        this.config = config;
    }

    get(path) {
        return path.split('.').reduce((obj, key) => obj[key], this.config);
    }

    env(local, path) {
        const dotpath = 'env.' + local + '.' + path;
        return this.get(dotpath);
    }

    uselib(libname) {
        const use = custom_config.vendor.use;
        if (use.indexOf(libname) > -1) {
            return true;
        }
        return false;
    }

    getVendorFiles(libname) {
        return custom_config.vendor[libname];
    }

};
