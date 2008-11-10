<?
    include_once('../common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

var    current_form = 'description';
var    num_changed = 0;
var    num_errors = 0;
var editor;
var editing='<?=$_SESSION['state']['product']['edit']?>';

var is_diferent = function(v1,v2,tipo){

    if(tipo=='money' || tipo=='number'){
	if(parseFloat(v1)!=parseFloat(v2))
	    return true;
	else
	    return false;
    }else{
	if(v1!=v2)
	    return true;
	else
	    return false;
    }
	

}


var change_element= function(o){

       
	var current_class=o.className;
	var tipo=o.getAttribute('tipo');


	if(is_diferent(o.getAttribute("ovalue"),o.value,tipo)){

	    if(current_class==''){
		num_changed++;
		}

	    val = vadilate(o);

	    if(!val){
		if(current_class!='error'){
		    num_errors++;
		}
		o.className='error';
	    }else{
		if(current_class=='error')
		    num_errors--;
		o.className='ok';

	    }

	}else{

	    if(current_class=='ok')
		num_changed--;
	    if(current_class=='error'){
		num_changed--;
		num_errors--;
	    }
	    o.className='';

	}

	if(editing=='suppliers'){
	    interpet_changes(o.getAttribute('supplier_id'));
	}else
	    interpet_changes();
	


    }




function save_form(){
    if(current_form == 'description')
	editor.saveHTML();
    YAHOO.util.Connect.setForm(document.getElementById(current_form)); 

    var request = YAHOO.util.Connect.asyncRequest('POST', 'ar_assets.php', callback);

}

var interpet_changes = function(id){
    
    if(editing=='suppliers'){
	if(num_changed>0 && num_errors==0){
	    Dom.get('save_supplier_'+id).style.display='';
	}else
	    Dom.get('save_supplier_'+id).style.display='none';

	
    }else{
	if(num_changed>0 && num_errors==0){
	    Dom.get('save').className='ok';
	    Dom.get('exit').className='nook';
	    YAHOO.util.Event.addListener('save', "click", save_form);
	}else{
	    YAHOO.util.Event.removeListener('save', "click");
	    Dom.get('save').className='disabled';
	    Dom.get('exit').className='ok';
	    
	}
    }
};
function delete_list_item (e,id){
    
    cat_td=YAHOO.util.Dom.get('cat_'+id);
    saved=cat_td.getAttribute('saved');
    
    if(cat_td.getAttribute('tipo')==1){
	cat_td.style.textDecoration = 'line-through';
	cat_td.style.color = '#777';
	
	
	YAHOO.util.Dom.get('cat_t_'+id).src='art/icons/arrow_rotate_anticlockwise.png';
	if(saved==1)
	    num_changed++;
	else
	    num_changed--;
	cat_td.setAttribute('tipo',0);
	var new_cat= new Array();
	
	var current_cat=Dom.get('v_cat').value;
	      //	      alert(current_cat);
	current_cat=current_cat.split(',');
	
	
	for (x in current_cat){
	    //		  alert(current_cat[x]+' '+id);
		  if(current_cat[x]!=id)
		      new_cat.push(current_cat[x])
	      }

	      Dom.get('v_cat').value=new_cat.join(',');
	      //alert(Dom.get('v_cat').value)
		  
	      

		  }else{

	      cat_td.style.textDecoration = 'none';
	      cat_td.style.color = '#000';
	      YAHOO.util.Dom.get('cat_t_'+id).src='art/icons/cross.png';
	      if(saved==1)
		  num_changed--;
	      else
		  num_changed++;

	      cat_td.setAttribute('tipo',1);

	      var v_cat=new Array();
	      v_cat=Dom.get('v_cat').value;
	      v_cat=v_cat.split(',');
	      v_cat.push(id);
	      Dom.get('v_cat').value=v_cat.join(',');
	      


	      
	  }
	  //	  alert(num_changed);
    interpet_changes();
}








    var check_number = function(e){
	re=<?=$regex['thousand_sep']?>;
	value=this.value.replace(re,'')
	re=<?=$regex['number']?>;
	re_strict=<?=$regex['strict_number']?>;

	if(!re.test(value)){
	    this.className='text aright error';
	}else if(!re_strict.test(this.value)){
	    this.className='text aright warning';
	}else
	    this.className='text aright ok';
    };

    var check_dimension = function(e,scope){
     
     
	if(typeof(scope)=='undefined')
	    scope=this;
     
	 
	tipo=Dom.get(scope.id+'_shape').selectedIndex;

	if(tipo==0){
	    scope.className='text aright error';
	    return
		}else if(tipo==1)
	    re=<?=$regex['dimension3']?>;
	else if(tipo==3 || tipo==5)
	    re=<?=$regex['dimension2']?>;
	else if(tipo==2 || tipo==4)
	    re=<?=$regex['dimension1']?>;


	re_prepare=<?=$regex['thousand_sep']?>;
	value=scope.value.replace(re_prepare,'')



	if(!re.test(value)){
	    scope.className='text aright error';
	}else
	    scope.className='text aright ok';
    }
    var change_shape= function(e){
	tipo=this.selectedIndex;
	shape_examples=new Array(<?='"'.join('","',$_shape_example).'"'?>)
	Dom.get(this.id+'_ex').innerHTML=shape_examples[tipo];
	check_dimension('',Dom.get(this.id.replace(/_shape/,'')))

    }

    var vadilate = function(o){
	if(o.getAttribute('tipo')=='money'){
	    if(isNaN(o.value))
		 return false;
	    if(o.value.match(/[a-z]/))
		return false;

	}else if( o.getAttribute('tipo')=='text_nonull'  ){
	    if(o.value==''){
		return false;
	    }
	}else if(!o.value.match(/[a-z]/))
	    return false;
	
	
	return true;
    }
    var change_textarea=function(e,name){
	//editor.saveHTML(); 
	//html = editor.get('element').value; 
	
	info=Dom.get('i_'+name);
	if(info.style.visibility=='hidden'){
	    info.style.visibility='visible';
	    num_changed++;
	    interpet_changes();
	}

    }




var handleSuccess = function(o){
    //    alert(o.responseText);
    var r =  YAHOO.lang.JSON.parse(o.responseText);
    if (r.state == 200) {
	YAHOO.util.Event.removeListener('save', "click");
	Dom.get('save').className='disabled';
	Dom.get('exit').className='ok';
	for (x in r.res){
	    if(r.res[x]['res']==1){
		
		num_changed--;
		Dom.get('c_'+x).style.visibility='visible';
		Dom.get('c_'+x).style.src='art/icons/accept.png';
		var attributes = {opacity: { to: 0 }};
		YAHOO.util.Dom.setStyle('c_'+x, 'opacity', 1);
		var myAnim = new YAHOO.util.Anim('c_'+x, attributes); 
		myAnim.duration = 10; 
		myAnim.animate(); 
		if(x=='details'){
		    Dom.get('i_'+x).style.visibility='hidden';
		}else{
		    Dom.get('v_'+x).className='';
		    Dom.get('v_'+x).setAttribute("ovalue",r.res[x]['new_value']);
		}

	    }else if(r.res[x]['res']==0){
		Dom.get('v_'.x).className='error';
	    }
		
	}

	interpet_changes();

    }
};

var handleFailure = function(o){

};



var callback =
{

    success:handleSuccess,
    failure:handleFailure,
    argument:['foo','bar']
};




var add_list_element=function(e){
    
    var box=Dom.get(this.getAttribute('box'));
    var selected=box.selectedIndex;
    var name=box.options[selected].text;
    var id=box.options[selected].getAttribute('cat_id');
    
    // disable parents 
    var parents=box.options[selected].getAttribute('parents');
    if(parents!=''){
	var _parents = new Array();
	_parents = parents.split(',');
	Dom.get('cat_o_'+id).setAttribute('disabled','disabled');
	for (x in _parents){
	    
	    Dom.get('cat_o_'+_parents[x]).setAttribute('disabled','disabled');
	}
    }
    //add tr to the cat table
    
    table=Dom.get(box.name+'_list');
    var newRow = table.insertRow(0);
    var newCell = newRow.insertCell(0);
    newCell.innerHTML = '<img  src="art/icons/cross.png"  id="cat_t_'+id+'" cat_id="'+id+'" style="cursor:pointer" />';
    YAHOO.util.Event.addListener(newCell, "click", delete_list_item,id);
    var newCell = newRow.insertCell(0);
    newCell.innerHTML = name;
    newCell.id='cat_'+id;
    newCell.setAttribute('tipo','1');
    newCell.setAttribute('saves','0');
    
    YAHOO.util.Event.removeListener('add_cat');
    num_changed++;
    

    
    var v_cat=new Array();
    v_cat=Dom.get('v_cat').value;
    v_cat=v_cat.split(',');
    v_cat.push(id);
    Dom.get('v_cat').value=v_cat.join(',');
    
    
	    
	    

    interpet_changes();
}
var prepare_list_element=function(e){
    selected=this.selectedIndex;
    prev=this.getAttribute('prev')
    if(!(prev==0 || selected==prev))
	alert(prev+' '+selected)
	    }

var change_list_element=function(e){
	    
    selected=this.selectedIndex;
    if(selected==0){
	
    }else{
	item_name=this.options[selected].getAttribute('iname')
	this.options[selected].text=item_name;
	YAHOO.util.Event.addListener('add_cat', "click", add_list_element);
	
	prev=this.getAttribute('prev')
	if(prev>0)
	    this.options[prev].text=this.options[prev].getAttribute('sname');
	
	

	this.setAttribute('prev',selected)
    }
}
var change_block = function(e){
    

    Dom.get('d_suppliers').style.display='none';
    Dom.get('d_pictures').style.display='none';
    Dom.get('d_suppliers').style.display='none';
    Dom.get('d_prices').style.display='none';
    Dom.get('d_dimat').style.display='none';
    Dom.get('d_description').style.display='none';
    Dom.get('d_'+this.id).style.display='';
    

    
    Dom.get('suppliers').className='';
    Dom.get('pictures').className='';
    Dom.get('suppliers').className='';
    Dom.get('prices').className='';
    Dom.get('dimat').className='';
    Dom.get('description').className='';
    Dom.get(this.id).className='selected';
    
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-edit&value='+this.id );
    
    editing=this.id;


}

function init(){


    //var ids = ["v_description","v_sdescription"]; 
    //	YAHOO.util.Event.addListener(ids, "keyup", change_element);

	var ids = ["cat_select"]; 
	YAHOO.util.Event.addListener(ids, "change", change_list_element);
	var ids = ["description","pictures","prices","suppliers","dimat"]; 
	YAHOO.util.Event.addListener(ids, "click", change_block);
	
	//	YAHOO.util.Event.addListener(ids, "click", prepare_list_element);


	//	var ids = ["v_details"]; 
	//YAHOO.util.Event.addListener(ids, "keyup", change_textarea);




	//Tooltips
	//var myTooltip = new YAHOO.widget.Tooltip("myTooltip", { context:"upo_label,outall_label,awoutall_label,awoutq_label"} ); 


	//Details textarea editor ---------------------------------------------------------------------
	var texteditorConfig = {
	    height: '300px',
	    width: '730px',
	    dompath: true,
	    focusAtStart: true
	};     

 	editor = new YAHOO.widget.Editor('v_details', texteditorConfig);

 	editor._defaultToolbar.buttonType = 'basic';
 	editor.render();
	editor.on('editorKeyUp',change_textarea,'details' );
	//-------------------------------------------------------------
	

	    

}


    YAHOO.util.Event.onDOMReady(init);


var supplier_selected=function(sType, aArgs){
    var myAC = aArgs[0]; // reference back to the AC instance
    var elLI = aArgs[1]; // reference to the selected LI element
    var oData = aArgs[2]; // object literal of selected item's result data

    Dom.get('new_supplier_form').style.display='';

    Dom.setStyle('current_suppliers_form', 'opacity', .25); 

    Dom.get('new_supplier_name').innerHTML=oData.names;
    Dom.get('new_supplier_form').setAttribute('supplier_id',oData.id);
    Dom.get('new_supplier_input').value='';
};

var save_supplier=function(supplier_id){
    var cost=Dom.get('v_supplier_cost'+supplier_id).value;
    var code=Dom.get('v_supplier_code'+supplier_id).value;
    var request='ar_assets.php?tipo=ep_update_supplier&op_tipo=update&supplier_id='+ escape(supplier_id)+'&cost='+ escape(cost)+'&code='+ escape(code);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    //retutn to normal classes
		    if(Dom.get('v_supplier_cost'+supplier_id).className=='ok'){
			Dom.get('v_supplier_cost'+supplier_id).className='';
			num_changed--;
		    }
		    if(Dom.get('v_supplier_code'+supplier_id).className=='ok'){
			Dom.get('v_supplier_code'+supplier_id).className='';
			num_changed--;
		    }

		    
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};



var save_new_supplier=function(){
    var cost=Dom.get('new_supplier_cost').value;
    var code=Dom.get('new_supplier_code').value;
    var supplier_id=Dom.get('new_supplier_form').getAttribute('supplier_id');
    var request='ar_assets.php?tipo=ep_update_supplier&op_tipo=new&supplier_id='+ escape(supplier_id)+'&cost='+ escape(cost)+'&code='+ escape(code);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);

		if (r.state == 200) {


		//     var row = Dom.get('location_table').insertRow(r.where);
// 		    row.setAttribute("id", 'row_'+r.id );
// 		    row.setAttribute("pl_id", r.pl_id );
// 		    var cellLeft = row.insertCell(0);
// 		    cellLeft.setAttribute("id", 'loc_name'+r.id );
// 		    var cellLeft = row.insertCell(1);
// 		    cellLeft.setAttribute("id", 'loc_tipo'+r.id );
// 		    var cellLeft = row.insertCell(2);
// 		    var span = document.createElement("span");
// 		    span.innerHTML=' &uarr; ';
// 		    span.setAttribute("onclick", "rank_up("+r.id+")" );
// 		    span.setAttribute("style", "cursor:pointer" );
// 		    span.setAttribute("id", 'loc_picking_up'+r.id );
// 		    cellLeft.appendChild(span);
// 		    var span = document.createElement("span");
// 		    span.setAttribute("id", 'loc_picking_tipo'+r.id );
// 		    cellLeft.appendChild(span);
// 		    cellLeft.style.textAlign='right';
// 		    cellLeft.setAttribute("id", 'loc_pick_info'+r.id );
// 		    var img = document.createElement("img");
// 		    img.setAttribute("src", "art/icons/basket.png" );
// 		    img.setAttribute("style", "cursor:pointer" );
// 		    img.setAttribute("title", "" );
// 		    img.setAttribute("id", 'loc_picking_img'+r.id );
// 		    //img.setAttribute("onclick", "desassociate_loc()" );
// 		     cellLeft.appendChild(img);


// 		     var cellLeft = row.insertCell(3);
// 		     cellLeft.setAttribute("id", 'loc_stock'+r.id );
// 		     cellLeft.style.textAlign='right';
// 		     var cellLeft = row.insertCell(4);
// 		     var img = document.createElement("img");
// 		     img.setAttribute("src", "art/icons/cross.png" );
// 		     img.setAttribute("style", "cursor:pointer" );
// 		     img.setAttribute("title", "<?=_('Free the location')?>" );
// 		     img.setAttribute("id", 'loc_del'+r.id );
// 		     img.setAttribute("onclick", "desassociate_loc("+r.id+")" );
		     
// 		     cellLeft.appendChild(img);
// 		     location_data=r.data;
// 		     clear_actions();
		    
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};
var cancel_new_supplier=function(){
    Dom.setStyle('current_suppliers_form', 'opacity', 1.0); 
    Dom.get('new_supplier_form').style.display='none';
    Dom.get('new_supplier_name').innerHTML='';
    Dom.get('new_supplier_form').setAttribute('supplier_id','');


}	


YAHOO.util.Event.onContentReady("adding_new_supplier", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_suppliers.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name","code","id","names"]
 	};

 	var oAC = new YAHOO.widget.AutoComplete("new_supplier_input", "new_supplier_container", oDS);
 	oAC.resultTypeList = false; 
	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=suppliers_name&except_product=<?=$_SESSION['state']['product']['id']?>&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(supplier_selected); 
    });







