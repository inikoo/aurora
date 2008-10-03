<?
    include_once('../common.php');
?>
var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;

	var    current_form = 'description';
	var    num_changed = 0;
	var    num_errors = 0;
var editor;





	function save_form(){
	    if(current_form == 'description')
		 editor.saveHTML();
	YAHOO.util.Connect.setForm(document.getElementById(current_form)); 

	var request = YAHOO.util.Connect.asyncRequest('POST', 'ar_assets.php', callback);

	}

  var interpet_changes = function(){
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







function init(){






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
    }

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

	if(o.id=='v_description' || o.id=='v_sdescription'){
	    if(o.value==''){
		return false;
		o.inerhtml='caca';
	    }else if(!o.value.match(/[a-z]/))
		return false;

	}
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


    var change_element= function(e){

       
	current_class=this.className;



	if(this.getAttribute("ovalue")!=this.value){

	    if(current_class==''){
		num_changed++;
		}

	    val = vadilate(this);

	    if(!val){
		if(current_class!='error'){
		    num_errors++;
		}
		this.className='error';
	    }else{
		if(current_class=='error')
		    num_errors--;
		this.className='ok';

	    }

	}else{

	    if(current_class=='ok')
		num_changed--;
	    if(current_class=='error'){
		num_changed--;
		num_errors--;
	    }
	    this.className='';

	}

	interpet_changes();
	


    }
	//   var ids = ["v_uw"]; 
	//   YAHOO.util.Event.addListener(ids, "keyup", check_number);
	//   var ids = ["v_udim","v_odim"]; 
	//   YAHOO.util.Event.addListener(ids, "keyup", check_dimension);
  
	//   var ids = ["v_udim_shape","v_odim_shape"]; 
	//   YAHOO.util.Event.addListener(ids, "change", change_shape);




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
	    alert(e.id);
	    
	}

	var ids = ["v_description","v_sdescription"]; 
	YAHOO.util.Event.addListener(ids, "keyup", change_element);

	var ids = ["cat_select"]; 
	YAHOO.util.Event.addListener(ids, "change", change_list_element);
	var ids = ["description"]; 
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
	
	var change_block = function(e){
	    alert(e.id);
	    
	}
	    

}


    YAHOO.util.Event.onDOMReady(init);

