{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 January 2019 at 12:28:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<style>
    .mixed_recipients_table td.operations{
        width: 24px;
        text-align: center;
    }
</style>

<table border=0 class="mixed_recipients_container" data-field="{$field.id}" data-added="0" data-removed="0" class="{if $mode=='edit'}hidex{/if}  ">
    <tr class="bold {if $mixed_recipients.external_emails|@count == 0   and  $mixed_recipients.user_keys|@count == 0 }hide{/if}"    >
        
        <td class="operations"></td>
        <td class="recipient">{t}Recipient{/t}</td>
    </tr>
    <tbody class="users_recipients_items">
    {include file="users_recipients_items.edit.tpl" users=$mixed_recipients.users}
    </tbody>
    <tbody class="external_emails_recipients_items">
    {include file="external_emails_recipients_items.edit.tpl" external_emails=$mixed_recipients.external_emails}
    </tbody>

    <tr class="new_user_recipient user_tr new in_process hide">
        <td class="operations">
            <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
            <input type="hidden" class="user_recipient_value " value="" ovalue="">
        </td>
        <td class="mixed_recipients">
            <input type="hidden" class="user_recipient_value user_key" value="" ovalue="">
            <span class="User_Handle hide"></span>
            <input class="User_Handle_value" value="" ovalue="" placeholder="{t}User{/t}" parent_key="1"
                   parent="account" scope="users">
            <div class="search_results_container">
                <table class="results" border="1">
                    <tr class="hide search_result_template" field="" value="" formatted_value=""
                        onclick="select_dropdown_user_recipient(this)">
                        <td class="code"></td>
                        <td style="width:85%" class="label"></td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="new_external_email_recipient user_tr new in_process hide">
        <td class="operations">
            <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
        </td>
        <td class="mixed_recipients">
            <input class="potentially_valid" value="" ovalue=""   placeholder="{t}External email{/t}">

        </td>
    </tr>
    <tr class="add_new_mixed_recipients_tr">

        <td colspan="2">
            <span onclick="add_user_to_mixed_recipients(this)" class="button">{t}Add user{/t} <i class="fa fa-plus"></i></span>
            <span onclick="add_external_email_to_mixed_recipients(this)" class="button padding_left_10">{t}Add external email{/t} <i class="fa fa-plus"></i></span>

            <span   onclick="save_mixed_recipients(this)" class=" save padding_left_50 {if $mode=='new'}hide{/if} ">{t}Save{/t} <i id="{$field.id}_save_button" class="fa fa-cloud  "></i></span>

        </td>

    </tr>
</table>

<script>


    function remove_recipient(element) {

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');

        if ($(element).closest('tr').hasClass('new')) {

            $(element).closest('tr').remove()



            mixed_recipients_container.data('added', mixed_recipients_container.data('added')-1)


        } else {
            if ($(element).closest('tr').hasClass('very_discreet')) {

                mixed_recipients_container.data('removed',mixed_recipients_container.data('removed')+1)
                
                $(element).closest('tr').removeClass('very_discreet')
                $(element).closest('tr').find('.User_Handle').removeClass('deleted')
                $(element).closest('tr').find('.user_recipient_value').prop('readonly', false);

            } else {
                $(element).closest('tr').addClass('very_discreet')
                $(element).closest('tr').find('.User_Handle').addClass('deleted')
                $(element).closest('tr').find('.user_recipient_value').prop('readonly', true);
                mixed_recipients_container.data('removed',mixed_recipients_container.data('removed')-1)

            }
        }


        

        validate_mixed_recipients_list(mixed_recipients_container)

    }


    function delayed_on_change_user_recipient_dropdown_select_field(object, timeout) {
       
        var new_value = $(object).val()


        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_user_recipient_dropdown_select(object, new_value)
        }, timeout));
    }

    function get_user_recipient_dropdown_select(object, new_value) {

        var parent_key = $(object).attr('parent_key')
        var parent = $(object).attr('parent')
        var scope = $(object).attr('scope')


        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&parent=' + parent + '&parent_key=' + parent_key + '&state=' + JSON.stringify(state)
        console.log(request)
        $.getJSON(request, function (data) {

            var results_container = $(object).closest('td.mixed_recipients').find('.search_results_container')


            if (data.number_results > 0) {
                results_container.removeClass('hide').addClass('show')
            } else {


                results_container.addClass('hide').removeClass('show')

            }

            results_container.find('.result').remove();



            var first = true;

            for (var result_key in data.results) {

                var clone = results_container.find('.search_result_template').clone()


                clone.addClass('result').removeClass('hide search_result_template')
                clone.attr('value', data.results[result_key].value)
                clone.attr('formatted_value', data.results[result_key].formatted_value)
                clone.data('metadata', data.results[result_key].metadata)

                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".code").html(data.results[result_key].code)
                clone.children(".label").html(data.results[result_key].description)

                results_container.find(".results").append(clone)
                    }

        })


    }

    function select_dropdown_user_recipient(element) {

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');


        $(element).closest('.user_tr').removeClass('in_process')
      //  $(element).closest('.user_tr').find('.fa-trash-alt').removeClass('fa-trash').addClass('fa-trash')

        $(element).closest('td.mixed_recipients').find('.User_Handle').html($(element).attr('formatted_value')).removeClass('hide')
        $(element).closest('td.mixed_recipients').find('.User_Handle_value').remove()

        $(element).closest('td.mixed_recipients').find('.user_key').val($(element).attr('value'))


        $(element).closest('td.mixed_recipients').find('.search_results_container').remove()


        mixed_recipients_container.data('added', mixed_recipients_container.data('added')+1)
        
        
        validate_mixed_recipients_list(mixed_recipients_container)

    }




    function add_user_to_mixed_recipients(element) {

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');



        var clone = mixed_recipients_container.find('.new_user_recipient').clone()
        clone.prop('id','')
        clone.removeClass('hide new_user_recipient')


        mixed_recipients_container.find(".users_recipients_items").append(clone);

        clone.find('.User_Handle_value').focus()


    }

    function add_external_email_to_mixed_recipients(element){

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');


        var clone = mixed_recipients_container.find('.new_external_email_recipient').clone()
        clone.prop('id', '')
        clone.removeClass('hide new_external_email_recipient').find('input').addClass('external_email_mixed_recipients_value')
        mixed_recipients_container.find(".external_emails_recipients_items").append(clone);
        clone.find('.external_email_mixed_recipients_value').focus()

        mixed_recipients_container.data('added',mixed_recipients_container.data('added')+1)
        validate_mixed_recipients_list(mixed_recipients_container)
    }

    //

    function save_mixed_recipients(element) {
        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');

        save_field($('#fields').attr('object'), $('#fields').attr('key'),mixed_recipients_container.data('field'))
    }


    $(document).on('input propertychange', '.external_email_mixed_recipients_value', function (evt) {

        var email=$(this).val();
        var validation_data=client_validation('email',true,email,'')




        $(this).removeClass('invalid potentially_valid valid').addClass(validation_data.class)



        var mixed_recipients_container=$(this).closest('.mixed_recipients_container');

        validate_mixed_recipients_list(mixed_recipients_container)

    });


        $(document).on('input propertychange', '.mixed_recipients_container .User_Handle_value', function (evt) {


        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_user_recipient_dropdown_select_field($(evt.target), delay)
    });



    function validate_mixed_recipients_list(mixed_recipients_container) {



        var validation = 'valid';

        $('.external_email_mixed_recipients_value',mixed_recipients_container).each(function (i, obj) {

           if($(obj).hasClass('invalid')){
               validation = 'invalid';
               return false
           }
            if($(obj).hasClass('potentially_valid')){
                validation = 'potentially_valid';
                return false
            }

        })



        console.log(validation)

        var save_element=  mixed_recipients_container.find('.save')

        var save_field=  $('#'+mixed_recipients_container.data('field')+'_field')


        save_element.removeClass('invalid valid potentially_valid').addClass(validation)
        save_field.removeClass('invalid valid potentially_valid').addClass(validation)



        if( mixed_recipients_container.data('added')>0 || mixed_recipients_container.data('removed')>0){
            save_element.addClass('changed')
            save_field.addClass('changed')
        }else{
            save_element.removeClass('changed valid')
            save_field.removeClass('changed valid')

        }


    }


    

</script>