// function init(){


//     var Event = YAHOO.util.Event;
//     var Dom   = YAHOO.util.Dom;
    

//     function mygetTerms(query) {
// 	var Dom = YAHOO.util.Dom
// 	    var table=YAHOO.product.XHR_JSON.ProductsDataTable;
// 	var data=table.getDataSource();
// 	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

// 	//	alert(newrequest);
// 	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
//     };
//     <?if($LU->checkRight(ORDER_VIEW)){?>

// 	var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
// 	oACDS.queryMatchContains = true;
// 	var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
// 	oAutoComp.minQueryLength = 0; 
// 	<?}?>

    



    


// 	  var handleSubmit = function() {
// 	      this.submit();
// 	  };




// 	  var handleCancel = function() {
// 	      this.cancel();
// 	  };
// 	  var handleSuccess = function(o) {
// 	      //	    alert(o.responseText);
// 	      var response = YAHOO.lang.JSON.parse(o.responseText);

// 	      document.location.reload();

// 	      // 	     if(response.state==200){
// 	      // 		 YAHOO.product.XHR_JSON.ProductsDataTable.addRow(response.data,0);
// 	      // 		 YAHOO.product.dialog1.hide();
// 	      // 	     }else{
// 	      // 		 alert(response.resp);
// 	      // 	     }
	    
