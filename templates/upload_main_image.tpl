<form id="add_image_form" method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate
      class="box main_image wraptocenter {$class}">
    <div class="box__input">
        <input type="file" name="files[]" id="file" class="box__file hide"
               data-multiple-caption="*count* files selected" multiple/>
        <label for="file">{t}Click to choose a file{/t} <span class="box__dragndrop"><br/>
                {t}or drag it here{/t}</span>.</label>
        <button type="submit" class="box__button hide">{t}Upload{/t}</button>
    </div>
    <div class="box__uploading">
        {t}Uploading{/t}&hellip;
    </div>
    <div class="box__success">
    </div>
    <div class="box__error">

    </div>
</form>


<script>
    'use strict';

    (function ($, window, document, undefined) {


        $('.box').each(function () {
            var $form = $(this),
                    $input = $form.find('input[type="file"]'),
                    $label = $form.find('label'),
                    $errorMsg = $form.find('.box__error span'),
                    $restart = $form.find('.box__restart'),
                    droppedFiles = false,
                    showFiles = function (files) {
                        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('*count*', files.length) : files[0].name);
                    };

            // letting the server side to know we are going to make an Ajax request
            $form.append('<input type="hidden" name="ajax" value="1" />');

            // automatically submit the form on file select
            $input.on('change', function (e) {
                showFiles(e.target.files);


                $form.trigger('submit');


            });


            // drag&drop files if the feature is available
            if (isAdvancedUpload) {
                $form.addClass('has-advanced-upload') // letting the CSS part to know drag&drop is supported by the browser
                        .on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                            // preventing the unwanted behaviours
                            e.preventDefault();
                            e.stopPropagation();
                        }).on('dragover dragenter', function () //
                {
                    $form.addClass('is-dragover');
                }).on('dragleave dragend drop', function () {
                    $form.removeClass('is-dragover');
                }).on('drop', function (e) {
                    droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
                    showFiles(droppedFiles);


                    $form.trigger('submit'); // automatically submit the form on file drop
                });
            }


            // if the form was submitted
            $form.on('submit', function (e) {
                // preventing the duplicate submissions if the current one is in progress
                if ($form.hasClass('is-uploading')) return false;

                $form.addClass('is-uploading').removeClass('is-error');

                if (isAdvancedUpload) // ajax file upload for modern browsers
                {
                    e.preventDefault();

                    // gathering the form data
                    var ajaxData = new FormData($form.get(0));

                    //var ajaxData = new FormData( );
                    if (droppedFiles) {
                        $.each(droppedFiles, function (i, file) {
                            ajaxData.append($input.attr('name'), file);
                        });
                    }

                    ajaxData.append("tipo", 'upload_images')
                    ajaxData.append("parent", '{$object}')
                    ajaxData.append("parent_key", '{$key}')

                    $.ajax({
                        url: "/ar_upload.php",
                        type: 'POST',
                        data: ajaxData,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,


                        complete: function () {
                            $form.removeClass('is-uploading');
                        },
                        success: function (data) {
                            console.log(data)
                            if (data.state == 200) {

                                if (data.number_images > 0) {


                                    $('#main_image').removeClass('hide')
                                    $('#add_image_form').addClass('hide')
                                    $('#main_image img').attr("src", '/image_root.php?id=' + data.main_image_key + '&amp;size=small')

                                    var imagetabReg = /\.images$/
                                    if (imagetabReg.test(state.tab)) {
                                        rows.fetch({
                                            reset: true
                                        });
                                    }

                                } else {
                                    $form.addClass('is-error')
                                    $errorMsg.text(data.error);
                                }

                            } else {
                                $form.addClass('is-error')
                                $errorMsg.text(data.error);
                            }


                        },
                        error: function () {
                            console.log('Error. Please, contact the webmaster!');
                        }
                    });
                } else // fallback Ajax solution upload for older browsers
                {
                    var iframeName = 'uploadiframe' + new Date().getTime(),
                            $iframe = $('<iframe name="' + iframeName + '" style="display: none;"></iframe>');

                    $('body').append($iframe);
                    $form.attr('target', iframeName);

                    $iframe.one('load', function () {
                        var data = $.parseJSON($iframe.contents().find('body').text());
                        $form.removeClass('is-uploading').addClass(data.success == true ? 'is-success' : 'is-error').removeAttr('target');
                        if (!data.success) $errorMsg.text(data.error);
                        $iframe.remove();
                    });
                }
            });


            // restart the form if has a state of error/success
            $restart.on('click', function (e) {
                e.preventDefault();
                $form.removeClass('is-error is-success');
                $input.trigger('click');
            });

            // Firefox focus bug fix for file input
            $input.on('focus', function () {
                $input.addClass('has-focus');
            }).on('blur', function () {
                $input.removeClass('has-focus');
            });
        });

    })(jQuery, window, document);

</script>	
		
				