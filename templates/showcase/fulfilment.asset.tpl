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
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:5px;text-align:center;">

            <span class="Fulfilment_Asset_State"> {$asset->get('State')} </span>

        </div>
        <table class="info_block acenter">
        </table>
    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <div class="node  Invoice_Info {if $asset->get('State Index')!=110}hide{/if} ">
                    <span class="node_label">
                        <i class="fal fa-file-invoice-dollar fa-fw"></i>
                        <span class="Formatted_Invoice_Public_ID margin_right_10">{$asset->get('Formatted Invoice Public ID')}</span>
                        <span class="italic Formatted_Invoice_Date">{$asset->get('Formatted Invoice Date')}</span>
                    </span>
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

