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

$(document).ready(function() {
    $("button[id^='submit_']").click(function() {
        var id = $(this).attr('id').replace('submit_', '');
        var editor = editors[id];
        var mathmlContent = editor.getMathML();
        console.log(mathmlContent);

        // Get the correct answer from the hidden input field
        var correctAnswer = document.getElementById('solution_' + id).value;
        var answerSection = document.getElementById('answer_' + id);
        answerSection.innerHTML = '<p>Vaša odpoveď: ' + mathmlContent + '</p>';
        answerSection.innerHTML += '<p>Správna odpoveď: ' + correctAnswer + '</p>';

        // Render LaTeX
        MathJax.typeset();

        // Remove the editor and submit button after submission
        var editorContainer = document.getElementById(id);
        var submitButton = document.getElementById('submit_' + id);

        editorContainer.remove();
        submitButton.remove();
    });
});

