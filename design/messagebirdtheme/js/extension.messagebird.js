(function() {
    $('#template-to-send').change(function() {
        $.getJSON(WWW_DIR_JAVASCRIPT + '/messagebird/rendersend/' + $(this).val(), function(data) {
            $('#arguments-template').html(data.preview);
            $('#arguments-template-form').html(data.form);
        });
    });
})();

