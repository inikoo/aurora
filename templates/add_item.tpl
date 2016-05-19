<div id="inline_form" style="float:right;" class="hide">
<span id="invalid_msg" class="hide">{t}Invalid value{/t}</span> 

<span id="fields" object='{$data.object}' key='' parent='{$data.parent}' parent_key='{$data.parent_key}' >
<span id="add_item" class=" hide"   object='{$data.object}' key='' parent='{$data.parent}' parent_key='{$data.parent_key}' field='{$data.field_id}' >
<span id="{$data.field_id}_container" class="value " _required="true" field_type='{$data.field_edit}' field='{$data.field_id}'  server_validation=''> 
<i id="{$data.object}_close"  class=" fa fa-search fa-fw"></i>  {$data.field_label} 

<input id="{$data.field_id}" class="{$data.field_edit}_input_field " value="" placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
{if $data.field_edit=='time'}
<input type="hidden" id="{$data.field_id}_date"  value="{$data.date}">
{/if}
</span> 
</span>
</div>
<script>

$('#{$trigger}').on("click", function() {

    toggle_add_item_form('{$trigger}')

});



$("#{$data.field_id}").keypress(function(e) {

    if (!$('#{$data.object}_save').hasClass('fa-cloud')) {
        e.preventDefault(e);
        return;
    }
});


$("#{$data.field_id}").on("input propertychange", function(evt) {

    if (!$('#{$data.object}_save').hasClass('fa-cloud')) {
        evt.preventDefault(ent);
        return;
    }
    var delay = 50;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_field($(this), delay)

});

$("#{$data.object}_save").on("click", function(evt) {
    save_add_item('{$trigger}')
});

$("#add_item_msg").on("click", function(evt) {
   $("#add_item_msg").html('').removeClass('success error')
   
});

function toggle_add_item_form(trigger) {




    var field = $('#add_item').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')

    $('#add_item_msg').html('').removeClass('error success')

    if ($('#icon_' + trigger).hasClass('fa-plus') ) {

        
        

        if ($('#icon_' + trigger).hasClass('fa-link')) {
            var icon = 'fa-link';
        } else {
            var icon = 'fa-plus';
        }

        $('#inline_form').removeClass('hide')

        $('#add_item').removeClass('hide')
        $('#icon_' + trigger).removeClass('fa-plus').removeClass('fa-link').addClass('fa-times')




        $('#icon_' + trigger).attr('icon', icon)



    } else {
        $('#add_item').addClass('hide')
        $('#inline_form').addClass('hide')

        $('#icon_' + trigger).addClass($('#icon_' + trigger).attr('icon')).removeClass('fa-times')

    }

}


</script>