// 	  };
// 	  var handleSuccess_stock = function(o) {
// 	      var Dom = YAHOO.util.Dom;
// 	      var response = YAHOO.lang.JSON.parse(o.responseText);
	    

// 	      if(response.state==200){
// 		  Dom.get('stock').innerHTML=response.stock;

// 	      }else{
// 		  alert(response.resp);
// 	      }
	    
// 	  };
// 	  var handleSubmit_details = function() {
// 	      YAHOO.product.Editor.saveHTML();
// 	      this.submit();
// 	  };
// 	  var handleSuccess_details = function(o) {
// 	      var Dom = YAHOO.util.Dom;
// 	      var response = YAHOO.lang.JSON.parse(o.responseText);
     
     
// 	      if(response.state==200){
		 
// 		  YAHOO.product.Editor.saveHTML();
		 
		 
// 		  var html = YAHOO.product.Editor.get('element').value;

		    
// 		  Dom.get('extended_description').innerHTML=html;


// 	      }else{
// 		  alert(response.resp);
// 	      }
	    
// 	  };


// 	  var upload_pic= function(o) {

// 	      var Dom   = YAHOO.util.Dom;
// 	      //  alert(o.responseText)
// 	      var r = YAHOO.lang.JSON.parse(o.responseText);
// 	      if(r.state==200){
// 		  //alert(r.new_src);
// 		  Dom.get('image').src=r.new_src;
// 		  Dom.get('image').setAttribute('pic_id',r.new_id);
	
