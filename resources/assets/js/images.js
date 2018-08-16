require('./bootstrap');

require('./helpers');


const buildTagsList = () => {

    axios.get(
        '/api/tags',
        {}
    )
        .then(
            response => {

                $.each(response.data.tags_categories_list, function (index, category) {

                    $('#image_frm .form-group')
                        .last()
                        .before(
                            $('<div />')
                                .addClass('form-group')
                                .append(
                                    $('<label />')
                                        .addClass('bmd-label-floating')
                                        .attr('for', 'category_' + category.id)
                                        .text(category.name)
                                )
                                .append(
                                    $('<select />')
                                        .addClass('form-control images-tags')
                                        .attr('id', 'category_' + category.id)
                                        .append(
                                            $('<option />')
                                                .attr('selected', true)
                                                .text('Select tag ...')
                                                .val(0)
                                        )
                                )
                        )

                    $('#images_list #images_list_headers th')
                        .last()
                        .before(
                            $('<th />')
                                .addClass('text-info tag-category')
                                .attr('id', 'il_cat_' + category.id)
                                .text(category.name)
                        )

                    $('#images_list #images_list_filters th')
                        .last()
                        .before(
                            $('<th />')
                                .addClass('text-info')
                                .append(
                                    $('<select />')
                                        .addClass('form-control images-tags-filters text-primary')
                                        .attr('id', 'filter_cat_' + category.id)
                                        .append(
                                            $('<option />')
                                                .addClass('text-primary')
                                                .attr('selected', true)
                                                .val(0)
                                                .text('...')
                                        )
                                )
                        )

                });

                $.each(response.data.tags_list, function (index, tag) {

                    $('#category_' + tag.category.id)
                        .append(
                            $('<option />')
                                .data('parent_tag', tag.parent_tag)
                                .text(tag.name)
                                .val(tag.id)
                        )

                    $('#filter_cat_' + tag.category.id)
                        .append(
                            $('<option />')
                                .text(tag.name)
                                .val(tag.id)
                        )

                });

            }
        )
        .catch(
            () => {

                showError('Error loading tags');

            }
        )

};

const buildImagesList = () => {

    axios.get(
        '/api/images',
        {}
    )
        .then(
            response => {

                $.each(response.data, function (index, image) {

                    addImage(image);

                });

            }
        )
        .catch(
            () => {

                showError('Error loading images');

            }
        )

};

