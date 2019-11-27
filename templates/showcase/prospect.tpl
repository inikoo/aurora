{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2018 at 14:36:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<div class="sticky_notes">
{include file="sticky_note.tpl" value=$prospect->get('Sticky Note') object="Prospect" key="{$prospect->id}" field="Prospect_Sticky_Note"  }
</div>
{if $prospect->get('Prospect Customer Assigned by User Key')}
    <div style="padding: 5px 15px"><i class="warning fa fa-exclamation-circle"></i> {t}Customer manually linked{/t}  <span class="link" onclick="change_view('customers/{$prospect->customer->get('Store Key')}/{$prospect->customer->id}')">{$prospect->customer->get('Name')}</span> </div>
{/if}


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

            <table class="overview">

                <tr>
                    <td>{t}Agent{/t}:</td>
                    <td class="aright">{$prospect->user->get('Alias')}</td>
                </tr>

                <tr>
                    <td>{t}Created{/t}:</td>
                    <td class="aright">{$prospect->get('Created Date')}</td>
                </tr>



                <tr class="contacted_date_tr {if $prospect->get('Prospect Status')=='NoContacted'}hide{/if}">
                    <td>{t}Contacted{/t}</td>
                    <td class="aright Contacted_Date">{$prospect->get('First Contacted Date')}</td>
                </tr>

                <tr  class="fail_date_tr {if $prospect->get('Prospect Status')!='NotInterested'}hide{/if}">
                    <td>{t}Fail{/t}</td>
                    <td class="aright Lost_Date">{$prospect->get('Lost Date')}</td>
                </tr>

                <tr  class="registration_date_tr {if !($prospect->get('Prospect Status')=='Registered' or $prospect->get('Prospect Status')=='Invoiced' ) }hide{/if}">
                    <td>{t}Registered{/t}</td>
                    <td class="aright Registration_Date">{$prospect->get('Registration Date')}</td>
                </tr>
                <tr  class="registration_date_tr {if  $prospect->get('Prospect Status')!='Invoiced'  }hide{/if}">
                    <td>{t}Invoiced{/t}</td>
                    <td class="aright Invoiced_Date">{$prospect->get('Invoiced Date')}</td>
                </tr>


            </table>

            <style>
                .not_interested_button{
                    clear:both;position:relative;top:10px;margin-top:20px;border:1px solid indianred; lightpink;padding:5px 10px
                }
                .not_interested_button:hover{
                    opacity: 1;
                }

             </style>

            <span  class="button unselectable error very_discreet not_interested_button {if $prospect->get('Prospect Status')!='Contacted'}hide{/if}" onclick="prospect_not_interested(this)" data-key="{$prospect->id}">{t}Set as not interested{/t} <i class="fal fa-frown margin_left_5 fa-fw" ></i></span>


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

    $("#take_order").on( 'click',function () {
        open_new_order()
    })



    function prospect_not_interested(element){


        var save_icon = $(element).find('i')

        if (save_icon.hasClass('wait')) {
            return;
        }


        console.log(save_icon)


        save_icon.removeClass('fa-frown ').addClass('fa-spin fa-spinner wait')

        var ajaxData = new FormData();


        ajaxData.append("tipo", 'edit_field')
        ajaxData.append("object", 'Prospect')
        ajaxData.append("key", $(element).data('key'))
        ajaxData.append("field", 'Prospect_Status')
        ajaxData.append("value",'NotInterested')


        $.ajax({
            url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {


                if (data.state == '200') {


                    for (var key in data.update_metadata.class_html) {
                        $('.' + key).html(data.update_metadata.class_html[key])
                    }



                    for (var key in data.update_metadata.hide) {


                        $('.' + data.update_metadata.hide[key]).addClass('hide')
                    }

                    for (var key in data.update_metadata.show) {

                        $('.' + data.update_metadata.show[key]).removeClass('hide')
                    }

                    console.log(state.tab)


                    if(state.tab='prospect.history'){
                        rows.fetch({
                            reset: true
                        });
                        get_elements_numbers(rows.tab, rows.parameters)

                    }


                } else if (data.state == '400') {


                    save_icon.addClass('fa-frown ').removeClass('fa-spin fa-spinner wait')



                }


            }, error: function () {

            }
        });

    }

</script>