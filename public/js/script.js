$( document ).ready(function() {

    $('.test-ssh').click(function (){
        var element = $(this);
        var serverId = element.data('server-id');

        // Reset the results
        element.text('');

        // Check if its mid ssh test
        if (element.hasClass('fast-right-spinner')) {
            return;
        }

        element.addClass('fast-right-spinner');

        $.ajax({
            url: window.location.pathname +  '/' + serverId + '/test-ssh',
            type: 'GET',
            success: function(response) {
                if (response.status == 'ok') {
                    element.text('Working');
                } else {
                    element.text('Error');
                }

                element.removeClass('fast-right-spinner');

            },
            error: function(xhr) {
                element.text('Error');
                element.removeClass('fast-right-spinner');
            }
        });
    });

    listenSaveModal('addPrefixPoolModel', ['ip', 'cidr'], ['server'])
    listenSaveModal('addTunnelServerModel', ['address', 'name', 'city', 'ssh_password', 'ssh_port'], ['country_code'])

    function listenSaveModal(modalName, inputParams, selectParams) {
        $('#' + modalName + ' .save-modal-data').click(function() {
            var button = $(this);

            // Reset the error message
            $('#main-error').text('');
            $('span.error-msg').text('');

            // Set up the params for the post
            var params = {};

            $.each(inputParams, function(key, value) {
                params[value] = $('#' + value).val();
            });

            $.each(selectParams, function(key, value) {
                params[value] = $('#' + value + ' option:selected').val();
            });

            var errors = false;

            // Loop through all the param
            $.each(params, function(key, value) {
                // Set the red errors on screen if empty
                if (!value || 0 === value.length) {
                    $('#' + key).siblings('span.error-msg').text('This is a required field');
                    errors = true;
                }
            });

            // If there were errors stop logic
            if (errors) {
                return;
            }

            button.button('loading');
            $.ajax({
                url: window.location.pathname + '/create',
                type: 'POST',
                data: params,
                success: function(response) {
                    if (response.status === 'ok') {
                        $('#' + modalName).modal('hide');
                        location.reload();
                    } else {
                        $('#main-error').text(response.status_message);
                    }
                    button.button('reset');
                },
                error: function(xhr) {
                    // Unprocessable Entity - Laravel Error
                    if (xhr.status == 422) {
                        // Loop through errors and display them
                        $.each(xhr.responseJSON, function(key, value) {
                            $('#' + key).siblings('span.error-msg').text(value[0]);
                        });

                    } else {
                        var errorMsg = 'Error: ' + xhr.statusText + ' (Code ' + xhr.status + ')';
                        $('#main-error').text(errorMsg);
                    }
                    button.button('reset');

                }
            });
        });
    }
});
