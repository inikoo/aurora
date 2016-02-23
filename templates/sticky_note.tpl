<div id="showcase_sticky_note" class="data_container {if $_object->get('Sticky Note')==''}hide{/if} ">
			<div class="sticky_note_button">
				<i class="fa fa-sticky-note button" onClick="show_sticky_note_edit_dialog()"></i> 
			</div>
			<div  class="sticky_note" ondblClick="show_sticky_note_edit_dialog()"> 
				{$_object->get('Sticky Note')} 
			</div>
		</div>

<div id="edit_sticky_note_dialog"  class="hide textarea_dialog" object="{$object}" key="{$key}" field="{$sticky_note_field}">
<textarea id="sticky_note_value">{$_object->get('Sticky Note')}</textarea><br>


<i id="sticky_note_close_button" class="fa fa-sign-out fa-flip-horizontal fw " onclick="close_sticky_note_dialog()"></i> 
 
<i id="sticky_note_save_button" class="fa fa-cloud save fw" onclick="save_sticky_note()"></i> 
</div>
<script>

</script>
