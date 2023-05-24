var editors = {};

window.addEventListener('load', function () {
    var params = {
        'toolbar': '<toolbar ref="mathml2"/>',
        'language': 'en'
    };

    var editorContainers = document.getElementsByClassName('editorContainer');
    for(var i = 0; i < editorContainers.length; i++) {
        var editorContainer = editorContainers[i];

        var editor = com.wiris.jsEditor.JsEditor.newInstance(params);
        editor.insertInto(editorContainer);

        editors[editorContainer.id] = editor;
    }
});

$(document).ready(function() {
    $("button[id^='submit_']").click(function() {
        var id = $(this).attr('id').replace('submit_', '');
        var editor = editors[id];
        var mathmlContent = editor.getMathML();
        console.log(mathmlContent);

        var correctAnswer = document.getElementById('solution_' + id).value;
        var answerSection = document.getElementById('answer_' + id);
        answerSection.innerHTML = '<p>Vaša odpoveď: ' + mathmlContent + '</p>';
        answerSection.innerHTML += '<p>Správna odpoveď: ' + correctAnswer + '</p>';

        MathJax.typeset();

        var editorContainer = document.getElementById(id);
        var submitButton = document.getElementById('submit_' + id);

        editorContainer.remove();
        submitButton.remove();

        $.post('save_answer.php', {task_id: id, answer: mathmlContent});
    });
});

