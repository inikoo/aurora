{include file="sticky_note.tpl" value=$customer->get('Sticky Note') object="Customer" key="{$customer->id}" field="Customer_Sticky_Note"  }


<div id="customer" class="subject_profile" key="{$customer->id}" store_key="{$customer->get('Store Key')}">
    <div id="contact_data">
        <div class="data_container">
            <div class="data_field  {if $customer->get('Customer Type')!='Company'}hide{/if}">
                <i title="{t}Company name{/t}" class="fa fa-building-o"></i> <span
                        class="Customer_Name">{$customer->get('Customer Name')}</span>
            </div>
            <div class="data_field">
                <i title="{t}Contact name{/t}" class="fa fa-male"></i> <span
                        class="Customer_Main_Contact_Name">{$customer->get('Customer Main Contact Name')}</span>
            </div>
            <div class="data_field {if !$customer->get('Customer Tax Number')}hide{/if}">
                <i title="{t}Tax number{/t}" class="fa fa-black-tie"></i></i> <span
                        class="Customer_Tax_Number">{$customer->get('Tax Number')}</span>
            </div>
        </div>
        <div class="data_container">
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
                    <i title="Telephone" class="fa fa-fw fa-phone"></i> <span
                            class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
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
        <div class="data_container">
            <div style="min-height:80px;float:left;width:28px">
                <i class="fa fa-map-marker"></i>
            </div>
            <div class="Contact_Address" style="float:left;min-width:272px">
                {$customer->get('Contact Address')}
            </div>
        </div>


        <div style="clear:both">
        </div>
    </div>
    <div id="info">
        <div id="overviews">
            <table border="0" class="overview" style="">
                <tr id="account_balance_tr" class="main">
                    <td id="account_balance_label">{t}Account Balance{/t}</td>
                    <td id="account_balance" class="aright highlight">{$customer->get('Account Balance')} </td>
                </tr>
                <tr id="last_credit_note_tr" style="display:none">
                    <td colspan="2" class="aright" style="padding-right:20px">{t}Credit note{/t}: <span
                                id="account_balance_last_credit_note"></span></td>
                </tr>
                <tr style="{if $customer->get_pending_payment_amount_from_account_balance()==0}display:none{/if}">
                    <td id="account_balance_label">{t}Payments in Process{/t}</td>
                    <td id="account_balance" class="aright"
                        style="padding-right:20px;font-weight:800"> {$customer->get_formatted_pending_payment_amount_from_account_balance()} </td>
                </tr>
            </table>
            <table border="0" class="overview">
                {if $customer->get('Customer Level Type')=='VIP'}
                    <td></td>
                    <td class="highlight">{t}VIP Customer{/t}</td>
                {/if} {if $customer->get('Customer Level Type')=='Partner'}
                    <td></td>
                    <td class="highlight">{t}Partner Customer{/t}</td>
                {/if} {if $customer->get('Customer Type by Activity')=='Losing'}
                    <tr>
                        <td colspan="2">{t}Losing Customer{/t}</td>
                    </tr>
                {elseif $customer->get('Customer Type by Activity')=='Lost'}
                    <tr>
                        <td>{t}Lost Customer{/t}</td>
                        <td>{$customer->get('Lost Date')}</td>
                    </tr>
                {/if}
                <tr>
                    <td>{t}Contact Since{/t}:</td>
                    <td>{$customer->get('First Contacted Date')}</td>
                </tr>
                {assign var="correlation_msg" value=$customer->get_correlation_info()} {if $correlation_msg}
                    <tr>
                        <td>{$correlation_msg}</td>
                    </tr>
                {/if} {foreach from=$customer->get_category_data() item=item key=key}
                    <tr>
                        <td>{$item.root_label}:</td>
                        <td>{$item.value}</td>
                    </tr>
                {/foreach}
            </table>
            {if $customer->get('Customer Send Newsletter')=='No' or $customer->get('Customer Send Email Marketing')=='No' or $customer->get('Customer Send Postal Marketing')=='No'}
                <table border="0" class="overview compact">
                    <tr class="{if $customer->get('Customer Send Newsletter')=='Yes'}hide{/if}">
                        <td colspan="2"><i class="fa fa-ban"></i> <span>{t}Don't send newsletters{/t}</span></td>
                    </tr>
                    <tr class="{if $customer->get('Customer Send Email Marketing')=='Yes'}hide{/if}">
                        <td colspan="2"><i class="fa fa-ban"></i> <span>{t}Don't send marketing by email{/t}</span></td>
                    </tr>
                    <tr class="{if $customer->get('Customer Send Postal Marketing')=='Yes'}hide{/if}">
                        <td colspan="2"><i class="fa fa-ban"></i> <span>{t}Don't send marketing by post{/t}</span></td>
                    </tr>
                </table>
            {/if} {if $customer->get('Customer Orders')>0}
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
                        <td class="text"> {if $customer->get('Customer Orders')==1}
                            <p>
                                {$customer->get('Name')} {t}has place one order{/t}.
                            </p>
                            {elseif $customer->get('Customer Orders')>1 } {$customer->get('Name')} {if $customer->get('Customer Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if}
                            <b>{$customer->get('Customer Orders')}</b> {if $customer->get('Customer Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}
                            , {t}which amounts to a total of{/t} <b>{$customer->get('Net Balance')}</b> {t}plus tax{/t}
                            ({t}an average of{/t} {$customer->get('Total Net Per Order')} {t}per order{/t}
                            ). {if $customer->get('Customer Orders Invoiced')}
                            </p>
                            <p>
                                {if $customer->get('Customer Type by Activity')=='Lost'}{t}This customer used to place an order every{/t}{else}{t}This customer usually places an order every{/t}{/if} {$customer->get('Order Interval')}
                                .{/if} {else} Customer has not place any order yet. {/if}
                            </p>
                        </td>
                    </tr>
                </table>
            {/if}
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>

<script>


    function email_width_hack() {
        var email_length = $('#showcase_Customer_Main_Plain_Email').text().length

        if (email_length > 30) {
            $('#showcase_Customer_Main_Plain_Email').css("font-size", "90%");
        }
    }

    email_width_hack();

    $("#take_order").click(function () {
        open_new_order()
    })

    function open_new_order() {


        if (!$('#take_order i').hasClass('fa-shopping-cart')) {
            return;
        }

        $('#take_order i').removeClass('fa-shopping-cart').addClass('fa-spinner fa-spin')


        var request = '/ar_find.php?tipo=number_orders_in_process&customer_key=' + $('#customer').attr('key')

        $.getJSON(request, function (data) {


            if (data.orders_in_process > 0) {
                $('#take_order i').addClass('fa-shopping-cart').removeClass('fa-spinner fa-spin')

            } else {
                new_order();
            }


        })

    }

    function new_order() {


        var object = 'Order'
        var parent = 'customer'
        var parent_key = $('#customer').attr('key')
        var fields_data = {};


        var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)
        console.log(request)
        var form_data = new FormData();
        form_data.append("tipo", 'new_object')
        form_data.append("object", object)
        form_data.append("parent", parent)
        form_data.append("parent_key", parent_key)
        form_data.append("fields_data", JSON.stringify(fields_data))

        var request = $.ajax({
            url: "/ar_edit.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'
        })

        request.done(function (data) {


            $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

            //console.log(data)
            if (data.state == 200) {
                change_view('orders/' + $('#customer').attr('store_key') + '/' + data.new_id)

            }
            else if (data.state == 400) {
                //TODO make a nice msg
                alert(data.msg)


            }
        })

        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)
            $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
            $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


        });


    }
</script>