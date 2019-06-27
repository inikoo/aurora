{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 June 2018 at 12:13:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}




{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="_block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">





                    <div  class="block reg_form">
                        <form  class="sky-form">
                            <header id="_unsubscribe_title" contenteditable="true">{$data.labels._unsubscribe_title}</header>


                            <fieldset >
                                <label id="_unsubscribe_text" contenteditable="true" class="label">{$data.labels._unsubscribe_text}</label>
                            </fieldset>

                            <fieldset style="margin-top: 20px;border-top:none">

                                <section>
                                    <label class="checkbox" style="position:relative;top:-22px;" ><input type="checkbox" name="subscription" id="subscription"><i></i> </label>
                                    <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_newsletter"
                                          contenteditable="true">{$data.labels._newsletter}</span>
                                    <label class="checkbox"  style="position:relative;top:-20px;"><input type="checkbox" name="terms" id="terms"><i></i> </label>
                                    <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_marketing_emails"
                                          contenteditable="true">{$data.labels._marketing_emails}</span>


                                </section>
                            </fieldset>


                            <footer>
                                <button type="submit" class="button " id="_save_unsubscribe_label" contenteditable="true">{$data.labels._save_unsubscribe_label}</button>
                            </footer>
                        </form>
                    </div>







</div>
<script>

  
    function change_block(element) {

        $('.block').addClass('hide')
        $('#' + $(element).attr('block')).removeClass('hide')

        $('.sidebar_widget .block_link').removeClass('selected')
        $(element).addClass('selected')
    }




    function show_edit_input(element) {
        offset = $(element).closest('section').offset();
        $('#input_editor').removeClass('hide').offset({
            top: offset.top, left: offset.left - 35}).data('element',element)
        $('#input_editor_placeholder').val($(element).closest('label').find('input').attr('placeholder'))
        $('#input_editor_tooltip').val($(element).closest('label').find('b').html())
    }

    function save_edit_input() {

        var element = $('#input_editor').data('element')
        $(element).closest('label').find('input').attr('placeholder', $('#input_editor_placeholder').val())
    $(element).closest('label').find('b').html($('#input_editor_tooltip').val())

        $('#input_editor').addClass('hide')
        $('#save_button', window.parent.document).addClass('save button changed valid')
    }


    $('.order_number').each(function (i, obj) {
        $(obj).html(Math.floor((Math.random() * 30000) + 10000))
    })

 

    function show_address_labels_editor() {
        offset_form = $('.reg_form').offset();
        offset_address_fields = $('#address_fields').offset();


        $('#address_labels_editor').removeClass('hide').offset({
            top: offset_address_fields.top,
            left: offset_form.left
        });

    }

    function save_address_labels() {
        $('#address_labels_editor').addClass('hide')
        var element = $('#' + $('#input_editor').attr('element_id'))

        $('#save_button', window.parent.document).addClass('save button changed valid')
    }


    $('#address_labels_editor input').on('input propertychange', function() {
        $('#save_button', window.parent.document).addClass('save button changed valid')

    });

    $('.poll_queries').sortable({

        items:".poll_query_section",
        handle: '.handle',

        stop: function (event, ui) {

            $('#save_button', window.parent.document).addClass('save button changed valid')

        }

    })


</script>
