require('./bootstrap');

require('./helpers');


const buildTagsList = () => {

    axios.get(
        '/api/tags',
        {}
    )
        .then(
            response => {

                $.each(response.data.tags_categories_list, function(index, category) {

                   addCategory(category);

                });

                $.each(response.data.tags_list, function(index, tag) {

                    addTag(tag);

                });

            }
        )
        .catch(
            error => {

                processErrors(error, $(this));

            }
        )

};

const addCategory = category => {

    $('#categories_list')
        .prepend(
            $('<tr />')
                .attr('id', category.id)
                .append(
                    $('<td />')
                        .html(category.name)
                )
                .append(
                    $('<td />')
                        .addClass('td-actions text-right')
                        .append(
                            $('<button />')
                                .addClass('btn btn-danger btn-link btn-sm remove-category-btn')
                                .attr(
                                    {
                                        'type': 'button',
                                    }
                                )
                                .append(
                                    $('<i />')
                                        .addClass('material-icons')
                                        .text('delete_forever')
                                )
                        )

                )
        )

    $('#tag_frm select[name="category"]')
        .prepend(
            $('<option />')
                .val(category.id)
                .text(category.name)
        )
};

const addTag = tag => {

    $('#tags_list')
        .prepend(
            $('<tr />')
                .attr('id', tag.id)
                .append(
                    $('<td />')
                        .html(tag.name)
                )
                .append(
                    $('<td />')
                        .html(tag.category.name)
                )
                .append(
                    $('<td />')
                        .html(tag.parent_tag.name)
                )
                .append(
                    $('<td />')
                        .addClass('td-actions text-right')
                        .append(
                            $('<button />')
                                .addClass('btn btn-danger btn-link btn-sm remove-tag-btn')
                                .attr(
                                    {
                                        'type': 'button',
                                    }
                                )
                                .append(
                                    $('<i />')
                                        .addClass('material-icons')
                                        .text('delete_forever')
                                )
                        )

                )
        )

    $('#tag_frm select[name="parent_tag"]')
        .prepend(
            $('<option />')
                .val(tag.id)
                .text(tag.name)
        )
};

const removeCategory = id => {

    const credentials = {
        id
    };

    axios.post(
        '/api/tags/categories/remove',
        qs.stringify(credentials)
    )
        .then(
            () => {

                $('#categories_list').find('tr#'+ id).remove();

            }
        )
        .catch(
            error => {

                processErrors(error, $(this));

            }
        )

}

const removeTag = id => {

    const credentials = {
        id
    };

    axios.post(
        '/api/tags/remove',
        qs.stringify(credentials)
    )
        .then(
            () => {

                $('#tags_list').find('tr#'+ id).remove();

            }
        )
        .catch(
            error => {

                processErrors(error, $(this));

            }
        )

}

/**
 * Document ready
 */
$(document).ready(function () {


    /**
     * Search on the page
     */
    $('#search_form input[type="text"]').on('keyup', function (event) {

        // Clear form
        if (event.keyCode === 27) {

            $('#clear_search').trigger('click');

        }

        // Toggle visibility of the clear button
        if ($(this).val().length === 0) {

            $('#clear_search').addClass('d-none');

        } else {

            $('#clear_search').removeClass('d-none');

        }

    });

    $('#clear_search').on('click', function () {

        $('#search_form input[type="text"]').val('');

        $('#search_form').trigger('submit');

        $(this).addClass('d-none');

    });

    $('#search_form').on('submit', function (event) {

        event.preventDefault();

        const search = $('input[type="text"]', $(this)).val().toLowerCase();

        $('#categories_list tbody tr').each(function () {

            const categoryName = $('td:eq(0)', $(this)).text().toLowerCase();

            const notFound = (categoryName.indexOf(search) === -1);

            if (notFound && search.length > 0) {
                $(this).hide();
            } else {
                $(this).show();
            }

        });

        $('#tags_list tbody tr').each(function () {

            const tagName = $('td:eq(0)', $(this)).text().toLowerCase();
            const tagCategory = $('td:eq(1)', $(this)).text().toLowerCase();
            const tagParent = $('td:eq(2)', $(this)).text().toLowerCase();

            const notFound = (tagName.indexOf(search) === -1 && tagCategory.indexOf(search) === -1 && tagParent.indexOf(search) === -1);

            if (notFound && search.length > 0) {
                $(this).hide();
            } else {
                $(this).show();
            }

        });

    });


    /**
     * Handle actions
     */
    $('#tag_category_frm').on('submit', function (event) {

        event.preventDefault();

        const button = $(this).find('button[type="submit"]');

        showSpinner(button);

        const credentials = {
            name: $('input[name="name"]', this).val(),
        };

        axios.post(
            '/api/tags/categories',
            qs.stringify(credentials)
        )
            .then(
                response => {

                    hideSpinner(button);

                    clearForm($(this));

                    showMessage('Category successfully created.')
                        .then(
                            () => addCategory(response.data)
                        )

                }
            )
            .catch(
                error => {

                    hideSpinner(button);

                    processErrors(error, $(this));

                }
            )
    });

    $('#tag_frm').on('submit', function (event) {

        event.preventDefault();

        const button = $(this).find('button[type="submit"]');

        showSpinner(button);

        const credentials = {
            name: $('input[name="name"]', this).val(),
            category: $('select[name="category"]', this).val(),
            parent_tag: $('select[name="parent_tag"]', this).val(),
        };

        axios.post(
            '/api/tags',
            qs.stringify(credentials)
        )
            .then(
                response => {

                    hideSpinner(button);

                    clearForm($(this));

                    showMessage('Tag successfully created.')
                        .then(
                            () => addTag(response.data)
                        )

                }
            )
            .catch(
                error => {

                    hideSpinner(button);

                    processErrors(error, $(this));

                }
            )
    });

    $(document).on('click', '.remove-category-btn', function(event) {

        event.preventDefault();

        const id = $(this).parents('tr').attr('id');

        showConfirmation('You are trying to delete tags category.')
            .then(
                value => {

                    if (value) {
                        removeCategory(id)
                    }

                }
            )

    });

    $(document).on('click', '.remove-tag-btn', function(event) {

        event.preventDefault();

        const id = $(this).parents('tr').attr('id');

        showConfirmation('You are trying to delete tag.')
            .then(
                value => {

                    if (value) {
                        removeTag(id)
                    }

                }
            )

    });

    buildTagsList();

});