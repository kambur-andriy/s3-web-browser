require('./bootstrap');

require('./helpers');

/**
 * Document ready
 */
$(document).ready(function () {

    $('#log_in_frm').on('submit', function (event) {

        event.preventDefault();

        const button = $(this).find('button[type="submit"]');

        showSpinner(button);

        const credentials = {
            email: $('input[name="email"]', this).val(),
            password: $('input[name="password"]', this).val(),
        };

        axios.post(
            '/account/login',
            qs.stringify(credentials)
        )
            .then(
                response => {

                    hideSpinner(button);

                    clearForm($(this));

                    window.location.href = response.data.homePage;

                }
            )
            .catch(
                error => {

                    hideSpinner(button);

                    processErrors(error, $(this));

                }
            )
    });

    $('#log_out').on('click', function (event) {

        event.preventDefault();

        axios.post(
            '/account/logout',
            qs.stringify({})
        )
            .then(
                () => {

                    window.location.href = '/';

                }
            )
            .catch(
                error => {

                    processErrors(error, $(this));

                }
            )
    });

});