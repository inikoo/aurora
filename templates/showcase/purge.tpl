{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2018 at 19:12:52 GMT+8, Kuala Lumpur, Malaydia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<style>
    #select_date_control_panel td {
        padding: 0px
    }

    .purge_data > div {
        padding: 5px 10px 15px 10px;
        border-right: 1px solid #ccc;
        flex-grow: 1;
        text-align: center
    }

    .purge_data > div label {
        font-size: smaller;
    }

    .purge_data > div div {
        font-weight: bold;
        margin-top: 5px;
    }

</style>



<div id="purge" data-object='{$object_data}' data-purge_key="{$purge->id}">


    <div class="order control_panel ">
        <div class="block estimated_orders " style="padding:10px 20px; align-items: stretch;flex: 1">


            <div class="estimated_orders_pre_sent   {if $purge->get('State Index')>=20}hide{/if}">
                <span>{t}Estimated orders{/t}</span> <span class="strong Estimated_Orders">{$purge->get('Estimated Orders')}</span> (<span class=" Estimated_Amount">{$purge->get('Estimated Amount')}</span>)
            </div>

            <div class="estimated_orders_post_sent {if $purge->get('State Index')<20 or $purge->get('State Index')==100 }hide{/if}">
                <span class="Purged_Orders_Info">{$purge->get('Purged Orders Info')}</span>
            </div>

            <div style="clear:both"></div>
        </div>
        <div class="block purge_operations" style="align-items: stretch;flex: 1;">
            <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
                <div id="back_operations">

                    <div id="delete_operations" class="purge_operation {if $purge->get('State Index')<0 or   $purge->get('State Index')>=20 }hide{/if}">
                        <div class="square_button left" title="{t}Delete{/t}">


                            <i class="far fa-trash-alt discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i>
                            <table id="delete_dialog" class="order_operation_dialog hide">
                                <tr class="top">
                                    <td colspan="2">{t}Delete mailshot{/t}</td>
                                </tr>
                                <tr class="changed buttons">
                                    <td>
                                        <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('delete')"></i>
                                    </td>
                                    <td class="aright">
                                    <span data-data='{ "object": "purge", "key":"{$purge->id}"  }' onClick="delete_object(this)">
                                    <span class="label">{t}Delete{/t}</span>
                                    <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i>
                                    </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>




                    <div id="stop_operations" class="purge_operation {if $purge->get('State Index')!=20   }hide{/if}">
                        <div class="square_button left  " title="{t}Stop sending emails{/t}">
                            <i class="fa  fa-stop button error" id="stop_save_buttons" aria-hidden="true" data-data='{  "field": "Order Basket Purge State","value": "Cancelled","dialog_name":"stop"}'
                               onclick="save_purge_operation(this)"></i>

                        </div>
                    </div>


                </div>
                <span style="float:left;padding-left:10px;padding-top:5px" class="Purge_State"> {$purge->get('State')} </span>
                <div id="forward_operations">



                    <div id="start_purge_operations" class="purge_operation {if $purge->get('State Index')!=10    }hide{/if}">
                        <div class="square_button right" title="{t}Start purge{/t}">


                            <i class="far fa-skull discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('start_purge')"></i>
                            <table id="start_purge_dialog" class="order_operation_dialog hide">
                                <tr class="top">
                                    <td colspan="2">{t}Start purge{/t}</td>
                                </tr>
                                <tr class="changed buttons">
                                    <td>
                                        <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('start_purge')"></i>
                                    </td>
                                    <td class="aright">
                                    <span id="start_purge_save_buttons" class="button" data-data='{  "field": "Order Basket Purge State","value": "Purging","dialog_name":"start_purge"}' onClick="save_purge_operation(this)">
                                    <span class="label">{t}Start purge{/t}</span>
                                    <i class="fa fa-skull fa-fw  " aria-hidden="true"></i>
                                    </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                    


                </div>
            </div>

            <table class="hide info_block acenter">

                <tr>

                    <td>
                        <span ><i class="fa fa-cube fa-fw discreet" aria-hidden="true"></i> <span class="Order_Number_items">{$purge->get('Number Items')}</span></span>
                        <span style="padding-left:20px"><i class="fa fa-tag fa-fw  " aria-hidden="true"></i> <span class="Order_Number_Items_with_Deals">{$purge->get('Number Items with Deals')}</span></span>
                        <span class="error {if $purge->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                                    class="Order_Number_Items_with_Out_of_Stock">{$purge->get('Number Items Out of Stock')}</span></span>
                        <span class="error {if $purge->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                    class="Order_Number_Items_with_Returned">{$purge->get('Number Items Returned')}</span></span>
                    </td>
                </tr>


            </table>

        </div>
        <div class="block " style="align-items: stretch;flex:2;padding:0px ">
            <div style="display:flex" class="{if $purge->get('State Index')>=20 }hide{/if} purge_data ">
                <div>
                    <label>{t}Orders purged{/t}</label>
                    <div class="Purged_Orders">{$purge->get('Purged Orders')}</div>
                </div>
                <div>
                    <label>{t}Items purged{/t}</label>
                    <div class="Purged_Transactions">{$purge->get('Purged Transactions')}</div>
                </div>
                <div>
                    <label>{t}Amount{/t}</label>
                    <div class="Purged_Amount">{$purge->get('Purged Amount')}</div>
                </div>


            </div>
       
        </div>

        <div style="clear:both"></div>
    </div>



