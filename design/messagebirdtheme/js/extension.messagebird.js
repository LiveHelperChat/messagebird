(function() {

    $('#template-to-send').change(function() {
        $.postJSON(WWW_DIR_JAVASCRIPT + '/messagebird/rendersend/' + $(this).val(), {'data': JSON.stringify(messageFieldsValues)}, function(data) {
            $('#arguments-template').html(data.preview);
            $('#arguments-template-form').html(data.form);
        });
    });

    if ($('#template-to-send').val() != '') {
        $.postJSON(WWW_DIR_JAVASCRIPT + '/messagebird/rendersend/' + $('#template-to-send').val(), {'data': JSON.stringify(messageFieldsValues)}, function(data) {
            $('#arguments-template').html(data.preview);
            $('#arguments-template-form').html(data.form);
        });
    }

})();

