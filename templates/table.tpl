{if isset($period)  and   !isset($hide_period) }
 {include file="utils/date_chooser.tpl" period=$period from=$from to=$to from_mmddyy=$from_mmddyy  to_mmddyy=$to_mmddyy from_locale=$from_locale  to_locale=$to_locale  } 
{/if}
{if isset($elements) and count(elements)>0}


<div id="elements" class="elements tabs ">
<div  id="element_type" onClick="show_elements_types()"><i id="element_type_select_icon"  class="fa fa-bars" "></i></div>
{foreach from=$elements item=element_group key=_elements_type} 
<div id="elements_group_{$_elements_type}" elements_type="$_elements_type" class="elements_group {if $_elements_type!=$elements_type}hide{/if}" >
{foreach from=$element_group['items']|@array_reverse item=element key=id} 
<div id="element_{$id}" item_key="{$id}"  class="element right  {if isset($element.selected) and $element.selected}selected{/if}"  onclick="change_table_element(event,'{$id}')" title="{$elements[$elements_type]['label']}: {if isset($element.title)}{$element.title}{else}{$element.label}{/if}">
		<i  id="element_checkbox_{$id}" class="fa {if $element.selected}fa-check-square-o{else}fa-square-o{/if}"></i>	 <span class="label"> {$element.label}</span>  <span  class="qty" id="element_qty_{$id}"></span>
		</div>
{/foreach}
</div>
{/foreach} 
</div>
{/if} 
<div class="table_info" >
	<div id="last_page" onclick="rows.getLastPage()" class="square_button right hide" title="{t}Last page{/t}" style="position:relative">
		<i class="fa fa-chevron-right fa-fw" style="position:absolute;left:2px;bottom:6px"></i> <i style="position:absolute;left:9px;bottom:6px" class="fa fa-chevron-right fa-fw"></i> 
	</div>
	<div id="next_page" onclick="rows.getNextPage()" class="square_button right hide" title="{t}Next page{/t}">
		<i class="fa fa-chevron-right fa-fw"></i> 
	</div>
	<div id="paginator" style="float:right;padding-left:10px;padding-right:10px;">
	</div>
	<div id="results_per_page" onclick="show_results_per_page()" class="square_button right hide" title="{t}Results per page{/t} ({$results_per_page})">
		<i class="fa fa-reorder fa-fw"></i>
	</div>
	{foreach from=$results_per_page_options item=results_per_page_option} 
	<div id="results_per_page_{$results_per_page_option}" onclick="change_results_per_page({$results_per_page_option})" class="square_button right hide results_per_page {if $results_per_page==$results_per_page_option}selected{/if}">
		{$results_per_page_option} 
	</div>
	{/foreach} 
	<div id="prev_page" onclick="if(rows.state.currentPage==1)return;rows.getPreviousPage()" class="square_button right disabled hide" title="{t}Previous page{/t}">
		<i class="fa fa-chevron-left   fa-fw"></i> 
	</div>
	<div id="first_page" onclick="rows.getFirstPage()" class="square_button right hide" title="{t}First page{/t}" style="position:relative">
		<i class="fa fa-chevron-left fa-fw" style="position:absolute;left:2px;bottom:6px"></i> <i style="position:absolute;left:9px;bottom:6px" class="fa fa-chevron-left fa-fw"></i> 
	</div>
	<div  id="show_export_dialog"  class=" square_button right {if !isset($export_fields)}hide{/if}  " title="{t}Export{/t}" >
		 <i onclick="show_export_dialog()" class="fa fa-download fa-fw"></i> 
		
	</div>
	<div id="export_dialog_container" style="position:relative;float:right" class="  ">
	<span class="hide" id="export_queued_msg"><i class="fa background fa-spinner fa-spin"></i> {t}Queued{/t}</span>
	<div id="export_dialog" class="export_dialog hide">
        <table border=0 style="width:100%">
	        <tr class="no_border"> 
                <td class="export_progress_bar_container" ><a href=""  id="download_excel" download hidden ></a> 
                 <span class="hide export_progress_bar_bg" id="export_progress_bar_bg_excel"></span>
                 <div class="hide export_progress_bar" id="export_progress_bar_excel"  ></div>
                 <div class="export_download hide" id="export_download_excel"  > {t}Download{/t}</div>
                </td>	   
                <td class="width_20" ><i id="stop_export_table_excel" stop=0 onclick="stop_export('excel')" class="fa button fa-hand-stop-o error hide" title="{t}Stop{/t}"></td>
                <td id="export_table_excel"  class="link" onclick="export_table('excel')"><i class="fa fa-file-excel-o" title="Excel"></i>Excel</td></tr>
	        <tr>
	            <td class="export_progress_bar_container"><a href=""  id="download_csv" download hidden ></a> 
                    <span class="hide export_progress_bar_bg" id="export_progress_bar_bg_csv"></span>
                 <div class="hide export_progress_bar" id="export_progress_bar_csv"  ></div>
                 <div class="export_download hide " id="export_download_csv"  > {t}Download{/t}</div>

	            </td>
	            <td class="width_20" ><i  id="stop_export_table_csv" onclick="stop_export('csv')" class="fa button fa-hand-stop-o error hide" title="{t}Stop{/t}"></td>
                <td id="export_table_csv" class="link" onclick="export_table('csv')"><i class="fa fa-table" title="{t}Comma Separated Value{/t}"></i>CSV</td></tr>
	        <tr>
	            <td colspan="2"class=""><i onclick="open_export_config()" class="fa fa-cogs button"></i></td><td><div onclick="hide_export_dialog()" class="button disabled"  ><i class="fa fa-times" title="{t}Close dialog{/t}"></i>{t}Close{/t}</div></td>
	        </tr>
	   </table>
	
      </div>
       <div id="export_dialog_config" class="export_dialog hide" >
         {if isset($export_fields)}
       <table >
     
       {foreach from=$export_fields item=export_field key=_key} 
       <tr class="small_row">
       <td>{$export_field.label}</td>
       <td style="width_20" class="field_export" >
            <i id="field_export_{$_key}"  onclick="toggle_export_field({$_key})" key="{$_key}"  class="button fa {if $export_field.checked }fa-check-square-o{else}fa-square-o{/if}"></i>
        </td>
        </tr>
       {/foreach}
       
       </table>
       {/if}
	      </div>
	</div>
	

	
	<div id="filter_container" class="hide">
	<div id="show_filter" onclick="show_filter()" class="square_button right " title="{t}Filter table{/t}" >
		 <i class="fa fa-filter fa-fw"></i> 
	</div>
	<div id="filter_submit" onclick="$('#filter form.backgrid-filter').submit()" class="square_button right filter hide" title="{t}Apply filter{/t}" >
		 <i class="fa fa-filter fa-fw"></i> 
	</div>
	<div id="filter" class="filter hide">
	
	</div>
	<div id="filter_field" class="filter hide">
	{$f_label}:
	</div>
	</div>
	<div id="table_buttons">
	{if (isset($table_buttons) and count(table_buttons)>0)  }
	
	{foreach from=$table_buttons item=button } 
	
	 {if isset($button.inline_new_object)} 
	  {include file="inline_new_object.tpl" data=$button.inline_new_object trigger={$button.id}} 
	 {/if} 
	
	<div  {if isset($button.id) and $button.id }id="{$button.id}"{/if}  class="square_button right "       {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"{/if} {if isset($button.title)}title="{$button.title}"{/if}>
	 <i {if isset($button.id) and $button.id }id="icon_{$button.id}"{/if} class="fa fa-{$button.icon} fa-fw"></i> 
	</div>
	
	 {if isset($button.inline_new_object)} 
	<span id="inline_new_object_msg" class="invalid"></span>
		 {/if} 	
	{/foreach}
	
	
	 
	{/if}
	
	{if isset($upload_file)}
	
	<div   class="square_button right " >
		<form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate  >

	<input type="file" name="image_upload" id="file_upload" class="inputfile" multiple/>
	 <label for="file_upload"><i  class="fa fa-upload fa-fw button"></i><label>
	</form>
	</div>
	<span id="file_upload_msg" style="float:right;padding-right:10px"></span>
	<script>
	var droppedFiles = false;

	$('#file_upload').on('change', function(e) {
	    upload_file()
	});

	function upload_file() {
    var ajaxData = new FormData();

    //var ajaxData = new FormData( );
    if (droppedFiles) {
        $.each(droppedFiles, function(i, file) {
            ajaxData.append('files', file);
        });
    }




    $.each($('#file_upload').prop("files"), function(i, file) {
        ajaxData.append("files[" + i + "]", file);

    });



    ajaxData.append("tipo", '{$upload_file.tipo}')
    ajaxData.append("scope", '{$upload_file.scope}')
    ajaxData.append("scope_key", '{$upload_file.scope_key}')

    $.ajax({
        url: "/ar_edit.php",
        type: 'POST',
        data: ajaxData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,






        complete: function() {

        },
        success: function(data) {



            if (data.state == '200') {

                if (data.tipo == 'upload_images') {

                    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
                    rows.fetch({
                        reset: true
                    });

                    if (data.number_images == 0) {

                        $('div.main_image').addClass('hide')
                        $('form.main_image').removeClass('hide')
                        $('div.main_image img').attr('src', '/art/nopic.png')


                    } else {
                        $('div.main_image').removeClass('hide')
                        $('form.main_image').addClass('hide')
                        $('div.main_image img').attr('src', '/image_root.php?id=' + data.main_image_key + '&size=small')




                    }

                }
                else if(data.tipo == 'upload_objects'){
                    change_view(state.request+'/import/'+data.import_key);
                }

            } else if (data.state == '400') {
                $('#file_upload_msg').html(data.msg).addClass('error')
            }



        },
        error: function() {

        }
    });
    }



	</script>
	{/if}
	
	 </div>
	<span id="rtext"></span> 
</div>
{if isset($table_views) and count($table_views)>1}
<div class="table_views tabs ">
{foreach from=$table_views item=view key=id} 
<div id="view_{$id}" class="view tab left {if isset($view.selected) and $view.selected}selected{/if}"  onclick="change_table_view('{$id}',true)" title="{if isset($view.title)}{$view.title}{else}{$view.label}{/if}">
			{if isset($view.icon) and $view.icon!=''}<i class="fa fa-{$view.icon}"></i>{/if} <span class="label"> {$view.label}</span> 
		</div>
{/foreach} 
</div>
{/if}
<div class="table" id="table">
</div>



<script>
{if isset($title)}
$('#nav_title').html("{$title}")
{/if}

var HtmlCell = Backgrid.HtmlCell = Backgrid.Cell.extend({

    /** @property */
    className: "html-cell",
    
    initialize: function () {
        Backgrid.Cell.prototype.initialize.apply(this, arguments);
    },

    render: function () {
        this.$el.empty();
        var rawValue = this.model.get(this.column.get("name"));
        var formattedValue = this.formatter.fromRaw(rawValue, this.model);
        this.$el.append(formattedValue);
        this.delegateEvents();
        return this;
    }
});
var RhtmlCell = Backgrid.RhtmlCell = Backgrid.Cell.extend({

    /** @property */
    className: "html-cell aright",
    
    initialize: function () {
        Backgrid.Cell.prototype.initialize.apply(this, arguments);
    },

    render: function () {
        this.$el.empty();
        var rawValue = this.model.get(this.column.get("name"));
        var formattedValue = this.formatter.fromRaw(rawValue, this.model);
        this.$el.append(formattedValue);
        this.delegateEvents();
        return this;
    }
});



var integerHeaderCell= Backgrid.HeaderCell.extend({
 className: "align-right",
 
 
 
 render: function () {
 
      this.constructor.__super__.render.apply(this, arguments);
         this.$el.addClass('align-right');
      return this;
    }
}
);

console.log('{$tab}')

{include file="columns/`$tab`.cols.tpl" }


var Row = Backbone.Model.extend({
});

var Rows = Backbone.PageableCollection.extend({
    model: Row,
    url: '{$request}',
    ar_file: '{$ar_file}',
    tipo: '{$tipo}',
    parameters: '{$parameters}',
    tab: '{$tab}',
    state: {
    pageSize: {$results_per_page},
    sortKey: '{$sort_key}',
    order: parseInt({$sort_order})
  }
  ,queryParams: {
    totalRecords: null,
    pageSize: "nr",
    sortKey: "o",
    order : "od"
  },
  
  
  
  
  parseState: function(resp, queryParams, state, options) {
    $('#rtext').html(resp.resultset.rtext)

    var total_pages = Math.ceil(resp.resultset.total_records / state.pageSize)

    if (resp.resultset.total_records > 20) {
        $('#results_per_page').removeClass('hide')
    }
 
  if (total_pages > 1 && '{$f_label}'!='') {
    $('#filter_container').removeClass('hide')
  }
    
 if (total_pages == 1) {
        $('#paginator').html('')
        $('#first_page').addClass('disabled')
        $('#prev_page').addClass('disabled')
        $('#last_page').addClass('disabled')
        $('#next_page').addClass('disabled')

    }
     else if (total_pages >1) {
     
        $('#first_page').removeClass('hide')
        $('#prev_page').removeClass('hide')
        $('#last_page').removeClass('hide')
        $('#next_page').removeClass('hide')


        $('#paginator').html(rows.state.currentPage + '/' + total_pages)

        $('#first_page').removeClass('disabled')
        $('#prev_page').removeClass('disabled')
        $('#last_page').removeClass('disabled')
        $('#next_page').removeClass('disabled')


        if (rows.state.currentPage == 1) {
            $('#prev_page').addClass('disabled')
            $('#first_page').addClass('hide')

        } else if (rows.state.currentPage == 2) {

            $('#first_page').addClass('disabled')

        } else if (rows.state.currentPage == total_pages) {
            $('#next_page').addClass('disabled')

            $('#last_page').addClass('disabled')

        }

    }

    $('th.ascending').removeClass('ascending')
    $('th.descending').removeClass('descending')

    if (resp.resultset.sort_dir == 'desc') $('th.' + resp.resultset.sort_key).addClass('descending')
    else $('th.' + resp.resultset.sort_key).addClass('ascending')




    return {
        totalRecords: parseInt(resp.resultset.total_records),
        sortKey: resp.resultset.sort_key
    };
},

   parseRecords: function (resp, options) {
   
    return resp.resultset.data;
  }
  
});




var rows = new Rows();
var grid = new Backgrid.Grid({
    columns: columns,
    collection: rows,
});


var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
  collection: rows,
  name: "f_value",
  placeholder: "" 
});


grid.render()
$("#table").append(grid.el);

change_table_view('{$table_view}',false)
 
$("#filter").append(serverSideFilter.render().el);
 
rows.fetch(
{  reset: true}
);


{if isset($elements) and count(elements)>0}
var with_elements=true;
get_elements_numbers('{$tab}',{$parameters|@json_encode})
{else}
var with_elements=false;

{/if}

{if isset($js_code) }
{include file="string:$js_code" } 
{/if}

</script> 

<div id="elements_chooser" class="hide panel" >
{foreach from=$elements item=element_group key=_elements_type} 
<div onClick="change_elements_type('{$_elements_type}')" id="element_group_option_{$_elements_type}" elements_type="$_elements_type" class="{if $_elements_type==$elements_type}selected{/if}" >
<i class="fa fw {if $_elements_type==$elements_type}fa-circle{else}fa-circle-o{/if}"></i> {$element_group['label']}
</div>
{/foreach} 


</div>
{if isset($aux_templates) }
{foreach from=$aux_templates item=aux_template } 
{include file="$aux_template" } 
{/foreach}
{/if}

