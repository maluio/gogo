var $ = require('jquery');
var learn = require('./learn');

var simpleMDE = require('simplemde/dist/simplemde.min.js');

$(function () {
    learn();

    $('textarea').each(function () {
        new simpleMDE({
            element: $(this)[0],
            forceSync: true
        });
    });

    $('.alert').fadeIn(200).delay(1000).fadeOut(200);
});