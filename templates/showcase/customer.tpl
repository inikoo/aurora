<div class="sticky_notes">

    {include file="sticky_note.tpl" _scope="customer_sticky_note" value=$customer->get('Sticky Note') object="Customer" key="{$customer->id}" field="Customer_Sticky_Note"  }
    {include file="sticky_note.tpl" _scope="order_sticky_note" value=$customer->get('Order Sticky Note') object="customer" key="{$customer->id}" field="Customer_Order_Sticky_Note"  }
    {include file="sticky_note.tpl" _scope="delivery_note_sticky_note" value=$customer->get('Delivery Sticky Note') object="customer" key="{$customer->id}" field="Customer_Delivery_Sticky_Note"  }

</div>

<div id="customer" class="subject_profile" style="padding-bottom: 0px;border-bottom:none"  key="{$customer->id}" store_key="{$customer->get('Store Key')}">


    <div style="float: left;width: 590px;">

        <div class="{if $customer->get('Customer Name')|strlen <50 }hide{/if}">
            <h1 style="margin-bottom: 0px;position: relative;top:-10px" class="Customer_Name ">{$customer->get('Customer Name')}</h1>
        </div>
        <div class="data_container" >

            <div class="data_field" style="min-width: 270px;">
                <i title="{t}Contact name{/t}" class="fa fa-fw  fa-male"></i><span class="Customer_Main_Contact_Name">{$customer->get('Customer Main Contact Name')}</span>
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
            <div class="Contact_Address" style="float:left;min-width:242px">
                {$customer->get('Contact Address')}
            </div>

        </div>
        <div class="data_container" >
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
            <table border="0" class="overview" style="">
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
            <table border="0" class="overview">

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

                <tr>
                    <td>{t}Subscriptions{/t}:</td>
                    <td style="text-align: right">
                        <i title="{t}Newsletters{/t}" style="margin-right: 10px;position: relative;top:1px" class="Customer_Send_Newsletter {if $customer->get('Customer Send Newsletter')=='No' }discreet error {/if} far fa-fw fa-newspaper" aria-hidden="true"></i> <i title="{t}Marketing by email{/t}" style="margin-right: 10px"  class="Customer_Send_Email_Marketing {if $customer->get('Customer Send Email Marketing')=='No' }discreet error {/if} far fa-fw fa-envelope" aria-hidden="true"></i>  <i title="{t}Marketing by post{/t}" class="Customer_Send_Postal_Marketing {if $customer->get('Customer Send Postal Marketing')=='No' }discreet error {/if} far fa-fw fa-person-carry" aria-hidden="true"></i>
                    </td>
                </tr>

            </table>



        </div>
    </div>
    <div style="float: right;width: 310px;;margin-right: 20px">

        {if $customer->get('Customer Orders')>0}
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
        {/if}

    </div>
    <div style="clear: both"></div>
</div>



<div style="clear:both;border-top:1px solid #aaa;;border-bottom:1px solid #aaa;min-height: 30px;padding:0px 20px;">
    <table class="customer_showcase_order">
        <td style="width:30px;padding:0px;text-align: center"><i title="{t}Order in basket{/t}" class="fa fa-shopping-basket"></i></td>
        <td style="width: 80px"><span >{$order_basket.public_id}</span></td>
        <td  style="width: 80px"><span title="{t}Items net{/t} {$order_basket.items_net}">{$order_basket.number_items}</span></td>
        <td  style="width: 80px">{$order_basket.weight}</td>

        <td class="hide"  style="width: 80px"><span title="{t}Shipping and charges{/t}">{$order_basket.other_net}</span></td>

        <td style="width: 150px"><span ><span class="discreet small">{$order_basket.tax_description}</span> {$order_basket.tax}</span></td>

        <td style="width: 80px"><span class="strong">{$order_basket.total}</span></td>

        <td class="customer_online_icon">
            {if $online}
            <i title="{t}Online{/t}" class="far success fa-globe"></i>
            {else}
                <i title="{t}Offline{/t}" class="fal super_discreet fa-globe"></i>
            {/if}
        </td>
        <td class="customer_web_info_log_out {if $online}hide{/if} " >{$customer_web_info_log_out}</td>
        <td class="customer_web_info_log_in {if !$online}hide{/if} " style="padding:0px">{$customer_web_info_logged_in}</td>
    </table>


