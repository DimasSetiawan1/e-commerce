$(function() {
    if (localStorage.chkbx && localStorage.chkbx != '') {
        $('#chkbx').attr('checked', 'checked');
        $('#email').val(localStorage.email);
        $('#password').val(localStorage.password);
    } else {
        $('#chkbx').removeAttr('checkedd');
        $('#email').val('');
        $('#password').val('');
    }

    $('#chkbx').click(function() {
        if ($('#chkbx').is(':checked')) {
            localStorage.email = $('#email').val();
            localStorage.password = $('#password').val();
            localStorage.chkbx = $('#chkbx').val();
        } else {
            localStorage.email = '';
            localStorage.password = '';
            localStorage.chkbx = '';
        }
    });
});