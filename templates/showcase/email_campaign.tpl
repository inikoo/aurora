{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2017 at 18:53:20 GMT+8, Kuala Lumpur, Malaydia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<style>
    #select_date_control_panel td {
        padding: 0px
    }

    .sent_email_data > div {
        padding: 5px 10px 15px 10px;
        border-right: 1px solid #ccc;
        flex-grow: 1;
        text-align: center
    }

    .sent_email_data > div label {
        font-size: smaller;
    }

    .sent_email_data > div div {
        font-weight: bold;
        margin-top: 5px;
    }

</style>


<div id="email_campaign" data-object='{$object_data}' data-email_campaign_key="{$email_campaign->id}">

    <div class="timeline_horizontal with_time     ">

        <ul class="timeline">


            {if $email_campaign->get('Email Campaign Type')=='Newsletter'}
                <li id="composed_email_node" class="li {if $email_campaign->get('State Index')>=30}complete{/if}">
                    <div class="label     ">
                        <span class="state ">{t}Compose newsletter{/t}</span>
                    </div>
                    <div class="timestamp">
                        <span class="Email_Campaign_Composed_Date">{$email_campaign->get('Composed Date')} &nbsp;</span> <span class="start_date">{$email_campaign->get('Creation Date')}</span>
                    </div>
                    <div class="dot"></div>
                </li>
            {else}
                <li id="setup_mail_list_node" class="li {if $email_campaign->get('State Index')>=20}complete{/if}">
                    <div class="label     ">
                        <span class="state ">{t}Setup mail list{/t}</span>
                    </div>
                    <div class="timestamp">
                        <span class="Email_Campaign_Setup_Date">{$email_campaign->get('Setup Date')}&nbsp;</span> <span class="start_date">{$email_campaign->get('Creation Date')}</span>
                    </div>
                    <div class="dot"></div>
                </li>
                <li id="composed_email_node" class="li  {if $email_campaign->get('State Index')>=30}complete{/if} ">
                    <div class="label">
                        <span class="state">&nbsp;{t}Compose email{/t}&nbsp;<span></i></span></span>
                    </div>
                    <div class="timestamp">
                        <span class="Email_Campaign_Composed_Date" ">&nbsp;{$email_campaign->get('Composed Date')}&nbsp;</span>
                    </div>
                    <div class="dot"></div>
                </li>
            {/if}


            <li id="scheduled_node" class="hide li {if $email_campaign->get('State Index')>=40}complete{/if} ">
                <div class="label">
                    <span class="state">{t}Schedule send{/t}</span>
                </div>
                <div class="timestamp">
                    <span>&nbsp;<span class="Email_Campaign_Scheduled_Date">&nbsp;{$email_campaign->get('Scheduled Date')}</span></span>
                </div>
                <div class="dot"></div>
            </li>

            <li id="sending_node" class="li {if $email_campaign->get('State Index')>=50}complete{/if} ">
                <div class="label">
                    <span class="state">{t}Start send{/t}</span>
                </div>
                <div class="timestamp">
                    <span>&nbsp;<span class="Email_Campaign_Start_Send_Date">&nbsp;{$email_campaign->get('Start Send Date')}</span></span>
                </div>
                <div class="dot"></div>
            </li>

            <li id="stopped_node" class="li stopped_node cancelled {if $email_campaign->get('State Index')==60}complete{else}hide{/if} ">
                <div class="label">
                    <span class="state">{t}Stopped{/t}</span>
                </div>
                <div class="timestamp">
                    <span>&nbsp;<span class="Email_Campaign_Stopped_Date">&nbsp;{$email_campaign->get('Start Stopped Date')}</span></span>
                </div>
                <div class="dot"></div>
            </li>


            <li id="sent_node" class="li sent_node {if $email_campaign->get('State Index')>=100}complete{/if}">
                <div class="label">
                    <span class="state">{t}Sent{/t}</span>
                </div>
                <div class="timestamp">
                <span>&nbsp;<span class="Email_Campaign_End_Send_Date"> {$email_campaign->get('End Send Date')}</span>
                    &nbsp;</span>
                </div>
                <div class="dot"></div>
            </li>

        </ul>


    </div>
    <div class="order control_panel  {if $email_campaign->get('State Index')==100}hide{/if}">
        <div class="block estimated_recipients" style="padding:10px 20px; align-items: stretch;flex: 1">


            <div style="margin-bottom: 10px" class="_mailshot_scope   {if $email_campaign->metadata('description')==''}hide{/if}">
                <span class="mailshot_scope">{$email_campaign->metadata('description')}</span>
            </div>

            <div class="estimated_recipients_pre_sent   {if $email_campaign->get('State Index')>=50}hide{/if}">
                <span class="hide Email_Campaign_Number_Estimated_Emails">{$email_campaign->get('Email Campaign Number Estimated Emails')}</span>
                <span>{t}Estimated recipients{/t}</span> <span class="strong Number_Estimated_Emails">{$email_campaign->get('Number Estimated Emails')}</span>
            </div>


            <div class="estimated_recipients_post_sent   {if $email_campaign->get('State Index')<50}hide{/if}">
                <span class="_Sent_Emails_Info">{$email_campaign->get('Sent Emails Info')}</span>
            </div>

            {if $email_campaign->get('Email Campaign Type')=='Newsletter'}


                {$email_campaign->get('Email Campaign Wave Type')}
            {if $email_campaign->get('Email Campaign Wave Type')!='Wave' }

            <div class="second_wave_option small" style="margin-top: 10px">
                <span onClick="toggle_set_second_wave(this)" class="button"><span class="_Second_Wave_Option">{t}2nd wave{/t} <i
                                class="button far {if  $email_campaign->get('Email Campaign Wave Type')=='Yes'} fa-toggle-on {else}  fa-toggle-off{/if}"></i></span></span>
            </div>
                {/if}
            {/if}

            <div style="clear:both"></div>
        </div>
        <div class="block email_campaign_operations" style="align-items: stretch;flex: 1;">
            <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
                <div id="back_operations">

                    <div id="delete_operations" class="email_campaign_operation {if $email_campaign->get('State Index')<0 or   $email_campaign->get('State Index')>=50 }hide{/if}">
                        <div class="square_button left" title="{t}Delete{/t}">


                            <i class="far fa-trash-alt discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i>
                            <table id="delete_dialog" class="order_operation_dialog hide">
                                <tr class="top">
                                    <td colspan="2">{if  $email_campaign->get('Email Campaign Type')=='Newsletter'}{t}Delete newsletter{/t}{else}{t}Delete mailshot{/t}{/if}</td>
                                </tr>
                                <tr class="changed buttons">
                                    <td>
                                        <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('delete')"></i>
                                    </td>
                                    <td class="aright">
                                    <span data-data='{ "object": "email_campaign", "key":"{$email_campaign->id}"  }' onClick="delete_object(this)">
                                    <span class="label">{t}Delete{/t}</span>
                                    <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i>
                                    </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>


                    <div id="undo_set_selecting_blueprint"
                         class="email_campaign_operation {if !($email_campaign->get('Email Campaign State')=='ComposingEmail' and   $email_campaign->get('Email Campaign Selecting Blueprints')=='Yes') }hide{/if}">
                        <div class="square_button left  " title="{t}Undo{/t}">
                            <i class="fa  fa-undo button " id="stop_save_buttons" aria-hidden="true"
                               onclick="undo_email_template_as_selecting_blueprints({$email_campaign->get('Email Campaign Email Template Key')})"></i>

                        </div>
                    </div>


                    <div id="undo_set_as_ready_operations" class="email_campaign_operation {if $email_campaign->get('State Index')!=30   }hide{/if}">
                        <div class="square_button left  " title="{t}Edit email{/t}">
                            <i class="fa discreet fa-edit button" id="undo_set_as_ready_save_buttons" aria-hidden="true"
                               data-data='{  "field": "Email Campaign State","value": "ComposingEmail","dialog_name":"undo_set_as_ready"}' onclick="save_email_campaign_operation(this)"></i>

                        </div>
                    </div>


                    <div id="stop_operations" class="email_campaign_operation {if $email_campaign->get('State Index')!=50   }hide{/if}">
                        <div class="square_button left  " title="{t}Stop sending emails{/t}">
                            <i class="fa  fa-stop button error" id="stop_save_buttons" aria-hidden="true" data-data='{  "field": "Email Campaign State","value": "Stopped","dialog_name":"stop"}'
                               onclick="stop_mailshot(this)"></i>

                        </div>
                    </div>

                    <div id="set_mail_list_operations"
                         class="email_campaign_operation  {if $email_campaign->get('State Index')!=20 or $email_campaign->get('Email Campaign Type')=='Newsletter' or $email_campaign->get('Email Campaign Type')=='AbandonedCart'
                         or ($email_campaign->get('Email Campaign Type')=='Marketing')
                         }hide{/if}">
                        <div class="square_button left  " title="{t}Set mailing list{/t}">
                            <i class="fa fa-users button discreet" id="set_mail_list_save_buttons" aria-hidden="true" data-data='{  "field": "Email Campaign State","value": "InProcess","dialog_name":"set_mail_list"}'
                               onclick="save_email_campaign_operation(this)"></i>

                        </div>
                    </div>


                </div>
                <span style="float:left;padding-left:10px;padding-top:5px" class="Email_Campaign_State"> {$email_campaign->get('State')} </span>
                <div id="forward_operations">


                    <div id="compose_email_operations" class="email_campaign_operation {if $email_campaign->get('State Index')!=10   or  $email_campaign->get('Email Campaign Number Estimated Emails')==0 }hide{/if}">
                        <div class="square_button right  " title="{t}Compose email{/t}">
                            <i class="fa fa-edit button discreet" id="compose_email_save_buttons" aria-hidden="true" data-data='{  "field": "Email Campaign State","value": "ComposingEmail","dialog_name":"compose_email"}'
                               onclick="save_email_campaign_operation(this)"></i>

                        </div>
                    </div>


                    <div id="send_mailshot_operations" class="email_campaign_operation {if $email_campaign->get('State Index')!=30}hide{/if}">
                        <div class="square_button right" title="{t}Send mailshot{/t}">


                            <i class="fa fa-paper-plane " aria-hidden="true" onclick="toggle_order_operation_dialog('send_mailshot')"></i>
                            <table id="send_mailshot_dialog" class="order_operation_dialog hide">
                                <tr class="top">
                                    <td colspan="2">{t}Send mailshot now{/t}</td>
                                </tr>
                                <tr class="changed buttons">
                                    <td>
                                        <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('send_mailshot')"></i>
                                    </td>
                                    <td class="aright">
                                    <span data-data='{ "object": "email_campaign", "key":"{$email_campaign->id}"  }' onClick="send_mailshot_now(this)">
                                    <span class="label">{t}Start sending{/t}</span>
                                    <i class="fa fa-paper-plane fa-fw  " aria-hidden="true"></i>
                                    </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>

                    <div id="schedule_mailshot_operations" class="hide email_campaign_operation {if {$email_campaign->get('State Index')}!=30  }hide{/if}">
                        <div class="square_button right  " title="{t}Schedule mailshot{/t}">
                            <i class="far fa-clock  " aria-hidden="true" onclick="toggle_order_operation_dialog('schedule_mailshot')"></i>


                            <table id="schedule_mailshot_dialog" style="width: 650px" border="1" class="order_operation_dialog hide">
                                <tr class="top">
                                    <td class="label" colspan="2"><i class="fa fa-sign-out fa-flip-horizontal button padding_left_20" aria-hidden="true"
                                                                     onclick="close_dialog('schedule_mailshot')"></i>{t}Schedule mailshot{/t}</td>
                                </tr>

                                <tr style="height: 50px">
                                    <td style="text-align: center">
                                    <span onclick="send_mailshot_now(this)" class="square_button" style="border:1px solid #ccc;padding:5px">{t}Send now{/t} <i class="fa fa-fw fa-paper-plane"></i>
                                    <span class="square_button hide " style="border:1px solid #ccc;padding:5px;margin-right: 10px;margin-left: 10px">{t}Send in <em>n</em> hours{/t}</span>
                                    <span class="square_button hide" style="border:1px solid #ccc;padding:5px">{t}Choose time{/t}</span>

                                    </td>
                                </tr>


                                <tr class="changed buttons hide">

                                    <td style="text-align: center">{t}Send in{/t} <input type="number" style="width:3em"> {t}hours{/t} <span class="save "><i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span>
                                    </td>
                                </tr>


                                <tr class="changed buttons hide">

                                    <td style="text-align: center">

                                        <div id="select_date_control_panel" class="xhide">
                                            <div id="select_date_datepicker" class="datepicker" style="float:left"></div>
                                            <div class="date_chooser_form">

                                                <table>
                                                    <tr style="height: 20px">
                                                        <td style="text-align: left;line-height:20px" class="small">{t}Date{/t}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input id="select_date_formatted" style="width: 7em" value=""/></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr style="height: 20px">
                                                        <td style="text-align: left;line-height:20px" class="small">{t}time{/t}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><input id="select_time_formatted" style="width: 5em" value=""/></td>
                                                        <td><i onclick="submit_date()" id="select_date_save" class="fa button fa-play save padding_left_20"></i></td>
                                                    </tr>
                                                </table>


                                            </div>
                                            <div style="clear:both"></div>
                                        </div>

                                    </td>
                                </tr>


                                <tr class="changed buttons hide">

                                    <td class="aright"><span data-data='{  "field": "Order State","value": "Approved","dialog_name":"schedule_mailshot"}' id="schedule_mailshot_save_buttons" class="valid save button"
                                                             onclick="save_email_campaign_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div id="resume_operations" class="email_campaign_operation {if $email_campaign->get('State Index')!=60   }hide{/if}">
                        <div class="square_button right  " title="{t}Resume sending{/t}">
                            <i class="fa  fa-play button success" id="resume_save_buttons" aria-hidden="true" data-data='{  "field": "Email Campaign State","value": "Resume","dialog_name":"resume"}'
                               onclick="resume_mailshot(this)"></i>

                        </div>
                    </div>

                </div>
            </div>

            <table class="hide info_block acenter">

                <tr>

                    <td>
                        <span><i class="fa fa-cube fa-fw discreet" aria-hidden="true"></i> <span class="Order_Number_items">{$email_campaign->get('Number Items')}</span></span>
                        <span style="padding-left:20px"><i class="fa fa-tag fa-fw  " aria-hidden="true"></i> <span class="Order_Number_Items_with_Deals">{$email_campaign->get('Number Items with Deals')}</span></span>
                        <span class="error {if $email_campaign->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                                    class="Order_Number_Items_with_Out_of_Stock">{$email_campaign->get('Number Items Out of Stock')}</span></span>
                        <span class="error {if $email_campaign->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                    class="Order_Number_Items_with_Returned">{$email_campaign->get('Number Items Returned')}</span></span>
                    </td>
                </tr>


            </table>

        </div>
        <div class="block " style="align-items: stretch;flex:2;padding:0px ">
            <div style="display:flex" class=" sent_email_data">
                <div>
                    <label>{t}Sent{/t}</label>
                    <div class="_Email_Campaign_Sent">{$email_campaign->get('Sent')}</div>

                </div>
                <div class="hide">
                    <label>{t}Bounces{/t}</label>
                    <div class="Email_Campaign_Bounces_Percentage">{$email_campaign->get('Bounces Percentage')}</div>
                </div>
                <div>
                    <label>{t}Hard bounces{/t}</label>
                    <div title="{$email_campaign->get('Hard Bounces')}" class="Email_Campaign_Hard_Bounces_Percentage">{$email_campaign->get('Hard Bounces Percentage')}</div>
                </div>
                <div>
                    <label>{t}Soft bounces{/t}</label>
                    <div title="{$email_campaign->get('Soft Bounces')}" class="Email_Campaign_Soft_Bounces_Percentage">{$email_campaign->get('Soft Bounces Percentage')}</div>
                </div>
                <div class="hide">
                    <label>{t}Delivered{/t}</label>
                    <div class="Email_Campaign_Delivered">{$email_campaign->get('Delivered')}</div>
                </div>
                <div>
                    <label> {t}Opened{/t}</label>
                    <div class="Email_Campaign_Open">{$email_campaign->get('Open')}</div>
                </div>
                <div>
                    <label>{t}Clicked{/t}</label>
                    <div class="Email_Campaign_Clicked">{$email_campaign->get('Clicked')}</div>
                </div>

            </div>
        </div>

        <div style="clear:both"></div>
    </div>

    <div class="order sent_email_data {if $email_campaign->get('State Index')!=100}hide{/if}">


        <div class="block  " style="font-size:small;padding:5px 20px; align-items: stretch;flex: 4">


            <div style="margin-bottom: 2px;text-align: left" class="_mailshot_scope   {if $email_campaign->metadata('description')==''}hide{/if}">
                <span class="mailshot_scope">{$email_campaign->metadata('description')}</span>
            </div>


            <div class="second_wave_info small {if $email_campaign->get('Email Campaign Wave Type')!='Yes' or $email_campaign->get('Email Campaign Second Wave Date')==''}hide{/if}  " style="margin-top: 10px;font-weight:normal"  >
                {t}2nd wave{/t}<br> {$email_campaign->get('Second Wave Formatted Date')}
              </div>


            <div style="clear:both"></div>
        </div>

        <div>
            <label>{t}Sent{/t}
            </label>
            <div><span class="Email_Campaign_Sent">{$email_campaign->get('Sent')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span style="font-weight: normal;font-size: xx-small">{t}2nd{/t} <i class="fal fa-water"></i></span> <span class="Second_Wave_Sent">{$second_wave->get('Sent')}</span></div>
        </div>
        <div>
            <label>{t}Hard bounce{/t}</label>
            <div><span class="Email_Campaign_Hard_Bounce">{$email_campaign->get('Hard Bounces')}</span> <span class="padding_left_10 Email_Campaign_Hard_Bounces_Percentage">{$email_campaign->get('Hard Bounces Percentage')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span class="Second_Wave_Hard_Bounce">{$second_wave->get('Hard Bounces')}</span> <span class="padding_left_10 Second_Wave_Hard_Bounces_Percentage">{$second_wave->get('Hard Bounces Percentage')}</span></div>

        </div>
        <div>
            <label>{t}Soft bounce{/t}</label>
            <div><span class="Email_Campaign_Soft_Bounce">{$email_campaign->get('Soft Bounces')}</span> <span class="padding_left_10 Email_Campaign_Soft_Bounces_Percentage">{$email_campaign->get('Soft Bounces Percentage')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span class="Second_Wave_Soft_Bounce">{$second_wave->get('Soft Bounces')}</span> <span class="padding_left_10 Second_Wave_Soft_Bounces_Percentage">{$second_wave->get('Soft Bounces Percentage')}</span></div>

        </div>
        <div class="hide">
            <label>{t}Delivered{/t}</label>
            <div><span class="Email_Campaign_Delivered">{$email_campaign->get('Delivered')}</span> <span class="padding_left_10 Email_Campaign_Delivered_Percentage">{$email_campaign->get('Delivered Percentage')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span class="Second_Wave_Delivered">{$second_wave->get('Delivered')}</span> <span class="padding_left_10 Second_Wave_Delivered_Percentage">{$second_wave->get('Delivered Percentage')}</span></div>

        </div>

        <div>
            <label> {t}Opened{/t}</label>
            <div><span class="Email_Campaign_Open">{$email_campaign->get('Open')}</span> <span class="padding_left_10 Email_Campaign_Open_Percentage">{$email_campaign->get('Open Percentage')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span class="Second_Wave_Open">{$second_wave->get('Open')}</span> <span class="padding_left_10 Second_Wave_Open_Percentage">{$second_wave->get('Open Percentage')}</span></div>

        </div>
        <div>
            <label>{t}Clicked{/t}</label>
            <div><span class="Email_Campaign_Clicked">{$email_campaign->get('Clicked')}</span> <span class="padding_left_10 Email_Campaign_Clicked_Percentage">{$email_campaign->get('Clicked Percentage')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span class="Second_Wave_Clicked">{$second_wave->get('Clicked')}</span> <span class="padding_left_10 Second_Wave_Clicked_Percentage">{$second_wave->get('Clicked Percentage')}</span></div>

        </div>

        <div>
            <label>{t}Spam{/t}</label>
            <div><span class="Email_Campaign_Spams">{$email_campaign->get('Spams')}</span> <span class="padding_left_10 Email_Campaign_Spams_Percentage">{$email_campaign->get('Spams Percentage')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span class="Second_Wave_Spams">{$second_wave->get('Spams')}</span> <span class="padding_left_10 Second_Wave_Spams_Percentage">{$second_wave->get('Spams Percentage')}</span></div>

        </div>
        <div>
            <label>{t}Unsubscribed{/t}</label>
            <div><span class="Email_Campaign_Unsubscribed">{$email_campaign->get('Unsubscribed')}</span> <span class="padding_left_10 Email_Campaign_Unsubscribed_Percentage">{$email_campaign->get('Unsubscribed Percentage')}</span></div>
            <div class="{if !isset($second_wave)}hide{/if}"><span class="Second_Wave_Unsubscribed">{$second_wave->get('Unsubscribed')}</span> <span class="padding_left_10 Second_Wave_Unsubscribed_Percentage">{$second_wave->get('Unsubscribed Percentage')}</span></div>

        </div>
    </div>

</div>


<script>

    $("#select_date_datepicker").datepicker({
        showOtherMonths: true, selectOtherMonths: true, defaultDate: new Date(""), altField: "#select_date", altFormat: "yy-mm-dd", minDate: 0, onSelect: function () {
            $('#select_date').change();
            $('#select_date_formatted').val($.datepicker.formatDate("dd-mm-yy", $(this).datepicker("getDate")))
            validate_date()
        }
    });

    function validate_date() {
        $('#select_date_save').removeClass('possible_valid valid invalid')

        if ($("#select_date_formatted").val() == '') {
            validation = 'possible_valid';
        } else {
            validation = 'valid';

        }
        $('#select_date_save').addClass(validation)

    }

    /*
        function set_up_mailing_list(){

            $('#mailshot\\.set_mail_list').removeClass('hide')
            change_tab('email_campaign.set_mail_list')

        }

    */

    function toggle_set_second_wave(element) {

        var icon = $(element).find('i');
        if (icon.hasClass('wait')) {
            return

        }


        var second_wave = 'No';
        if (icon.hasClass('fa-toggle-off')) {
            second_wave = 'Yes';

        }


        icon.addClass('fa-spin fa-spinner wait')


        var form_data = new FormData();

        form_data.append("tipo", 'set_second_wave')
        form_data.append("key", $('#email_campaign').data('email_campaign_key'))
        form_data.append("second_wave", second_wave)


        var request = $.ajax({

            url: "/ar_edit_marketing.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })

        request.done(function (data) {

            icon.removeClass('fa-spin fa-spinner wait')

            if (data.state == 200) {

                if (data.second_wave) {
                    icon.removeClass('fa-toggle-off ').addClass('fa-toggle-on')
                } else {
                    icon.addClass('fa-toggle-off').removeClass('fa-toggle-on')
                }
            } else {

            }
        })


    }

    function set_mailing_list() {

        var form_data = new FormData();

        var fields_data = {};
        var re = new RegExp('_', 'g');

        $(".value").each(function (index) {

            var field = $(this).attr('field')
            var field_type = $(this).attr('field_type')

            if (field == 'List_Name') return 1;
            if (field == 'List_Type') return 1;


            if (field_type == 'time') {
                value = clean_time($('#' + field).val())
            } else if (field_type == 'date' || field_type == 'date_interval') {
                if ($('#' + field + '_value').val() != '') {
                    value = $('#' + field + '_value').val() + ' ' + $('#' + field + '_time').val()
                } else {
                    value = ''
                }
            } else if (field_type == 'password' || field_type == 'password_with_confirmation' || field_type == 'password_with_confirmation_paranoid' || field_type == 'pin' || field_type == 'pin_with_confirmation' || field_type == 'pin_with_confirmation_paranoid') {
                value = sha256_digest($('#' + field).val())
            } else if (field_type == 'attachment') {
                form_data.append("file", $('#' + field).prop("files")[0])
                value = ''
            } else if (field_type == 'country_select') {
                value = $('#' + field).countrySelect("getSelectedCountryData").code

            } else if (field_type == 'telephone') {
                value = $('#' + field).intlTelInput("getNumber");

            } else if (field_type == 'subscription') {
                var icon = $(this).find('i')
                if (icon.hasClass('fa-toggle-on')) {
                    value = 'Yes'

                } else {
                    value = 'No'
                }

            } else if (field_type == 'elements') {
                var icon = $(this).find('i')
                if (icon.hasClass('fa-check-square')) {
                    value = 'Yes'

                } else {
                    value = 'No'
                }

            } else if (field_type == 'with_field') {
                var icon = $(this).find('i')
                if (icon.hasClass('fa-toggle-on')) {
                    value = 'Yes'

                } else if (icon.hasClass('fa-toggle-off')) {
                    value = 'No'

                } else {
                    value = ''
                }
            } else {
                var value = $('#' + field).val()
            }


            fields_data[field.replace(re, ' ')] = value


        });


        // used only for debug
        var request = '/ar_edit_marketing.php?tipo=set_mailing_list&object=' + $('#fields').attr('object') + '&key=' + $('#fields').attr('key') + '&fields_data=' + JSON.stringify(fields_data)
        console.log(request)


        //return;
        //=====
        form_data.append("tipo", 'set_mailing_list')
        form_data.append("object", $('#fields').attr('object'))
        form_data.append("key", $('#fields').attr('key'))
        form_data.append("fields_data", JSON.stringify(fields_data))

        var request = $.ajax({

            url: "/ar_edit_marketing.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })

        request.done(function (data) {


            console.log(data)


            switch (data.update_metadata.state) {
                case 'ComposingEmail':
                    $('#mailshot\\.workshop').removeClass('hide')
                    change_tab('mailshot.workshop')
                    break;


            }


            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.email_campaign_operation').addClass('hide')
            // $('.items_operation').addClass('hide')


            for (var key in data.update_metadata.operations) {

                console.log('#' + data.update_metadata.operations[key])

                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }


            $('.timeline .li').removeClass('complete')


            if (data.update_metadata.state_index >= 20) {
                $('#setup_mail_list_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 30) {
                $('#composed_email_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 80) {
                $('#packed_done_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 90) {
                $('#approved_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 100) {
                $('#dispatched_node').addClass('complete')
            }


        })

        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });


    }


    function create_second_wave_newsletter() {


        // used only for debug
        var request = '/ar_edit_marketing.php?tipo=create_new_wave_newsletter&object=' + $('#fields').attr('object') + '&key=' + $('#fields').attr('key')
        console.log(request)


        var form_data = new FormData();

        form_data.append("tipo", 'create_new_wave_newsletter')
        form_data.append("object", $('#fields').attr('object'))
        form_data.append("key", $('#fields').attr('key'))

        var request = $.ajax({

            url: "/ar_edit_marketing.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })

        request.done(function (data) {


            console.log(data)


            switch (data.update_metadata.state) {
                case 'ComposingEmail':
                    $('#mailshot\\.workshop').removeClass('hide')
                    change_tab('mailshot.workshop')
                    break;


            }


            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.email_campaign_operation').addClass('hide')
            // $('.items_operation').addClass('hide')


            for (var key in data.update_metadata.operations) {

                console.log('#' + data.update_metadata.operations[key])

                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }


            $('.timeline .li').removeClass('complete')


            if (data.update_metadata.state_index >= 20) {
                $('#setup_mail_list_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 30) {
                $('#composed_email_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 80) {
                $('#packed_done_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 90) {
                $('#approved_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 100) {
                $('#dispatched_node').addClass('complete')
            }


        })

        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });


    }


</script>
