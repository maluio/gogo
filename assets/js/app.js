var $ = require('jquery');
var learn = require('./learn');

$(function () {
    learn();

    $('.alert').fadeIn(200).delay(1000).fadeOut(200);
});