// 		  //alert(r.others)
// 		  if(r.others==0){
// 		      Dom.get('caption').style.display="";
// 		      Dom.get('caption').innerHTML=r.caption;
// 		      Dom.get('otherimages').style.display="none";

// 		  }else{
// 		      Dom.get('caption').style.display="none";
// 		      Dom.get('caption').innerHTML=r.caption;
// 		      Dom.get('otherimages').style.display="";

// 		      for (i=0;i<5;i++) 
// 			  {
// 			      //			    alert("contador "+i);
// 			      // alert(Dom.get('oim_'+i)+' '+r.other_img[0]);
// 			      //			    if(Dom.get('oim_'+i).child!='undefined')
// 			      //	Dom.get('oim_'+i).removeChild(Dom.get('oim_'+i).child);


// 			      child=Dom.getChildren('oim_'+i);
// 			      Dom.get('imagediv').setAttribute('pic_id',r.new_id);
// 			      //alert(child)
// 			      for (x in child){
// 				  //				alert(x+' '+child[x]);
// 				  Dom.get('oim_'+i).removeChild(child[x]);
// 			      }

// 			      //Dom.get('oim_'+i).removeChild(child);
// 			      //alert('caca')
// 			      if(r.other_img_id[i]>0){

// 				  var im=document.createElement('img');

