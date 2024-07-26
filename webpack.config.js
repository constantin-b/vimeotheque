const externals = {
    wp: 'wp',
    react: 'React',
    'react-dom': 'ReactDOM',
    lodash: 'lodash',
    jquery: 'jQuery',
    jQuery: 'jQuery',
    Vimeo: 'Vimeo'
};

const isProduction = process.env.NODE_ENV === 'production';
const mode = isProduction ? 'production' : 'development';

module.exports = {
    mode,
    entry: {
        'assets/back-end/js/apps/add_video/app.build'                   : './assets/back-end/js/apps/add_video/app.js',
        'assets/back-end/js/apps/block-editor/playlist/app.build'       : './assets/back-end/js/apps/block-editor/playlist/block.js',
        'assets/back-end/js/apps/block-editor/video_position/app.build' : './assets/back-end/js/apps/block-editor/video_position/block.js',
        'assets/back-end/js/apps/block-editor/video/app.build'          : './assets/back-end/js/apps/block-editor/video/block.js',
        'assets/back-end/js/apps/player/app.build'                      : './assets/back-end/js/apps/player/app.js',
        'assets/back-end/js/setup'                                      : './assets/back-end/js/apps/setup/app.js',

        'assets/front-end/js/load-next-video'       : './assets/front-end/js/apps/load-next-video/app.js',

        'themes/default/assets/js/block/app.build'  : './themes/default/assets/js/block/block.js',
        'themes/simple/assets/script'               : './themes/simple/assets/app/app.js',
        'themes/listy/assets/script'                : './themes/listy/assets/app/app.js',

        // Series
        'assets/series/js/video-list'       : './assets/series/js/apps/video-list/app.js',
        'assets/series/js/theme'            : './assets/series/js/apps/theme/app.js',
        'assets/series/js/post-actions'     : './assets/series/js/apps/post-actions/app.js',
        'assets/series/js/post-title'       : './assets/series/js/apps/post-title/app.js',
        'assets/series/js/player'           : './assets/series/js/apps/player/app.js',
        'assets/series/js/block-editor'     : './assets/series/js/apps/block-editor/app.js',

        // Theme Default
        'themes-series/default/assets/js/script': './themes-series/default/assets/js/app/app.js',
        'themes-series/default/assets/js/editor': './themes-series/default/assets/js/editor/app.js',

        // Theme List
        'themes-series/list/assets/js/script': './themes-series/list/assets/js/app/app.js',
        'themes-series/list/assets/js/editor': './themes-series/list/assets/js/editor/app.js',

        // Theme Carousel
        'themes-series/carousel/assets/js/script': './themes-series/carousel/assets/js/app/app.js',
        'themes-series/carousel/assets/js/editor': './themes-series/carousel/assets/js/editor/app.js',
    },
    output: {
        path: __dirname,
        filename: '[name].js',
    },
    externals: externals,
    module: {
        rules: [
            {
                test: /\.js$|jsx/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                },
            },
        ],
    },
    resolve: {
        extensions: ['.js','.jsx']
    }
};