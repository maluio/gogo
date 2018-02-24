$(function () {
    $('button.js-show').on('click', function () {
        $('div.js-answer').toggle();
    });

    $('.alert').fadeIn(200).delay(1000).fadeOut(200);
});