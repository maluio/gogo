var $ = require('jquery');
var learn = require('./learn');
var simpleMde = require('./simple-mde');

$(function () {
    learn();
    simpleMde();

    $('.alert').fadeIn(200).delay(1000).fadeOut(200);
});