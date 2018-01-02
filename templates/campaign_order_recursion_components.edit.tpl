{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 December 2017 at 13:19:52 GMT, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<script>

    function edit_component_allowance(element) {
        $('#campaign_order_recursion_components_edit_dialog').removeClass('hide').offset({
            top: $(element).offset().top - 5, left: $(element).offset().left
        }).attr('key', $(element).attr('key'))
        $('#campaign_order_recursion_components_edit_dialog').find('.target').html($(element).attr('target'))
        $('#campaign_order_recursion_components_edit_dialog').find('.description').val($(element).attr('description'))
        $('#campaign_order_recursion_components_edit_dialog').find('.allowance').val($(element).attr('allowance'))

        $('#campaign_order_recursion_components_edit_dialog').find('.save').removeClass('error changed valid').find('i').removeClass('fa-spinner fa-spin')
        $('#campaign_order_recursion_components_status_edit_dialog').addClass('hide')

    }

    $(document).on('input propertychange', '#campaign_order_recursion_components_edit_dialog input', function (evt) {

        validate_edit_component_allowance()
    })


    function edit_component_status(element) {
        $('#campaign_order_recursion_components_status_edit_dialog').removeClass('hide').offset({
            top: $(element).offset().top - 5, left: $(element).offset().left
        }).attr('key', $(element).attr('key'))


        $('#campaign_order_recursion_components_status_edit_dialog').find('.target').html($(element).attr('target'))

        $('#campaign_order_recursion_components_status_edit_dialog').find('.operation').removeClass('hide')

        $('#campaign_order_recursion_components_status_edit_dialog').find('.' + $(element).attr('status')).addClass('hide')

        $('#campaign_order_recursion_components_edit_dialog').addClass('hide')


    }

    $(document).on('input propertychange', '#campaign_order_recursion_components_edit_dialog input', function (evt) {

        validate_edit_component_allowance()
    })


    function validate_edit_component_allowance() {

        var error = false;

        allowance = $('#campaign_order_recursion_components_edit_dialog').find('.allowance')
        description = $('#campaign_order_recursion_components_edit_dialog').find('.description')


        validation_allowance = client_validation('percentage', true, allowance.val())

        if (validation_allowance.class == 'valid') {

            allowance.removeClass('error')
        } else {

            error = true;
            allowance.addClass('error')

        }

        if (description.val() == '') {
            error = true;
            description.addClass('error')
        } else {
            description.removeClass('error')

        }

        if (error) {
            $('#campaign_order_recursion_components_edit_dialog').find('.save').addClass('error').removeClass('valid')
        } else {
            $('#campaign_order_recursion_components_edit_dialog').find('.save').removeClass('error').addClass('changed valid')

        }


        console.log(validation_allowance)


    }

    function save_edit_component_allowance(element) {

        if (!$(element).hasClass('valid') || $(element).hasClass('wait')) {

            return;
        }

        $(element).addClass('wait')

        $(element).find('i').addClass('fa-spinner fa-spin')


        // used only for debug
        var request = '/ar_edit_marketing.php?tipo=edit_campaign_order_recursion_data&deal_component_key=' + $('#campaign_order_recursion_components_edit_dialog').attr('key') + '&allowance=' + $('#campaign_order_recursion_components_edit_dialog').find('.allowance').val() + '&description=' + $('#campaign_order_recursion_components_edit_dialog').find('.description').val()

        console.log(request)

        //=====
        var form_data = new FormData();
        form_data.append("tipo", 'edit_campaign_order_recursion_data')
        form_data.append("deal_component_key", $('#campaign_order_recursion_components_edit_dialog').attr('key'))
        form_data.append("allowance", $('#campaign_order_recursion_components_edit_dialog').find('.allowance').val())
        form_data.append("description", $('#campaign_order_recursion_components_edit_dialog').find('.description').val())


        var request = $.ajax({

            url: "/ar_edit_marketing.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })

        request.done(function (data) {


            $('#campaign_order_recursion_components_edit_dialog').addClass('hide')


            $('#deal_component_description_' + data.deal_component_key).html(data.description)
            $('#deal_component_allowance_' + data.deal_component_key).html(data.allowance)

            for (var key in data.updated_fields) {
                //console.log(key)
                //console.log(data.updated_fields[key])


                $('.' + key).html(data.updated_fields[key])
            }


        })

        request.fail(function (jqXHR, textStatus) {
        });

    }

    function save_edit_component_status(element) {


        var td = $(element).closest('td')
        var element = $(element)

        if ($(element).hasClass('wait')) {

            return;
        }

        td.addClass('wait')

        $(element).addClass('fa-spinner fa-spin')


        // used only for debug
        var request = '/ar_edit_marketing.php?tipo=edit_campaign_component_status&deal_component_key=' + $('#campaign_order_recursion_components_status_edit_dialog').attr('key') + '&status=' + element.attr('status')

        console.log(request)

        //=====
        var form_data = new FormData();
        form_data.append("tipo", 'edit_campaign_component_status')
        form_data.append("deal_component_key", $('#campaign_order_recursion_components_status_edit_dialog').attr('key'))
        form_data.append("status",  element.attr('status'))


        var request = $.ajax({

            url: "/ar_edit_marketing.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })

        request.done(function (data) {


            $('#campaign_order_recursion_components_status_edit_dialog').addClass('hide')


            $('#deal_component_status_' + data.deal_component_key).html(data.status)

            for (var key in data.updated_fields) {
                //console.log(key)
                //console.log(data.updated_fields[key])


                $('.' + key).html(data.updated_fields[key])
            }


        })

        request.fail(function (jqXHR, textStatus) {
        });

    }


</script>


<div id="campaign_order_recursion_components_edit_dialog" class="hide" style="position:absolute;width:300px;background: #fff;border: 1px solid #ccc;padding: 10px">

    <table border="0">
        <tr>
            <td class="target italic discreet"></td>
            <td class="aright"><i onclick="$('#campaign_order_recursion_components_edit_dialog').addClass('hide')" class="fa fa-window-close button"></i></td>
        </tr>
        <tr>
            <td>{t}Allowance{/t}</td>
            <td><input class="allowance" style=""></td>
        </tr>
        <tr>
            <td>{t}Description{/t}</td>
            <td><input class="description" style=""></td>
        </tr>
        <tr>
            <td colspan="2" class="aright"><span class="save" onclick="save_edit_component_allowance(this)">{t}Save{/t} <i class="fa fa-fw fa-cloud"></i></span></td>
        </tr>

    </table>

</div>


<div id="campaign_order_recursion_components_status_edit_dialog" class="hide" style="position:absolute;width:150px;background: #fff;border: 1px solid #ccc;padding: 10px">


    <table border="0">

        <tr>
            <td class="target italic discreet padding_right_20"></td>
            <td>
                <i onclick="save_edit_component_status(this)" status="Active" class="fa fa-play success fa-fw Active operation button" aria-hidden="true" title="{t}Active{/t}"></i>
                <i onclick="save_edit_component_status(this)" status="Suspended" class="fa fa-pause error fa-fw Suspended operation button" aria-hidden="true" title="{t}Suspended{/t}"></i>
                <i onclick="$('#campaign_order_recursion_components_status_edit_dialog').addClass('hide')" class="fa fa-window-close button padding_left_20"></i>

            </td>

        </tr>


    </table>

</div>


