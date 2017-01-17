
        <table style="width:50%;float:right;width:100%;" border="0">
            <tbody class="{if $dn->get('State Index')>=70}hide{/if}">
                <tr>
            <td>

                <label>{t}Packer{/t}</label>

                <input id="set_packer" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                <input id="set_packer_dropdown_select_label" field="set_packer" style="width:200px"
                       scope="employee" parent="account"
                       parent_key="1" class="dropdown_select"
                        data-metadata='{ "option":"only_working"}'
                       value="{$dn->get('Delivery Note Assigned Packer Alias')}" has_been_valid="0"
                       placeholder="{t}Name{/t}"/>
                <span id="set_packer_msg" class="msg"></span>
                <i id="set_packer_save_button" class="fa fa-cloud save dropdown_select hide"
                   onclick="save_this_field(this)"></i>
                <div id="set_packer_results_container" class="search_results_container">

                    <table id="set_packer_results" border="0"  >

                        <tr class="hide" id="set_packer_search_result_template" field="" value=""
                                                       formatted_value="" onClick="select_dropdown_handler('packer',this)">

                            <td class="code"></td>
                            <td style="width:85%" class="label"></td>

                        </tr>
                    </table>

                </div>
                <script>
                    $("#set_packer_dropdown_select_label").on("input propertychange", function (evt) {

                        var delay = 100;
                        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                        delayed_on_change_dropdown_select_field($(this), delay)
                    });
                </script>


            </td>


        </tr>
                <tr>
                <td colspan=2>
                <div style="display:flex">
                    <div  class=" very_discreet " style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center">
                        {if $dn->get('State Index')<=30 and  $dn->get('State Index')>=10}<span id="start_packing" class="button"><i class="fa fa-clock-o"  aria-hidden="true"></i> {t}Start packing{/t}</span>
                        {else}
                            <i class="fa fa-clock-o" aria-hidden="true"></i> {$dn->get('Start Packing Datetime')}

                        {/if}
                    </div>

                    <div class=" very_discreet" style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center"></span><i class="fa fa-barcode" title="{t}Scan mode{/t}" aria-hidden="true"></i> {t}Scan mode{/t}</div>
                  <div class=" very_discreet" style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center"> <i class="fa fa-square-o"  title="{t}Set all as packed{/t}" aria-hidden="true"></i>  {t}mark all as packed{/t}</div>
                </div>
                
                </td>
                
            </tr>
            </tbody>
            <tbody class="{if $dn->get('State Index')<70}hide{/if}">
            <tr>
                <td>
                    <span>{t}Packed by{/t}:  {$dn->get('Delivery Note Assigned Packer Alias')}</span>
             </td>
            </tr>
            <tr>
                <td>
                    <span>{$dn->get('Finish Packing Datetime')}</span> <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
                </td>
            </tr>
            </tbody>
        </table>
    