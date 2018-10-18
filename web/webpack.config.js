'use strict';

const path = require('path');
const glob = require('glob');
const HtmlWebpackPlugin = require("html-webpack-plugin");
const htmlWebpackPlugin = new HtmlWebpackPlugin({
    template: path.join(__dirname, "react_app/index.html"),
    filename: "./index.html"
});
let webpack = require('webpack');

console.log(__dirname);

module.exports = {
    entry: {
        "app": glob.sync('./react_app/src/app.js'),
        "app.min": glob.sync('./react_app/src/app.js')
    },
    watch: true,
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname + '/react_app', 'dist')
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
    resolve: {
        extensions: ['*', '.js', '.jsx']
    },
    stats: {
        colors: true
    },
    devtool: 'source-map',
    plugins: [
        htmlWebpackPlugin,
        new webpack.optimize.UglifyJsPlugin({
            include: /\.min\.js$/,
            minimize: true
        })
    ]
};