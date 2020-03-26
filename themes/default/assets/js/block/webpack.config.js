const externals = {
    wp: 'wp',
    react: 'React',
    'react-dom': 'ReactDOM',
    lodash: 'lodash'
};

const isProduction = process.env.NODE_ENV === 'production';
const mode = isProduction ? 'production' : 'development';

module.exports = {
    mode,
    entry: './script.js',
    output: {
        path: __dirname,
        filename: 'script.build.js',
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