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
            <table class="overview" >
                <tr id="account_balance_tr" class="main">
                    <td id="account_balance_label">{t}Account Balance{/t}

                        {if $user->can_supervisor('accounting')}
                            <span  onclick="show_edit_credit_dialog('add_funds')" class="button margin_left_5  " title="{t}Add money to customer balance{/t}">
                                <i class="fal fa-upload"></i>
                            </span>
                            <span  onclick="show_edit_credit_dialog('remove_funds')" class="button margin_left_5  " title="{t}Withdraw money to customer balance{/t}">
                                <i class="fal fa-download"></i>
                            </span>

                    {else}
                    <span data-labels='{ "footer":"{t}Authorised users{/t}: ","title":"{t}Restricted operation{/t}","text":"{t}Please ask an authorised user to add funds to customer account{/t}"}'  onclick="unauthorized_open_fund_credit(this)" class="button margin_left_5  " title="{t}Add money to customer balance{/t} ({t}locked{/t})">
                        <i class="fal fa-upload very_discreet"></i> <i class="fal fa-download very_discreet"></i>
                    </span>

                    {/if}

                    </td>


                    <td id="account_balance" class="aright "><span onclick="change_tab('customer.credit_blockchain')" class="very_discreet_on_hover small padding_right_10 button"><i class="fal fa-code-commit "></i> {$customer->get('Customer Number Credit Transactions')}</span>

                    {if $customer->get('Customer Account Balance')>0}
                        <span   onclick="show_transfer_credit_to({$customer->get('Customer Account Balance')})"   class=" button   highlight">{$customer->get('Account Balance')}</span>
                        {else}
                        <span      class="    highlight">{$customer->get('Account Balance')}</span>

                        {/if}

                    </td>
                </tr>

            </table>
            <table class="overview">

                <tr class="Customer_Sales_Representative_tr {if !$customer->get('Customer Sales Representative Key')>0}hide{/if}" >
                    <td>{t}Account manager{/t} </td>
                    <td class="Sales_Representative aright">{$customer->get('Sales Representative')}</td>
                </tr>

                {if $customer->get('Customer Type by Activity')=='Losing'}
                    <tr>
                        <td colspan="2">{t}Losing Customer{/t}</td>
                    </tr>
                {elseif $customer->get('Customer Type by Activity')=='Lost'}
                    <tr>
                        <td>{t}Lost Customer{/t}</td>
                        <td class="aright">{$customer->get('Lost Date')}</td>
                    </tr>
                {/if}
                <tr>
                    <td>{t}Contact since{/t}:</td>
                    <td class="aright">{$customer->get('First Contacted Date')}</td>
                </tr>
                {if $store->get('Store Type')=='Dropshipping'}
                <tr>
                    <td>{t}Customer's clients{/t}:</td>
                    <td class="aright">{$customer->get('Number Clients')}</td>
                </tr>
                {/if}
                <tr>
                    <td>{t}Subscriptions{/t}:</td>
                    <td style="text-align: right">
                        <i title="{t}Newsletters{/t}" style="margin-right: 10px;position: relative;top:1px" class="Customer_Send_Newsletter {if $customer->get('Customer Send Newsletter')=='No' }discreet error {/if} far fa-fw fa-newspaper" aria-hidden="true"></i> <i title="{t}Marketing by email{/t}" style="margin-right: 10px"  class="Customer_Send_Email_Marketing {if $customer->get('Customer Send Email Marketing')=='No' }discreet error {/if} far fa-fw fa-envelope" aria-hidden="true"></i>  <i title="{t}Marketing by post{/t}" class="Customer_Send_Postal_Marketing {if $customer->get('Customer Send Postal Marketing')=='No' }discreet error {/if} far fa-fw fa-person-carry" aria-hidden="true"></i>
                    </td>
                </tr>

            </table>



        </div>
    </div>
    <div style="float: right;width: 310px;margin-right: 20px">

            <table class="overview">
                {if $customer->get('Customer Type by Activity')=='Lost'}
                    <tr>
                        <td><span style="color:white;background:black;padding:1px 10px">{t}Lost Customer{/t}</span>
                        </td>
                    </tr>
                {/if} {if $customer->get('Customer Type by Activity')=='Losing'}
                    <tr>
                        <td>
                            <span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing customer{/t}</span>
                        </td>
                    </tr>
                {/if}
                <tr>
                    <td class="text"> {if $customer->get('Customer Number Invoices')==1}
                        <p>
                            {$customer->get('Name')} {t}has been invoiced once{/t}.
                        </p>
                        {elseif $customer->get('Customer Number Invoices')>1 } {$customer->get('Name')} {t}has been invoiced{/t}
                        <b>{$customer->get('Orders Invoiced')}</b> {if $customer->get('Customer Type by Activity')=='Lost'}{t}times{/t}{else}{t}times so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$customer->get('Invoiced Net Amount')}</b> <span class="very_discreet error {if $customer->get('Customer Refunded Net Amount')==0}hide{/if} ">({$customer->get('Absolute Refunded Net Amount')} {t}refunded{/t})</span> {t}plus tax{/t}
                        ({t}an average of{/t} {$customer->get('Total Net Per Order')} {t}per order{/t}
                        ). {if $customer->get('Customer Orders')}
                        </p>
                        <p>
                            {if $customer->get('Customer Type by Activity')=='Lost'}{t}This customer used to place an order every{/t}{else}{t}This customer usually places an order every{/t}{/if} {$customer->get('Order Interval')}
                            .{/if} {else} Customer has not place any order yet. {/if}
                        </p>
                    </td>
                </tr>
            </table>


    </div>
    <div style="clear: both"></div>
</div>




<div style="height: 10px;border-bottom:1px solid #ccc;padding: 0px"></div>



