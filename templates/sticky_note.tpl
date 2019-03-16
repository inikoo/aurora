{if isset($_scope)}{assign "scope" $_scope}{else}{assign "scope" "object_sticky_note"}{/if}


<div  class="sticky_note_container {$scope} {if $value==''}hide{/if}"  data-scope="{$scope}"  data-object="{$object}" data-key="{$key}"  data-field="{$field}"    >
    <i style="top:10px" class="fa fa-cog button hide fa-fw" aria-hidden="true"></i>
    <i style="top:30px" class="fal fa-trash-alt delete_sticky_note button fa-fw" aria-hidden="true"></i>
    <div class="sticky_note" contenteditable="true" >{$value}</div>
</div>

<script>
    var save_sticky_note_timer_{$scope}=false
    $('.sticky_note_container.{$scope}').on('blur keyup paste copy cut mouseup', '[contenteditable]', function () {
        if(save_sticky_note_timer_{$scope})
            clearTimeout(save_sticky_note_timer_{$scope});
        save_sticky_note_timer_{$scope} = setTimeout(function(){
            save_sticky_note('{{$scope}}')
        }, 400);
    })
</script>

