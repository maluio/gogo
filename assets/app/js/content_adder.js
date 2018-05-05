module.exports = function ($, editorInstances) {

    let $content = $('#content');
    let $editor = editorInstances[0];
    let $searchFieldl = $('#search');
    let $triggerSearch = $('#trigger-search');

    $triggerSearch.on('click', () => {
        $content.html('');
        $.getJSON('/search/' + $searchFieldl.val(), function (data) {
            data.forEach(item => {
                let img = $('<img src="' + item + '" />');

                img.on('click', function () {
                    $editor.value($editor.value() + '\n![](' + item + ')');
                });
                $content.append(img);
            });
        })
    });

};