// 3rd party markdown editor: https://simplemde.com/

require('simplemde/dist/simplemde.min.css');

const simpleMDE = require('simplemde/dist/simplemde.min.js');

module.exports = function ($) {

    function addCodeTags (codemirror, language){

        let selection = codemirror.getSelection();

        codemirror.replaceSelection(
            '\n<pre>\n<code class="' + language + '">\n' + selection + '\n</code>\n</pre>\n'
        );
    }
    let instances = [];

    $('textarea').each(function () {
        if( $(this).is('#item_data')){
            return;
        }
        let instance = new simpleMDE({
            element: $(this)[0],
            forceSync: true,
            spellChecker: false,
            toolbar: [
                "bold",
                "italic",
                "strikethrough",
                "quote",
                "unordered-list",
                "ordered-list",
                "clean-block",
                "link",
                "image",
                "table",
                "preview",
                "|",

                {
                    name: "php",
                    action: function embedInCodeTagsPHP(editor) {
                        const cm = editor.codemirror;
                        addCodeTags(cm, 'PHP')
                    },
                    className: "fa fa-code",
                    title: "PHP",
                },
                {
                    name: "js",
                    action: function embedInCodeTagsPHP(editor) {
                        const cm = editor.codemirror;
                        addCodeTags(cm, 'JavaScript')
                    },
                    className: "fa fa-code",
                    title: "JS",
                },
                {
                    name: "sql",
                    action: function embedInCodeTagsPHP(editor) {
                        const cm = editor.codemirror;
                        addCodeTags(cm, 'SQL')
                    },
                    className: "fa fa-code",
                    title: "SQL",
                }
            ],
        });
        instances.push(instance);
    });
    return instances;
};