
<div class="timeline_horizontal">

    <ul class="timeline" id="timeline">


            <li id="order_node" class="li complete">
                <div class="label">
                    <span class="state ">{t}Send to warehouse{/t}</span>
                    <span class="start Order_Public_ID button" onClick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')" >{$order->get('Public ID')} </span>
                </div>
                <div class="timestamp">
                    <span class="Date_Created">&nbsp;{$delivery_note->get('Creation Date')}</span>
                    <span class="start_date Order_Created_Date">{$order->get('Created Date')} </span>
                </div>
                <div class="dot">
                </div>
            </li>


        {if $delivery_note->get('State Index')<0 and $delivery_note->get('Dispatched Date')=='' and  $delivery_note->get('Received Date')==''  }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="">&nbsp;{$delivery_note->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}
        <li id="picked_node"
            class="li  {if $delivery_note->get('State Index')>=30 or ($delivery_note->get('State Index')<0 and ($delivery_note->get('Date Start Picking')!=''  or $delivery_note->get(' Date Start Packing')!=''))  }complete{/if}">
            <div class="label">
                <span class="state Delivery_Note_Picked_Label">{if $delivery_note->get('State Index')==20 }{t}Picking{/t}{else}{t}Picked{/t}{/if}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Picked_Percentage_or_Datetime">&nbsp;{$delivery_note->get('Picked Percentage or Datetime')}&nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="packed_node"
            class="li  {if $delivery_note->get('State Index')>=40 or ($delivery_note->get('State Index')<0 and ($delivery_note->get('Date Start Picking')!=''  or $delivery_note->get(' Date Start Packing')!=''))  }complete{/if}">
            <div class="label">
                <span class="state Delivery_Note_Packed_Label">{if $delivery_note->get('State Index')==40 }{t}Packing{/t}{else}{t}Packed{/t}{/if}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Packed_Percentage_or_Datetime">&nbsp{$delivery_note->get('Packed Percentage or Datetime')}&nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>



        <li id="dispatch_approved_node"
            class="li  {if $delivery_note->get('State Index')>=90  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Dispatch Approved{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Dispatched_Approved_Datetime">&nbsp;{$delivery_note->get('Dispatched Approved Datetime')}</span>
            </div>
            <div class="dot">
            </div>
        </li>


        <li id="dispatched_node"
            class="li  {if $delivery_note->get('State Index')>=100  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Dispatched{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Dispatched_Datetime">&nbsp;{$delivery_note->get('Dispatched Datetime')}</span>
            </div>
            <div class="dot">
            </div>
        </li>


    </ul>
</div>

<div id="delivery_note" class="order" dn_key="{$delivery_note->id}"   style="display: flex;">
    <div  class="block" style="padding:10px 20px;position: relative">



                <i style="position:absolute;top:15px;left:20px" class="fa fa-user button"  onclick="change_view('/customers/{$delivery_note->get('Delivery Note Store Key')}/{$delivery_note->get('Delivery Note Customer Key')}')" title="{$delivery_note->get('Customer Name')}"></i></i>

            <div style="margin-left:30px;min-width:250px">
                {$delivery_note->get('Delivery Note XHTML Ship To')}
            </div>




    </div>





    <div class="block " >
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px;min-width: 250px">
            <div id="back_operations">
                <div id="delete_operations"
                     class="order_operation {if $delivery_note->get('Delivery Note Number Picked Items')>0}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}delete{/t}">
                        <i class="fa fa-trash very_discreet " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete delivery note{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span data-data='{ "object": "DeliveryNote", "key":"{$delivery_note->id}"    }'
                                                         id="delete_save_buttons" class="error save button"
                                                         onclick="delete_object(this)"><span
                                                class="label">{t}Delete{/t}</span> <i class="fa fa-trash fa-fw" aria-hidden="true"></i></span>
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if $delivery_note->get('Delivery Note Number Picked Items')==0}hide{/if}">
                    <div class="square_button left" title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2">{t}Cancel order{/t}</td>
                            </tr>
                            <tr class="changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('cancel')"></i></td>
                                <td class="aright"><span id="received_save_buttons" class="error save button"
                                                         onclick="save_order_operation('cancel','Cancelled')"><span
                                                class="label">{t}Cancel{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                                      aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Delivery_Note_State"> {$delivery_note->get('State')} </span>
            <div id="forward_operations">
                <div id="dispatch_operations"
                     class="order_operation {if $delivery_note->get('Delivery Note State')!='Approved'}hide{/if}">
                    <div id="dispatch_operation"
                         class="square_button right  "
                         title="{t}Dispatch{/t}">
                        <i class="fa fa-paper-plane   " aria-hidden="true"
                           onclick="dispatch_delivery_note(this)"></i>

                    </div>
                </div>
            </div>
        </div>

        <table border="0" class="info_block acenter">

            <tr>



                <td>
                    <span style=""><i class="fa fa-square fa-fw discreet" aria-hidden="true"></i>
                          <span class="Number_Ordered_Parts">{$delivery_note->get('Number Ordered Parts')}</span> (<span class="Number_Ordered_Items">{$delivery_note->get('Number Ordered Items')}</span>)
                    <span style="padding-left:20px"><i class="fa fa-balance-scale fa-fw discreet " aria-hidden="true"></i> <span
                                class="Weight">{$delivery_note->get('Weight')}</span></span>
                    <span class="error {if $delivery_note->get('Order Number Items Out of Stock')==0}hide{/if}"
                          style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Out_of_Stock">{$delivery_note->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $delivery_note->get('Order Number Items Returned')==0}hide{/if}"
                          style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   "
                                                       aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$delivery_note->get('Number Items Returned')}</span></span>
                </td>
            </tr>


        </table>

    </div>



    <div  class="block">


        <i class="fa fa-file" onclick="print_label()"  aria-hidden="true"></i>


        <table border="0" class="info_block  {if $delivery_note->get('State Index')<70 or $delivery_note->get('State Index')>90 }hide{/if} ">

            <tr>
                <td class="aright"> {t}Parcels{/t}:</td>
                <td class="">
                    <input id="number_parcel_field" style="width:75px" value="{$delivery_note->get('Delivery Note Number Parcels')}" ovalue="{$delivery_note->get('Delivery Note Number Parcels')}" placeholder="{t}number{/t}"> <i onCLick="save_number_parcels(this)"  class="fa fa-plus button" aria-hidden="true"></i>

                </td>
                <td class="aright"> {t}Weight{/t}:</td>
                <td class=""><span id="formatted_number_parcels"><input style="width:75px" value="{$delivery_note->get('Weight For Edit')}" placeholder="{t}Kg{/t}">

                </td>
            </tr>

            <tr id="edit_consignment_tr">
                <td class="aright"> {t}Courier{/t}:</td>
                <td class="aright"><span id="formatted_consignment">{if $consignment==''}<span
                                onclick="show_dialog_set_dn_data()"
                                style="font-style:italic;color:#777;cursor:pointer">{t}Set consignment{/t}</span>{else}{$consignment}{/if}</span>
                </td>
            </tr>

        </table>

    </div>

