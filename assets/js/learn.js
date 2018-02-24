var $ = require('jquery');

module.exports = function () {
    $('button.js-show').on('click', function () {
        $('div.js-answer').toggle();
    });
};