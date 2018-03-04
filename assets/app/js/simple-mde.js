// 3rd party markdown editor: https://simplemde.com/

require('simplemde/dist/simplemde.min.css');

var $ = require('jquery');
var simpleMDE = require('simplemde/dist/simplemde.min.js');

module.exports = function () {
    $('textarea').each(function () {
        new simpleMDE({
            element: $(this)[0],
            forceSync: true,
            spellChecker: false
        });
    });
};