// 				  im.src=r.other_img[i];

// 				  Dom.get('oim_'+i).appendChild(im);

// 			      }

// 			  }		    

// 		  }

// 	      }else{
// 		  alert(r.resp);
// 	      }
	    
// 	  };


// 	  var handleFailure = function(o) {
// 	      alert("Submission failed" );
// 	  };
	
// 	  <?if($LU->checkRight(PROD_MODIFY)){?>
// 	      YAHOO.product.dialog1  = new YAHOO.widget.Dialog("edit_product_form",
// 							       { width : "30em",
// 								 fixedcenter : true,
// 								 zIndex:100,
// 								 visible : false, 
// 								 constraintoviewport : true,
// 								 buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
// 	      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
// 							       });

// 	      YAHOO.product.dialog1.callback = { success: handleSuccess,failure: handleFailure };
// 	      YAHOO.product.dialog1.render();



// 	      YAHOO.product.dialog2  = new YAHOO.widget.Dialog("addtosupplier_form",
// 							       { width : "30em",
// 								 fixedcenter : true,
// 								 visible : false, 
// 								 constraintoviewport : true,
// 								 buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },
// 	      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
// 							       });

// 	      YAHOO.product.dialog2.callback = { success: handleSuccess,failure: handleFailure };
// 	      YAHOO.product.dialog2.render();





// 	      YAHOO.product.dialog4  = new YAHOO.widget.Dialog("upload_pic_form",
// 							       { width : "30em",
// 								 fixedcenter : true,
// 								 visible : false, 
// 								 constraintoviewport : true,
// 								 //							       postmethod:"form",

// 								 buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },{ text:"<?=_('Cancel')?>", handler:handleCancel } ]
// 							       });
	
// 	      YAHOO.product.dialog4.callback = { upload: upload_pic};

