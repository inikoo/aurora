<span id="dn_data" class="hide"  dn_key="{$dn->id}"   picker_key="{$dn->get('Delivery Note Assigned Picker Key')}"  packer_key="{$dn->get('Delivery Note Assigned Packer Key')}"  ></span>
<div class="table_new_fields">
  
    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-left:1px solid #eee">
        <table style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;" border="0">
            <tbody>
           <tr>
            <td>

                <label>{t}Picker{/t}</label>

                <input id="set_picker" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                <input id="set_picker_dropdown_select_label" field="set_picker" style="width:200px"
                       scope="employee" parent="account"
                       parent_key="1" class="dropdown_select"
                        data-metadata='{ "option":"only_working"}'
                       value="{$dn->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0"
                       placeholder="{t}Name{/t}"/>
                <span id="set_picker_msg" class="msg"></span>
                <i id="set_picker_save_button" class="fa fa-cloud save dropdown_select hide"
                   onclick="save_this_field(this)"></i>
                <div id="set_picker_results_container" class="search_results_container">

                    <table id="set_picker_results" border="0"  >

                        <tr class="hide" id="set_picker_search_result_template" field="" value=""
                            formatted_value="" onClick="select_dropdown_picker(this)">
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


            </td>


        </tr>
            <tr>
                <td colspan=2>
                <div style="display:flex">
                  <div class=" very_discreet" style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center"></span><i class="fa fa-barcode" title="{t}Scan mode{/t}" aria-hidden="true"></i> {t}Scan mode{/t}</div>
                  <div class=" very_discreet" style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center"> <i class="fa fa-square-o"  title="{t}Set all as picked{/t}" aria-hidden="true"></i>  {t}mark all as picked{/t}</div>
                </div>
                
                </td>
                
            </tr>
           
        </tbody></table>
    </div>
    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-left:1px solid #eee">
        <table style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;" border="0">
            <tbody>
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
                                formatted_value="" onClick="select_dropdown_packer(this)">
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
                        <div class=" very_discreet" style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center"></span><i class="fa fa-barcode" title="{t}Scan mode{/t}" aria-hidden="true"></i> {t}Scan mode{/t}</div>
                        <div class=" very_discreet" style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center"> <i class="fa fa-square-o"  title="{t}Set all as picked{/t}" aria-hidden="true"></i>  {t}mark all as picked{/t}</div>
                    </div>

                </td>

            </tr>

            </tbody></table>
    </div>
    
    
     </div>

<script>


    function select_dropdown_picker(element) {


        field = $(element).attr('field')
        value = $(element).attr('value')

        if(value==0){
            console.log('cacacaca')


            return;
        }


        section_key =  $('#add_item_dialog').attr('section_key')

        formatted_value = $(element).attr('formatted_value')
        metadata = $(element).data('metadata')


        $('#' + field + '_dropdown_select_label').val(formatted_value)


        $('#' + field).val(value)

        $('#' + field + '_results_container').addClass('hide').removeClass('show')





        var request = '/ar_edit_orders.php?tipo=set_picker&delivery_note_key='+$('#dn_data').attr('dn_key')+'&staff_key='+value
        console.log(request)




        $.getJSON(request, function (data) {

            if(data.state==200){

                $('#dn_data').attr('picker_key',data.staff_key)






            }

        })



    }



</script>