</div>

<script>


    $(document).on('input propertychange', '#number_parcel_field', function () {


        if($(this).val()!=$(this).attr('ovalue')){
            $(this).next(i).addClass('fa-cloud').removeClass('fa-plus')
        }else{
            $(this).next(i).removeClass('fa-cloud').addClass('fa-plus')

        }

    })


    function save_number_parcels(element){

        $(element).addClass('fa-spinner fa-spin');

        var input = $(element).prev('input')
        var icon=$(element)

        if ($(element).hasClass('fa-plus')) {

            if (isNaN(input.val()) || input.val() == '') {
                var qty = 1
            } else {
                qty = parseFloat(input.val()) + 1
            }

            input.val(qty).addClass('discreet')

        } else if ($(element).hasClass('fa-minus')) {

            if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
                var qty = 0
            } else {
                qty = parseFloat(input.val()) - 1
            }

            input.val(qty).addClass('discreet')

        } else {
            qty = parseFloat(input.val())

        }

        if (qty == '') qty = 0;


        var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key='+$('#delivery_note').attr('dn_key')+'&field=Delivery_Note_Number_Parcels&value='+qty+'&metadata={}';
        console.log(request)

        var form_data = new FormData();

        form_data.append("tipo", 'edit_field')
        form_data.append("field", 'Delivery_Note_Number_Parcels')
        form_data.append("object", 'DeliveryNote')
        form_data.append("key",$('#delivery_note').attr('dn_key') )
        form_data.append("value", qty)
        var request = $.ajax({

            url: "/ar_edit.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })



        request.done(function (data) {
            $(element).removeClass('fa-spinner fa-spin')
            if (data.state == 200) {
                input.attr('ovalue',data.value)
                icon.removeClass('fa-cloud').addClass('fa-plus')
            } else if (data.state == 400) {
                sweetAlert(data.msg);
                input.val(input.attr('ovalue'))
            }

        })


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });




    }

   function dispatch_delivery_note(){


       var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key='+$('#dn_data').attr('dn_key')+'&value=Dispatched'
       $.getJSON(request, function (data) {
           if(data.state==200){


           }
       })
   }

   function print_label(){

       $("#printframe").remove();

       // create new printframe
       var iFrame = $('<iframe></iframe>');
       iFrame
           .attr("id", "printframe")
           .attr("name", "printframe")
           .attr("src", "about:blank")
           .css("width", "0")
           .css("height", "0")
           .css("position", "absolute")
           .css("left", "-9999px")
           .appendTo($("body:first"));

       // load printframe
       var url = 'test'
       if (iFrame != null && url != null) {
           iFrame.attr('src', url);
           iFrame.load(function() {
               // nasty hack to be able to print the frame
               var tempFrame = $('#printframe')[0];
               var tempFrameWindow = tempFrame.contentWindow? tempFrame.contentWindow : tempFrame.contentDocument.defaultView;
               tempFrameWindow.focus();
               tempFrameWindow.print();
           });
       }


   }

</script>