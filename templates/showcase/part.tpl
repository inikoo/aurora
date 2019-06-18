<div class="sticky_notes">
{include file="sticky_note.tpl" value=$part->get('Sticky Note') object="Part" key="{$part->id}" field="Part_Sticky_Note"  }
</div>

<div id="edit_stock_min_max" location_key="" class="hide " style="position:absolute;border:1px solid #ccc;padding:5px;width:auto;background-color: #fff;z-index: 100">
    <i style="position: relative;top:-5px;padding-right:5px" onClick="close_edit_min_max(this)" class="close_min_max button fa fa-window-close" aria-hidden="true"></i>
    <input class="recommended_min min_max" style="width:50px" ovalue="" value="" placeholder="{t}min{/t}"/>
    <input class="recommended_max min_max" style="width:50px" ovalue="" value="" placeholder="{t}max{/t}"/>
    <i onClick="save_recommendations('min_max',this)" class="fa fa-cloud save" aria-hidden="true"></i>
</div>

<div id="edit_recommended_move" location_key="" class="hide" style="position:absolute;border:1px solid #ccc;padding:5px;width:auto;background-color: #fff;z-index: 100">
    <i style="position: relative;top:-5px;padding-right:5px" onClick="close_edit_recommended_move(this)" class="close_recommended_move button fa fa-window-close" aria-hidden="true"></i>
    <input class="recommended_move min_max" style="width:70px" ovalue="" value=""/> <i onClick="save_recommendations('move',this)" class="fa fa-cloud save" aria-hidden="true"></i>
</div>



<div class="name_and_categories">
    <span class="strong"><span class="strong Part_Unit_Description">{$part->get('Part Package Description')}</span> </span>
    <ul class="tags Categories" style="float:right">
        {foreach from=$part->get_category_data() item=item key=key}
            <li><span class="button" onclick="change_view('category/{$item.category_key}')" title="{$item.label}">{$item.code}</span></li>
        {/foreach}
    </ul>
    <div style="clear:both"></div>
</div>

