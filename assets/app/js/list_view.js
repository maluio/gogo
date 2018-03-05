module.exports = function ($) {

    toggleModal();

    // Became necessary when I removed bootstrap JS
    function toggleModal() {
        let $modal = $('#deleteItemModal');
        $('button.delete-item').on('click', function (event) {
            $modal.css('display', 'block').css('opacity', '1');
        });

        $('button.close').on('click', function (event) {
            $modal.css('display', 'none').css('opacity', '0');
        });
    }
};