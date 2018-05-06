module.exports = function ($, editorInstances) {

    let $content = $('#content');
    let $editor = editorInstances[0];
    let $searchFieldl = $('#search');
    let $triggerSearch = $('#trigger-search');

    let $dataField = $('#item_data');

    let data = $('#item_data').val() ? JSON.parse($('#item_data').val()) : null;

    if(!data){
        data = {
            'images': [

            ],
            'texts': []
        };
    }

    $triggerSearch.on('click', () => {
        $content.html('');
        $.getJSON('/search/' + $searchFieldl.val(), function (response) {
            response.forEach(item => {
                let img = $('<img src="' + item + '" />');

                img.on('click', function () {
                    //$editor.value($editor.value() + '\n![](' + item + ')');
                    data.images.push(item);
                    $dataField.val('');
                    $dataField.val(JSON.stringify(data));
                    console.log(data);
                });
                $content.append(img);
            });
        })
    });

};