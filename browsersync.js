/*
 |--------------------------------------------------------------------------
 | Browser-sync config file
 |--------------------------------------------------------------------------
 |
 | For up-to-date information about the options:
 |   http://www.browsersync.io/docs/options/
 |
 | There are more options than you see here, these are just the ones that are
 | set internally. See the website for more info.
 |
 |
 */

const devip = require('dev-ip');
const custom_config = require("./package.json").br_com_umstudio;
const url = custom_config.hosts.local;
const host_parts = url.replace(/(^\w+:|^)\/\//, "").split(":");
const option_host = host_parts[0];
const option_port = host_parts[1] || 80;
const dev_hosts = devip();
const dev_host = dev_hosts[0];

module.exports = {
    ui: {
        port: 3001
    },
    files: custom_config.browsersync.files,
    watchEvents: ["add", "change"],
    watch: false,
    ignore: [],
    single: false,
    watchOptions: {
        ignoreInitial: true
    },
    server: false,
    proxy: `http://${option_host}:${option_port}`,
    port: parseInt(option_port) + 1,
    tunnel: false,
    middleware: false,
    serveStatic: [],
    ghostMode: {
        clicks: true,
        scroll: true,
        location: true,
        forms: {
            submit: true,
            inputs: true,
            toggles: true
        }
    },
    host: dev_host,
    logLevel: "info",
    logPrefix: "Browsersync",
    logConnections: false,
    logFileChanges: true,
    logSnippet: true,
    rewriteRules: [],
    open: "local",
    browser: "default",
    cors: false,
    xip: false,
    hostnameSuffix: false,
    reloadOnRestart: false,
    notify: true,
    scrollProportionally: true,
    scrollThrottle: 0,
    scrollRestoreTechnique: "window.name",
    scrollElements: [],
    scrollElementMapping: [],
    reloadDelay: 0,
    reloadDebounce: 500,
    reloadThrottle: 0,
    plugins: [],
    injectChanges: true,
    startPath: null,
    minify: true,
    localOnly: false,
    codeSync: true,
    timestamps: true,
    clientEvents: [
        "scroll",
        "scroll:element",
        "input:text",
        "input:toggles",
        "form:submit",
        "form:reset",
        "click"
    ],
    socket: {
        socketIoOptions: {
            log: false
        },
        socketIoClientConfig: {
            reconnectionAttempts: 50
        },
        path: "/browser-sync/socket.io",
        clientPath: "/browser-sync",
        namespace: "/browser-sync",
        clients: {
            heartbeatTimeout: 5000
        }
    },
    tagNames: {
        less: "link",
        scss: "link",
        css: "link",
        jpg: "img",
        jpeg: "img",
        png: "img",
        svg: "img",
        gif: "img",
        js: "script"
    },
    injectNotification: true
};
