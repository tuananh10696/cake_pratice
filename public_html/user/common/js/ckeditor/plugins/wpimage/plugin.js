CKEDITOR.plugins.add( 'wpimage', {
    icons: 'wpimage',
    init: function( editor ) {
        editor.ui.addButton( 'Addnow', {
            label: 'Insert Current time',
            command: 'addnow',
            toolbar: 'insert'
        });
        editor.addCommand('addnow', {
            exec: function(editor) {
                editor.insertHtml(
                    '<h2 class="recommend">現在時刻</h2>' +
                    '<span>' + afterDate + '</span>'
                );
            }
        });
    }
});