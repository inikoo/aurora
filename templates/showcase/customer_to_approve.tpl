{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02 March 2020  21:32::06  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div id="customer" class="subject_profile" style="padding-bottom: 0px;border-bottom:none"  key="{$customer->id}" store_key="{$customer->get('Store Key')}">


    <div style="float: left;width: 590px;">

        <div class="{if $customer->get('Customer Name')|strlen <50 }hide{/if}">
            <h1 style="margin-bottom: 0px;position: relative;top:-10px" class="Customer_Name Subject_Name">{$customer->get('Customer Name')}</h1>
        </div>
        <div class="data_container" >
            <div class="data_field" style="min-width: 270px;">
                <i title="{t}Company name{/t}" class="fa fa-fw  fa-building"></i><span class="Company_Name_Formatted">{$customer->get('Company Name Formatted')}</span> <i class="{if $customer->get('Customer Recargo Equivalencia')!='Yes'}hide{/if} recargo_equivalencia_tag fa fa-registered recargo_equivalencia"></i>
            </div>

            <div class="data_field Customer_Tax_Number_display {if !$customer->get('Customer Tax Number')}hide{/if}" style="min-width: 270px;">
                <i title="{t}Tax number{/t}" class="fal fa-fw fa-passport"></i></i><span
                        class="Customer_Tax_Number_Formatted">{$customer->get('Tax Number Formatted')}</span>
            </div>
            <div class="data_field Customer_Registration_Number_display {if !$customer->get('Customer Registration Number')}hide{/if}" style="min-width: 270px;">
                <i title="{t}Registration number{/t}" class="fal fa-fw fa-id-card"></i><span
                        class="Customer_Registration_Number">{$customer->get('Registration Number')}</span>
            </div>

            <div style="min-height:80px;float:left;width:28px">
                <i class="fa fa-fw fa-map-marker-alt"></i>
            </div>
            <div class="Contact_Address" style="float:left;max-width:242px;">
                {$customer->get('Contact Address')}
            </div>

        </div>
        <div class="data_container" >
            <div class="data_field" style="min-width: 270px;">
                <i title="{t}Contact name{/t}" class="fa fa-fw  fa-male"></i><span class="Main_Contact_Name_Formatted">{$customer->get('Main Contact Name Formatted')}</span>
            </div>

            <div id="Customer_Main_Plain_Email_display"
                 class="data_field   {if !$customer->get('Customer Main Plain Email')}hide{/if}">
                <i class="fa fa-fw fa-at"></i> <span
                        id="Customer_Other_Email_mailto">{if $customer->get('Customer Main Plain Email')}{mailto address=$customer->get('Main Plain Email')}{/if}</span>
            </div>
            {foreach from=$customer->get_other_emails_data() key=other_email_key item=other_email}
                <div id="Customer_Other_Email_{$other_email_key}_display" class="data_field ">
                    <i class="fa fa-fw fa-at discreet"></i> <span
                            id="Customer_Other_Email_{$other_email_key}_mailto">{mailto address=$other_email.email}</span>
                </div>
            {/foreach}
            <div id="Customer_Other_Email_display" class="data_field hide">
                <i class="fa fa-fw fa-at discreet"></i> <span class="Customer_Other_Email_mailto"></span>
            </div>
            <span id="display_telephones"></span> {if $customer->get('Customer Preferred Contact Number')=='Mobile'}
                <div id="Customer_Main_Plain_Mobile_display"
                     class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                    <i class="fa fa-fw fa-mobile"></i> <span
                            class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                </div>
                <div id="Customer_Main_Plain_Telephone_display"
                     class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                    <i class="fa fa-fw fa-phone"></i> <span
                            class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                </div>
            {else}
                <div id="Customer_Main_Plain_Telephone_display"
                     class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                    <i title="Telephone" class="fa fa-fw fa-phone"></i> <span  class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                </div>
                <div id="Customer_Main_Plain_Mobile_display"
                     class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                    <i title="Mobile" class="fa fa-fw fa-mobile"></i> <span
                            class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                </div>
            {/if}
            <div id="Customer_Main_Plain_FAX_display"
                 class="data_field {if !$customer->get('Customer Main Plain FAX')}hide{/if}">
                <i title="Fax" class="fa fa-fw fa-fax"></i> <span>{$customer->get('Main XHTML FAX')}</span>
            </div>

            {foreach $customer->get_other_telephones_data() key=other_telephone_key item=other_telephone}
                <div id="Customer_Other_Telephone_{$other_telephone_key}_display" class="data_field ">
                    <i class="fa fa-fw fa-phone discreet"></i> <span>{$other_telephone.formatted_telephone}</span>
                </div>
            {/foreach}
            <div id="Customer_Other_Telephone_display" class="data_field hide">
                <i class="fa fa-fw fa-phone discreet"></i> <span></span>
            </div>

        </div>



        <div style="clear:both">
        </div>
    </div>


    <div style="float: right;width: 300px;">
        <div id="overviews">

            <table class="overview">

                <tr>
                    <td>{t}Registered{/t}:</td>
                    <td class="aright">{$customer->get('First Contacted Date With Time')}</td>
                </tr>



            </table>
            <div style="margin-top:20px;padding-top:20px;clear: both">
                <span data-data='{ "object": "Customer", "key":"{$customer->id}"}' onClick="approve_object(this)" class="button success" style="border:1px solid darkseagreen;padding:5px 20px"><i class="fa fa-fw fa-check"></i>  {t}Approve{/t}</span>
            </div>
            <div style="margin-top:20px;padding-top:20px;clear: both">
                <span data-data='{ "object": "Customer", "key":"{$customer->id}"}' onClick="reject_object(this)" class="button error" style="border:1px solid indianred;padding:5px 20px"><i class="fa fa-fw fa-times"></i>  {t}Reject{/t}</span>
            </div>

        </div>
    </div>
    <div style="float: right;width: 310px;margin-right: 20px">

            <table class="overview">
               {foreach from=$poll_data item=$poll_item }
                   <tr style="height: initial">
                       <td class="small very_discreet" style="padding-top: 8px">{$poll_item.label}</td>
                   </tr>
                   <tr style="height: initial">
                       <td style="padding-top:3px">{if $poll_item.answer==''}<span class="error  italic">{t}No answer{/t}</span>{else}{$poll_item.answer}{/if}</td>
                   </tr>
               {/foreach}

            </table>


    </div>
    <div style="clear: both"></div>
</div>




<div style="height: 10px;border-bottom:1px solid #ccc;padding: 0px"></div>