</div>
<div style="height: 10px;border-bottom:1px solid #ccc;padding: 0px"></div>



<div id="add_payment" class="table_new_fields hide">

    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-bottom: 1px solid #ccc;position: relative">

        <i style="position:absolute;top:10px;" class="fa fa-window-close fa-flip-horizontal button" aria-hidden="true" onclick="close_add_payment_to_order()"></i>

        <table border="0" style="width:50%;float:right;width:100%;">
            <tr>
                <td class="strong " style="width: 100px;text-align: right;padding-right: 20px">
                {t}Transfer credit to{/t}
                <td>
                <td></td>

                <td>
                    <div id="new_payment_payment_account_buttons">
                        {foreach from=$store->get_payment_accounts('objects','Active') item=payment_account}

                            {if $payment_account->get('Payment Account Block')=='Accounts'}

                            {else}
                                <div class="button payment_button    " onclick="select_payment_account(this)    "
                                     data-settings='{ "payment_account_key":"{$payment_account->id}", "max_amount":"" , "payment_method":"{$payment_account->get('Default Payment Method')}", "block":"{$payment_account->get('Payment Account Block')}" }'
                                     class="new_payment_payment_account_button unselectable
                        button {if $payment_account->get('Payment Account Block')=='Accounts' and $customer->get('Customer Account Balance')<=0  }hide{/if}" style="border:1px solid #ccc;padding:10px
                        5px;margin-bottom:2px">{$payment_account->get('Name')}</div>
                            {/if}



                        {/foreach}
                    </div>
                    <input type="hidden" id="new_payment_payment_account_key"     value="">
                    <input type="hidden" id="new_payment_payment_method" value="">


                </td>

                <td class="payment_fields " style="padding-left:30px;width: 500px">
                    <table>
                        <tr>
                            <td> {t}Amount{/t}</td>
                            <td style="padding-left:20px"><input style="width: 150px;" disabled=true class="transfer_credit_to" id="new_payment_amount" placeholder="{t}Amount{/t}"></td>
                        </tr>
                        <tr>
                            <td>  {t}Reference{/t}</td>
                            <td style="padding-left:20px"><input style="width: 300px;" disabled=true class="transfer_credit_to" id="new_payment_reference" placeholder="{t}Reference{/t}"></td>
                        </tr>
                        <tr>
                            <td>  {t}Private note{/t}</td>
                            <td style="padding-left:20px"><textarea style="width: 300px;height: 60px" disabled=true class="transfer_credit_to" id="new_payment_note" ></textarea></td>
                        </tr>
                    </table>
                </td>

                <td id="save_new_payment" class="buttons save" onclick="transfer_credit_to()"><span>{t}Save{/t}</span> <i class=" fa fa-cloud " aria-hidden="true"></i></td>
            </tr>

        </table>
    </div>
</div>


