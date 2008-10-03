<?
require_once '../common.php';
if (!$LU) 
   die('An unknown error occurred');
if (!$LU->isLoggedIn()) {
   $target = $_SERVER['PHP_SELF'];
   include_once 'loginscreen.php';
   exit();
}



?>


YAHOO.namespace ("users"); 



YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.users.XHR_JSON = new function() {
	    this.formatLang=  function(el, oRecord, oColumn, oData) {
		el.innerHTML = '<img src="art/flags/'+oRecord.getData("countrycode")+'.gif" alt="'+oRecord.getData("country")+'"> '+oData;
	    }
		this.userLink=  function(el, oRecord, oColumn, oData) {
		var url="user.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }	

		//START OF THE TABLE=========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


	    var groups = new Array();
	    var langs = new Array();
	    <?
	    $sql="select lang.id as id ,lower(c.code2) as country,lang.code as code  from lang left join country as c on (c.id=country_id)";
	    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	    //	    print "langs[0]='"._('Chosse one')."'\n";
	    $count=0;	    
	    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		print "langs[$count]='<img src=\"art/flags/".$row['country'].".gif\" langid=\"".$row['id']."\" /> ".$_lang[$row['id']]."'\n";
		//	print "langs[$count]='".$_lang[$row['id']]."'\n";
		$count++;
	    }

	   $sql="select group_id from liveuser_groups";
	    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	    $count=0;	    
	    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		print 'groups['.$count.']=\'<group id='.$row['group_id'].'/>'.$_group[$row['group_id']]."'\n";
		$count++;
	    }
