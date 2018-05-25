{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2018 at 14:36:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
{include file="sticky_note.tpl" value=$prospect->get('Sticky Note') object="Prospect" key="{$prospect->id}" field="Prospect_Sticky_Note"  }


<div id="prospect" class="subject_profile" key="{$prospect->id}" store_key="{$prospect->get('Store Key')}">
    <div id="contact_data">
        <div class="data_container">
            <div class="data_field  {if $prospect->get('Prospect Type')!='Company'}hide{/if}">
                <i title="{t}Company name{/t}" class="fa fa-building"></i> <span
                        class="Prospect_Name">{$prospect->get('Prospect Name')}</span>
            </div>
            <div class="data_field">
                <i title="{t}Contact name{/t}" class="fa fa-male"></i> <span
                        class="Prospect_Main_Contact_Name">{$prospect->get('Prospect Main Contact Name')}</span>
            </div>
            <div class="data_field {if !$prospect->get('Prospect Tax Number')}hide{/if}">
                <i title="{t}Tax number{/t}" class="fab fa-black-tie"></i></i> <span
                        class="Prospect_Tax_Number">{$prospect->get('Tax Number')}</span>
            </div>
        </div>
        <div class="data_container">
            <div id="Prospect_Main_Plain_Email_display"
                 class="data_field   {if !$prospect->get('Prospect Main Plain Email')}hide{/if}">
                <i class="fa fa-fw fa-at"></i> <span
                        id="Prospect_Other_Email_mailto">{if $prospect->get('Prospect Main Plain Email')}{mailto address=$prospect->get('Main Plain Email')}{/if}</span>
            </div>

            <div id="Prospect_Other_Email_display" class="data_field hide">
                <i class="fa fa-fw fa-at discreet"></i> <span class="Prospect_Other_Email_mailto"></span>
            </div>
            <span id="display_telephones"></span> {if $prospect->get('Prospect Preferred Contact Number')=='Mobile'}
                <div id="Prospect_Main_Plain_Mobile_display"
                     class="data_field {if !$prospect->get('Prospect Main Plain Mobile')}hide{/if}">
                    <i class="fa fa-fw fa-mobile"></i> <span
                            class="Prospect_Main_Plain_Mobile">{$prospect->get('Main XHTML Mobile')}</span>
                </div>
                <div id="Prospect_Main_Plain_Telephone_display"
                     class="data_field {if !$prospect->get('Prospect Main Plain Telephone')}hide{/if}">
                    <i class="fa fa-fw fa-phone"></i> <span
                            class="Prospect_Main_Plain_Telephone">{$prospect->get('Main XHTML Telephone')}</span>
                </div>
            {else}
                <div id="Prospect_Main_Plain_Telephone_display"
                     class="data_field {if !$prospect->get('Prospect Main Plain Telephone')}hide{/if}">
                    <i title="Telephone" class="fa fa-fw fa-phone"></i> <span
                            class="Prospect_Main_Plain_Telephone">{$prospect->get('Main XHTML Telephone')}</span>
                </div>
                <div id="Prospect_Main_Plain_Mobile_display"
                     class="data_field {if !$prospect->get('Prospect Main Plain Mobile')}hide{/if}">
                    <i title="Mobile" class="fa fa-fw fa-mobile"></i> <span
                            class="Prospect_Main_Plain_Mobile">{$prospect->get('Main XHTML Mobile')}</span>
                </div>
            {/if}
            <div id="Prospect_Main_Plain_FAX_display"
                 class="data_field {if !$prospect->get('Prospect Main Plain FAX')}hide{/if}">
                <i title="Fax" class="fa fa-fw fa-fax"></i> <span>{$prospect->get('Main XHTML FAX')}</span>
            </div>

            {foreach $prospect->get_other_telephones_data() key=other_telephone_key item=other_telephone}
                <div id="Prospect_Other_Telephone_{$other_telephone_key}_display" class="data_field ">
                    <i class="fa fa-fw fa-phone discreet"></i> <span>{$other_telephone.formatted_telephone}</span>
                </div>
            {/foreach}
            <div id="Prospect_Other_Telephone_display" class="data_field hide">
                <i class="fa fa-fw fa-phone discreet"></i> <span></span>
            </div>

        </div>
        <div style="clear:both">
        </div>
        <div class="data_container">
            <div style="min-height:80px;float:left;width:28px">
                <i class="fa fa-map-marker-alt"></i>
            </div>
            <div class="Contact_Address" style="float:left;min-width:272px">
                {$prospect->get('Contact Address')}
            </div>
        </div>


        <div style="clear:both">
        </div>
    </div>
    <div id="info">
        <div id="overviews">

            <table border="0" class="overview">


                <tr>
                    <td>{t}Created{/t}:</td>
                    <td class="aright">{$prospect->get('First Contacted Date')}</td>
                </tr>

                {if $prospect->get('Prospect Status')=='NotInterested'}
                    <tr>
                        <td>{t}Fail{/t}</td>
                        <td>{$prospect->get('Lost Date')}</td>
                    </tr>
                {/if}

                {if $prospect->get('Prospect Status')=='Registered'}
                    <tr>
                        <td>{t}Registered{/t}</td>
                        <td>{$prospect->get('Registered Date')}</td>
                    </tr>
                {/if}

            </table>



        </div>
    </div>
    <div style="clear:both">
    </div>
</div>

<script>


    function email_width_hack() {
        var email_length = $('#showcase_Prospect_Main_Plain_Email').text().length

        if (email_length > 30) {
            $('#showcase_Prospect_Main_Plain_Email').css("font-size", "90%");
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


        var request = '/ar_find.php?tipo=number_orders_in_process&prospect_key=' + $('#prospect').attr('key')

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
        var parent = 'prospect'
        var parent_key = $('#prospect').attr('key')
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
                change_view('orders/' + $('#prospect').attr('store_key') + '/' + data.new_id)

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