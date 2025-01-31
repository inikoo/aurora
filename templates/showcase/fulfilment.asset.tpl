{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 July 2021 at 03:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div class="sticky_notes">
    {include file="sticky_note.tpl" color="yellow_note"   value=$asset->get('Note') object="fulfilment_asset" key="{$asset->id}" field="Fulfilment_Asset_Note"  }
</div>


<div class="order object_data" style="display: flex;" data-object='{$object_data}'>


    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">
            <div class="data_field  ">

              <span class="button"
                    onclick="change_view('fulfilment/{$delivery->get('Fulfilment Delivery Warehouse Key')}/customers/{if $delivery->get('Fulfilment Delivery Type')=='Part'}dropshipping{else}asset_keeping{/if}/{$delivery->get('Fulfilment Delivery Customer Key')}')">
                <i class="fa fa-user fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i> <span

                          class="button Order_Customer_Name">{$customer->get('Name')}</span> <span

                          class="link Order_Customer_Key">{$customer->get('Formatted ID')}</span>
              </span>
            </div>
            <div class="data_field {if ($customer->get('Name')==$customer->get('Contact Name')) or $customer->get('Contact Name')==''   }hide{/if} ">
                <i class="fa fa-fw fa-male super_discreet" title="{t}Contact name{/t}"></i> <span class=" Customer_Contact_Name">{$customer->get('Contact Name')}</span>
            </div>


            <div class="data_field small  " style="margin-top:10px">

                <span id="display_telephones"></span>
                {if $customer->get('Customer Preferred Contact Number')=='Mobile'}
                    <div id="Customer_Main_Plain_Mobile_display"
                         class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                        <i class="fal fa-fw fa-mobile"></i> <span class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                    </div>
                    <div id="Customer_Main_Plain_Telephone_display"
                         class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                        <i class="fal fa-fw fa-phone"></i> <span class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                    </div>
                {else}
                    <div id="Customer_Main_Plain_Telephone_display"
                         class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                        <i title="Telephone" class="fa fa-fw fa-phone"></i> <span class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                    </div>
                    <div id="Customer_Main_Plain_Mobile_display"
                         class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                        <i title="Mobile" class="fal fa-fw fa-mobile"></i> <span
                                class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                    </div>
                {/if}

            </div>

            <div class="data_field small {if $customer->get('Customer Main Plain Email')==''}hide{/if}" style="margin-top:5px">
                <div>
                    <i class="fal fa-envelope fa-fw" title="{t}Email{/t}"></i> {if $customer->get('Customer Main Plain Email')!=''}{mailto address=$customer->get('Customer Main Plain Email')}{/if}
                </div>
            </div>


        </div>
        <div style="clear:both"></div>
    </div>


    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">
                <div id="undo_booked_out_operations"
                     class=" undo_booked_out_operation order_operation {if $asset->get('Fulfilment Asset State')!='BookedOut'  }hide{/if}">
                    <div class="square_button left" title="{t}Undo book Out{/t}">
                        <i class="fa fa-sign-out error fa-flip-horizontal fa-fw" aria-hidden="true"
                           onclick="toggle_order_operation_dialog('undo_booked_out')"></i>
                        <table id="undo_booked_out_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Undo book out{/t}<br><span class="small error">{t}Remember to book in the location again{/t}</span></td>
                            </tr>

                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_booked_out')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Fulfilment Asset State","value": "BookedIn","dialog_name":"undo_booked_out"}'
                                            id="undo_booked_out_save_buttons" class="valid save button"
                                            onclick="save_assets_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
             <span style="float:left;padding-left:10px;padding-top:5px;text-align: center"
                   class="Fulfilment_Asset_State"> {$asset->get('State')} </span>

            <div id="forward_operations" class="hide">
                <div id="booked_out_operations"
                     class=" order_operation booked_out_operation {if $asset->get('Fulfilment Asset State')!='BookedIn'  }hide{/if}">
                    <div class="square_button right" title="{t}Book Out{/t}">
                        <i class="fa fa-sign-out fa-fw" aria-hidden="true"
                           onclick="toggle_order_operation_dialog('booked_out')"></i>
                        <table id="booked_out_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Book out{/t}</td>
                            </tr>

                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('booked_out')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Fulfilment Asset State","value": "BookedOut","dialog_name":"booked_out"}'
                                            id="booked_out_save_buttons" class="valid save button"
                                            onclick="save_assets_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>



            </div>
        <div class="location " style="height:30px;margin-bottom:10px;position:relative;top:5px;text-align:center;">

            <span class="Fulfilment_Asset_Location_Key"> {$asset->get('Formatted Location')} </span>

        </div>
        <div class="{if  $asset->get('State Index')>=80 }hide{/if}" style="height:30px;margin-bottom:10px;position:relative;top:5px;text-align:center;">
            <span class="pdf_label_container pdf_label_container_pallet {if $asset->get('Fulfilment Asset Type')!='Pallet'}hide{/if} ">
                    <img alt="{t}Download{/t}" class="button pdf_link  left_pdf_label_mark top_pdf_label_mark"
                         onclick="download_pdf_from_ui($('.pdf_asset_dialog.pallet'),'fulfilment_asset',{$asset->id},'pallet')" style="width: 50px;height:16px;position: relative;top:2px"
                         src="/art/pdf.gif">
                    <i onclick="show_pdf_settings_dialog(this,'fulfilment_asset',{$asset->id},'pallet')" title="{t}PDF pallet label settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>
            </span>
            <span class="pdf_label_container  pdf_label_container_box {if $asset->get('Fulfilment Asset Type')=='Pallet'}hide{/if} ">
                    <img alt="{t}Download{/t}" class="button pdf_link left_pdf_label_mark top_pdf_label_mark"
                         onclick="download_pdf_from_ui($('.pdf_asset_dialog.box'),'fulfilment_asset',{$asset->id},'box')" style="width: 50px;height:16px;position: relative;top:2px"
                         src="/art/pdf.gif">
                    <i onclick="show_pdf_settings_dialog(this,'fulfilment_asset',{$asset->id},'box')" title="{t}PDF box label settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>
            </span>

        </div>
        {include file="pdf_asset_dialog.tpl" asset='fulfilment_asset' type='pallet'}
        {include file="pdf_asset_dialog.tpl" asset='fulfilment_asset' type='box'}


    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <div class="state " style="display:flex;height:30px;margin-bottom:10px;position:relative;top:5px;text-align:center;">
            <div style="padding-left:10px;padding-right:10px ;">
                <i class="fa fa-sign-in" title="{t}Received{/t}"></i>
            </div>
            <div>
                <span class="Fulfilment_Asset_From"> {$asset->get('From')} </span>

            </div>

        </div>

        <div class="state" style="display:flex;height:30px;margin-bottom:10px;position:relative;top:5px;text-align:center;">
            <div style="padding-left:10px;padding-right:10px ;">
                <i class="fa fa-sign-out" title="{t}Booked out{/t}"></i>
            </div>
            <div>
                <span class="Fulfilment_Asset_To"> {$asset->get('To')} </span>

            </div>


        </div>

        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0 ">
        <div style="clear:both">
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>

