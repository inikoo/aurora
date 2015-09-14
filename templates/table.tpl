 
<div class="table_info">
	
	
		<div  id="last_page" onclick="rows.getLastPage()"  class="square_button right" title="{{t}}Last page{{/t}}" style="position:relative">
		<i class="fa fa-chevron-right fa-fw" style="position:absolute;left:2px;bottom:6px" ></i> <i style="position:absolute;left:9px;bottom:6px" class="fa fa-chevron-right fa-fw"></i> 
	</div>
	<div id="next_page" onclick="rows.getNextPage()"  class="square_button right" title="{{t}}Next page{{/t}}">
		<i class="fa fa-chevron-right fa-fw"></i> 
	</div>
	<div id="paginator" style="float:right;padding-left:10px;padding-right:10px;">
	</div>
	<div id="results_per_page" onclick="show_results_per_page()" class="square_button right" title="{{t}}Results per page{{/t}} ({{$results_per_page}})">
		<i class="fa fa-reorder fa-fw"></i> 
	</div>
	{{foreach from=$results_per_page_options item=results_per_page_option}} 
	<div id="results_per_page_{{$results_per_page_option}}" onclick="change_results_per_page({{$results_per_page_option}})"  class="square_button right hide results_per_page {{if $results_per_page==$results_per_page_option}}selected{{/if}}">
		{{$results_per_page_option}}
	</div>
	{{/foreach}} 
	<div id="prev_page" onclick="if(rows.state.currentPage==1)return;rows.getPreviousPage()" class="square_button right disabled" title="{{t}}Previous page{{/t}}">
		<i class="fa fa-chevron-left   fa-fw"></i> 
	</div>
	<div id="first_page" onclick="rows.getFirstPage()" class="square_button right hide" title="{{t}}First page{{/t}}" style="position:relative">
		<i class="fa fa-chevron-left fa-fw" style="position:absolute;left:2px;bottom:6px" ></i> <i style="position:absolute;left:9px;bottom:6px" class="fa fa-chevron-left fa-fw"></i> 
	</div>
	<span id="rtext"></span> 
</div>
<div class="table" id="table">
</div>
<script>

var columns = {{include file="`$columns_file`.tpl" }};


var Row = Backbone.Model.extend({});

var Rows = Backbone.PageableCollection.extend({
    model: Row,
    url: '{{$request}}',
    state: {
    pageSize: {{$results_per_page}},
    sortKey: '{{$sortKey}}',
    order: 1
  },queryParams: {
    totalRecords: null,
    pageSize: "nr",
    sortKey: "o",
    order : "od"
  },
  parseState: function (resp, queryParams, state, options) {
$('#rtext').html(resp.resultset.rtext)

var total_pages=Math.ceil(resp.resultset.total_records/state.pageSize)

    $('#paginator').html(rows.state.currentPage+'/'+total_pages) 

$('#first_page').removeClass('disabled')
$('#first_page').removeClass('hide')
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


    return {totalRecords: parseInt(resp.resultset.total_records)};
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


$("#table").append(grid.render().el);

rows.fetch({
    reset: true
});



</script> 