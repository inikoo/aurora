{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 14:15:23 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

  <footer class="footer">

            <div class="top_footer empty"></div><!-- end footer top section -->

            <div class="clearfix"></div>

      {foreach from=$footer_data.rows item=row}

          {if $row.type=='main_4'}

      <div class="container">

          {foreach from=$row.columns item=column name=main_4}

              {if $column.type=='address'}

                  <div class="one_fourth animate" data-anim-type="fadeIn" data-anim-delay="100">
                      <ul class="faddress">


                          {foreach from=$column.items item=item }
                              {if $item.type=='logo'}
                                  <li><img src="{$item.src}" alt=" {$item.alt}" /></li>
                              {elseif $item.type=='text'}
                                  <li><i class="fa fa-fw {$item.icon}"></i> {$item.text}</li>
                              {elseif $item.type=='email'}
                                  <li><a href="mailto:{$item.text}"><i class="fa fa-fw fa-envelope"></i> {$item.text}</a></li>
                              {/if}

                          {/foreach}


                       </ul>
                  </div>
              {elseif $column.type=='links'}
                  <div class="one_fourth animate" data-anim-type="fadeIn" data-anim-delay="300">
                      <div class="qlinks">

                          <h4 class="lmb">{$column.header}</h4>

                          <ul>
                              {foreach from=$column.items item=item }
                                  <li><a href="{$item.url}"><i class="fa fa-fw fa-angle-right"></i> {$item.label}</a></li>

                              {/foreach}


                          </ul>

                      </div>
                  </div>
              {elseif $column.type=='text'}
                  <div class="one_fourth animate {if $smarty.foreach.main_4.last}last{/if}" data-anim-type="fadeIn" data-anim-delay="500">
                      <div class="siteinfo">

                          <h4 class="lmb">{$column.header}</h4>

                          {$column.text}

                      </div>
                  </div>


              {/if}


          {/foreach}

      </div>



          {elseif $row.type=='copyright'}
              <div class="clearfix"></div>




              <div class="copyright_info">
                  <div class="container">

                      <div class="clearfix divider_dashed10"></div>



                      {foreach from=$row.columns item=column name=copyright_info}

                      {if $column.type=='text'}
                          <div class="one_half animate" data-anim-type="fadeInRight">
                            {$column.text}
                          </div>
                      {/if}



                    {/foreach}

                      <div class="one_half {if $smarty.foreach.copyright_info.last}last{/if}">

                          <ul class="footer_social_links">
                              {foreach from=$column.items item=item}
                                  <li class="animate" data-anim-type="zoomIn"><a href="{$item.url}"><i class="fa {$item.icon}"></i></a></li>

                              {/foreach}
                                          </ul>

                      </div>

                  </div>
              </div>
          {/if}


      {/foreach}




            <div class="clearfix"></div>

        </footer>