// 	      YAHOO.product.dialog4.render();
	

// 	      YAHOO.product.dialog5  = new YAHOO.widget.Dialog("edit_details_form",
// 							       { 
// 								   fixedcenter : true,
// 								   visible : false, 
// 								   constraintoviewport : true,
// 								   //							       postmethod:"form",
// 								   buttons : [ { text:"<?=_('Save')?>", handler:handleSubmit_details, isDefault:true },{ text:"<?=_('Cancel')?>", handler:handleCancel } ]
// 							       });
	

// 	      YAHOO.product.dialog5.callback = { success: handleSuccess_details,failure: handleFailure };
// 	      YAHOO.product.dialog5.render();
	



// 	      YAHOO.util.Event.addListener("edit_product", "click",  YAHOO.product.dialog1.show, YAHOO.product.dialog1, true );
// 	      YAHOO.util.Event.addListener("add_supplier", "click",  YAHOO.product.dialog2.show, YAHOO.product.dialog2, true );


// 	      YAHOO.util.Event.addListener("add_pic", "click", YAHOO.product.dialog4.show, YAHOO.product.dialog4, true);
// 	      YAHOO.util.Event.addListener("edit_details", "click",  YAHOO.product.dialog5.show, YAHOO.product.dialog5, true );


// 	      YAHOO.product.changeedit = function(e) {
// 		  var Dom   = YAHOO.util.Dom;
// 		  var Event = YAHOO.util.Event;
	    
// 		  if(!YAHOO.product.edit){
// 		      YAHOO.product.edit=true;
// 		      Dom.get('but_view5').className='edit';
// 		      Dom.get('otherimages').className='editborder other_images';


// 		      Dom.get('but_view5').innerHTML='<?=_('Editing')?>';

// 		      Dom.get('but_view0').className='disabled';
// 		      Dom.get('but_view1').className='disabled';
// 		      Dom.get('but_view2').className='disabled';
// 		      Dom.get('but_view3').className='disabled';
// 		      Dom.get('but_view4').className='disabled';
		
// 		      Event.removeListener("but_view0", "click");
// 		      Event.removeListener("but_view1", "click");
// 		      Event.removeListener("but_view2", "click");
// 		      Event.removeListener("but_view3", "click");
// 		      Event.removeListener("but_view4", "click");




// 		      YAHOO.product.views[5]=1;

// 		      Dom.get('block0').style.display='';

// 		      Dom.get('block1').style.display='none';
// 		      Dom.get('block2').style.display='none';
// 		      Dom.get('block3').style.display='none';
// 		      Dom.get('block4').style.display='none';
// 		      Dom.get('buts').style.display='none';
// 		      Dom.get('edit_buts').style.display='';


// 		  }else{
// 		      YAHOO.product.edit=false;
// 		      Dom.get('otherimages').className='other_images';

// 		      Dom.get('edit_buts').style.display='none';

// 		      Dom.get('but_view5').className='';
// 		      YAHOO.product.views[5]=0;	
// 		      Dom.get('buts').style.display='';
// 		      Dom.get('but_view5').innerHTML='<?=_('Edit')?>';
// 		      if(YAHOO.product.views[0]==0){
// 			  Dom.get('but_view0').className='';
// 		      }else{
// 			  Dom.get('but_view0').className='selected';
// 			  Dom.get('block0').style.display='none'
// 		      }
// 		      if(YAHOO.product.views[1]==0){
// 			  Dom.get('but_view1').className='';
// 		      }else{
// 			  Dom.get('but_view1').className='selected';
// 			  Dom.get('block1').style.display=''
// 		      }
// 		      if(YAHOO.product.views[2]==0){
// 			  Dom.get('but_view2').className='';
// 		      }else{
// 			  Dom.get('but_view2').className='selected';
// 			  Dom.get('block0').style.display=''
// 		      }
// 		      if(YAHOO.product.views[3]==0){
// 			  Dom.get('but_view3').className='';
// 		      }else{
// 			  Dom.get('but_view3').className='selected';
// 			  Dom.get('block3').style.display=''
// 		      }
// 		      if(YAHOO.product.views[4]==0){
// 			  Dom.get('but_view4').className='';
// 		      }else{
// 			  Dom.get('but_view4').className='selected';
// 			  Dom.get('block4').style.display=''
// 		      }
		

