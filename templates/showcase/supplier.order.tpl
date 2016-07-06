<div class="timeline_horizontal">
	{$order->get('State Index')} 
	<ul class="timeline" id="timeline">
		<li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}"> 
		<div class="label">
			<span class="state ">{t}Submitted{/t}</span> 
		</div>
		<div class="timestamp">
			<span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date')}</span> <span class="start_date">{$order->get('Creation Date')} </span> 
		</div>
		<div class="dot">
		</div>
		</li>
		
		<li id="send_node" class="li  "> 
		<div class="label">
			<span class="state" style="position:relative;left:5px">{t}Delivery{/t} <span></i></span></span> 
		</div>
		<div class="timestamp">
			<span class="Purchase_Order_Send_Date">&nbsp;</span> 
		</div>
		<div class="truck">
		</div>
		</li>
		
		<li id="send_node" class="li  {if $order->get('State Index')>=45}complete{/if}"> 
		<div class="label">
			<span class="state ">{t}Send{/t} <span></i></span></span> 
		</div>
		<div class="timestamp">
			<span class="Purchase_Order_Send_Date">&nbsp;{$order->get('Send Date')}</span> 
		</div>
		<div class="dot">
		</div>
		</li>
		<li class="li "> 
		<div class="label">
			<span class="state ">{t}Delivered{/t}</span> 
		</div>
		<div class="timestamp">
			<span class="Purchase_Order_Received_Date">&nbsp;{$order->get('Received Date')}</span> 
		</div>
		<div class="dot">
		</div>
		</li>
		<li class="li"> 
		<div class="label">
			<span class="state">{t}Checked{/t}</span> 
		</div>
		<div class="timestamp">
			<span>&nbsp;{$order->get('Checked Date')}</span> 
		</div>
		<div class="dot">
		</div>
		</li>
		<li class="li"> 
		<div class="label">
			<span class="state">{t}Placed{/t}</span> 
		</div>
		<div class="timestamp">
			<span>&nbsp;{$order->get('Consolidated Date')} &nbsp;</span> 
		</div>
		<div class="dot">
		</div>
		</li>
	</ul>
