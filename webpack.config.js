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
        'assets/back-end/js/apps/add_video': './assets/back-end/js/apps/add_video/app.js',
        'assets/back-end/js/apps/block-editor/playlist': './assets/back-end/js/apps/block-editor/playlist/block.js',
        'assets/back-end/js/apps/block-editor/video_position': './assets/back-end/js/apps/block-editor/video_position/block.js',
        'assets/back-end/js/apps/block-editor/video': './assets/back-end/js/apps/block-editor/video/block.js',
        'assets/back-end/js/apps/player': './assets/back-end/js/apps/player/app.js',
        'themes/default/assets/js/block/': './themes/default/assets/js/block/block.js'
    },
    output: {
        path: __dirname,
        filename: '[name]/app.build.js',
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