<div  class="add_funds_to_customer_account edit_funds_to_customer_account table_new_fields hide" data-operation_type="add_funds">

    <div style="align-items: stretch;flex: 1;padding:0px;border-bottom: 1px solid #ccc;position: relative">


        <table border="0" style="float:right;width:100%;">

            <tr>
                <td colspan="3" style="border-bottom:1px solid #ccc;padding-left: 20px">
                    <i  class="fa fa-window-close margin_right_20 fa-flip-horizontal button" aria-hidden="true" onclick="close_fund_credit()"></i>

                    <span class="strong"> {t}Add funds to customer credit account{/t}</span> <span class="italic padding_left_10">({t}Will increase account balance{/t})</span>


                </td>
            </tr>

            <tr>


                <td style="padding-left: 20px">
                    <div class="add_funds_to_customer_account_type_buttons">

                        <div  class="button fund_type_button unselectable  very_discreet " onclick="select_fund_type(this)    "
                             data-type="PayReturn"
                             style="border:1px solid #ccc;padding:10px;5px;margin-bottom:2px">{t}Pay for the shipping of a return{/t}</div>
                        <div class="button fund_type_button unselectable  very_discreet " onclick="select_fund_type(this)    "
                             data-type="Compensation"
                             style="border:1px solid #ccc;padding:10px;5px;margin-bottom:2px">{t}Compensate customer{/t}</div>
                        <div class="button fund_type_button unselectable  very_discreet " onclick="select_fund_type(this)    "
                             data-type="TransferIn"
                             style="border:1px solid #ccc;padding:10px;5px;margin-bottom:2px">{t}Transfer from other customer account{/t}</div>
                        <div class="button fund_type_button unselectable  very_discreet " onclick="select_fund_type(this)    "
                             data-type="AddFundsOther"
                             style="border:1px solid #ccc;padding:10px;5px;margin-bottom:2px">{t}Other reason{/t}</div>


                    </div>
                    <input type="hidden" class="add_funds_to_customer_account_type" value="">


                </td>

                <td  class="add_funds_to_customer_fields" style="padding-left:30px;width: 500px;padding-top: 10px;padding-bottom: 10px">
                    <table >
                        <tr>
                            <td> {t}Amount to deposit{/t}</td>
                            <td style="padding-left:20px"><input style="width: 150px;" disabled=true class="add_funds_to_customer_field edit_funds_to_customer_field  amount " placeholder="{t}Amount{/t}"></td>
                        </tr>

                        <tr>
                            <td >  {t}Private note{/t}</td>
                            <td style="padding-left:20px"><textarea style="width: 300px;height: 60px" disabled=true class="add_funds_to_customer_field edit_funds_to_customer_field note"  ></textarea></td>
                        </tr>
                    </table>
                </td>

                <td  class="buttons save save_add_funds_to_customer_account" onclick="save_edit_funds_to_customer_account('add_funds')"><span>{t}Save{/t}</span> <i class=" fa fa-cloud " aria-hidden="true"></i></td>
            </tr>

        </table>
    </div>
</div>


