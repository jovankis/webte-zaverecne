var editors = {};  // collection of editors

window.addEventListener('load', function () {
    var params = {
        'toolbar': '<toolbar ref="mathml2"/>',
        'language': 'en'
    };

    // Fetch all editor containers
    var editorContainers = document.getElementsByClassName('editorContainer');
    for(var i = 0; i < editorContainers.length; i++) {
        var editorContainer = editorContainers[i];

        // Create a new editor instance and insert into the current container
        var editor = com.wiris.jsEditor.JsEditor.newInstance(params);
        editor.insertInto(editorContainer);

        // Store the editor instance in the collection
        editors[editorContainer.id] = editor;
    }
});
