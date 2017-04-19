{foreach from=$categories item=category_data}


    {if $category_data.type=='category'}


    <div class="item_dragabble" style="height:70px;width: 70px;margin-right:5px;text-align: center;position:relative;display: table-cell;vertical-align: bottom;margin-bottom:20px;xborder:1px solid red "  item_type="{$category_data.item_type}" item_key="{$category_data.category_key}"  draggable="true"  ondragend="overview_items_ondragend(event)" ondragstart="overview_items_ondragstart(event)"  ondragover="overview_items_allowDrop(event)"  ondrop="overview_items_drop(event)" >
        <img draggable="false"  class="overview_item_dragabble" style="max-height:70px;max-width: 70px;vertical-align: bottom;xborder:1px solid red" src="{$category_data.image_src}"   >
        <i title="{t}Guest category{/t}" class="fa fa-circle {if $category_data.item_type!='Guest'}hide{/if}" style="font-size:70%;position:absolute;bottom:-10px;color:deeppink;left:30px;" aria-hidden="true"></i>
    </div>
    {/if}
{/foreach}
<div class="item_dragabble" item_key="0">
<div item_key=0 class="tail_drop_zone button overview_item_droppable" style="height:70px;width: 70px;margin-right:5px;border:1px dashed #ccc;text-align:center; " ondrop="overview_items_drop(event)"  ondragover="overview_items_allowDrop(event)"  >
    <i class="fa fa-plus" style="position:relative;top:27.5px" aria-hidden="true"></i>
</div>
</div>