</div>


<script>


    function save_purge_operation(element) {

        var data = $(element).data("data")


        var object_data = $('#purge').data("object")

        var dialog_name = data.dialog_name
        var field = data.field
        var value = data.value
        var object = object_data.object
        var key = object_data.key


        if (!$('#' + dialog_name + '_save_buttons').hasClass('button')) {
            console.log('#' + dialog_name + '_save_buttons')
            return;
        }

        $('#' + dialog_name + '_save_buttons').removeClass('button');
        $('#' + dialog_name + '_save_buttons i').addClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').addClass('hide')


        var metadata = {}

            //console.log('#' + dialog_name + '_dialog')

            $('#' + dialog_name + '_dialog  .option_input_field').each(function () {
                var settings = $(this).data("settings")


                if (settings.type == 'datetime') {
                    metadata[settings.field] = $('#' + settings.id).val() + ' ' + $('#' + settings.id + '_time').val()

                }


            });


        var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify(metadata)


        console.log(request)
        // return;
        //=====
        var form_data = new FormData();

        form_data.append("tipo", 'edit_field')
        form_data.append("object", object)
        form_data.append("key", key)
        form_data.append("field", field)
        form_data.append("value", value)
        form_data.append("metadata", JSON.stringify(metadata))

        var request = $.ajax({

            url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })


        request.done(function (data) {

            $('#' + dialog_name + '_save_buttons').addClass('button');
            $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
            $('#' + dialog_name + '_save_buttons .label').removeClass('hide')


            if (data.state == 200) {

                close_dialog(dialog_name)


                if (data.value == 'Cancelled') {
                    change_view(state.request, {
                        reload_showcase: true
                    })
                }


                switch (data.update_metadata.state) {
                    case 'Purging':
                        $('#purge\\.purged_orders').removeClass('hide')
                        change_tab('purge.purged_orders')
                        break;


                }


                for (var key in data.update_metadata.class_html) {
                    $('.' + key).html(data.update_metadata.class_html[key])
                }


                $('.purge_operation').addClass('hide')
                // $('.items_operation').addClass('hide')


                for (var key in data.update_metadata.operations) {

                    console.log('#' + data.update_metadata.operations[key])

                    $('#' + data.update_metadata.operations[key]).removeClass('hide')
                }





            } else if (data.state == 400) {


                swal($('#_labels').data('labels').error, data.msg, "error")
            }

        })


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });


    }

</script>
