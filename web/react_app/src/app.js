'use strict';
import {BrowserRouter} from 'react-router-dom';
import IndexController from "./controller";

let React = require('react');
let ReactDOM = require('react-dom');
let createReactClass = require('create-react-class');

(function() {

    let App = createReactClass({
        render: function() {
            return (
                <div className="app">
                    Всем привет, я компонент App!
                </div>
            );
        }
    });

    ReactDOM.render(
        <App />,
        document.getElementById('react_new_products_grid')
    );
})();