{if isset($elements) and count(elements)>0}
<div class="table_views tabs ">
{foreach from=$table_views item=view key=id} 
<div {if isset($view.id) and $view.id }id="{$view.id}"{/if} class="tab left {if isset($view.selected) and $view.selected}selected{/if}"  onclick="change_table_element('{$id}')" title="{$view.title}">
			 <span class="label"> {$view.label}</span>  (<span class="quantity">{$view.quantity}</span> )
		</div>
{/foreach} 
</div>
{/if}
{if isset($period)}
 {include file="utils/date_chooser.tpl" period=$period} 
{/if} 
<div class="table_info">
	<div id="last_page" onclick="rows.getLastPage()" class="square_button right hide" title="{t}Last page{/t}" style="position:relative">
		<i class="fa fa-chevron-right fa-fw" style="position:absolute;left:2px;bottom:6px"></i> <i style="position:absolute;left:9px;bottom:6px" class="fa fa-chevron-right fa-fw"></i> 
	</div>
	<div id="next_page" onclick="rows.getNextPage()" class="square_button right hide" title="{t}Next page{/t}">
		<i class="fa fa-chevron-right fa-fw"></i> 
	</div>
	<div id="paginator" style="float:right;padding-left:10px;padding-right:10px;">
	</div>
	<div id="results_per_page" onclick="show_results_per_page()" class="square_button right" title="{t}Results per page{/t} ({$results_per_page})">
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
	
	<span id="rtext"></span> 
</div>
{if isset($table_views) and count($table_views)>0}
<div class="table_views tabs ">
{foreach from=$table_views item=view key=id} 
<div id="view_{$id}" class="view tab left {if isset($view.selected) and $view.selected}selected{/if}"  onclick="change_table_view('{$id}',true)" title="{$view.title}">
			{if isset($view.icon) and $view.icon!=''}<i class="fa fa-{$view.icon}"></i>{/if} <span class="label"> {$view.label}</span> 
		</div>
{/foreach} 
</div>
{/if}
<div class="table" id="table">
</div>



<script>
{literal}



var integerHeaderCell= Backgrid.HeaderCell.extend({
 className: "align-right",
 
 
 
 render: function () {
 
      this.constructor.__super__.render.apply(this, arguments);
         this.$el.addClass('align-right');
      return this;
    }
});

 {/literal}{include file="columns/`$data.tab`.cols.tpl" }{literal};

var Row = Backbone.Model.extend({});

var Rows = Backbone.PageableCollection.extend({
    model: Row,
    url: '{/literal}{$request}{literal}',
    ar_file: '{/literal}{$ar_file}{literal}',
    tipo: '{/literal}{$tipo}{literal}',
    parameters: '{/literal}{$parameters}{literal}',
    state: {
    pageSize: {/literal}{$results_per_page}{literal},
    sortKey: '{/literal}{$sort_key}{literal}',
    order: parseInt({/literal}{$sort_order}{literal})
  },queryParams: {
    totalRecords: null,
    pageSize: "nr",
    sortKey: "o",
    order : "od"
  },
  
    
  
  
  parseState: function (resp, queryParams, state, options) {
$('#rtext').html(resp.resultset.rtext)

var total_pages=Math.ceil(resp.resultset.total_records/state.pageSize)

 if(total_pages==1){
    $('#paginator').html(rows.state.currentPage+'/'+total_pages) 
$('#first_page').addClass('disabled')
$('#prev_page').addClass('disabled')
$('#last_page').addClass('disabled')
$('#next_page').addClass('disabled')

    }else if(total_pages>=1){
      $('#first_page').removeClass('hide')
      $('#prev_page').removeClass('hide')
      $('#last_page').removeClass('hide')
     $('#next_page').removeClass('hide')


    $('#paginator').html(rows.state.currentPage+'/'+total_pages) 

$('#first_page').removeClass('disabled')
$('#prev_page').removeClass('disabled')
$('#last_page').removeClass('disabled')
$('#next_page').removeClass('disabled')


if(rows.state.currentPage==1){
$('#prev_page').addClass('disabled')
$('#first_page').addClass('hide')

}else if(rows.state.currentPage==2){

$('#first_page').addClass('disabled')

}else if(rows.state.currentPage==total_pages){
$('#next_page').addClass('disabled')

$('#last_page').addClass('disabled')

}

}
    
    $('th.ascending').removeClass('ascending')
    $('th.descending').removeClass('descending')
   
    if(resp.resultset.sort_dir=='desc')
    $('th.'+resp.resultset.sort_key).addClass('descending')
    else
    $('th.'+resp.resultset.sort_key).addClass('ascending')
    
   
   
    
    return {
    totalRecords: parseInt(resp.resultset.total_records),
    sortKey:resp.resultset.sort_key
    };
  },
   parseRecords: function (resp, options) {
   
    return resp.resultset.data;
  }
  
});




var rows = new Rows();


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





var grid = new Backgrid.Grid({


    columns: columns,
    collection: rows,
   
});



var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
  collection: rows,
  // the name of the URL query parameter
  name: "f_value",
  placeholder: "" // HTML5 placeholder for the search box
});


grid.render()
$("#table").append(grid.el);
change_table_view('{/literal}{$table_view}{literal}',false)
 
 $("#filter").append(serverSideFilter.render().el);
 
rows.fetch({  reset: true});


{/literal}

function show_filter(){
    $('#show_filter').addClass('hide')
    $('.filter').removeClass('hide')
     $('#filter input').focus()

}

</script> 