<div class="asset_container">

    <div class="block picture showcase_component" style="width: 370px">

        <div class="data_container">
            {assign "image_key" $part->get_main_image_key()}
            <div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img src="/{if $image_key}image.php?id={$image_key}&amp;s=270x270{else}art/nopic.png{/if}"> </span>
            </div>
            {include file='upload_main_image.tpl' object='Part' key=$part->id class="{if $image_key!=''}hide{/if}"}
        </div>
        <div style="clear:both"></div>




    </div>
    <div class="block sales_data showcase_component">
        <table class="sales">
            <tr class="header {if $part->get('Part Number Active Products')==0}hide{/if} ">
                <td colspan=3>{t}SKO commercial value{/t} <b>{$part->get('Commercial Value')}</b> <span class="tooltip" data-tooltip-content="#tooltip_part_margin">({$part->get('Margin')})</td>
            </tr>
            <tr class="header">
                <td colspan=3>{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$part->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td>{$part->get('Total Acc Dispatched Soft Minify')}</td>
                <td>{$customers}</td>
            </tr>
        </table>

        <table class="sales">
            <tr class="header">
                <td>{$year_data.0.header}</td>
                <td>{$year_data.1.header}</td>
                <td>{$year_data.2.header}</td>
                <td>{$year_data.3.header}</td>
                <td>{$year_data.4.header}</td>
            </tr>
            <tr>
                <td>
                    <span title="{$part->get('Year To Day Acc Invoiced Amount')}">{$part->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('1 Year Ago Invoiced Amount')}">{$part->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('2 Year Ago Invoiced Amount')}">{$part->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('3 Year Ago Invoiced Amount')}">{$part->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('4 Year Ago Invoiced Amount')}">{$part->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$part->get('Year To Day Acc Dispatched')}">{$part->get('Year To Day Acc Dispatched Minify')}</span>
                    <span title="{$year_data.0.dispatched_delta_title}">{$year_data.0.dispatched_delta}</span></td>
                <td>
                    <span title="{$part->get('1 Year Ago Dispatched')}">{$part->get('1 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.1.dispatched_delta_title}">{$year_data.1.dispatched_delta}</span></td>
                <td>
                    <span title="{$part->get('2 Year Ago Dispatched')}">{$part->get('2 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.2.dispatched_delta_title}">{$year_data.2.dispatched_delta}</span></td>
                <td>
                    <span title="{$part->get('3 Year Ago Dispatched')}">{$part->get('3 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.3.dispatched_delta_title}">{$year_data.3.dispatched_delta}</span></td>
                <td>
                    <span title="{$part->get('4 Year Ago Dispatched')}">{$part->get('4 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.4.dispatched_delta_title}">{$year_data.4.dispatched_delta}</span></td>
            </tr>
            <tr class="space">
                <td colspan="5"></td>
            </tr>
            <tr class="header">
                <td>{$quarter_data.0.header}</td>
                <td>{$quarter_data.1.header}</td>
                <td>{$quarter_data.2.header}</td>
                <td>{$quarter_data.3.header}</td>
                <td>{$quarter_data.4.header}</td>
            </tr>
            <tr>
                <td>
                    <span title="{$part->get('Quarter To Day Acc Invoiced Amount')}">{$part->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('1 Quarter Ago Invoiced Amount')}">{$part->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('2 Quarter Ago Invoiced Amount')}">{$part->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('3 Quarter Ago Invoiced Amount')}">{$part->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('4 Quarter Ago Invoiced Amount')}">{$part->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$part->get('Quarter To Day Acc Dispatched')}">{$part->get('Quarter To Day Acc Dispatched Minify')}</span>
                    <span title="{$quarter_data.0.dispatched_delta_title}">{$quarter_data.0.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('1 Quarter Ago Dispatched')}">{$part->get('1 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.1.dispatched_delta_title}">{$quarter_data.1.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('2 Quarter Ago Dispatched')}">{$part->get('2 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.2.dispatched_delta_title}">{$quarter_data.2.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('3 Quarter Ago Dispatched')}">{$part->get('3 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.3.dispatched_delta_title}">{$quarter_data.3.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$part->get('4 Quarter Ago Dispatched')}">{$part->get('4 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.4.dispatched_delta_title}">{$quarter_data.4.dispatched_delta}</span>
                </td>
            </tr>
        </table>





    </div>


    <div class="block stock_info">


        <div id="overviews">

            <table id="stock_table"  class="showcase_component overview" border="0" ">
                <tbody class="info">


                <tr class="main ">

                    <td class=" highlight Part_Status">{$part->get('Status')} </td>

                    <td class="aright "><span class="highlight big Stock_Status_Icon">{$part->get('Stock Status Icon')}</span>

                    </td>

                </tr>

                <tr>
                    <td colspan=2>
                        <table style="width:100%;;margin-bottom:10px">
                            <tr style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">
                                <td style="border-left:1px solid #ccc;width:30%" class="align_center " title="{t}Stock in locations{/t}"><i class="fa fa-inventory fa-fw" aria-hidden="true"></i> <span
                                            class="Current_On_Hand_Stock">{$part->get('Current On Hand Stock')}</span></td>
                                <td style="border-left:1px solid #ccc;width:20%" class="align_center discreet " title="{t}Reserved paid parts in process by customer services{/t}"><i class="fa fa-shopping-cart fa-fw"
                                                                                                                                                                                      aria-hidden="true"></i> <span
                                            class="Part_Current_Stock_Ordered_Paid">{$part->get('Current Stock Ordered Paid')}</span></td>
                                <td style="border-left:1px solid #ccc;width:20%" class="align_center discreet" title="{t}Parts been picked{/t}"
                                "><i style="font-size:80%;position: relative;top:-1px" class="fa fa-shopping-basket fa-fw" aria-hidden="true"></i> <span
                                        class="Current_Stock_In_Process">{$part->get('Current Stock In Process')}</span></td>
                                <td style="border-left:1px solid #ccc;width:30%;border-right:1px solid #ccc;font-size:110%" class=" align_center strong" title="{t}Stock available for sale{/t}"><span
                                            class="Current_Stock_Available">{$part->get('Current Stock Available')}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="Products_Web_Status">{$part->get('Products Web Status')}</td>
                    <td class="aright Available_Forecast">{$part->get('Available Forecast')}</td>
                </tr>

                </tbody>

                <tr class="main hide edit_controls" id="edit_stock_controls">

                    <td colspan=2>

                        <table style="width:100%">
                            <tr>
                                <td class="super_discreet highlight Current_On_Hand_Stock" style="font-size:200%">{$part->get('Current On Hand Stock')}</td>
                                <td id="stock_diff" class="acenter"></td>
                                <td id="new_stock" class="aright highlight" style="font-size:200%"></td>
                            </tr>
                            <tr>
                                <td><i class="fa  fa-times button discreet" aria-hidden="true" title="{t}Close edit{/t}" onClick="close_edit_stock()"></i></td>
                                <td></td>
                                <td id="saving_buttons" class="aright discreet  ">
                                    <span id="saving_buttons" class="aright discreet   ><span class=" save">{t}
                                    Save{/t}</span ><i class="fa  fa-cloud   save " aria-hidden="true" title="{t}Save{/t}"  onClick="save_stock(this)"></i></span>
                                </td>

                            </tr>


                        </table>

                    </td>

                </tr>

            </table>


            <table style="width:100%">


                <tr class="Part_Cost_in_Warehouse_info_not_set_up {if $part->get('Part Cost in Warehouse')!=''}hide{/if}">
                    <td colspan="2">
                        {t}SKO stock value no set up yet{/t}
                        <div class="italic discreet" style="margin-top:10px">{t}Add stock via a purchase order or update Stock value (per SKO){/t}</div>
                    </td>
                </tr>


                <tr class="Part_Cost_in_Warehouse_info_set_up {if $part->get('Part Cost in Warehouse')==''}hide{/if}">
                    <td colspan="2">

                       {t}Stock value{/t}: <span  style="font-size:85%" class="Part_Cost_in_Warehouse">{$part->get('Cost in Warehouse')}</span>


                </tr>


                <tr class="hide ">
                    <td>


                        <span id="close_edit_stock" class="button " onClick="close_edit_stock()">
                            <i  class="fa fa-sign-out fa-flip-horizontal  " title="{t}Exit edit stock{/t}" ></i> <span class="discreet italic">{t}Exit edit stock{/t}</span>
                        </span>
                    </td>
                    <td class="aright">
                        <span id="edit_stock_saving_buttons" class="aright discreet "><span class="save padding_right_5">{t}Save{/t}</span><i class="fa  fa-cloud   save " aria-hidden="true" title="{t}Save{/t}" onClick="save_stock(this)"></i></span>
                    </td>
                </tr>


                <tr class="hide ">
                    <td>


                        <span id="close_move_stock" class="button " onClick="close_move()">
                            <i  class="fa fa-sign-out fa-flip-horizontal  " title="{t}Exit move stock{/t}" ></i> <span class="discreet italic">{t}Exit move stock{/t}</span>
                        </span>
                    </td>
                    <td class="aright">
                        <span id="edit_stock_saving_buttons" class="aright discreet "><span class="save padding_right_5">{t}Save{/t}</span><i class="fa  fa-cloud   save " aria-hidden="true" title="{t}Save{/t}" onClick="save_stock(this)"></i></span>
                    </td>
                </tr>

            </table>

            <div id="set_part_location_note_bis" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:15px 10px 5px 10px;z-index: 100">
                <table border="0">
                    <tr style="height: 15px">
                        <td class="aright" style="padding-bottom: 0px"> <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_part_location_notes_bis()" aria-hidden="true"></i></td>
                    </tr>
                    <tr>
                        <td><textarea style="width: 200px"></textarea></td>
                    </tr>
                    <tr class="aright">
                        <td><i  onClick="save_part_location_notes_bis()" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"/></td>
                    </tr>
                </table>



            </div>


            <table id="locations_table" border="0" class="overview" part_sku="{$part->id}">

                <tr id="move_stock_tr" class="discreet button hide "  data-movements="{ }" style="border-bottom:1px solid #ccc" max="">
                    <td colspan=3><span id="move_from"></span> <i class="fa fa-fw fa-forklift padding_left_5 padding_right_5 " aria-hidden="true"></i> <span id="move_to"></span></td>

                    <td class="aright">
                        <input id="move_stock_qty" style="width:80px" value="" placeholder="{t}Move stock{/t}">
                        <i class="fa fa-fw fa-times button discreet" aria-hidden="true" title="{t}Close{/t}" onClick="close_move()"></i>

                    </td>

                </tr>


                <tbody id="part_locations" class="Part_Locations">
                {include file='part_locations.edit.tpl' locations_data=$part->get_locations('data') part_sku=$part->id  warehouse_unknown_location_key=$warehouse_unknown_location_key }
                </tbody>






                <tr id="add_location_template" class="hide">

                    <td style="width:20px" class="undo_unlink_operations ">
                        <i class="fal fa-fw   fa-trash-alt button " title="{t}Delete{/t}" onclick="delete_new_associated_location(this)"></i>
                    </td>
                    <td>
	    <span class="location_info">


              <span class="picking_location_note" style="margin-right: 3px">
                <i onclick="set_part_location_note_bis(this)" key="" class="button  fa-fw  super_discreet_on_hover far fa-sticky-note   " aria-hidden="true" title="{t}Part's location note{/t}" ></i>
                     <div  class="hide picking_location_note_value"></div>
              </span>

	        <span class="picking_location_icon"></span>
	        <span class="location_code link"></span>
        </span>

                        <span class="very_discreet recommendations">
	        <span onClick="open_edit_min_max(this)" class="min_max open_edit_min_max">{literal}{{/literal}<span
                        class="formatted_recommended_min"></span>,<span
                        class="formatted_recommended_max"></span>}</span>
	        <span class="edit_min_max hide"><i onClick="close_edit_min_max(this)"
                                               class="close_min_max button fa fa-times" aria-hidden="true"></i> <input
                        class="recommended_min min_max" style="width:30px" ovalue="" value="" placeholder="{t}min{/t}"/><input
                        class="recommended_max min_max" style="width:30px" ovalue="" value="" placeholder="{t}max{/t}"/> <i
                        onClick="save_recommendations('min_max',this)" class="fa fa-cloud save"
                        aria-hidden="true"></i></span>

	        <span onClick="open_edit_recommended_move(this)" class="recommended_move open_edit_recommended_move">[<span
                        class="formatted_recommended_move"></span>]</span>
	        <span class="edit_move hide"><i onClick="close_edit_recommended_move(this)"
                                            class="close_move button fa fa-times" aria-hidden="true"></i> <input
                        class="recommended_move" style="width:30px" ovalue="" value=""/> <i
                        onClick="save_recommendations('move',this)" class="fa fa-cloud save hide"
                        aria-hidden="true"></i></span>


	        </span>
                    </td>
                    <td class="aright  last_audit_days"></td>
                    <td class="aright  formatted_stock">0</td>
                    <td class="aright  hide stock_input"><span class="stock_change"></span>

                        <i class="far fa-dot-circle button super_discreet set_as_audit" aria-hidden="true"
                           title="{t}Mark as audited{/t}" onclick="set_as_audit(this)"></i>
                        <input class="stock" style="width:60px" action="" location_key="0" ovalue="0" value="0">

                        <input type="hidden" class="note" value="">
                        <i class="far fa-sticky-note button super_discreet add_note invisible xx" aria-hidden="true" title="{t}Note{/t}"
                           onclick="set_inventory_transaction_note(this)"></i>


                        <i class="fal fa-fw button fa-seedling hide location_to_be_associated_icon" title="{t}Location to be linked{/t}"  ></i>

                        <i class="fa fa-fw fa-forklift move_trigger button super_discreet invisible " aria-hidden="true"
                           title="{t}Move from{/t}" onclick="move(this)"></i></td>

                </tr>


                <tr id="add_location_tr" class="  hide">
                    <td></td>
                    <td colspan=3 style="padding-top: 10px" >

                        <input class="" style="width: 200px;" id="add_location" placeholder="{t}New linked location code{/t}">

                        <div id="add_location_results_container" class="search_results_container" style="width:220px;">

                            <table id="add_location_results" border="0" style="background:white;">
                                <tr class="hide" style=";" id="add_location_search_result_template" field="" value="" formatted_value=""
                                    onClick="select_add_location_option(this)">
                                    <td class="label" style="padding-left:5px;"></td>

                                </tr>
                            </table>

                        </div>

                    </td>

                </tr>
                <tr>


                    <td colspan="4" class="small " style="text-align: right">


                        <div style="float: left;" onclick="close_part_location_edit()"  class="button edit_stock_close_button hide"><i class="fas fa-sign-out-alt  fa-flip-horizontal"  ></i> {t}Close{/t} </div>

                        <span onclick="open_part_stock_check()"  class="button discreet_on_hover edit_stock_open_button margin_left_20">{t}Stock check{/t} <i class="far fa-clipboard-check"></i></span>
                        <span onclick="open_part_edit_move_stock()"  class="button discreet_on_hover edit_stock_open_button margin_left_20">{t}Move stock{/t} <i class="far fa-forklift"></i></span>
                        <span onclick="open_part_edit_locations()" class="button discreet_on_hover edit_stock_open_button margin_left_20">{t}Edit locations{/t} <i class="far fa-pallet"></i></span>


                        <span onclick="save_part_locations_edit_locations(this)" class="save edit_stock_save_button  part_locations_edit_locations_button hide"><span data-labels='{ "save_changes":"{t}Save changes{/t}","add_location":"{t}Link location{/t}","add_locations":"{t}Link locations{/t}","remove_location":"{t}Unlink location{/t}","remove_locations":"{t}Unlink locations{/t}","no_change":"{t}No changes{/t}"}'>{t}No changes{/t}</span> <i class="fa fa-cloud"></i></span>
                        <span onclick="save_part_locations_stock_check(this)" class="save edit_stock_save_button part_locations_stock_check_button hide"><span data-labels='{ "save_changes":"{t}Save changes{/t}","no_change":"{t}No changes{/t}"}'>{t}No changes{/t}</span> <i class="fa fa-cloud"></i></span>
                        <span onclick="save_part_locations_move_stock(this)" class="save edit_stock_save_button part_locations_move_stock_button hide"><span data-labels='{ "save_changes":"{t}Move stock{/t}","no_change":"{t}No changes{/t}"}'>{t}No changes{/t}</span> <i class="fa fa-cloud"></i></span>

                    </td>

                </tr>





                <div id="inventory_transaction_note" style="position:absolute;z-index:100" class="hide" scope="">
                    <textarea></textarea>
                </div>

                <script>
                    $('#inventory_transaction_note textarea').bind('input propertychange', function () {
                        inventory_transaction_note_changed()
                    });





                </script>









            </table>

            <style>
                #unknown_location_save_buttons td{
                    padding:10px 5px;border:1px solid #ccc;text-align: center;
                }

                #part_leakages_info td{
                    padding:5px 5px 10px 5px;border:1px solid #ccc;text-align: center;width: 25%
                }
                #part_leakages_info .label{
                   padding-bottom:4px
                }

                </style>


            <table id="unknown_location_table" border="0" class="overview showcase_component" >

              <tr id="unknown_location_tr" class="{if $part->get('Part Unknown Location Stock')==0}hide{/if}">
                  <td colspan="3"><i class="fa error fa-exclamation-circle" aria-hidden="true"></i>  {t}Lost & found{/t}</td>
                  <td onCLick="show_dialog_consolidate_unknown_location(this)" id="Part_Unknown_Location_Stock" class="aright  strong Unknown_Location_Stock button"  part_sku="{$part->id}" qty="{$part->get('Part Unknown Location Stock')}"  >{$part->get('Unknown Location Stock')}</td>
              </tr>


                <tr id="part_leakages_info">
                    <td class=""><div class="label">{t}Found{/t}</div><div class="Stock_Found_SKOs">{$part->get('Stock Found SKOs')}</div></td>
                    <td class="" ><div class="label">{t}Errors{/t}</div><div class="error Stock_Errors_SKOs" >{$part->get('Stock Errors SKOs')}</div></td>
                    <td ><div class="label">{t}Damaged{/t}</div><div class="error Stock_Damaged_SKOs">{$part->get('Stock Damaged SKOs')}</div></td>
                    <td ><div class="label">{t}Lost{/t}</div><div class="error Stock_Lost_SKOs">{$part->get('Stock Lost SKOs')}</div></td>

                </tr>

            </table>

            <div class="hide" id="edit_stock_dialog_consolidate_unknown_location" style="position:absolute;padding:10px;border:1px solid #ccc;background-color:#fff;z-index:2000">

                <table  border="0" class=""  style="width: 300px">

                    <tr>
                        <td colspan="2">{t}Quantity{/t} <input  id="part_leakage_qty_input" style="width:60px;"  class="qty" val=""></td>
                        <td class="aright"><i onclick="$('#edit_stock_dialog_consolidate_unknown_location').addClass('hide')"  class="fa fa-window-close button" style="position: relative;top:-10px" aria-hidden="true"></i></td>

                    </tr>

                    <tr>
                        <td colspan="3"><textarea id="part_leakage_note_input" style="width:95%;"  plaecholder="{t}Note{/t}" /></td>
                    </tr>

                    <tr  id="unknown_location_save_buttons">
                        <td class=" super_discreet" type="Other Out" onclick="save_leakage(this)" style="width: 30%"><span class="label _error"><span class="lost_error">{t}Error{/t}</span><span class="found_error">{t}Found{/t}</span></span><i class="fa fa-spinner fa-spin hide"></i></td>
                        <td class=" super_discreet" type="Broken" onclick="save_leakage(this)" style=""><span class="label damaged">{t}Damaged{/t}</span><i class="fa fa-spinner fa-spin hide"></i></td>
                        <td class=" super_discreet" type="Lost" onclick="save_leakage(this)" style="width: 30%"><span class="label lost">{t}Lost{/t}</span><i class="fa fa-spinner fa-spin hide"></i></td>

                    </tr>

                </table>

            </div>


            <div class="hide" id="edit_stock_dialog_to_production" style="position:absolute;padding:10px;border:1px solid #ccc;background-color:#fff;z-index:2000">

                <table  border="0" class=""  style="width: 300px">

                    <tr>
                        <td colspan="2">{t}Quantity{/t} <input  id="part_to_production_qty_input" style="width:60px;"  max="" class="qty" val=""> <span class="discreet italic">{t}max{/t} <span class="max"></span></span> </td>
                        <td class="aright"><i onclick="$('#edit_stock_dialog_to_production').addClass('hide')"  class="fa fa-window-close button" style="position: relative;top:-10px" aria-hidden="true"></i></td>

                    </tr>

                    <tr>
                        <td colspan="3"><textarea id="part_to_production_note_input" style="width:95%;"  plaecholder="{t}Note{/t}" /></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="aright "><span onclick="save_stock_dialog_to_production(this)" class="save">{t}Send to production{/t} <i class="fa fa-hand-rock" aria-hidden="true"></i></span></td>
                    </tr>

                </table>

            </div>




            {assign "next_deliveries" $part->get('Next Deliveries Data')}



            <table border="0" class="overview with_title next_deliveries {if $next_deliveries|@count==0}hide{/if}  ">
                <tr class="top">
                    <td colspan="3">{t}Next deliveries{/t}</td>
                </tr>
                {foreach from=$next_deliveries item=next_delivery }
                    <tr class="main ">
                        <td>{$next_delivery.formatted_link}</td>
                        <td>{$next_delivery.formatted_state}</td>
                        <td class="aright highlight">{$next_delivery.qty}</td>
                    </tr>
                {/foreach}
            </table>


        </div>
    </div>


    <div style="width: 770px">
        <table border="0" class="overview  ">
            <tr class=" units_data">

                <td>
                    <i class="fal fa-fw fa-stop-circle" title="{t}Unit{/t}" ></i>
                </td>
                <td>

                </td>

                <td>
                    <a  target="_blank"title="{t}Commercial unit label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=unit"><i class="fal fa-barcode-alt fa-fw padding_right_5" ></i></a> <span class="Part_Barcode_Number" data-label_no_set="{t}Not set{/t}" >{if $part->get('Part Barcode Number')==''}<span class="discreet italic">{t}Not set{/t}</span>{else}{$part->get('Part Barcode Number')}{/if}</span>
                    {if $part->get('Part Barcode Key')}
                        <i class="discreet_on_hover button fal fa-external-link-square" onClick="change_view('inventory/barcode/{$part->get('Part Barcode Key')}')"></i>
                    {/if}
                    <span onclick="download_label()" class="link download_a4_label  "  target="_blank" title="{t}Commercial unit label in A4{/t}" data-href="/asset_label.php?object=part&key={$part->id}&type=" data-type="unit_EL30" ><i class="fal fa-th fa-fw padding_right_5" ></i></span>



                        <span onclick="open_a4_label_chooser()" class="a4_label button italic">EL30</span>

                    <div class="a4_label_chooser hide" style="width:200px;position: absolute;padding:10px 10px 20px 20px ;background-color:#fff;border:1px solid #ccc;z-index:2000">

                        <i onclick="close_a4_label_chooser()" style="" class="fa fa-window-close button"></i>

                        <input class="a4_label_selected_type" type="hidden"  value="EL30" >

                        <div style="clear:both:margin-top:20px;padding-top:10px">

                        <select class="a4_labels_options small" style="width: 500px;margin-top:40px">
                            {foreach from=$a4_labels_options item=label}
                                <option value="{$label.code}" {if $label.code=='EL30'}selected="selected"{/if} >{$label.description}</option>
                            {/foreach}


                        </select>
                        </div>

                        <script>
                            $('.a4_labels_options').niceSelect();

                            $( ".a4_labels_options" ).on('change',
                                function() {

                                    var value=$( ".a4_labels_options option:selected" ).val();
                                    $('.a4_label_selected_type').val(value)


                                    $('.download_a4_label').data('type','unit_'+value)
                                    $('.a4_label').html(value)


                                    $('.a4_label_chooser').addClass('hide')

                                });
                            function open_a4_label_chooser(){
                                $('.a4_label_chooser').removeClass('hide')
                            }

                            function close_a4_label_chooser(){
                                $('.a4_label_chooser').addClass('hide')
                            }

                            function download_label(){
                                window.open($('.download_a4_label').data('href')+$('.download_a4_label').data('type'), '_blank');
                            }


                        </script>


                    </div>





                </td>



                <td style="text-align: right">
                    <span class="Unit_Weight">{$part->get('Unit Weight')}</span>
                </td>
                <td style="text-align: right">
                <span class="Unit_Dimensions">{$part->get('Unit Dimensions')}</span>
                </td>

            </tr>
            <tr class="sko_data">

                <td>
                    <i class="fal fa-box fa-fw" title="{t}SKO{/t}" ></i>
                </td>
                <td style="padding-left: 4px">
                    <span class="discreet" title="{t}Units per SKO{/t}"><i class="fal fa-fwx fa-stop-circle very_discreet" style="font-size: 80%;margin-right: 1px" ></i><i class="fal fa-fws very_discreet fa-times" style="position: relative;top:1px;margin-right: 3px"></i>{$part->get('Units Per Package')}</span>
                </td>

                <td>
                    <a target="_blank" title="{t}Stock keeping unit (Outer) label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=package"><i class="fas fa-barcode-alt fa-fw padding_right_5" ></i></a> <span class="Part_SKO_Barcode" data-label_no_set="{t}Not set{/t}" >{if $part->get('Part SKO Barcode')==''}<span class="discreet italic">{t}Not set{/t}</span>{else}{$part->get('Part SKO Barcode')}{/if}</span>

                </td>

                <td style="text-align: right">
                    <span class="Package_Weight">{$part->get('Package Weight')}</span>
                </td>

                <td style="text-align: right">
                    <span class="Package_Dimensions">{$part->get('Package Dimensions')}</span>
                </td>

            </tr>
            <tr class="carton_data">

                <td>
                    <i class="fal fa-pallet fa-fw" title="{t}Carton{/t}" ></i>
                </td>
                <td style="padding-left: 4px">
                    <span class="discreet" title="{t}Units per carton{/t}"><i class="fal fa-fwx fa-stop-circle very_discreet" style="font-size: 80%;margin-right: 1px" ></i><i class="fal fa-fws very_discreet fa-times" style="position: relative;top:1px;margin-right: 3px"></i>{$part->get('Units Per Carton')}</span>

                </td>
                <td>
                    {if $part->get('Part Number Supplier Parts')==1}<a target="_blank"  title="{t}Carton label{/t}" href="/asset_label.php?object=supplier_part&key={$part->get_main_supplier_part_key()}&type=carton"><i class="far fa-barcode-alt fa-fw padding_right_5" ></i></a>{/if} <span class="Part_Carton_Barcode" data-label_no_set="{t}Not set{/t}" >{if $part->get('Part Carton Barcode')==''}<span class="discreet error italic">{t}Not set{/t}</span>{else}{$part->get('Part Carton Barcode')}{/if}</span>


                </td>
                <td style="text-align: right">
                    <span class="Carton_Weight">{$part->get('Carton Weight')}</span>
                </td>
                <td style="text-align: right">
                    <span class="Carton_CBM">{$part->get('Carton CBM')}</span>
                </td>
            </tr>

        </table>
    </div>

    <div style="clear:both"></div>


</div>

<div class="tooltip_templates">
    <span id="tooltip_part_margin">
        <table>
            <tr><td>{t}Margin{/t}:</td><td class="Part_Margin">{$part->get('Margin')}</td></tr>
            <tr><td>{t}SKO cost{/t}:</td><td class="Cost">{$part->get('Cost')}</td></tr>

        </table>
    </span>
</div>

<script>






    $(document).on('input propertychange', '.min_max', function (evt) {

        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        min_max_changed($(this))
    });

    $(document).on('input propertychange', '.recommended_move', function (evt) {

        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        recommended_move_changed($(this))
    });


    $(document).on('input propertychange', '.stock', function (evt) {

        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        stock_field_changed($(this))
    });

    $(document).on('input propertychange', '#move_stock_qty', function (evt) {

        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        move_qty_changed($(this))
    });


    $(document).on('input propertychange', '#add_location_tr', function (evt) {


        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_add_location_field($(this), delay)
    });

    $('.tooltip').tooltipster();

</script>