</div>
<div class="order" style="display: flex;" data-object="{$object_data}">
	<div class="block" style=" align-items: stretch;flex: 1">
		<div class="data_container">
			<div class="data_field">
				<i class="fa fa-ship fa-fw" aria-hidden="true" title="{t}Supplier{/t}"></i> <span onclick="change_view('{if $order->get('Purchase Order Parent')=='Supplier'}supplier{else}agent{/if}/{$order->get('Purchase Order Parent Key')}')" class="link Purchase_Order_Parent_Name">{$order->get('Purchase Order Parent Name')}</span> 
			</div>
			<div class="data_field">
				<i class="fa fa-share fa-fw" aria-hidden="true" title="Incoterm"></i> <span class="Purchase_Order_Incoterm">{$order->get('Purchase Order Incoterm')}</span> 
			</div>
			<div class="data_field">
				<i class="fa fa-arrow-circle-right fa-fw" aria-hidden="true" title="{t}Port of export{/t}"></i> <span class="Purchase_Order_Port_of_Export">{$order->get('Port of Export')}</span> 
			</div>
			<div class="data_field">
				<i class="fa fa-arrow-circle-left fa-fw" aria-hidden="true" title="{t}Port of import{/t}"></i> <span class="Purchase_Order_Port_of_Import">{$order->get('Port of Import')}</span> 
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="block " style="align-items: stretch;flex: 1;">
		<div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
			<div id="back_operations">
				<div id="delete_operations" class="order_operation {if $order->get('Purchase Order State')!='In Process'}hide{/if}">
					<div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}delete{/t}">
						<i class="fa fa-trash very_discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i> 
						<table id="delete_dialog" border="0" class="order_operation_dialog hide">
							<tr class="top">
								<td colspan="2">{t}Delete purchase order{/t}</td>
							</tr>
							<tr class="changed">
								<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('delete')"></i></td>
								<td class="aright"><span id="received_save_buttons" class="error save button" onclick="save_order_operation('Delete')"><span class="label">{t}Delete{/t}</span> <i class="fa fa-trash fa-fw  " aria-hidden="true"></i></span> </td>
							</tr>
						</table>
					</div>
				</div>
				<div id="cancel_operations" class="order_operation {if $order->get('Purchase Order State')=='In Process'}hide{/if}">
					<div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}Cancel{/t}">
						<i class="fa fa-minus-circle error " aria-hidden="true" onclick="toggle_order_operation_dialog('cancel')"></i> 
						<table id="cancel_dialog" border="0" class="order_operation_dialog hide">
							<tr class="top">
								<td colspan="2">{t}Cancel purchase order{/t}</td>
							</tr>
							<tr class="changed">
								<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('cancel')"></i></td>
								<td class="aright"><span id="received_save_buttons" class="error save button" onclick="save_order_operation('cancel','Cancelled')"><span class="label">{t}Cancel{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span> </td>
							</tr>
						</table>
					</div>
				</div>
				<div id="undo_submit_operations" class="order_operation {if $order->get('Purchase Order State')!='Submitted'}hide{/if}">
					<div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}Undo submit{/t}">
												<span class="fa-stack"  onclick="toggle_order_operation_dialog('undo_submit')">
						<i class="fa fa-paper-plane-o discreet "  aria-hidden="true" ></i> 
						<i class="fa fa-ban fa-stack-1x discreet error"></i>
						</span>
						
						
						<table id="undo_submit_dialog" border="0" class="order_operation_dialog hide">
							<tr class="top">
								<td colspan="2">{t}Undo submission{/t}</td>
							</tr>
							<tr class="changed">
								<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_submit')"></i></td>
								<td class="aright"><span id="undo_submit_save_buttons" class="valid save button" onclick="save_order_operation('undo_submit','In Process')"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span> </td>
							</tr>
						</table>
					</div>
				</div>
				<div id="undo_send_operations" class="order_operation {if $order->get('Purchase Order State')!='Send'}hide{/if}">
					<div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}Unmark as send{/t}">
						<span class="fa-stack" onclick="toggle_order_operation_dialog('undo_send')">
						<i class="fa fa-plane discreet "  aria-hidden="true" ></i> 
						<i class="fa fa-ban fa-stack-1x very_discreet error"></i>
						</span>
						<table id="undo_send_dialog" border="0" class="order_operation_dialog hide">
							<tr class="top">
								<td colspan="2" class="label">{t}Unmark as send{/t}</td>
							</tr>
							<tr class="buttons changed">
								<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_send')"></i></td>
								<td class="aright"><span id="undo_send_save_buttons" class="valid save button" onclick="save_order_operation('undo_send','Submitted')"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span> </td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<span style="float:left;padding-left:10px;padding-top:5px" class="Purchase_Order_State"> {$order->get('State')} </span> 
			<div id="forward_operations">
				<div id="submit_operations" class="order_operation {if $order->get('Purchase Order State')!='In Process'}hide{/if}">
					<div class="square_button right" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}Submit{/t}">
						<i class="fa fa-paper-plane-o" aria-hidden="true" onclick="toggle_order_operation_dialog('submit')"></i> 
						<table id="submit_dialog" border="0" class="order_operation_dialog hide">
							<tr class="top">
								<td colspan="2">{t}Submit purchase order{/t}</td>
							</tr>
							<tr class="changed">
								<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('submit')"></i></td>
								<td class="aright"><span id="submit_save_buttons" class="valid save button" onclick="save_order_operation('submit','Submitted')"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span> </td>
							</tr>
						</table>
					</div>
				</div>
				<div id="received_operations" class=" order_operation {if !($order->get('Purchase Order State')=='Submitted' or  $order->get('Purchase Order State')=='Send') }hide{/if}">
					<div class="square_button right" title="{t}received{/t}">
						<i class="fa fa-sign-in fa-fw" aria-hidden="true" onclick="toggle_order_operation_dialog('received')"></i> 
						<table id="received_dialog" border="0" class="order_operation_dialog hide">
							<tr class="top">
								<td colspan="2">{t}Mark purchase order as received{/t}</td>
							</tr>
							<tr class="changed">
								<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('received')"></i></td>
								<td class="aright"><span id="received_save_buttons" class="valid save button" onclick="save_order_operation('received','Received')"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span> </td>
							</tr>
						</table>
					</div>
				</div>

				<div id="send_operations" class="order_operation {if $order->get('Purchase Order State')!='Submitted'}hide{/if}">
					<div class="square_button right" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}send{/t}">
						<i class="fa fa-plane fa-fw" aria-hidden="true" onclick="toggle_order_operation_dialog('send')"></i> 
						<table id="send_dialog" border="0" class="order_operation_dialog hide">
							<tr class="top">
								<td class="label" colspan="2">{t}Purchase order send{/t}</td>
							</tr>
							<tr class="top">
								<td class="label">{t}Date{/t}</td>
								<td> 
								<input id="send_date" type="hidden" value="{$smarty.now|date_format:'%Y-%m-%d'}" ovalue="{$smarty.now|date_format:'%Y-%m-%d'}" has_been_valid="0" />
								<input id="send_date_time" type="hidden" value="{$smarty.now|date_format:'%H:%M:%S'}" />
								<input id="send_date_formatted" class="option_input_field" data-settings='{ "type": "datetime","id":"send_date", "field": "Purchase Order Send Date"}'    style="width:8em" value="{$smarty.now|date_format:'%d/%m/%Y'}" />
								<span id="send_date_msg" class="msg"></span> 
								<script>
		                            $(function() {
		    $("#send_date_datepicker").datepicker({
		        showOtherMonths: true,
		        selectOtherMonths: true,
		        defaultDate: new Date('{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}'),
		        altField: "#send_date",
		        altFormat: "yy-mm-dd",
		        //minDate: new Date({$mindate_send_order}),
		        //maxDate: 0,

		        onSelect: function() {
		            $('#send_date').change();
		            $('#send_date_formatted').val('xx');

		            //     var date = $(this).datepicker("getDate");
		            $('#send_date_formatted').val($.datepicker.formatDate("dd/mm/yy", $(this).datepicker("getDate")))

		            $('#send_date_datepicker').addClass('hide')
		        }
		    });
		});

		                            $('#send_date_formatted').focusin(function() {
		    $('#send_date_datepicker').removeClass('hide')

		});

		                            $('#send_date_formatted').on('input', function() {
		    var date = chrono.parseDate($('#send_date_formatted').val())

		    if (date == null) {
		        var value = '';
		    } else {
		        var value = date.toISOString().slice(0, 10)
		        $("#send_date_datepicker").datepicker("setDate", date);
		    }


		    $('#send_date').val(value)
		    $('#send_date').change();

		});
		                            $('#send_date').on('change', function() {
		    on_changed_value('send_date', $('#send_date').val())

		});

		                            if ('{$smarty.now|date_format:"%Y-%m-%d %H-%M-%S"}' == '') {
		    $('#send_date').val('')
		}
		                        </script>
		                       </td>
							</tr>
							<tr>
								<td colspan="2">
								<div id="send_date_datepicker" class="hide datepicker">
								</div>
								</td>
							</tr>
							<tr class="buttons changed">
								<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('send')"></i></td>
								<td class="aright"><span id="send_save_buttons" class="valid save button" onclick="save_order_operation('send','Send')"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span> </td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="delivery_node {if {$order->get('State Index')|intval}<30 }hide{/if}" style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">
			<div id="back_operations"></div>
			<span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i class="fa fa-truck fa-flip-horizontal button" aria-hidden="true" ></i> {t}Delivery Note{/t} </span> 
			<div id="forward_operations">

				<div id="received_operations" class="order_operation {if !($order->get('Purchase Order State')=='Submitted' or  $order->get('Purchase Order State')=='Send') }hide{/if}">
					<div class="square_button right" style="padding:0;margin:0;position:relative;top:0px" title="{t}Input delivery note{/t}">
						<i class="fa fa-plus" aria-hidden="true" onclick="show_create_delivery()"></i> 
						
					</div>
				</div>
			
			
			
			</div>
			
		</div>
	
		{*} 
		<table id="delivery_notes" border="1" class="ul_table">
			{foreach from=$order->get_sdn_objects() item=dn} 
			<tr>
				<td class="icon"><i class="fa fa-fw fa-truck"></i> </td>
				<td colspan="2"> <span class="link" onclick="change_view('order/{$order->id}/delivery_note/{$dn->id}')" ">{$dn->get('Delivery Note ID')}</span> <a class="pdf_link" target='_blank' href="/dn.pdf.php?id={$dn->id}"> <img style="" src="/art/pdf.gif"></a> </td>
				<td class="state">{$dn->get('Delivery Note XHTML State')} </td>
			</tr>
			<tr>
				<td class="more_dn_opertions"> </td>
				<td colspan="3" class="state"> {$dn->get_info()} </td>
			</tr>
			<tr id="dn_operations_tr_{$dn->id}" style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if}">
				<td colspan="3" class="state" id="operations_container{$dn->id}">{$dn->get_operations($user,'order',$order->id)}</td>
			</tr>
			<tr style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if};border-bottom:1px solid #ccc;border-top:1px solid #eee">
				<td colspan="4"> 
				<table border="0" style="width:100%;margin:0px;font-size:80%;">
					<tr>
						<td style="border-right:1px solid #eee;width:50%;text-align:center" id="pick_aid_container{$dn->id}"><span class="link" onclick="change_view('order/{$order->id}/pick_aid/{$dn->id}')">{t}Picking Aid{/t}</span> <a class="pdf_link" target='_blank' href="pdf/order_pick_aid.pdf.php?id={$dn->id}"> <img src="/art/pdf.gif"></a> </td>
						<td style="text-align:center" class="aright" id="pack_aid_container{$dn->id}"><span class="link" onclick="change_view('order/{$order->id}/pack_aid/{$dn->id}')">{t}Pack Aid{/t}</span></td>
					</tr>
				</table>
				</td>
			</tr>
			{/foreach} 
		</table>
		<table id="invoices" border="1" class="ul_table">
			{foreach from=$order->get_invoices_objects() item=invoice} 
			<tr>
				<td class="icon"><i class="fa fa-fw fa-usd"></i> </td>
				<td> <span class="link" onclick="change_view('order/{$order->id}/invoice/{$invoice->id}')">{$invoice->get('Invoice Public ID')}</span> <a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img src="/art/pdf.gif"></a> </td>
				<td style="text-align:right;padding-right:10px;font-size:80%;"> {$invoice->get_formatted_payment_state()} </td>
			</tr>
			<tr>
				<td colspan="2" class="right" style="text-align:right" id="operations_container{$invoice->id}">{$invoice->get_operations($user,'order',$order->id)}</td>
			</tr>
			{/foreach} 
		</table>
		{*} 
	</div>
	<div class="block " style="align-items: stretch;flex: 1 ">
		<table border="0" class="info_block">
			<tr>
				<td class="label">{t}Cost{/t} ({$order->get('Purchase Order Currency Code')}) </td>
				<td class="aright Purchase_Order_Total_Amount">{$order->get('Total Amount')}</td>
			</tr>
			<tr class="{if $account->get('Account Currency')==$order->get('Purchase Order Currency Code')}hide{/if}">
				<td colspan="2" class="Purchase_Order_Total_Amount_Account_Currency aright ">{$order->get('Total Amount Account Currency')}</td>
			</tr>
			<tr>
				<td class="label">{t}Weight{/t}</td>
				<td class="aright Purchase_Order_Weight">{$order->get('Weight')}</td>
			</tr>
			<tr>
				<td class="label">{t}CBM{/t}</td>
				<td class="aright Purchase_Order_CBM">{$order->get('CBM')}</td>
			</tr>
		</table>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>

 
