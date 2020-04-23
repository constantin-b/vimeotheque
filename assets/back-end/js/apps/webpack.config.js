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
        add_video: './add_video/app.js',
        'block-editor/playlist': './block-editor/playlist/block.js',
        'block-editor/video_position': './block-editor/video_position/block.js',
        'block-editor/video': './block-editor/video/block.js',
        player: './player/app.js'
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