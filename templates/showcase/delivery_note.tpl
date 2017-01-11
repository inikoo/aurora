
<div class="timeline_horizontal">

    <ul class="timeline" id="timeline">


            <li id="order_node" class="li complete">
                <div class="label">
                    <span class="state ">{t}Send to warehouse{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Date_Created">&nbsp;{$delivery_note->get('Creation Date')}</span>
                    <span class="start_date Order_Placed_Date">{$delivery_note->get('Order Date Placed')} </span>
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
                <span class="state ">{t}Picked{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Picked_Percentage_or_Date">&nbsp;{$delivery_note->get('Picked Percentage or Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="dispatched_node"
            class="li  {if $delivery_note->get('State Index')>=30 or ($delivery_note->get('State Index')<0 and ($delivery_note->get('Dispatched Date')!=''  or $delivery_note->get('Received Date')!=''))  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Packed{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Dispatched_Date">&nbsp;{$delivery_note->get('Dispatched Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="dispatched_node"
            class="li  {if $delivery_note->get('State Index')>=30 or ($delivery_note->get('State Index')<0 and ($delivery_note->get('Dispatched Date')!=''  or $delivery_note->get('Received Date')!=''))  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Dispatch Approved{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Dispatched_Date">&nbsp;{$delivery_note->get('Dispatched Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>


        <li id="dispatched_node"
            class="li  {if $delivery_note->get('State Index')>=30 or ($delivery_note->get('State Index')<0 and ($delivery_note->get('Dispatched Date')!=''  or $delivery_note->get('Received Date')!=''))  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Dispatched{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Dispatched_Date">&nbsp;{$delivery_note->get('Dispatched Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>


    </ul>
</div>

<div class="order_header delivery_note" style="">
    <div id="xcontact_data" class="xblock" xstyle="float:left;padding:20px 20px;max-width:500px;min-height:170px;">
        <div class="data_container">
            <div class="data_field">
                <i class="fa fa-user"></i> <span>{$delivery_note->get('Delivery Note Customer Name')}</span>
            </div>

        </div>


        <div style="clear:both">
        </div>
        <div id="billing_address_container" class="data_container" style="">
            <div style="min-height:80px;float:left;width:16px">
                <i class="fa fa-map-marker"></i></i>
            </div>
            <div style="font-size:90%;float:left;min-width:150px;max-width:220px;">
                {$delivery_note->get('Delivery Note XHTML Ship To')}
            </div>
        </div>


        <div style="clear:both">
        </div>
    </div>

    <div id="xdates" class="xblock xdates" xstyle="float:right;border-left:1px solid #ccc;height:100%;;width:300px">
        <table border="0" class="date_and_state">

            <tr>
                <td colspan="2" class="state">{$delivery_note->get('State')}</td>
            </tr>
            <tr class="state two-columns">
                <td id="pick_aid_container{$delivery_note->id}"><span class="link"
                                                                      onclick="delivery_note('order/{$delivery_note->id}/pick_aid')">{t}Picking Aid{/t}</span>
                    <a class="pdf_link" target='_blank' href="pdf/order_pick_aid.pdf.php?id={$delivery_note->id}"> <img
                                src="/art/pdf.gif"></a></td>
                <td id="pack_aid_container{$delivery_note->id}"><span class="link"
                                                                      onclick="change_view('delivery_note/{$delivery_note->id}/pack_aid')">{t}Pack Aid{/t}</span>
                </td>
            </tr>
        </table>


    </div>
    <div id="xtotals" class="xblock xtotals">

        <table border="0" class="info_block">
            <tr id="edit_weight_tr">
                <td class="aright"> {t}Weight{/t}:</td>
                <td class="aright"><span id="formatted_parcels_weight">
					{if $weight==''}<span onclick="show_dialog_set_dn_data()"
                                          style="font-style:italic;color:#777;cursor:pointer">{t}Set weight{/t}</span>
                    {else}{$weight}{/if}</span></td>
            </tr>
            <tr id="edit_parcels_tr">
                <td class="aright"> {t}Parcels{/t}:</td>
                <td class="aright"><span id="formatted_number_parcels">{if $parcels==''}<span
                                onclick="show_dialog_set_dn_data()"
                                style="font-style:italic;color:#777;cursor:pointer">{t}Set parcels{/t}</span>{else}{$parcels}{/if}</span>
                </td>
            </tr>
            <tr id="edit_consignment_tr">
                <td class="aright"> {t}Courier{/t}:</td>
                <td class="aright"><span id="formatted_consignment">{if $consignment==''}<span
                                onclick="show_dialog_set_dn_data()"
                                style="font-style:italic;color:#777;cursor:pointer">{t}Set consignment{/t}</span>{else}{$consignment}{/if}</span>
                </td>
            </tr>
            {if $delivery_note->get('Delivery Note Date Start Picking')!='' or $delivery_note->get('Delivery Note Picker Assigned Alias')!=''}
                <tr>
                    <td class="aright"> {if $delivery_note->get('Delivery Note Date Finish Picking')==''}{t}Picking by{/t}{else}{t}Picked by{/t}{/if}
                        :
                    </td>
                    <td width="200px" class="aright">{$delivery_note->get('Delivery Note Assigned Picker Alias')} </td>
                </tr>
                {if $delivery_note->get('Delivery Note Date Finish Picking')!=''}
                    <tr>
                        <td class="aright">{t}Finish picking{/t}:</td>
                        <td class="aright">{$delivery_note->get('Date Finish Picking')}</td>
                    </tr>
                {else if $delivery_note->get('Delivery Note Date Finish Picking')!=''}
                    <tr>
                        <td class="aright">{t}Start picking{/t}:</td>
                        <td class="aright">{$delivery_note->get('Date Start Picking')}</td>
                    </tr>
                {/if}

            {/if} {if $delivery_note->get('Delivery Note Date Start Packing')!='' or $delivery_note->get('Delivery Note Packer Assigned Alias')!=''}
                <tr>
                    <td class="aright"> {if $delivery_note->get('Delivery Note Date Finish Packing')==''}{t}Packing by{/t}{else}{t}Packed by{/t}{/if}
                        :
                    </td>
                    <td width="200px" class="aright">{$delivery_note->get('Delivery Note XHTML Packers')} </td>
                </tr>
                {if $delivery_note->get('Delivery Note Date Finish Packing')!=''}
                    <tr>
                        <td class="aright">{t}Finish packing{/t}:</td>
                        <td class="aright">{$delivery_note->get('Date Finish Packing')}</td>
                    </tr>
                {else if $delivery_note->get('Delivery Note Date Finish Packing')!=''}
                    <tr>
                        <td class="aright">{t}Start packing{/t}:</td>
                        <td class="aright">{$delivery_note->get('Date Start Packing')}</td>
                    </tr>
                {/if}

            {/if}
        </table>

        <div id="sticky_note_div" class="sticky_note pink"
             style="position:relative;left:-20px;width:270px;{if $delivery_note->get('Sticky Note')==''}display:none{/if}">
            <img id="sticky_note_bis" style="float:right;cursor:pointer" src="/art/icons/edit.gif">
            <div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
                {$delivery_note->get('Sticky Note')}
            </div>
        </div>


        <div style="clear:both">
        </div>
    </div>

</div>
<script>

    $('#totals').height($('#object_showcase').height())
    $('#dates').height($('#object_showcase').height())
</script>