const externals = {
    wp: 'wp',
    react: 'React',
    'react-dom': 'ReactDOM',
    lodash: 'lodash',
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

        'assets/front-end/js/load-next-video'       : './assets/front-end/js/apps/load-next-video/app.js',

        'themes/default/assets/js/block/app.build'  : './themes/default/assets/js/block/block.js',
        'themes/simple/assets/script'               : './themes/simple/assets/app/app.js',
        'themes/listy/assets/script'                : './themes/listy/assets/app/app.js'
    },
    output: {
        path: __dirname,
        filename: '[name].js',
    },
    externals,
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