?>


	 	this.changepwd=function(oEditor, oSelf){
		    //elContainer.innerHTML='<table><tr><td><?=_('Password')?>:</td><td><input type="text"name="p1"  value=""><tr><ts><?=_('Confirm Password')?>:</td><td><input type="text" name="p2" value=""></td></tr></table>';
		    var elCell = oEditor.cell;
		    var oRecord = oEditor.record;
		    var oColumn = oEditor.column;
		    var elContainer = oEditor.container;
		    var value_confirm;
		    var value;
		    var score;
		    
		    var p1Id="mypwd1"+oRecord.getId();
		    var p2Id="mypwd2"+oRecord.getId();
		    var gscoreId="gscore"+oRecord.getId();
		    var tscoreId="tscore"+oRecord.getId();
		    var respId="resp"+oRecord.getId();

		    elContainer.innerHTML='<div id="'+respId+'" style="margin:auto;text-align:center"><?=_('Write the new password')?></div><table ><tr><td  ><?=_('Password Meter')?>:</td><td> <div style="margin:auto;font-size:70%;"><div style="float:left;height:10px;width:100px;border:1px solid #222;margin-right:5px">  <div   id="'+gscoreId+'" style="height:10px;background:red;width:0%";color:red  ></div>    </div> <span id="'+tscoreId+'"></span>  </div></tr><tr><td><?=_('Password')?>:</td><td><input type="password" name="p1" id="'+p1Id+'"  value=""><tr><td><?=_('Confirm Password')?>:</td><td><input type="password"  id="'+p2Id+'" name="p2" value=""></td></tr></table>';

		    elTextbox1=document.getElementById(p1Id);
		    elTextbox2=document.getElementById(p2Id);
		    gscore=document.getElementById(gscoreId);
		    tscore=document.getElementById(tscoreId);
		    var resp=document.getElementById(respId);
		    oSelf._oCellEditor.resp=resp;
		    oSelf._oCellEditor.score=0;
		    // Set up a listener on each textbox to track the input value
		    
		    
		    

		    
		    YAHOO.util.Event.addListener(elTextbox1, "keyup", function(){
			    //TODO: set on a timeout
			    oSelf._oCellEditor.value = elTextbox1.value;
			    intScore=testPassword(elTextbox1.value);
			    

	 
			    if(intScore < 16)
				{
				    strVerdict = "<?=_('very weak')?>"
					color='#a00';
				}
			    else if (intScore > 15 && intScore < 25)
				{
				    strVerdict = "<?=_('weak')?>";
				    color='#f90';
				}
			    else if (intScore > 24 && intScore < 35)
				{
				    strVerdict = "<?=_('still weak')?>"
					color='#7fff00'; 
				}
			    else if (intScore > 34 && intScore < 45)
				{
				    strVerdict = "<?=_('strong')?>"
					color='#b6ff00'; 
				}
			    else
				{
				    strVerdict = "<?=_('stronger')?>"
					color='#7bff00'; 
				}
	
	 

			    if(intScore>45)
				intScore=45;
			    score=100*intScore/45;
			    
			    
			    gscore.style.backgroundColor=color;
			    gscore.style.width=score+'%';
			    tscore.innerHTML=strVerdict;
			    oSelf._oCellEditor.score=intScore;
			    oSelf.fireEvent("editorUpdateEvent",{editor:oSelf._oCellEditor});
			});
		    
		    YAHOO.util.Event.addListener(elTextbox2, "keyup", function(){
// 	 // 	 //TODO: set on a timeout
			    oSelf._oCellEditor.value_confirm = elTextbox2.value;
			    oSelf.fireEvent("editorUpdateEvent",{editor:oSelf._oCellEditor});
			});
		    
		}
	    
	    
	    this.deleteuser=function(oEditor, oSelf){
		
		var elContainer = oEditor.container;
		elContainer.innerHTML='<?=_('Are you sure you want to delete this user?<br/> If so press OK')?>';
		oSelf._oCellEditor.value=1;
		
	    }
		
		
		

	    var UsersColumnDefs = [
				   {key:"delete",label:"" ,width:16 ,hidden:true},
				   {key:"active",label:"" ,width:16 , editorOptions:{radioOptions:['<?=_('Activate')?>','<?=_('Desactivate')?>'],disableBtns:true} },
				   {key:"password",label:"" ,width:16 ,hidden:true},
				   {key:"handle", label:"<?=_('Handle')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   {key:"name", label:"<?=_('Name')?>",sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   {key:"email", label:"<?=_('Email')?>",sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   {key:"lang", label:"<?=_('Language')?>",sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editorOptions:{dropdownOptions:langs}},
				   {key:"groups", label:"<?=_('Groups')?>",className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editorOptions:{checkboxOptions:groups} ,editor:"textbox"  }



      ];
	    this.UsersDataSource = new YAHOO.util.DataSource("ar_users.php?tipo=users&tid="+tableid);
	    this.UsersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.UsersDataSource.connXhrMode = "queueRequests";
	    this.UsersDataSource.responseSchema = {
		resultsList: "resultset.data",
		totalRecords: 'resultset.total_records',
		fields: ["id","active","handle","name","email","lang","groups","password","delete"]
		//fields: ["is","handle"]
	    };
	    this.UsersDataSource.doBeforeCallback = mydoBeforeCallback;
	    this.UsersDataTable = new YAHOO.widget.DataTable(
							     tableDivEL, 
							     UsersColumnDefs,
							     this.UsersDataSource, {renderLoopSize: 50});





	    // Hook into custom event to customize save-flow of "radio" editor 

	    this.UsersDataTable.subscribe("editorUpdateEvent", function(oArgs) { 

	            if(oArgs.editor.column.key === "active") { 

	                this.saveCellEditor(); 

	            } 

	        });


 

		
		this.UsersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		this.UsersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.UsersDataTable}  } ]);
		this.UsersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.UsersDataTable}  } ]);
		this.UsersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.UsersDataTable}  } ]);
		this.UsersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.UsersDataTable}  } ]);
		this.UsersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.UsersDataTable}  } ]);
		this.UsersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.UsersDataTable}  } ]);
		YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.UsersDataTable.paginatorMenu.show, null, this.UsersDataTable.paginatorMenu);
		this.UsersDataTable.paginatorMenu.render(document.body);
		this.UsersDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		this.UsersDataTable.filterMenu.addItems([{ text: "<?=_('Product Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Family Code')?>"},scope:this.UsersDataTable}  } ]);
		this.UsersDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.UsersDataTable}  } ]);
		YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.UsersDataTable.filterMenu.show, null, this.UsersDataTable.filterMenu);
		this.UsersDataTable.filterMenu.render(document.body);
		
		this.UsersDataTable.myreload=reload;
		this.UsersDataTable.sortColumn = mysort;
		this.UsersDataTable.id=tableid;
		this.UsersDataTable.editmode=false;
		this.UsersDataTable.subscribe("initEvent", dataReturn); 
		YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.UsersDataTable); 
		YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.UsersDataTable); 
		YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.UsersDataTable); 
		YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.UsersDataTable); 
	    



	    // Set up editing flow
	    this.highlightEditableCell = function(oArgs) {

		var elCell = oArgs.target;

		if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
		    this.highlightCell(elCell);
            }
	    };


	    this.UsersDataTable.mySaveEditor = function (){
		
		if(this._oCellEditor.isActive) {
		    var newData = this._oCellEditor.value;
		    // Copy the data to pass to the event
		    var oldData = YAHOO.widget.DataTable._cloneObject(this._oCellEditor.record.getData(this._oCellEditor.column.key));

		    // Validate input data
		    if(this._oCellEditor.validator) {
			newData = this._oCellEditor.value = this._oCellEditor.validator.call(this, newData, oldData, this._oCellEditor);
			if(newData === null ) {
			    this.resetCellEditor();
			    this.fireEvent("editorRevertEvent",
					   {editor:this._oCellEditor, oldData:oldData, newData:newData});
			    YAHOO.log("Could not save Cell Editor input due to invalid data " +
				      lang.dump(newData), "warn", this.toString());
			    return;
			}
		    }

		    if(this._oCellEditor.column.getKey()=='password'){
			
			if(this._oCellEditor.score<16){
			    this._oCellEditor.resp.innerHTML="<?=_('Password too week, try again')?>.";
			    return;
			}
			if(newData!= this._oCellEditor.value_confirm){
			    this._oCellEditor.resp.innerHTML="<?=_('Passwords do not match, reconfirm')?>.";
			    return;
			}
			this._oCellEditor.value='<img src="art/icons/key_add.png" />';
			newData=sha256_digest(newData);
			
		    }
		    else   if(this._oCellEditor.column.getKey()=='active'){
			

			if(newData=='<?=_('Activate')?>'){
			    this._oCellEditor.value='<img src="art/icons/status_online.png" />';
			    newData=1;
			}
			else{
			    this._oCellEditor.value='<img src="art/icons/status_offline.png" />';
			    newData=0;
			}
			
		    }



		    //alert(oldData+" "+newData);
		    //Update the database
		    //  alert('ar_users.php?tipo=updateone&key='+this._oCellEditor.column.getKey()+'&value=' + escape(newData) +'&id=' + escape(this._oCellEditor.record.getData("id")));
		    YAHOO.util.Connect.asyncRequest(
						    'POST',
						    'ar_users.php?tipo=updateone&key='+this._oCellEditor.column.getKey()+'&value=' + escape(newData) +
						    '&id=' + escape(this._oCellEditor.record.getData("id")),{
							success: function (o) {
							    //  alert(o.responseText);
							    var r =  YAHOO.lang.JSON.parse(o.responseText);

							    if (r.state == 200) {
								//alert("ok");
								// Update the Record
								this._oRecordSet.updateRecordValue(this._oCellEditor.record, this._oCellEditor.column.key, this._oCellEditor.value);
								// Update the UI
								this.formatCell(this._oCellEditor.cell.firstChild);
								this._syncColWidths(false);
								
								if(this._oCellEditor.column.getKey()=='delete'){
								    
								    this.deleteRow(this._oCellEditor.record);
								}	
								if(this._oCellEditor.column.getKey()=='groups'){
								    // alert(r.gdata);
								    //  for(i in  r.gdata){
								    //		alert(r.gdata[i].users);
								    // }
								    recordset=YAHOO.users.XHR_JSON.GroupsDataTable.getRecordSet();
								    recordset.replaceRecords(r.gdata);
								    var newSortedBy = {
									key: "id",
									dir: YAHOO.widget.DataTable.CLASS_DESC
								    }
								    YAHOO.users.XHR_JSON.GroupsDataTable.set("sortedBy", newSortedBy);
								    YAHOO.users.XHR_JSON.GroupsDataTable.render();
								    
								}	

								
							    }else{
								//alert(o.responseText)
							    }
							    // Clear out the Cell Editor
							    this.resetCellEditor();
							    
							},
							failure: function(o) {alert("error")},
							scope: this
						    }
						    ); 

		

		}
	    }


	    this.UsersDataTable.saveCellEditor =this.UsersDataTable.mySaveEditor;
	    this.UsersDataTable.subscribe("cellMouseoverEvent", this.highlightEditableCell);
 	    this.UsersDataTable.subscribe("cellMouseoutEvent", this.UsersDataTable.onEventUnhighlightCell);
 	    this.UsersDataTable.subscribe("cellClickEvent", this.UsersDataTable.onEventShowCellEditor);
	    
        // Hook into custom event to customize save-flow of "radio" editor
	    this.UsersDataTable.subscribe("editorUpdateEvent", function(oArgs) {
		    if(oArgs.editor.column.key === "lang") {
			this.saveCellEditor();
            }
		});
	    this.UsersDataTable.subscribe("editorBlurEvent", function(oArgs) {
		     this.cancelCellEditor();
		});

	    

	    //END OF THE TABLE=========================================================================================================================



 //START OF THE TABLE=========================================================================================================================

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var GroupsColumnDefs = [
				    {key:"id", label:"<?=_('Id')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				    {key:"name", label:"<?=_('Group')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				    {key:"users", label:"<?=_('Users')?>", sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   
				   ];
	    this.GroupsDataSource = new YAHOO.util.DataSource("ar_users.php?tipo=groups&tid="+tableid);
	    this.GroupsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.GroupsDataSource.connXhrMode = "queueRequests";
	    this.GroupsDataSource.responseSchema = {
		resultsList: "resultset.data",
		fields: ["id","name","users"]
	    };

	    

	    this.GroupsDataSource.doBeforeCallback = mydoBeforeCallback;
	    this.GroupsDataTable = new YAHOO.widget.DataTable(tableDivEL, GroupsColumnDefs,this.GroupsDataSource, {renderLoopSize: 50});
	    
	    this.GroupsDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	    this.GroupsDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.GroupsDataTable}  } ]);
		this.GroupsDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.GroupsDataTable}  } ]);
		this.GroupsDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.GroupsDataTable}  } ]);
		this.GroupsDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.GroupsDataTable}  } ]);
		this.GroupsDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.GroupsDataTable}  } ]);
		this.GroupsDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.GroupsDataTable}  } ]);
		YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.GroupsDataTable.paginatorMenu.show, null, this.GroupsDataTable.paginatorMenu);
		this.GroupsDataTable.paginatorMenu.render(document.body);
		this.GroupsDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		this.GroupsDataTable.filterMenu.addItems([{ text: "<?=_('Product Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Family Code')?>"},scope:this.GroupsDataTable}  } ]);
		this.GroupsDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.GroupsDataTable}  } ]);
		YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.GroupsDataTable.filterMenu.show, null, this.GroupsDataTable.filterMenu);
		this.GroupsDataTable.filterMenu.render(document.body);
		
		this.GroupsDataTable.myreload=reload;
		this.GroupsDataTable.sortColumn = mysort;
		this.GroupsDataTable.id=tableid;
		this.GroupsDataTable.editmode=false;
		this.GroupsDataTable.subscribe("initEvent", dataReturn); 
		YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.GroupsDataTable); 
		YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.GroupsDataTable); 
		YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.GroupsDataTable); 
		YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.GroupsDataTable); 




	    //END OF THE TABLE=========================================================================================================================



