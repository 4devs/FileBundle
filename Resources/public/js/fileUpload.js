$(function () {
    $('.fdevs-file').each(function () {
        toggleButton($(this).data('id'));
    });

    $('.fdevs-file').on('click', function () {
        var id = $(this).data('id'),
            formData = $('#file_' + id).data('form');

        if (!$(this).data('file-applied')) {
            $(':file', this).fileupload({
                dataType: 'json',
                formData: formData,
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        if (file.error) {
                            setError(file.error, id);
                        } else {
                            $('#' + id).val(file.url);
                            clearErrors(id);
                            toggleButton(id);
                            $('#delete_' + id).data('url', file.deleteUrl);
                        }
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css('width', progress + '%');
                }
            }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

            $('#file_' + id).fileupload('option',formData.validation_options);

            $(this).data('file-applied', true);
        }
    });

    $('.fdevs-file [id*=delete_]').on('click', function (e) {
        var id = $(this).parents('.fdevs-file').data('id'),
            formData = $('#file_' + id).data('form');

        e.preventDefault();
        $.ajax($(e.target).data('url'), {type: $(e.target).data('type'), data: formData})
            .done(function (data) {
                $.each(data.files, function (index, file) {
                    if (file !== true) {
                        setError(file, id);
                    } else {
                        clearErrors(id);
                    }
                    $('#' + id).val('');
                    toggleButton(id);
                });
            });
    });

    function setError(msg, id) {
        var htmlError = '<ul><li>' + msg + '</li></ul>',
            error = $('#error_' + id);

        if (error.size()) {
            error.html(htmlError);
        } else {
            $('#group_' + id).before('<div class="help-block" id="error_' + id + '">' + htmlError + '</div>');
            $("div[id$='" + id + "']").addClass('has-error');
        }
    }

    function toggleButton(id) {
        var file = !!$('#' + id).val();

        $('#select_' + id).toggleClass('disabled', file);
        $('#delete_' + id).toggleClass('disabled', !file);
    }

    function clearErrors(id) {
        $('#' + id).addClass('has-success');
        $('#select_' + id).addClass('disabled');
        $('#delete_' + id).removeClass('disabled');
        $('#error_' + id).remove();
        $("div[id$='" + id + "']").removeClass('has-error');
    }
});