// 		      Event.addListener("but_view0","click",YAHOO.product.changeview,0);
// 		      Event.addListener("but_view1","click",YAHOO.product.changeview,1);
// 		      Event.addListener("but_view2","click",YAHOO.product.changeview,2);
// 		      Event.addListener("but_view3","click",YAHOO.product.changeview,3);
// 		      Event.addListener("but_view4","click",YAHOO.product.changeview,4);
		
		    
// 		  }
		

// 	      }











// 	      <?}?>    		   


// 		<?if($LU->checkRight(PROD_STK_MODIFY)){?>

// 	      YAHOO.product.dialog3  = new YAHOO.widget.Dialog("setstock_form",
// 							       { width : "30em",
// 								 fixedcenter : true,
// 								 visible : false, 
// 								 constraintoviewport : true,
// 								 buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
// 	      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
// 							       });

// 	      YAHOO.product.dialog3.callback = { success: handleSuccess_stock,failure: handleFailure };
// 	      YAHOO.product.dialog3.render();
// 	      YAHOO.util.Event.addListener("update_stock", "click", YAHOO.product.dialog3.show, YAHOO.product.dialog3, true);
// 	      <?}?>

		    










// 		      YAHOO.product.plot='<?=$_SESSION['views']['product_plot']?>';
// 		      document.getElementById('plot_'+YAHOO.product.plot).className='selected';
// 		      YAHOO.product.views = new Array();

// 		      YAHOO.product.views[0]=<?=$_SESSION['views']['product_blocks'][0]?>;
// 		      YAHOO.product.views[1]=<?=$_SESSION['views']['product_blocks'][1]?>;
// 		      YAHOO.product.views[2]=<?=$_SESSION['views']['product_blocks'][2]?>;
// 		      YAHOO.product.views[3]=<?=$_SESSION['views']['product_blocks'][3]?>;
// 		      YAHOO.product.views[4]=<?=$_SESSION['views']['product_blocks'][4]?>;
// 		      YAHOO.product.views[5]=<?=$_SESSION['views']['product_blocks'][5]?>;


// 		      <?if($LU->checkRight(ORDER_VIEW)){?>



// // 			  if(YAHOO.product.plot==0)
// // 			      YAHOO.product.show_plot_weeksales();
// // 			  else if(YAHOO.product.plot==1)
// // 			      YAHOO.product.show_plot_weekorders();
// // 			  else if(YAHOO.product.plot==2)
// // 			      YAHOO.product.show_plot_weeksalesperorder();
// // 			  else if(YAHOO.product.plot==3)
// // 			      YAHOO.product.show_plot_monthsales();
	
// 			  <?}?>


	
// 			    YAHOO.product.changeview = function(e,view) {

// 				var Dom   = YAHOO.util.Dom;
// 				if(YAHOO.product.views[view]==0){
// 				    Dom.get('but_logo'+view).style.display='';
// 				    Dom.get('block'+view).style.display='';
// 				    YAHOO.product.views[view]=1;
// 				    YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductblock&value=1&block=' + escape(view) ); 

// 				}else{
// 				    Dom.get('but_logo'+view).style.display='none';
// 				    Dom.get('block'+view).style.display='none';
// 				    YAHOO.product.views[view]=0;	
// 				    YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductblock&value=0&block=' + escape(view) ); 

// 				}
		

// 			    }

//  			    YAHOO.product.changeplot = function(e,plot_name) {
				
// 				document.getElementById('plot_'+YAHOO.product.plot).className='opaque';
// 				YAHOO.product.plot=plot_name;
// 				document.getElementById('the_plot').src = 'plot.php?tipo='+plot_name;
// 				document.getElementById('plot_'+plot_name).className='selected';
				
// 				YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductplot&value=' + escape(plot_name) ); 


// 			    }
// // 				old_plot=YAHOO.product.plot;
// // 				if(old_plot==new_plot)
// // 				    return;
// // 				var Dom   = YAHOO.util.Dom;
// // 				YAHOO.product.plot=new_plot;
	    
// // 				Dom.get('plot'+old_plot).style.display='none'

// // 				Dom.get('plot'+new_plot).style.display=''

// // 				Dom.get("plot_view"+old_plot).className='';
// // 				Dom.get("plot_view"+new_plot).className='selected';