//     var GroupsColumnDefs = [

// 			    {key:"name", label:"<?=_('Name')?>", sortable:true},
// 			    {key:"rights", label:"<?=_('Rights')?>"},

//         ];



//         this.GroupsDataSource = new YAHOO.util.DataSource("ar_users.php?");
//         this.GroupsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
//         this.GroupsDataSource.connXhrMode = "queueRequests";
//         this.GroupsDataSource.responseSchema = {
//             resultsList: "resultset.data",
//             fields: ["id","name","rights"]
//         };

//         this.GroupsDataTable = new YAHOO.widget.DataTable("groups_list", GroupsColumnDefs,
//                 this.GroupsDataSource, {initialRequest:"tipo=groups"});





    };
});

// function adduser(){
//     alert('add user');

// }


// YAHOO.util.Event.onContentReady("add_user", function () {
	
// 	// Create Buttons using existing <input> elements as a data source
// 	var oPushButton1 = new YAHOO.widget.Button("add_user");
// 	oPushButton1.on("click", adduser);
	
//     });


function init() {

	// Define various event handlers for Dialog
	var handleSubmit = function() {
	    this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
	    
	    var response = YAHOO.lang.JSON.parse(o.responseText);


	    
	    if(response.state==200){
		//	alert('ok');
	    //var pwd=document.getElementById("resp").innerHTML;
	    //	document.getElementById("resp").style.display ='' ;
	    //	document.getElementById("resp").innerHTML='<p><?=_("A new user has been added to the database")?>.</p><ul><li><?=_("Handle")?>:'+response.newuser+'</li><li><?=_("Password")?>:'+pwd+'</li></ul>';
		

		YAHOO.users.XHR_JSON.UsersDataTable.addRow(response.data,0);
	    	YAHOO.users.dialog1.hide();

		YAHOO.users.panel1 = new YAHOO.widget.Panel("resp", 
							    { 
								width:"300px",
								close:true,
								visible : true, 
								context:["yui-dt0-bdrow"+YAHOO.users.numberUsers,"tl", "bl"],
								constraintoviewport:true
							    } );
		
		YAHOO.users.panel1.setHeader('<?=_('New user password')?>');
		YAHOO.users.panel1.setBody( YAHOO.users.newpassword);
		YAHOO.users.panel1.render(); 
		
		YAHOO.users.panel1.show();
		
		YAHOO.users.numberUsers=YAHOO.users.numberUsers+1;
	    

		recordset=YAHOO.users.XHR_JSON.GroupsDataTable.getRecordSet();
		recordset.replaceRecords(response.gdata);
		var newSortedBy = {
		    key: "id",
		    dir: YAHOO.widget.DataTable.CLASS_DESC
		}
		YAHOO.users.XHR_JSON.GroupsDataTable.set("sortedBy", newSortedBy);
		YAHOO.users.XHR_JSON.GroupsDataTable.render();	



	    }else{

		alert(response.resp);
	    }

	    
	    

	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};


	// Instantiate the Dialog
	YAHOO.users.dialog1 = new YAHOO.widget.Dialog("add_user_dialog", 
						      { 
							  width:"300px",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							  //  postmethod:"form",
							   hideaftersubmit  :false,
							  buttons : [ { text:"Submit", handler:handleSubmit, isDefault:true },{ text:"Cancel", handler:handleCancel } ]
						      } );
	
	
	YAHOO.users.randomPassword=function (length)
	{
	    chars = "abcdefghjkmnpqrstuvwxyz1234567890qerzxcvbnm";
	    pass = "";
	    for(x=0;x<length;x++)
		{
		    i = Math.floor(Math.random() * 38);
		    pass += chars.charAt(i);
		}
	    return pass;
	}
	


	    
	// Validate the entries in the form to require that both first and last name are entered
	YAHOO.users.dialog1.validate = function() {
		var data = this.getData();
		if (data.name == "") {
		    alert("<?=_('Please enter the name of the user')?>.");
		    return false;
		}else if (data.email != "" && !emailcheck(data.email) ){
		    alert("<?=_('Please enter a valid email')?>.");
		    return false;
		} else {
		    return true;
		}
	};

	YAHOO.users.generatedialog = function() {

	    //YAHOO.users.newpassword=YAHOO.users.randomPassword(12);
	    //document.getElementById("ep").value=sha256_digest(pwd);
	    //document.getElementById("resp").innerHTML=pwd;
	    YAHOO.users.newpassword=YAHOO.users.randomPassword(12);
	    document.getElementById("ep").value=sha256_digest(YAHOO.users.newpassword);
	    YAHOO.users.dialog1.show();
	};


	// Wire up the success and failure handlers
	YAHOO.users.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	
	// Render the Dialog
	YAHOO.users.dialog1.render();
	

	
	YAHOO.users.edituserstable = function() {

	    if(YAHOO.users.XHR_JSON.UsersDataTable.editmode){
		YAHOO.users.XHR_JSON.UsersDataTable.editmode=false;
		//document.getElementById("table0").className="dtable btable "; 
		YAHOO.users.XHR_JSON.UsersDataTable.hideColumn('delete');
		YAHOO.users.XHR_JSON.UsersDataTable.hideColumn('password');
		
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('name').editor="";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('email').editor="";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('lang').editor="";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('groups').editor="";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('delete').editor="";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('password').editor="";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('active').editor="";
		YAHOO.users.XHR_JSON.UsersDataTable.render();
	    }else{
		YAHOO.users.XHR_JSON.UsersDataTable.editmode=true;
		//document.getElementById("table0").className="dtable btable etable"; 
		YAHOO.users.XHR_JSON.UsersDataTable.showColumn('delete');
		YAHOO.users.XHR_JSON.UsersDataTable.showColumn('password');
		
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('name').editor="textbox";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('email').editor="textbox";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('lang').editor="dropdown";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('groups').editor="checkbox";
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('delete').editor=YAHOO.users.XHR_JSON.deleteuser;
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('password').editor=YAHOO.users.XHR_JSON.changepwd;
		YAHOO.users.XHR_JSON.UsersDataTable.getColumn('active').editor="radio";
		YAHOO.users.XHR_JSON.UsersDataTable.render();
	    }


	};






	var addUserButton= new YAHOO.widget.Button("add_user");
	YAHOO.util.Event.addListener("add_user", "click", YAHOO.users.generatedialog);
	var editUsersButton= new YAHOO.widget.Button("edit_users",{ 
		type:"checkbox", 
		value:"1", 
		checked:false });
	YAHOO.util.Event.addListener("edit_users", "click", YAHOO.users.edituserstable);	

}

YAHOO.util.Event.onDOMReady(init);



function emailcheck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    
		    return false
		 }

 		 return true					
	}