<div id="new_delivery" class="table_new_fields hide" >
	<div style="align-items: stretch;flex: 1;padding:20px 5px;border-right:1px solid #eee">
		<i key="" class="fa fa-fw fa-square-o button" aria-hidden="true"></i> <span>{t}Select all{/t}</span> 
	</div>
	<div style="align-items: stretch;flex: 1;padding:10px 20px;">
		<table border="0" style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
			<tr>
				<td class="label ">{t}Delivery Number{/t}</td>
				<td>
				<input class="new_delivery_field" id="delivery_number" placeholder="{t}Delivery number{/t}"></td>
			</tr>
			<tr>
				<td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_create_delivery()"></i></td>
				<td class="buttons save" onclick="save_create_delivery()" ><span>{t}Save{/t}</span> <i class=" fa fa-cloud fa-flip-horizontal " aria-hidden="true" ></i></td>
			</tr>
		</table>
	</div>
</div>



<script>

$('#new_delivery').on('input propertychange', '.new_delivery_field', function(evt) {


    var field_id=$(this).attr('id')

    if (field_id == 'delivery_number') {

        $(this).closest('table').find('td.buttons i').addClass('fa-spinner fa-spin').removeClass('fa-cloud')



        var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))
        console.log(object_data)
        var value = $(this).val()

        var parent = object_data.order_parent
        var parent_key = object_data.order_parent_key
        var object = 'Supplier Delivery'
        var key = ''
        var field = 'Supplier Delivery Public ID'
        
        if(value==''){
                  $(this).closest('table').find('td.buttons').removeClass('changed')     
        }else{
        
        $(this).closest('table').find('td.buttons').addClass('changed')     
        var request = '/ar_validation.php?tipo=check_for_duplicates&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value+'&metadata='+JSON.stringify({ option:'creating_dn_from_po'})

        console.log(request)


        $.getJSON(request, function(data) {
 


            $('#'+field_id).removeClass('waiting invalid valid')
$('#'+field_id).closest('table').find('td.buttons').removeClass('waiting invalid valid')

            $('#'+field_id).closest('table').find('td.buttons i').removeClass('fa-spinner fa-spin').addClass('fa-cloud')

       

            if (data.state == 200) {

                var validation = data.validation
                var msg = data.msg

            } else {
                var validation = 'invalid'
                var msg = "Error, can't verify value on server"

            }
       $('#'+field_id).closest('table').find('td.buttons').addClass(validation)
   




        })
}


    }

});


</script>