const addImage = image => {


    $('#images_list')
        .prepend(
            $('<tr />')
                .attr('id', image.id)
                .append(
                    $('<td />')
                        .html(image.name)
                )
                .append(
                    $('<td />')
                        .html(image.path)
                )
                .append(
                    $('<td />')
                        .addClass('td-actions text-right')
                        .append(
                            $('<button />')
                                .addClass('btn btn-danger btn-link btn-sm remove-image-btn')
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

    $('.tag-category').each(function () {

        const id = $(this).attr('id');

        $('#images_list #' + image.id + ' td')
            .last()
            .before(
                $('<td />')
                    .addClass(id)
                    .text('')
            )
    });

    $.each(image.tags, function (index, imageTag) {

        if (!imageTag.tag) {

            return;

        }

        const category = imageTag.tag.category_id;

        $('#images_list #' + image.id + ' td.il_cat_' + category)
            .text(imageTag.tag.name)

    });

};

const getContent = (path = '.') => {

    startProcess();

    axios.get(
        '/api/list',
        {
            params: {
                path
            }

        }
    )
        .then(
            response => {

                stopProcess();

                $('#content_list tbody').empty();

                showInfo(response.data.info)
                showNavigation(response.data.quick_navigation)
                showDirectories(
                    sortByName(response.data.directories)
                );
                showFiles(
                    sortByName(response.data.files)
                );

            }
        )
        .catch(
            error => {

                stopProcess();

                showError(error.response.data.message);

            }
        )

}

const showDirectories = directoriesList => {

    $.each(directoriesList, function (index, directory) {

        $('#content_list tbody')
            .append(
                $('<tr />')
                    .append(
                        $('<td />')
                            .addClass('td-type text-left')
                            .append(
                                $('<i />')
                                    .addClass('material-icons text-gray text-center')
                                    .text('folder')
                            )
                    )
                    .append(
                        $('<td />')
                            .append(
                                $('<a />')
                                    .addClass('text-gray change-dir')
                                    .attr('href', directory.path)
                                    .text(directory.name)
                            )
                    )
            )

    });

}

const showFiles = filesList => {

    $.each(filesList, function (index, file) {

        $('#content_list tbody')
            .append(
                $('<tr />')
                    .append(
                        $('<td />')
                            .addClass('td-type text-left')
                            .append(
                                $('<img />')
                                    .addClass('file-preview')
                                    .attr(
                                        {
                                            'src': file.url,
                                            'alt': file.name
                                        }
                                    )
                            )
                    )
                    .append(
                        $('<td />')
                            .append(
                                $('<a />')
                                    .addClass('text-gray select-media')
                                    .attr('href', file.path)
                                    .text(file.name)
                            )
                    )
            )

    });

}

const showNavigation = navigationList => {

    $('#quick_navigation .breadcrumb').empty();

    $.each(navigationList, function (index, directory) {

        $('#quick_navigation .breadcrumb')
            .append(
                $('<li />')
                    .addClass('breadcrumb-item')
                    .append(
                        $('<a />')
                            .addClass('change-dir text-primary font-weight-bold')
                            .attr('href', directory.path)
                            .text(directory.name)
                    )
            )
    });

}

const showInfo = dirInfo => {

    $('#select_all_files').prop('checked', false);

    $('#current_directory').data('path', dirInfo.dirPath);

    $('#directory_name').html(dirInfo.dirName);

    $('#dir_count').html(dirInfo.dirCount);

    $('#files_count').html(dirInfo.filesCount);

}

const sortByName = list => {

    if (!$('#sort_content').hasClass('asc') && !$('#sort_content').hasClass('desc')) {
        return list;
    }

    const sortOrder = $('#sort_content').hasClass('asc') ? -1 : 1;

    list.sort(
        (fileSource, fileDest) => {

            const digSource = parseInt(fileSource.name);

            let sourceFileName = fileSource.name;

            if (!isNaN(digSource)) {

                sourceFileName = digSource;

            }

            const digDest = parseInt(fileDest.name);

            let destFileName = fileDest.name;

            if (!isNaN(digDest)) {

                destFileName = digDest;

            }

            if ((typeof sourceFileName) != (typeof destFileName)) {

                return sortOrder;

            }

            if (sourceFileName < destFileName) {

                return sortOrder;

            }

            if (sourceFileName > destFileName) {

                return -sortOrder;

            }

            return 0;

        }
    );

    return list;

}

const showPreview = target => {

    const fileUrl = target.attr('src');
    const fileName = target.attr('alt');

    if ($('.preview-container').length !== 0) {

        $('.preview-container .file-info').html(fileName);
        $('.preview-container .preview-img').data('img_src', fileUrl).css('background-image', 'url(' + fileUrl + ')');

        return;
    }

    $('body')
        .append(
            $('<div />')
                .addClass('preview-container')
                .append(
                    $('<div />')
                        .addClass('preview-nfo mb-2')
                        .append(
                            $('<span />')
                                .addClass('file-info')
                                .html(fileName)
                        )
                        .append(
                            $('<span />')
                                .addClass('close-preview')
                                .html('&times;')
                                .on('click', function (event) {
                                    event.preventDefault();
                                    $('.preview-container').remove()
                                })
                        )
                )
                .append(
                    $('<div />')
                        .addClass('preview-img')
                        .css('background-image', 'url(' + fileUrl + ')')
                        .data('img_src', fileUrl)
                )
                .append(
                    $('<div />')
                        .addClass('preview-actions mt-2 mb-2')
                        .append(
                            $('<button />')
                                .addClass('btn btn-primary btn-round btn-just-icon')
                                .attr(
                                    {
                                        id: 'preview-prev',
                                        type: 'button',
                                    }
                                )
                                .append(
                                    $('<i />')
                                        .addClass('material-icons')
                                        .html('keyboard_arrow_left')
                                )
                        )
                        .append(
                            $('<button />')
                                .addClass('btn btn-primary btn-round btn-just-icon ml-4')
                                .attr(
                                    {
                                        id: 'preview-next',
                                        type: 'button',
                                    }
                                )
                                .append(
                                    $('<i />')
                                        .addClass('material-icons')
                                        .html('keyboard_arrow_right')
                                )
                        )
                )
        )

}

const removeImage = id => {

    const credentials = {
        id
    };

    axios.post(
        '/api/images/remove',
        qs.stringify(credentials)
    )
        .then(
            () => {

                $('#images_list').find('tr#' + id).remove();

            }
        )
        .catch(
            () => {

                showError('Error removing image.')

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

        $('#images_list tbody tr').each(function () {

            const fileName = $('td:eq(0)', $(this)).text().toLowerCase();
            const filePath = $('td:eq(1)', $(this)).text().toLowerCase();

            let notFound = (fileName.indexOf(search) === -1 && filePath.indexOf(search) === -1);

            $(this).find('td:gt(1)').not('.td-actions').each(function () {

                const tagName = $(this).text().toLowerCase();

                notFound = notFound && (tagName.indexOf(search) === -1);
            });

            if (notFound && search.length > 0) {
                $(this).hide();
            } else {
                $(this).show();
            }

        });

    });


    /**
     * Media Library
     */
    $(document).on('click', '.change-dir', function (event) {

        event.preventDefault();

        const directory = $(this).attr('href');

        getContent(directory);

    });

    $(document).on('click', '.file-preview', function (event) {

        event.preventDefault();

        showPreview($(this));

    });

    $(document).on('click', '#preview-next', function (event) {

        event.preventDefault();

        const currentPreviewImg = $('.preview-img').data('img_src');

        const currentFile = $('#content_list').find('img[src="' + currentPreviewImg + '"]')
        let nextTarget = currentFile.parents('tr').next('tr').find('.file-preview');

        if (nextTarget.length === 0) {
            nextTarget = $('#content_list .file-preview:first-child');
        }

        showPreview(nextTarget);

    });

    $(document).on('click', '#preview-prev', function (event) {

        event.preventDefault();

        const currentPreviewImg = $('.preview-img').data('img_src');

        const currentFile = $('#content_list').find('img[src="' + currentPreviewImg + '"]')
        let nextTarget = currentFile.parents('tr').prev('tr').find('.file-preview');

        if (nextTarget.length === 0) {
            nextTarget = $('#content_list .file-preview').last();
        }

        showPreview(nextTarget);

    });

    $(document).on('keydown', function (event) {

        switch (event.keyCode) {

            case 27:
                $('.close-preview').trigger('click')
                break;

            case 39:
                $('#preview-next').trigger('click')
                break;

            case 37:
                $('#preview-prev').trigger('click')
                break;

        }

    });

    $(document).on('click', '.select-media', function (event) {

        event.preventDefault();

        const path = $(this).attr('href');
        const name = $(this).text();

        $('#image_frm input[name="path"]').val(path);
        $('#image_frm input[name="name"]').val(name);

        $('#media_library .close').trigger('click');

    });


    /**
     * Handle actions
     */
    $('#image_frm').on('submit', function (event) {

        event.preventDefault();

        const button = $(this).find('button[type="submit"]');

        showSpinner(button);

        let tags = [];

        $('select', this).each(function () {

            const tagId = $(this).val();

            if (tagId === 0) {
                return;
            }

            tags.push(tagId);

        });

        const credentials = {
            path: $('input[name="path"]', this).val(),
            name: $('input[name="name"]', this).val(),
            tags_list: tags
        };

        axios.post(
            '/api/images',
            qs.stringify(credentials)
        )
            .then(
                response => {

                    hideSpinner(button);

                    clearForm($(this));

                    showMessage('Image successfully created.')
                        .then(
                            () => addImage(response.data)
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

    $('#image_frm input[name="path"]').on('click', function () {

        $('#image_frm input[name="name"]').trigger('focus');

        $('#images_library').addClass('d-none');
        $('#media_library').removeClass('d-none');

        getContent();

    });

    $('#media_library .close').on('click', function () {

        $('#images_library').removeClass('d-none');
        $('#media_library').addClass('d-none');

    });

    $(document).on('change', '#image_frm select', function (event) {

        event.preventDefault();

        const selectedTagId = parseInt($(this).val());

        $('#image_frm option').each(function () {

            $(this).show();

            const parentTag = $(this).data('parent_tag');

            if (!parentTag) {
                return;
            }

            if (parentTag.id === 0) {
                return;
            }

            if (parentTag.id === selectedTagId) {
                return;
            }

            $(this).hide();

        });

    });

    $(document).on('change', '#images_list select', function (event) {

        event.preventDefault();

        let selectedFilters = [];

        $('.images-tags-filters').each(function () {

            const id = $(this).attr('id').replace('filter', 'il');
            const filterVal = $(this).find('option:selected').text().toLowerCase();

            if (parseInt($(this).val()) !== 0) {
                selectedFilters[id] = filterVal;
            }

        });


        $('#images_list tbody tr').each(function () {

            let notFound = false;

            let row = $(this);

            $('.images-tags-filters').each(function () {

                if (parseInt($(this).val()) === 0) {
                    return;
                }

                const id = $(this).attr('id').replace('filter', 'il');
                const filterName = $(this).find('option:selected').text().toLowerCase();

                const tagName = $('.' + id, row).text().toLowerCase();

                if (tagName !== filterName) {
                    notFound = true;

                    return;
                }

            });

            if (notFound) {
                row.hide();
            } else {
                row.show();
            }

        });

    });

    $(document).on('click', '.remove-image-btn', function (event) {

        event.preventDefault();

        const id = $(this).parents('tr').attr('id');

        showConfirmation('You are trying to delete image.')
            .then(
                value => {

                    if (value) {
                        removeImage(id)
                    }

                }
            )

    });


    /**
     * Start application
     */
    buildTagsList();

    buildImagesList();


});