// // 				if(new_plot==0){
// // 				    YAHOO.product.show_plot_weeksales();
// // 				    Dom.get('plot_title').innerHTML='<?=_('Product sales value per week')?>';
// // 				}else if(new_plot==1){
// // 				    YAHOO.product.show_plot_weekorders();
// // 				    Dom.get('plot_title').innerHTML='<?=_('Orders with this product per week')?>';
// // 				}else if(new_plot==2){
// // 				    YAHOO.product.show_plot_weeksalesperorder();
// // 				    Dom.get('plot_title').innerHTML='<?=_('Sales value per order per week')?>';
// // 				}else if(new_plot==3){
// // 				    YAHOO.product.show_plot_monthsales();
// // 				    Dom.get('plot_title').innerHTML='<?=_('Product sales value per month')?>';
// // 				}else if(new_plot==4){
// // 				    YAHOO.product.show_plot_monthorders();
// // 				    Dom.get('plot_title').innerHTML='<?=_('Orders with this product per month')?>';
// // 				}else if(new_plot==5){
// // 				    YAHOO.product.show_plot_monthsalesperorder();
// // 				    Dom.get('plot_title').innerHTML='<?=_('Sales value per order per month')?>';
// // 				}

	    
// // 				YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductplot&value=' + escape(new_plot) ); 
	    
	    
	    
// // 			    }
	
	
	
	

	
	
// 			    Event.addListener("plot_sales_week","click",YAHOO.product.changeplot,'sales_week');
// 			    Event.addListener("plot_sales_month","click",YAHOO.product.changeplot,'sales_month');
// 			    Event.addListener("plot_sales_quarter","click",YAHOO.product.changeplot,'sales_quarter');
// 			    Event.addListener("plot_sales_year","click",YAHOO.product.changeplot,'sales_year');
// 			    Event.addListener("plot_out_week","click",YAHOO.product.changeplot,'out_week');
// 			    Event.addListener("plot_out_month","click",YAHOO.product.changeplot,'out_month');
// 			    Event.addListener("plot_out_quarter","click",YAHOO.product.changeplot,'out_quarter');
// 			    Event.addListener("plot_out_year","click",YAHOO.product.changeplot,'out_year');
// 			    Event.addListener("plot_stock_day","click",YAHOO.product.changeplot,'stock_day');


// // 			    Event.addListener("plot_view0","click",YAHOO.product.changeplot,0);
// // 			    Event.addListener("plot_view1","click",YAHOO.product.changeplot,1);
// // 			    Event.addListener("plot_view2","click",YAHOO.product.changeplot,2);
// // 			    Event.addListener("plot_view3","click",YAHOO.product.changeplot,3);
// // 			    Event.addListener("plot_view4","click",YAHOO.product.changeplot,4);
// // 			    Event.addListener("plot_view5","click",YAHOO.product.changeplot,5);


// 			    Event.addListener("but_view0","click",YAHOO.product.changeview,0);
// 			    Event.addListener("but_view1","click",YAHOO.product.changeview,1);
// 			    Event.addListener("but_view2","click",YAHOO.product.changeview,2);
// 			    Event.addListener("but_view3","click",YAHOO.product.changeview,3);
// 			    Event.addListener("but_view4","click",YAHOO.product.changeview,4);
// 			    Event.addListener("but_view5","click",YAHOO.product.changeedit,5);


// 			    Event.addListener("oim_0","click",img_click,0);
// 			    Event.addListener("oim_1","click",img_click,1);
// 			    Event.addListener("oim_2","click",img_click,2);
// 			    Event.addListener("oim_3","click",img_click,3);
// 			    Event.addListener("oim_4","click",img_click,4);
// 			    Event.addListener("imagediv","click",img_click,-1);






    
// 			    var texteditorConfig = {
// 				height: '300px',
// 				width: '530px',
// 				dompath: true,
// 				focusAtStart: true
// 			    };


// 			    YAHOO.product.Editor = new YAHOO.widget.Editor('editor', texteditorConfig);
// 			    YAHOO.product.Editor._defaultToolbar.buttonType = 'basic';
// 			    YAHOO.product.Editor.render();


// 			    YAHOO.product.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );

// 			    YAHOO.product.cal1.update=updateCal;
// 			    YAHOO.product.cal1.id=1;

// 			    YAHOO.product.cal1.render();
// 			    YAHOO.product.cal1.update();

// 			    YAHOO.product.cal1.selectEvent.subscribe(CalhandleSelect, YAHOO.product.cal1, true); 
 




// 			    // YAHOO.util.Event.addListener("calpop1", "click", YAHOO.product.cal1.show, YAHOO.product.cal1, true);



		       
// 			    var myTooltip = new YAHOO.widget.Tooltip("myTooltip", { context:"upo_label,outall_label,awoutall_label,awoutq_label"} ); 
// }

// YAHOO.util.Event.onDOMReady(init);
