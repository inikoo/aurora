{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 November 2017 at 20:45:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="edit_location_dialog" class="hide" location_key="" style="border:1px solid #ccc;width: 300px;background: #fff;z-index: 2000">
    <ul style="list-style: none;">
        {foreach from=$flags item=flag}
            <li class="button" style="padding:5px 0px" onclick="select_location_flag(this)" flag_key="{$flag.key}" color="{$flag.color}"  label="{$flag.label}" ><i class="fa fa-flag {$flag.color|lower} padding_right_10 " aria-hidden="true"></i> {$flag.label}</li>
        {/foreach}
    </ul>
</div>

<script>
    function show_edit_flag_dialog(element) {

        var offset = $(element).offset()

        $('#edit_location_dialog').removeClass('hide').offset({
            top: offset.top - 7.5, left: offset.left + $(element).width() + 10
        }).attr('location_key',$(element).attr('location_key'))

    }

    function select_location_flag(element) {

        var flag_key = $(element).attr('flag_key');
        var flag_color = $(element).attr('color');
        var flag_label = $(element).attr('label');

        console.log(flag_key)

        var request='ar_edit.php?tipo=edit_field&object=Location&key='+$('#edit_location_dialog').attr('location_key')+'&field=Location_Warehouse_Flag_Key&value='+flag_key+'&metadata={ }'

       // console.log(request)
       // return
        var ajaxData = new FormData();


        ajaxData.append("tipo", 'edit_field')
        ajaxData.append("object", 'Location')
        ajaxData.append("key", $('#edit_location_dialog').attr('location_key'))
        ajaxData.append("field", 'Location_Warehouse_Flag_Key')
        ajaxData.append("value", flag_key)
        ajaxData.append("metadata", '{ }')


        $.ajax({
            url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {

                console.log(data)

                if (data.state == '200') {

console.log(flag_label)


                    $('#flag_location_'+$('#edit_location_dialog').attr('location_key')).removeClass('far super_discreet blue green orange pink purple red yellow').addClass('fa fa-flag '+flag_color).prop('title',flag_label)
                    $('#edit_location_dialog').addClass('hide')

                } else if (data.state == '400') {

                }


            }, error: function () {

            }
        });



    }


</script>