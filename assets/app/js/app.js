// jquery
let $ = require('jquery/dist/jquery.min.js');

//bootstrap css
require('bootstrap/dist/css/bootstrap.min.css');

// icons
require('open-iconic/font/css/open-iconic-bootstrap.css');

// simple mde
let simpleMde = require('./simple-mde');

// highlight js
// stored in assets/libs because the is no minified dist in the node package and I prefer the minified version
// for dev, too.
let highlightJs = require('../../lib/highlightJs/highlightJs.min');
require('../../lib/highlightJs/highlightJs-solarized-dark.css');

$(function () {
    $('.alert-custom.alert').fadeIn(100).delay(1000).fadeOut(200);

    simpleMde($);

    highlightJs.initHighlightingOnLoad();
});