 {foreach from=$products item=product_data}
     {assign stack_index $product_data.stack_index}

            <div class="product_wrap item_wrap"  stack_index="{$stack_index}" max_free_slots="{$product_data.data.max_free_slots}" >


                {if $product_data.type=='product'}
                    {assign 'product' $product_data.object}
                    <div id="product_target_div_{$stack_index}" stack_index="{$product_data.product_stack_index}" draggable="{if $product->get('Web State')=='For Sale' }true{else}false{/if}" ondragstart="product_drag(event)" product_code="{$product->get('Code')}"  index_key="{$product_data.index_key}" product_id="{$product->id}" ondrop="product_drop(event)" ondragover="product_allowDrop(event)" class="product_block product_showcase " style="position:relative">

                <div class="product_header_text fr-view" >
                    {$product_data.header_text}
                </div>




               <div class="wrap_to_center product_image" onCLick="console.log('move')">

                   <i class="fa fa-info-circle more_info" aria-hidden="true"></i>



                    <img draggable="false" src="{$product->get('Image')}" />
                 </div>


                <div class="product_description"  >
                    <span class="code">{$product->get('Code')}</span>
                    <div class="name item_name">{$product->get('Name')}</div>

                </div>


                <div class="product_prices log_in " >
                    <div class="product_price">{t}Price{/t}: {$product->get('Price')}</div>
                    {assign 'rrp' $product->get('RRP')}
                    {if $rrp!=''}<div>{t}RRP{/t}: {$rrp}</div>{/if}
                </div>

                <div class="product_prices log_out hide" >
                    <div >{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</div>
                 </div>


                {if $product->get('Web State')=='Out of Stock'}
                    <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">

                        <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                        <span class="product_footer reminder"><i class="fa fa-envelope-o" aria-hidden="true"></i>  </span>


                    </div>
                {else if $product->get('Web State')=='For Sale'}

                <div class="ordering log_in " >
                    <input maxlength=6  class='order_input ' id='but_qty{$product->id}'   type="text" size='2'  value='{$product->get('Ordered Quantity')}' ovalue='{$product->get('Ordered Quantity')}'>
                     <span class="product_footer order_button"   ><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> {t}Order now{/t}</span>
                     <span class="product_footer  favorite "><i class="fa fa-heart-o" aria-hidden="true"></i>  </span>


                </div>



                {/if}
                <div class="ordering log_out hide" >

                    <div ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                    <div ><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


            </div>


            </div>
                    <div class="product_block item_overlay hide" >



                    <div class="buttons panel_type"  >
                        <div class="flex-item button" type="image"><i class="fa fa-picture-o" aria-hidden="true"></i></div>
                        <div class="flex-item button" type="text"><i class="fa fa-align-center" aria-hidden="true"></i></div>
                        <div class="flex-item button " type="code"><i class="fa fa-code" aria-hidden="true"></i></div>
                        <div class="flex-item button invisible" type="banner"><i class="fa fa-bullhorn" aria-hidden="true"></i></div>
                    </div>



                    <div  class="buttons super_discreet panel_size">
                        <div class="flex-item {if $product_data.data.max_free_slots<1}hide{/if}" size="1" >1x</div>
                        <div class="flex-item {if $product_data.data.max_free_slots<2}hide{/if}" size="2">2x</div>
                        <div class="flex-item {if $product_data.data.max_free_slots<3}hide{/if}" size="3">3x</div>
                        <div class="flex-item {if $product_data.data.max_free_slots<4}hide{/if}" size="4">4x</div>
                    </div>




                </div>
                {else}

                {if $product_data.data.type=='text'}
                    <div id="{$product_data.data.id}" style="position:relative" class=" panel  panel_{$product_data.data.size} {$product_data.data.class}">
                        <div  class="edit_toolbar hide" section="panels"  style=" z-index: 200;position:absolute;left:-20px;top:7px;">
                            <i class="fa close_edit_text fa-window-close fa-fw button text" style="margin-bottom:10px" aria-hidden="true"></i><br>
                            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

                        </div>

                        <div class="panel_content fr-view">
                        {$product_data.data.content}
                       </div>




                        <div class="panel_controls hide">
                        <div class="panel_settings buttons hide" >


                            <div class="flex-item button" type="update_text"><i class="fa fa-pencil edit_text_icon" aria-hidden="true"></i></div>
                            <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>

                            <div class="flex-item button invisible" type="update_class"><i class="fa fa-css3  class_icon" aria-hidden="true"></i></div>
                            <div class="flex-item button invisible" ><i class="fa" aria-hidden="true"></i></div>

                        </div>
                        </div>

                    </div>



                    {elseif $product_data.data.type=='image'}
                        <div id="{$product_data.data.id}" style="position:relative" class=" panel image panel_{$product_data.data.size}">



                            <img  class="panel_image" src="{$product_data.data.image_src}"  title="{$product_data.data.caption}" />

                            <div class="panel_controls hide">
                             <div class="panel_settings buttons hide">




                            <div class="flex-item button" type="update_image" title="{t}Change image{/t}">
                                <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                                    <input type="file" name="image_upload"  panel_key="{$product_data.data.id}" id="file_upload_{$product_data.data.id}" class="input_file input_file_panel " multiple/>
                                    <label for="file_upload_{$product_data.data.id}">
                                        <i class="fa  fa-picture-o fa-fw button" aria-hidden="true"></i><br>
                                    </label>
                                </form>
                                </div>
                            <div class="flex-item button" type="update_caption"><i class="fa fa-comment caption_icon" aria-hidden="true"></i></div>
                            <div class="flex-item button" type="update_link"><i class="fa fa-link link_url_icon link_icon" aria-hidden="true"></i></div>
                            <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>
                        </div>

                        <div style="position: absolute;color: white;top:60px;left:12px">
                            {if $product_data.data.size=='1x'}
                            220 x 320
                            {elseif $product_data.data.size=='2x'}
                            457 x 320
                            {elseif $product_data.data.size=='3x'}
                            696 x 320
                            {elseif $product_data.data.size=='4x'}
                                934 x {t}any height{/t}
                            {/if}
                        </div>

                            <div class="input_container caption hide column_{$stack_index % 4}  " style="">
                                <input  value="{$product_data.data.caption}" >
                            </div>
                            <div class="input_container link_url hide column_{$stack_index % 4}  " style="">
                                <input  value="{$product_data.data.link}" placeholder="http://">
                            </div>
                            </div>
                        </div>


                {elseif $product_data.data.type=='code'}
                    <div id="{$product_data.data.id}" code_key="{$product_data.data.key}" style="position:relative;" class=" panel image panel_{$product_data.data.size}">


                        <div  class="edit_toolbar hide" section="panels"  style=" z-index: 200;position:absolute;left:-20px;top:7px;">
                            <i class="fa close_edit_text fa-window-close fa-fw button code" style="margin-bottom:10px" aria-hidden="true"></i><br>
                            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

                        </div>

                        <iframe class="" src="/panel_code.php?id={$product_data.data.key}"


                                style="position: absolute; height: 100%;width: 100%;padding:0px;margin:0px; "
                                marginwidth="0"
                                marginheight="0"
                                hspace="0"
                                vspace="0"
                                frameborder="0"
                                scrolling="no"



                                sandbox="allow-scripts allow-same-origin" />

                        <div class="code_editor_container hide">
                        <textarea  id="code_editor_{$product_data.data.key}"  style="width:100%;height: 100%">{$product_data.data.content}</textarea>

                            </div>
                        <div class="panel_controls hide">
                            <div class="panel_settings buttons hide">




                                <div class="flex-item button" type="update_code"><i class="fa fa-file-code-o code_icon" aria-hidden="true"></i></div>
                                <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>
                            </div>



                            <div class="input_container caption hide column_{$stack_index % 4}  " style="">
                                <input  value="{$product_data.data.caption}" >
                            </div>
                            <div class="input_container link_url hide column_{$stack_index % 4}  " style="">
                                <input  value="{$product_data.data.link}" placeholder="http://">
                            </div>
                        </div>

                    </div>
                {/if}

                {/if}

            </div>


 {/foreach}