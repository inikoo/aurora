{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 14:15:23 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<style>

    .control_panel{

        color:#444
    }

    .button {
        cursor:pointer

    }
    .editables_block {
        border: 1px solid transparent;
    }
.editables_block:hover {
    border: 1px solid yellow;
}


    .input_container{
        position:absolute;top:60px;left:10px;z-index: 100;border:1px solid #ccc;background-color: white;padding:10px 10px 10px 5px

    }





    .input_container input{
        width:400px
    }

    .editing{
        color:yellow;

    }
.add_link{
    opacity:.1
}

    .qlinks:hover .add_link {
        opacity:.5;
        -webkit-transition-duration: 500ms;
        transition-duration:500ms;
    }

    .drag_mode{
        opacity: .7;
    }

    .drag_mode.on{
        opacity: 1;
    }

</style>

<div id="input_container_link" class="input_container link_url hide  " style="">
    <input  value="" placeholder="http://... or webpage code">
</div>


<i id="delete_link" class="fa fa-trash hide editing button" aria-hidden="true" onClick="delete_link()" style="position:absolute" title="{t}Remove item{/t}" ></i>

<div>

<div style="padding:20px;" class="control_panel">
    <span class="hide"><i class="fa fa-toggle-on" aria-hidden="true"></i> {t}Logged in{/t}</span>
    <span class="button drag_mode" onClick="change_drag_mode(this)"><i class="fa fa-hand-rock-o discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Drag mode{/t}</span>


</div>


  <footer class="footer">






      <div class="top_footer empty"></div><!-- end footer top section -->

            <div class="clearfix"></div>

      {foreach from=$footer_data.rows item=row}

          {if $row.type=='main_4'}

      <div class="container sortable_container ">



          {foreach from=$row.columns item=column name=main_4}


              {if $column.type=='address'}

                  <div class="one_fourth  editable_block {if $smarty.foreach.main_4.last}last{/if}" >
                      <ul class="faddress">
                          <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>

                          {foreach from=$column.items item=item }
                              {if $item.type=='logo'}
                                  <li><img src="{$item.src}" alt=" {$item.alt}" /></li>
                              {elseif $item.type=='text'}
                                  <li><i class="fa fa-fw {$item.icon}"></i> <span contenteditable>{$item.text}</span></li>
                              {elseif $item.type=='email'}
                                  <li><i class="fa fa-fw fa-envelope"></i> <span contenteditable>{$item.text}</span></li>
                              {/if}

                          {/foreach}


                       </ul>
                  </div>
              {elseif $column.type=='links'}
                  <div class="one_fourth  editable_block {if $smarty.foreach.main_4.last}last{/if}" >
                      <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                      <div class="qlinks">

                          <h4 class="lmb" contenteditable>{$column.header}</h4>

                          <ul class="links_list">
                              {foreach from=$column.items item=item }
                                  <li class="item"><a href="{$item.url}"><i class="fa fa-fw fa-angle-right link_icon" onClick="update_link(this)"></i> <span ondrop="return false;" contenteditable>{$item.label}<span></span></a></li>

                              {/foreach}

                              <li onClick="add_link(this)"  class="ui-state-disabled add_link"><a href="{$item.url}"><i class="fa fa-fw fa-plus editing link_icon" onClick="update_link(this)"></i> <span class="editing" ondrop="return false;" >{t}Add link{/t}<span></span></a></li>

                          </ul>

                      </div>
                  </div>
              {elseif $column.type=='text'}
                  <div class="one_fourth  editable_block {if $smarty.foreach.main_4.last}last{/if}" >
                      <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                      <div class="siteinfo">

                          <h4 class="lmb" contenteditable>{$column.header}</h4>

                          <div contenteditable>
                          {$column.text}
                          </div>
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
                          <div class="one_half " >
                            {$column.text}
                          </div>
                          {elseif $column.type=='social_links'}
                          <div class="one_half {if $smarty.foreach.copyright_info.last}last{/if}">

                              <ul class="footer_social_links">
                                  {foreach from=$column.items item=item}
                                      <li class="" ><a href="{$item.url}"><i class="fa {$item.icon}"></i></a></li>

                                  {/foreach}
                              </ul>

                          </div>
                      {/if}



                    {/foreach}



                  </div>
              </div>
          {/if}


      {/foreach}




            <div class="clearfix"></div>

        </footer>

    </div>


    <script>

    var current_editing_link_id=false;

    function update_link(element){
        $(element).uniqueId()
        var id= $(element).attr('id')




        if($('#input_container_link').hasClass('hide')   ){
            current_editing_link_id=id

            $('#input_container_link').removeClass('hide').offset({ top:$(element).offset().top-55, left:$(element).offset().left+20  }).find('input').val($(element).closest('a').attr("href"))
            $('#delete_link').removeClass('hide').offset({ top:$(element).offset().top, left:$(element).offset().left-15  }).attr('link_id',id)
            $(element).addClass('editing fa-window-close').next('span').addClass('editing')
        }else{

            console.log(id)

            if(current_editing_link_id==id){
                $('#input_container_link').addClass('hide')
                $('#delete_link').addClass('hide').offset({ top:$(element).offset().top, left:$(element).offset().left-15  })
                $(element).removeClass('editing fa-window-close').next('span').removeClass('editing')
            }else{
                $('#'+current_editing_link_id).removeClass('editing fa-window-close').next('span').removeClass('editing')
                current_editing_link_id=id

                $('#input_container_link').removeClass('hide').offset({ top:$(element).offset().top-55, left:$(element).offset().left+20  }).find('input').val($(element).closest('a').attr("href"))
                $('#delete_link').removeClass('hide').offset({ top:$(element).offset().top, left:$(element).offset().left-15  }).attr('link_id',id)
                $(element).addClass('editing fa-window-close').next('span').addClass('editing')

            }


        }



    }


    function change_drag_mode(element){

        if($(element).hasClass('on')){

            $('.links_list').sortable({
                disabled: true
            });

            $('.faddress').sortable({
                disabled: true
            });

            $('.sortable_container').sortable({
                disabled: true

            });
            $('.dragger').addClass('hide')
            $(element).removeClass('on')
        }else{

            $('.links_list').sortable({
                disabled: false,
                items: "li:not(.ui-state-disabled)",
                connectWith: ".links_list"
            });

            $('.faddress').sortable({
                disabled: false,
                items: "li:not(.ui-state-disabled)",
                connectWith: ".faddress"
            });

            $('.sortable_container').sortable({
                disabled: false,
                update: function( event, ui ) {
                    $(this).children().removeClass('last')
                    $(this).children().last().addClass('last')


                }

            });
            $('.dragger').removeClass('hide')
            $(element).addClass('on')

        }
    }


function add_link(element){

//    var new_data= $('li.item:last', element.).clone();
 //   new_data.appendTo(this);
}

    $(document).on('click', 'a', function (e) {
        if (e.which == 1 && !e.metaKey && !e.shiftKey) {

            return false
        }
    })


</script>