<div  class="remove_funds_to_customer_account edit_funds_to_customer_account table_new_fields hide" data-operation_type="remove_funds" >

    <div style="align-items: stretch;flex: 1;padding:0px;border-bottom: 1px solid #ccc;position: relative">


        <table border="0" style="float:right;width:100%;">

            <tr>
                <td colspan="3" style="border-bottom:1px solid #ccc;padding-left: 20px">
                    <i  class="fa fa-window-close margin_right_20 fa-flip-horizontal button" aria-hidden="true" onclick="close_fund_credit()"></i>

                    <span class="strong"> {t}Withdraw funds from customer credit account{/t}</span> <span class="italic padding_left_10">{t}Will decrease account balance{/t}</span>


                </td>
            </tr>

            <tr>


                <td style="padding-left: 20px">
                    <div class="remove_funds_to_customer_account_type_buttons customer_account_type_buttons"  >


                        <div class="button fund_type_button unselectable  very_discreet " onclick="select_fund_type(this)    "
                             data-type="MoneyBack"
                             style="border:1px solid #ccc;padding:10px;5px;margin-bottom:2px">{t}Customer want money back{/t}</div>
                        <div class="button fund_type_button unselectable  very_discreet " onclick="select_fund_type(this)    "
                             data-type="TransferOut"
                             style="border:1px solid #ccc;padding:10px;5px;margin-bottom:2px">{t}Transfer to other customer account{/t}</div>
                        <div class="button fund_type_button unselectable  very_discreet " onclick="select_fund_type(this)    "
                             data-type="RemoveFundsOther"
                             style="border:1px solid #ccc;padding:10px;5px;margin-bottom:2px">{t}Other reason{/t}</div>


                    </div>
                    <input type="hidden" class="remove_funds_to_customer_account_type" value="">


                </td>

                <td  class="remove_funds_to_customer_fields" style="padding-left:30px;width: 500px;padding-top: 10px;padding-bottom: 10px">
                    <table >
                        <tr>
                            <td> {t}Withdraw amount{/t}</td>
                            <td style="padding-left:20px"><input style="width: 150px;" disabled=true class="remove_funds_to_customer_field edit_funds_to_customer_field  amount " placeholder="{t}Amount{/t}"></td>
                        </tr>

                        <tr>
                            <td >  {t}Private note{/t}</td>
                            <td style="padding-left:20px"><textarea style="width: 300px;height: 60px" disabled=true class="remove_funds_to_customer_field edit_funds_to_customer_field note"  ></textarea></td>
                        </tr>
                    </table>
                </td>

                <td  class="buttons save save_remove_funds_to_customer_account" onclick="save_edit_funds_to_customer_account('remove_funds')"><span>{t}Save{/t}</span> <i class=" fa fa-cloud " aria-hidden="true"></i></td>
            </tr>

        </table>
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

    $("#take_order").on( 'click',function () {
        open_new_order()
    })

    function open_new_order() {


        if (!$('#take_order i').hasClass('fa-shopping-cart')) {
            return;
        }

        $('#take_order i').removeClass('fa-shopping-cart').addClass('fa-spinner fa-spin')

        new_order();


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




    function show_transfer_credit_to(max_amount) {


        if ($('#add_payment').hasClass('hide')) {



            $('#tabs').addClass('hide')


            $("#add_payment :input").attr("disabled", true);
            $(".payment_fields").addClass("just_hinted");
            $('#new_payment_reference').closest('tr').removeClass('hide')


            $('#add_payment').removeClass('hide')

            $('#new_payment_payment_account_key').val('')
            $('#new_payment_payment_method').val('')

            $('.new_payment_payment_account_button').removeClass('super_discreet')


            $('#new_payment_amount').val(max_amount)


            $('#new_payment_payment_account_key').data('max',max_amount)

            // $('#delivery_number').val('').focus()

        }

    }



    $(document).on('input propertychange', '.transfer_credit_to', function (evt) {

        validate_transfer_credit_to();
    })


    function validate_transfer_credit_to() {

        //console.log($('#new_payment_reference').val() != '')
        //console.log(!validate_number($('#new_payment_amount').val(), 0, 999999999))
        //console.log($('#new_payment_payment_account_key').val() > 0)

        var invalid=false;

        var valid_reference=($('#new_payment_reference').val()==''?false:true);


        var max_amount=$('#new_payment_payment_account_key').data('max')




        if(max_amount!=''){
            var valid_max_amount=(parseFloat(max_amount)<parseFloat($('#new_payment_amount').val())?false:true)


        }else{
            var valid_max_amount=true;

        }


        if( !validate_number($('#new_payment_amount').val(), 0, 999999999) && $('#new_payment_payment_account_key').val() > 0){
            var valid_amount=true;
        }else{
            var valid_amount=false;

        }



        if(valid_max_amount){
            $('#new_payment_amount').removeClass('invalid');

        }else{
            $('#new_payment_amount').addClass('invalid');
            invalid=true

        }




        //console.log(valid_reference)
        //console.log(valid_max_amount)

        //console.log(valid_amount)

        if (valid_reference && valid_max_amount &&  valid_amount) {
            //console.log('xx')
            $('#save_new_payment').addClass('valid changed')
        } else {
            $('#save_new_payment').removeClass('valid changed')
        }


        if(invalid){
            $('#save_new_payment').addClass('invalid changed').removeClass('valid')

        }else{
            $('#save_new_payment').addClass('valid').removeClass('invalid')

        }


    }



    function transfer_credit_to() {



        var object_data = $('#object_showcase div.order').data("object")


        if ($('#save_new_payment').hasClass('wait')   ||  !$('#save_new_payment').hasClass('valid')  ) {
            return;
        }
        $('#save_new_payment').addClass('wait')

        $('#save_new_payment i').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'transfer_customer_credit_to')

        ajaxData.append("customer_key", $('#customer').attr('key'))
        ajaxData.append("payment_account_key", $('#new_payment_payment_account_key').val())
        ajaxData.append("amount", $('#new_payment_amount').val())
        ajaxData.append("payment_method", $('#new_payment_payment_method').val())

        ajaxData.append("reference", $('#new_payment_reference').val())
        ajaxData.append("note", $('#new_payment_note').val())



        $.ajax({
            url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {




                $('#save_new_payment').removeClass('wait')
                $('#save_new_payment i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');


                //console.log(data)

                if (data.state == '200') {



                    change_view(state.request, { 'reload_showcase': 1})
                    if (state.tab == 'customer.credit_blockchain' || state.tab=='customer.history' ) {
                        rows.fetch({
                            reset: true
                        });
                    }




                } else if (data.state == '400') {
                    $('#tabs').removeClass('hide')

                    swal("Error!", data.msg, "error")
                }


            }, error: function () {
                $('#save_new_payment').removeClass('wait')
                $('#save_new_payment i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

            }
        });


    }



</script>