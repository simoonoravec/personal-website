$('#message').on('input', function() {
    let msg = $(this);
    $('#message-length').html(msg.val().trim().length);
    if (this.scrollHeight > 500) {
        msg.height(500);
    } else {
        msg.height(140).height(this.scrollHeight);
    }
});

const validateEmail = (email) => {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,8})?$/;
    return emailReg.test(email);
}

const toggleForm = (status) => {
    $('#contactform_submitbtn').prop('disabled', !status);
    status ? $('#contactform').removeClass('disabled') : $('#contactform').addClass('disabled');
}

$('#contactform').submit(e => {
    e.preventDefault();
    toggleForm(false);

    let formdata = $('#contactform').serializeArray();
    let errors = [];

    if (formdata[0].value.length == 0 || !validateEmail(formdata[0].value)) {
        errors.push('Please enter a valid email address.');
    }

    let name_len = formdata[1].value.trim().length;
    if (name_len < 4 || name_len > 24) {
        errors.push('Name must contain 4-24 characters.');
    }

    let title_len = formdata[2].value.trim().length;
    if (title_len < 8 || title_len > 64) {
        errors.push('Title must contain 8-64 characters.');
    }

    let msg_len = formdata[3].value.trim().length;
    if (msg_len < 50 || msg_len > 3000) {
        errors.push('Message must contain 50-3000 characters.');
    }

    if (formdata[4].value.length == 0 || hcaptcha.getResponse() == 0 || hcaptcha.getResponse() != formdata[4].value) {
        errors.push('Are you sure you are not a robot?');
    }

    if (errors.length > 0) {
        let errors_str = errors.join('\r\n');
        popup('error', `Please correct the folowing error${errors.length>1 ? 's' : ''}`, errors_str, 'OK');
        toggleForm(true);
        return;
    }

    $.ajax({
        type: 'POST',
        url: '/api/contact-form',
        data: $('#contactform').serialize(),
        success: function (data) {
            if (data.success) {
                $('#contact-box').fadeOut(300, () => {
                    $('#success-splash').fadeIn(300);
                });
            } else {
                popup('error', 'Error', data.msg, 'OK');
                hcaptcha.reset();
                toggleForm(true);
            }
        },
        error: function (data) {
            popup('error', 'Error', 'An internal error has occured! Try later.', 'OK');
            hcaptcha.reset();
            toggleForm(true);
        }
    });
});
