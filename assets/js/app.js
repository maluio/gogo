var $ = require('jquery');
require('bootstrap');
var learn = require('./learn');
var simpleMde = require('./simple-mde');

$(function () {
    $('.alert-custom.alert').fadeIn(100).delay(1000).fadeOut(200);

    //learn();
    simpleMde();
});