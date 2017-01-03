{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2016 at 16:22:33 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div style="padding:20px;border-bottom:1px solid #ccc">

    <span style="margin-right:20px" class="hide">
        <i class="fa fa-desktop padding_right_5 " aria-hidden="true"></i>
        <i class="fa fa-tablet padding_right_5" aria-hidden="true"></i>
        <i class="fa fa-mobile" aria-hidden="true"></i>
    </span>

    <span class="button " onclick="toggle_logged_in_view(this)"><i class="fa fa-toggle-on " aria-hidden="true" alt="{t}On{/t}"></i> <span class="unselectable">{t}Logged in{/t}</span></span>

    <a target="_blank" href="http://{$webpage->get('Page URL')}" ><i class="fa fa-external-link" aria-hidden="true"  style="float:right;margin-left:20px;position:relative;top:2px"></i>   </a>

    <span id="publish" class="button save {if $webpage->get('Publish')}changed valid{/if}" style="float:right" onclick="publish(this)"><span class="unselectable">{t}Publish{/t}</span> <i class="fa fa-rocket" aria-hidden="true"></i></span>


</div>


{assign 'see_also'  $public_product->webpage->get_see_also() }
{assign 'css'  $public_product->webpage->get('CSS') }


{include file="category.webpage.preview.style.tpl" }

<span id="ordering_settings" class="hide" data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Order now{/t}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>


<div id="page_content" style="position:relative">



    <div id="product_bd" style="padding:5px 20px 0px 20px;clear:both;">

            <div class="product" style="display: flex; ">
                <div class="images" style="width:280px">
                    <div style="border:1px solid #ccc;background:#FFF">
                        <div class="wraptocenter">
                            <a href="{$public_product->get('Image')}" class="imgpop"><img src="{$public_product->get('Image')}"></a>
                        </div>
                    </div>
                    <div style="{if $public_product->get_number_images()<1}xdisplay:none{/if}">



                        <ul class="gallery">
                            {foreach from=$public_product->get_images_slidesshow() item=image name=foo}
                                {if $image.subject_order>1  }
                                    <li><a href="{$image.normal_url}" class="imgpop"> <img class="thumbs" src="{$image.small_url}" alt="{$image.name}" /> </a> </li>
                                {/if}
                            {/foreach}
                        </ul>
                    </div>
                </div>
                <div class="information" style="margin-left:40px">
                    <h1 style="padding-top:5px;margin:2px 0;font-size:190%">
                        {$public_product->get('Name')}  <i class="fa fa-heart-o" style="margin-left:20px" aria-hidden="true"></i>
                    </h1>
                    <div class="highlight_box">
                        <div style="float:left;margin-right:4px;min-width:200px">
                            {t}Product code{/t}: <span class="code">{$public_product->get('Code')} </span>
                        </div>

                    </div>


                    <div class="ordering-container  log_in" style="display: flex;margin-top:40px;" >

                    <div class="product_prices log_in " style="margin-left:0px;padding-left:0px;font-size: 120%;width:250px" >
                        <div class="product_price">{t}Price{/t}: {$public_product->get('Price')}</div>
                        {assign 'rrp' $public_product->get('RRP')}
                        {if $rrp!=''}<div style="margin-top:4px">{t}RRP{/t}: {$rrp}</div>{/if}
                    </div>

                    <div style="margin-left:10px;">
                        <div class="ordering log_in " >
                            <input maxlength=6  class='order_input ' id='but_qty{$public_product->id}'   type="text" size='2'  value='{$public_product->get('Ordered Quantity')}' ovalue='{$public_product->get('Ordered Quantity')}'>
                            <span class="product_footer order_button"   ><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> {t}Order now{/t}</span>



                        </div>
                    </div>

                    </div>


                    <div class="product_prices log_out hide" >
                        <div >{t}For prices, please login or register{/t}</div>
                    </div>




                    <div class=" fr-view" style="border:1px dashed #ccc;margin-top:25px;padding:0px">
                       {$public_product->get('Description')} xx
                    </div>
                </div>



            </div>
    </div>

    <section class="product_tabs">

        <input id="tab-one" type="radio" name="grp" checked="checked"/>
        <label for="tab-one">Tab One</label>
        <div>
            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
        </div>

        <input id="tab-two" type="radio" name="grp" />
        <label for="tab-two">Tab Two</label>
        <div>
            <img src="http://sbarthel.github.io/images/img/berg.JPG" alt="mountain" />
        </div>

        <input id="tab-three" type="radio" name="grp" />
        <label for="tab-three">Tab Three</label>
        <div>
            ... no fixed height for tabbed area!
        </div>

    </section>




    <div style="clear:both"></div>

</div>