
{foreach from=$categories item=category_data}
       {assign stack_index $category_data.stack_index}
            <div class="category_wrap item_wrap"  item_key="{$category_data.category_key}" stack_index="{$stack_index}" category_stack_index="{$category_data.category_stack_index}"  max_free_slots="{$category_data.data.max_free_slots}" >


                {if $category_data.type=='category'}
                <div id="category_target_div_{$stack_index}"  item_key="{$category_data.category_key}" stack_index="{$category_data.category_stack_index}"    index_key="{$category_data.index_key}" category_key="{$category_data.category_key}"  class="category_block item_showcase category_showcase item_dragabble" style="position:relative"   item_type="{$category_data.item_type}" item_key="{$category_data.category_key}"  draggable="true" ondragend="overview_items_ondragend(event)" ondragstart="overview_items_ondragstart(event)"  ondragover="overview_items_allowDrop(event)"  ondrop="overview_items_drop(event)">


                <div class="item_header_text fr-view"  style="text-align: center">
                    {$category_data.header_text}
                </div>
               <div class="wrap_to_center category_image">
                   
                    <img draggable="false" src="{$category_data.image_src}"  id="item_image_{$category_data.index_key}"  />
                 
                    
                    
                    
                 </div>

               
                </div>
                    
                    
                    
                    <div class="category_block item_overlay hide" >

  <div style="display:flex;padding:0px 5px;position:relative;top:-5px;font-size:90%" >
                    <div class="flex-item" style="background-color:white">{$category_data.category_code}</div>
                    <div class="flex-item" style="background-color:white"> {$category_data.number_products} <i class="fa fa-cube" aria-hidden="true"></i></div>
                </div>

                    <div class="buttons panel_type"  >
                        <div class="flex-item button" type="image"><i class="fa fa-picture-o" aria-hidden="true"></i></div>
                        <div class="flex-item button" type="text"><i class="fa fa-align-center" aria-hidden="true"></i></div>
                        <div class="flex-item button" type="code"><i class="fa fa-code" aria-hidden="true"></i></div>
                        <div class="flex-item button invisible" type="banner"><i class="fa fa-bullhorn" aria-hidden="true"></i></div>
                    </div>



                    <div  class="buttons super_discreet panel_size">
                        <div class="flex-item {if $category_data.data.max_free_slots<1}xhide{/if}" size="1" >1x</div>
                        <div class="flex-item {if $category_data.data.max_free_slots<2}xhide{/if}" size="2">2x</div>
                        <div class="flex-item {if $category_data.data.max_free_slots<3}xhide{/if}" size="3">3x</div>
                        <div class="flex-item {if $category_data.data.max_free_slots<4}xhide{/if}" size="4">4x</div>
                    </div>


 <div  class="buttons  ">
                        <div class="flex-item button make_category_no_public"    ><i class="fa fa-microphone-slash discreet red" aria-hidden="true" title="{t}Make category not public{/t}"></i></div>
                        <div class="flex-item invisible" ></div>
                        <div class="flex-item invisible" ></div>
                         <div class="flex-item invisible" ></div>
                    </div>
                      
                      
                      
              


                </div>
                
                
                {else}
                    {if $category_data.data.type=='text'}
                    <div id="{$category_data.data.id}" style="position:relative" class=" panel  panel_{$category_data.data.size} {$category_data.data.class}">
                        <div  class="edit_toolbar hide" section="panels"  style=" z-index: 200;position:absolute;left:-20px;top:7px;">
                            <i class="fa close_edit_text fa-window-close fa-fw button text" style="margin-bottom:10px" aria-hidden="true"></i><br>
                            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

                        </div>

                        <div class="panel_content fr-view">
                        {$category_data.data.content}
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
                    {elseif $category_data.data.type=='image'}
                        <div id="{$category_data.data.id}" style="position:relative" class=" panel image panel_{$category_data.data.size}">



                            <img  class="panel_image" src="{$category_data.data.image_src}"  title="{$category_data.data.caption}" />

                            <div class="panel_controls hide">
                             <div class="panel_settings buttons hide">




                            <div class="flex-item button" type="update_image" title="{t}Change image{/t}">
                                <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                                    <input type="file" name="image_upload"  panel_key="{$category_data.data.id}" id="file_upload_{$category_data.data.id}" class="input_file input_file_panel " multiple/>
                                    <label for="file_upload_{$category_data.data.id}">
                                        <i class="fa  fa-picture-o fa-fw button" aria-hidden="true"></i>
                                    </label>
                                </form>
                                </div>
                            <div class="flex-item button" type="update_caption"><i class="fa fa-comment caption_icon" aria-hidden="true"></i></div>
                            <div class="flex-item button" type="update_link"><i class="fa fa-link link_url_icon link_icon" aria-hidden="true"></i></div>
                            <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>
                        </div>





                        <div style="position: absolute;color: white;top:60px;left:12px">
                            {if $category_data.data.size=='1x'}
                            220 x 220
                            {elseif $category_data.data.size=='2x'}
                            457 x 320
                            {elseif $category_data.data.size=='3x'}
                            696 x 320
                            {elseif $category_data.data.size=='4x'}
                                934 x {t}any height{/t}
                            {/if}
                        </div>

                             



                            <div class="input_container caption hide column_{$stack_index % 4}  " style="">
                                <input  value="{$category_data.data.caption}" >
                            </div>
                            <div class="input_container link_url hide column_{$stack_index % 4}  " style="">
                                <input  value="{$category_data.data.link}" placeholder="http://">
                            </div>
                            </div>
                        </div>
                    {elseif $category_data.data.type=='code'}
                    <div id="{$category_data.data.id}" code_key="{$category_data.data.key}" style="position:relative;" class=" panel image  panel_{$category_data.data.size}">



                        <iframe class="" src="/panel_code.php?id={$category_data.data.key}"  style="position: absolute; height: 100%;width: 100%;padding:0px;margin:0px "
                                marginwidth="0"
                                marginheight="0"
                                hspace="0"
                                vspace="0"
                                frameborder="0"
                                scrolling="no"

                                sandbox="allow-scripts allow-same-origin" />

                        <textarea  class="hide" id="code_editor_{$category_data.data.key}">{$category_data.data.content}</textarea>

                          
                        <div class="panel_controls hide">
                            <div class="panel_settings buttons hide">




                                <div class="flex-item button" type="update_code"><i class="fa fa-file-code-o code_icon" aria-hidden="true"></i></div>
                                <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>
                            </div>



                            <div class="input_container caption hide column_{$stack_index % 4}  " style="">
                                <input  value="{$category_data.data.caption}" >
                            </div>
                            <div class="input_container link_url hide column_{$stack_index % 4}  " style="">
                                <input  value="{$category_data.data.link}" placeholder="http://">
                            </div>
                        </div>

                    </div>
                    {elseif $category_data.data.type=='page_break'}
                    <div id="{$category_data.data.id}" style="position:relative" class="page_break panel_{$category_data.data.size}">
                        <span class="title" contenteditable="true">
                           {$category_data.data.title}
                       </span>
                        <span class="sub_title" contenteditable="true">

                        </span>






                    </div>
                {/if}

                {/if}

            </div>
{/foreach}
<div class="category_wrap item_dragabble" item_key="0">
<div item_key=0 class="tail_drop_zone button overview_item_droppable" style="height:218px;width: 218px;margin-right:5px;border:1px dashed #ccc;text-align:center; " ondrop="overview_items_drop(event)"  ondragover="overview_items_allowDrop(event)"  >
    <i class="fa fa-plus very_discreet" style="font-size:400%;position:relative;top:75px" aria-hidden="true"></i>
</div>
</div>