$( document ).ready(function() {


    listenSaveModal('addPrefixPoolModel', ['ip', 'cidr'], ['server'])
    listenSaveModal('addTunnelServerModel', ['address', 'name', 'city', 'ssh_password', 'ssh_port'], ['country_code'])

    function listenSaveModal(modalName, inputParams, selectParams) {
        $('#' + modalName + ' .save-modal-data').click(function() {

            // Resest the main error message
            $('#main-error').text('');

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
            $.each(params, function( key, value ) {
                // Set the red errors on screen if empty
                if (!value || 0 === value.length) {
                    $('#' + key).siblings("span.error-msg").text('This is a required field');
                    errors = true;
                } else {
                    $('#' + key).siblings("span.error-msg").text('');
                }
            });

            // If there were errors stop logic
            if (errors) {
                return;
            }

            $.ajax({
                url: window.location.pathname + "/new",
                type: "POST",
                data: params,
                success: function(response) {
                    if (response.status === 'ok') {
                        $('#' + modalName).modal('hide');
                    } else {
                        $('#main-error').text(response.status_message);
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Error: ' + xhr.statusText + ' (Code ' + xhr.status + ')';
                    $('#main-error').text(errorMsg);
                }
            });

        });
    }
});
