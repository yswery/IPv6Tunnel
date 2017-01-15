$( document ).ready(function() {
    $('#addPrefixPoolModel .save-prefix-to-pool').click(function() {
        var params = {
            "ip": $('#addPrefixPoolModel #prefix-ip').val(),
            "cidr": $('#addPrefixPoolModel #prefix-cidr').val(),
            "server": $('#addPrefixPoolModel #prefix-server option:selected').val()
        };
        console.log(params);
    });
});
