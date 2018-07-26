// Errors
window.showFormError = (message, target) => {

    if (typeof message === 'object') {
        message = message.join('');
    }

    target.addClass('is-invalid');
    target.parent().addClass('has-error');

    if (target.next('.invalid-tooltip').length === 0) {
        target.after(
            $('<div />').addClass('invalid-tooltip')
        );
    }

    target.next('.invalid-tooltip').html(message);

};

window.clearFormError = target => {

    target.parent().removeClass('has-error');
    target.removeClass('is-invalid');
    target.next('.invalid-tooltip').remove();

};

$(document).on('focus', 'input[type="text"], input[type="email"], input[type="password"], textarea', function () {

    clearFormError($(this));

});

window.processErrors = (error, form) => {

    if (error.response.status == 422) {

        const errors = error.response.data.errors;

        $.each(
            errors,
            (field, message) => {

                let formControll = $('input[name="' + field + '"]', form);

                if (formControll.length === 0) {

                    formControll = $('#' + field, form);

                }

                showFormError(message, formControll);

            }
        )

    } else {

        const message = error.response.data.message;

        showError(message)
    }

}


// Messages
window.showError = message => {

    if (typeof message === 'object') {
        message = message.join('');
    }

    if (message.length === 0) {
        message = 'An error occurred'
    }

    return swal(
        {
            title: "Error!",
            text: message,
            icon: "error",
            dangerMode: true,
        }
    );

};

window.showMessage = message => {

    if (typeof message === 'object') {
        message = message.join('');
    }

    return swal(
        {
            title: "Success!",
            text: message,
            icon: "success",
            buttons: {
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "swal-button--success",
                    closeModal: true
                }
            },
        }
    );

}

window.showConfirmation = message => {

    return swal(
        {
            title: "Are you sure?",
            text: message,
            icon: "info",
            buttons: true,
        }
    );

}

window.showEdit = (title, form) => {

    return swal(
        {
            title: title,
            icon: "info",
            content: form,
            buttons: true,
        }
    );

}

// Spinner
window.showSpinner = target => {

    target.prop('disabled', true);

    target
        .append(
            $('<div />')
                .addClass('spinner-container')
                .append(
                    $('<i />').addClass('material-icons spinner').html('autorenew')
                )
        )
}

window.hideSpinner = target => {

    target.prop('disabled', false);

    $('.spinner-container').remove();

}

window.startProcess = () => {

    $('body')
        .append(
            $('<div />')
                .addClass('process-container')
                .append(
                    $('<i />').addClass('material-icons spinner').html('autorenew')
                )
        )

}

window.stopProcess = () => {

    $('.process-container').remove();

}


// Form
window.clearForm = form => {

    $('input[type="text"], input[type="email"], input[type="password"]', form).each(function () {
        $(this).val('');
    });

    $('textarea', form).each(function () {
        $(this).val('');
    });

}