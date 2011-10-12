function change_edit_pages_view(e,data){

	var table=tables['table'+data.table_id];
	var tipo=this.id;
	
	
	table.hideColumn('store_title');
	table.hideColumn('link_title');
	table.hideColumn('url');
	table.hideColumn('page_title');
	table.hideColumn('page_keywords');
	
	if(tipo=='page_header'){
	    table.showColumn('store_title');
	}else if(tipo=='page_html_head'){
	    table.showColumn('page_title');
	    table.showColumn('page_keywords');

	}else if(tipo=='page_properties'){
	    table.showColumn('link_title');
	    table.showColumn('url');
	}
	
    Dom.removeClass(['page_header','page_html_head','page_properties'],'selected')
    Dom.addClass(this,'selected')
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-edit_pages-view&value=' + escape(tipo),{} );
 
 
 }