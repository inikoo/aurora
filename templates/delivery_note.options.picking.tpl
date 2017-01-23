
        <table style="width:50%;float:right;width:100%;min-height: 100px;" border="0">
            <tbody class="{if $dn->get('State Index')>=30}hide{/if}">
                <tr>
            <td style="width: 50%;padding:10px;border-right:1px solid whitesmoke" >

                <label>{t}Picker{/t}</label>

                <input id="set_picker" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px"
                       scope="employee" parent="account"
                       parent_key="1" class="dropdown_select"
                        data-metadata='{ "option":"only_working"}'
                       value="{$dn->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0"
                       placeholder="{t}Name{/t}"/>
                <span id="set_picker_msg" class="msg"></span>
                <i id="set_picker_save_button" class="fa fa-cloud save dropdown_select hide"
                   onclick="save_this_field(this)"></i>
                <div id="set_picker_results_container" class="search_results_container hide">

                    <table id="set_picker_results" border="0"  >

                        <tr class="hide" id="set_picker_search_result_template" field="" value=""
                            formatted_value="" onClick="select_dropdown_handler('picker',this)">
                            <td class="code"></td>
                            <td style="width:85%" class="label"></td>

                        </tr>
                    </table>

                </div>
                <script>
                    $("#set_picker_dropdown_select_label").on("input propertychange", function (evt) {

                        var delay = 100;
                        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                        delayed_on_change_dropdown_select_field($(this), delay)
                    });
                </script>

                <div style="display:flex;margin-top:10px">
                    <div  class=" very_discreet Delivery_Note_Start_Picking_Datetime" style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center">
                        {if $dn->get('State Index')==10}<span id="start_picking" class="button"><i class="fa fa-clock-o" title="{t}Start picking{/t}" aria-hidden="true"></i> {t}Start picking{/t}</span>
                        {else}
                            {$dn->get('Start Picking Datetime')}

                        {/if}


                    </div>

                    <div class=" very_discreet hide" style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center"><i class="fa fa-barcode" title="{t}Scan mode{/t}" aria-hidden="true"></i> {t}Scan mode{/t}</div>
                    <div class=" very_discreet hide" style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center"> <i class="fa fa-square-o"  title="{t}Set all as picked{/t}" aria-hidden="true"></i>  {t}mark all as picked{/t}</div>

                    <div class="  "style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center">

                        <a class="pdf_link" target='_blank' href="/pdf/order_pick_aid.pdf.php?id={$delivery_note->id}"> <img src="/art/pdf.gif"></a>

                    </div>

                </div>


            </td>

            <td id="picking_barcode_feedback" style="position:relative;padding:0px">
                <i  class="fa fa-barcode" aria-hidden="true" style="position:absolute;top:10px;right:10px"></i>


                <div class="hide">

                <div style="display:flex;"  >
                    <div style="align-items: stretch;flex: 0">


                    <img src="/art/nopic.png" style="max-height: 70px;max-width: 100px">
                    </div>
                    <div style="align-items: stretch;flex: 1">
                        <span id="picking_barcode_part_reference"></span>
                        <p style="padding:0px;margin: 0px;margin-bottom:4px;font-size:90%" id="picking_barcode_part_description"></p>

                        <span class="ordered_quamtity"  style="padding:0px 10px"></span> <input val="" style="width: 50px"> <i class="fa fa-plus" aria-hidden="true"></i>

                    </div>


                </div>

                </div>
            </td>


        </tr>

            </tbody>
            <tbody class="{if $dn->get('State Index')<30}hide{/if}">
            <tr>
                <td style="text-align: center">
                    <span class="discreet">{t}Picked by{/t}:</span>  {$dn->get('Delivery Note Assigned Picker Alias')}
             </td>
            </tr>
            <tr>
                <td>
                    <div style="display:flex">
                        <div  class=" " style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center">

                        <span class="Delivery_Note_Finish_Picking_Datetime discreet">{$dn->get('Finish Picking Datetime')}</span> <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
                        </div>
                        <div class="  "style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center">

                            <a class="pdf_link" target='_blank' href="/pdf/order_pick_aid.pdf.php?id={$delivery_note->id}"> <img src="/art/pdf.gif"></a>

                        </div>

                    </div>

                </td>
            </tr>
            </tbody>
        </table>
    