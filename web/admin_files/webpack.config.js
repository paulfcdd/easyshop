'use strict';

const path = require('path');
const glob = require('glob');
let webpack = require('webpack');

module.exports = {
    entry: {
        "js": glob.sync('./js/*.js')
    },
    watch: true,
    output: {
        filename: 'admin.js',
        path: path.resolve(__dirname, 'dist')
    },
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'jshint-loader'

            },
            {
                test: /\.js$/,
                loader: "babel-loader",
                exclude: /node_modules/,
                options: {
                    presets: ['env'],
                }
            }
        ]
    },
    stats: {
        colors: true
    },
    devtool: 'source-map',
    plugins: [
        new webpack.optimize.UglifyJsPlugin({
            include: /\.min\.js$/,
            minimize: true
        })
    ]
};