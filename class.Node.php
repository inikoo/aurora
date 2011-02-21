<?php
/***************************************************************
Nodes Class
Author: Shadi Ali
Em@il: write2shadi@gmail.com

You Can use this class freely in your Commercial Applications.
---------------------------------

SQL TABLE:
-- at min you must have the following fields in your table structure ..

CREATE TABLE `nodes` (
`id` BIGINT NOT NULL AUTO_INCREMENT ,
`position` VARCHAR( 255 ) NOT NULL ,
`ord` int NOT NULL ,

-- add your fields here
--  ie -> `node_name` varchar(160),
-- add your fields here

PRIMARY KEY ( `id` ) ,
INDEX ( `position` ) 
);

class summary:
------------------

->add_new($parent , $name , $fields )  // add new node
->delete($id) // delete existing node and all sub-nodes, returns the affected Ids .. so you could run any other operations later .. maybe deleting linked records or deleting linked image files based on the returned ids.
->update($id , $parent , $fields  ) // update existing node
->build_list($id=0,$clickable=TRUE) // return array with the nodes ordered by "ord" , it could be clickable by setting $clickable = true, or else , it will be fully expanded
->browse_by_id($id) // return array with sub nodes under a specific node only.
->fetch ($id) // return existing node info.
->count_nodes($id) // get sub nodes count below a TOP-LEVEL node $id.
->order_node($id , $new_order) // change the order of a node Inside Its LEVEL.
->html_output($id , $clickable) // output a html list ( customizable via the class variable $HtmlTree );
->html_row_output($id) // out put a You>Are>Here like menu .. requires the current node id.
********************************************************************/

class nodes
{

var $id=0;
var $HtmlTree;
var $HtmlRow;
var $table_name   = "`Category Dimension`";
var $table_fields = array( 'id' => '`Category Key`',
			   'position' => '`Category Position`',
			   'deep' => '`Category Deep`',
			   'ord'	  => '`Category Order`',
			    'name'	  => '`Category Name`',
			   'is_default'	  => '`Category Default`',
			   );

// use the following keys into the $HtmlTree varialbe.

/**************************************************
	--- NO CHANGES TO BE DONE BELOW ---
**************************************************/	


var $sql_condition; // overall sql conditions without WHERE ... (i.e  .. AND myfield = 5 AND myfield2 = 2 )


var $sql_condition_where; // DON'T CHANGE THIS 
var $c_list  = array();  // DON'T CHANGE THIS

function nodes($table_name = NULL){

$this->table_name = $table_name;


//  --> use direct fields names from your mysql table into the following template variables.
//  --> just put the field name inside square brackets!
//  --> [fieldname] will be replaced with the correct value...
//////////////////////////////////////////////////////////////

// HtmlTree is used with $this->html_output() method to print a nested list

$this->HtmlTree = array(
"OpenTag"			=> '<ul>' , // this is the overall tag opener , example <ul>
"FirstLevelOpenTag" 		=> '<li><a href="?id=[id]">[id]</a><ul>', // this is printed for the ROOT parent ( node has children/sub-nodes) ie : <ul><li><h2>[name]</h2>
"FirstLevelOpenTagSelected" => '<li><a href="?id=[id]"><b>[id]</b></a><ul>', // this is printed for the ROOT parent ( node has children/sub-nodes) ie : <ul><li><h2>[name]</h2>
"LevelOpenTag" 				=> '<li><a href="?id=[id]">[id]</a><ul>', // this is printed for the parent ( node has children/sub-nodes) ie : <ul><li><h2>[name]</h2>
"LevelOpenTagSelected"  	=> '<li><a href="?id=[id]"><b>[id]</b></a><ul>' , // // this is printed for the parent ( node has children/sub-nodes) .. WHEN SELECTED! ie : <ul><li><h2><STRONG>[name]cp </STRONG></h2> 
"Node"			 				=> '<li><a href="?id=[id]">[id]</a></li>', // node item tag .. 
"NodeSelected"	 				=> '<li><a href="?id=[id]"><b>[id]</b></a></li>' , // node item tag .. when selected !
"FirstLevelCloseTag"  		=> '</ul></li>', // ROOT parent tag closer. ( when getting out of sub-level)
"FirstLevelCloseTagSelected"=> '</ul></li>', // ROOT parent tag closer, while selected.
"LevelCloseTag"  			=> '</ul></li>', // parent tag closer. ( when getting out of sub-level)
"LevelCloseTagSelected"		=> '</ul></li>', // parent tag closer, while selected.
"CloseTag"			=> '</ul>' , // this is the overall tag opener , example <ul>
);


// HtmlTree is used with $this->html_row_output() method to print a You>Are>Here like menu
$this->HtmlRow = array(
"OpenTag"		=> '<div>' , // this is the overall tag opener , example <ul>
"Seprator" 		=> ' &gt; ', // seprator between the items. example " &gt; " which means " > "
"NodeUnselected" 		=> '<a href="?id=[id]">[id]</a>', // item tag .. 
"NodeSelected"	 		=> '<a href="?id=[id]"><strong>[id]</strong></a>' , // item tag .. when selected !
"CloseTag"		=> '</div>' , // this is the overall tag opener , example <ul>
);


if($this->sql_condition != "") $this->sql_condition_where = " WHERE ".$this->sql_condition;
}

// ********************************************************
//		Add New Node
// ********************************************************


function add_new($parent = 0 , $fields = array() )  // add new category
{
$keys = array_keys($fields);



$values= array_values($fields);
// lets get the position from the $parent value
$position  = $this->get_position($parent);

// lets insert add the new category into the database.

//$sql = "INSERT into ".$this->table_name." (";

//print_r($fields);
$fields['Category Parent Key']=$parent;
 $_keys='';
        $_values='';
        foreach($fields as $key=>$value) {
        
        
if(!preg_match('/^\`.+\`$/',$key)){
$key="`".$key."`";
}

            $_keys.=",".$key."";
            $_values.=','.prepare_mysql($value,false);
        }
        $_values=preg_replace('/^,/','',$_values);
        $_keys=preg_replace('/^,/','',$_keys);

        $sql="insert into ".$this->table_name." ($_keys) values ($_values)";


//.$this->table_fields['position'].", ".implode("," , $keys).") VALUES('', '".implode("','" , $values)."' )";



//print "$sql\n";
mysql_query($sql) or 
die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
$this->id=mysql_insert_id();




$node_id   = mysql_insert_id();
$position .= $node_id.">";
$deep= count(preg_split('/>/',$position))-1;

$sql = "UPDATE ".$this->table_name."
		SET ".$this->table_fields['position']." = '".$position."' ,  ".$this->table_fields['deep']." = '".$deep."'
		WHERE ".$this->table_fields['id']." = '".mysql_insert_id()."' ".$this->sql_condition;

mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));




$this->_optimize_orders($position);
}

// ********************************************************
//		Delete Node
// ********************************************************

function delete($id) // delete this category and all categories under it 
{

	$myNode = $this->fetch($id);
	// lets get the ids before delete operations
	$sql1 = "SELECT ".$this->table_fields['id']."
			FROM ".$this->table_name." ".$this->sql_condition_where;
			
	$res1 =	mysql_query($sql1) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql1."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
	$before_ids = array();
	while($crd_id = mysql_fetch_array($res1)){
	$before_ids[] = $crd_id;
	}
	
	$position = $this->get_position($id);
	
	$sql2 = "DELETE FROM ".$this->table_name."
			WHERE 
				".$this->table_fields['position']."
		    LIKE
				 '".$position."%' ".$this->sql_condition;
		 
	mysql_query($sql2) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql2."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
	
	// ok now lets get the ids after the deletion.
	$sql3 = "SELECT ".$this->table_fields['id']."
			FROM ".$this->table_name." ".$this->sql_condition_where;
	$res3 =	mysql_query($sql1) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql3."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
	$after_ids = array();
	while($crd_id = mysql_fetch_array($res1)){
	$after_ids[] = $crd_id;
	}
	
	$this->_optimize_orders($myNode['position']); 
	
	// lets return an array with the affected IDs
	$affected_ids = array_diff($before_ids, $after_ids);
	return $affected_ids;

}



// ********************************************************
//		Update Node
// ********************************************************

function update($id , $parent = 0 , $fields= array())
{
$keys = array_keys($fields);
$values= array_values($fields);


// lets get the current position
$position     = $this->get_position($id);
$new_position = $this->get_position($parent).$id.">";

if($position != $new_position){
// then we update all the sub_categories position to be still under the current category
$sql1 = "SELECT ".$this->table_fields['id'].",".$this->table_fields['position']."
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['position']."	RLIKE '^".$position."([0-9]+>)+' ".$this->sql_condition;
$res = mysql_query($sql1) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql1."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

while($sub = mysql_fetch_array($res)){


$new_sub_position = str_replace($position,$new_position,$sub[$this->table_fields['position']]);
$sql2 = "UPDATE ".$this->table_name."
		SET ".$this->table_fields['position']." = '".$new_sub_position."'
		WHERE ".$this->table_fields['position']."	=  '".$sub[$this->table_fields['position']]."' ".$this->sql_condition;
mysql_query($sql2) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql2."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
}

}

// finally update the category position.
$sql3 = "UPDATE ".$this->table_name."
		SET ".$this->table_fields['position']." = '".$new_position."'
		WHERE ".$this->table_fields['position']."	=  '".$position."' ".$this->sql_condition;
mysql_query($sql3) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql3."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

$this->_optimize_orders($position);
$this->_optimize_orders($new_position);
 
$sql = "UPDATE ".$this->table_name."
		SET ";

// lets see what changes should be done and add it to the sql query.
foreach($fields as $key => $value){
if ($key 	== $this->table_fields['id']) continue;		// no change will be done on the id
if ($key 	== $this->table_fields['position'] ) continue; // position change have been done in the section above
$sql .= "".$key." = '".$value."',";
}

$sql = substr_replace($sql,"",-1); // remove the extra comma , 
$sql .= "WHERE ".$this->table_fields['id']." =".$id." ".$this->sql_condition;

mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
}

// ********************************************************
//		Build Nodes Array
// ********************************************************

function build_list($id=0,$clickable = true) //return an array with the categories ordered by position
{
$RootPos = "";
$this->c_list = array();

if($id != 0){
$this_category  = $this->fetch($id);
$positions      = explode(">",$this_category['position']);
$RootPos        = $positions[0];
}

// lets fetch the root categories
$sql = "SELECT *
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['position']."	RLIKE '^([0-9]+>){1,1}$' ".$this->sql_condition." order by ".$this->table_fields['ord']."  ASC";
$res = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

while($root = mysql_fetch_array($res)){
$root["prefix"] = $this->get_prefix($root['position']);
$this->c_list[$root[$this->table_fields['id']]] = $root;

	if($RootPos == $root[$this->table_fields['id']] AND $id != 0 AND $clickable){
	$this->list_by_id($id);
	continue;

	}else{
	
	// lets check if there is sub-categories
		if($clickable == "" AND $id==0){
		$has_children = $this->has_children($root[$this->table_fields['position']]);
		if($has_children == TRUE) $this->load_children($root[$this->table_fields['position']],0);
	}
}}
return $this->c_list;
}


// ********************************************************
//		Check if Node has childrens
// ********************************************************

function has_children($position) // return TRUE if that position has sub-categories otherwise returns FALSE
{
$check_sql = "SELECT ".$this->table_fields['id']." FROM ".$this->table_name." WHERE ".$this->table_fields['position']." RLIKE  '^".$position."[0-9]+>$' ".$this->sql_condition;
$check_res = mysql_query($check_sql) ;
$check = mysql_fetch_array($check_res);

//print "--> $check_sql\n";
if($check[preg_replace('/`/','',$this->table_fields['id'])] != "") return TRUE;
else return FALSE;
}

// ********************************************************
//		Load  Childrens
// ********************************************************

function load_children($position , $id = 0,$recursive=true){

$sql = "SELECT * FROM ".$this->table_name." WHERE ".$this->table_fields['position']."	RLIKE '^".$position."[0-9]+>$' ".$this->sql_condition." order by ".$this->table_fields['ord']." ";
$res = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
print "$sql\n";
while($child = mysql_fetch_array($res)){
  $child["prefix"] = $this->get_prefix($child[ preg_replace('/`/','',$this->table_fields['position']) ]);
  
  if($id != 0)
    {
  
      
      $has_children = $this->has_children($child[preg_replace('/`/','',$this->table_fields['position'])]);
      $child['has_children']= $has_children ;
      $this->c_list_by_id[$child[$this->table_fields['id']]] = $child;
      if($recursive){
	$has_children = $this->has_children($child[preg_replace('/`/','',$this->table_fields['position'])]);
	if($has_children == TRUE){
	  $this->load_children($child[preg_replace('/`/','',$this->table_fields['position'])]);
	}
      }
      continue;
      
    }else{
    
    // lets check if there is sub-categories
    $has_children = $this->has_children($child[preg_replace('/`/','',$this->table_fields['position'])]);
    $child['has_children']= $has_children ;
    $child['position']= $child[preg_replace('/`/','',$this->table_fields['position'])] ;
    $this->c_list[$child[preg_replace('/`/','',$this->table_fields['id'])]] = $child;
    if($recursive){
      
    if($has_children == TRUE)$this->load_children($child[preg_replace('/`/','',$this->table_fields['position'])]);
    }
  }}
}


// ********************************************************
//		Get  Childrens
// ********************************************************

function get_children($position){

  $children=array();

$sql = "SELECT * FROM ".$this->table_name." WHERE ".$this->table_fields['position']."	RLIKE '^".$position."[0-9]+>$' ".$this->sql_condition." order by ".$this->table_fields['name']." ";
$res = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
//print "$sql\n";
$contador=0;
while($child = mysql_fetch_assoc($res)){
  $child["prefix"] = $this->get_prefix($child[ preg_replace('/`/','',$this->table_fields['position']) ]);
  $has_children = $this->has_children($child[preg_replace('/`/','',$this->table_fields['position'])]);
  $child['has_children']= $has_children ;
  $child['position']= $child[preg_replace('/`/','',$this->table_fields['position'])] ;
  $child['is_default']= $child[preg_replace('/`/','',$this->table_fields['is_default'])] ;
    $child['contador']=$contador++;
  $children[$child[preg_replace('/`/','',$this->table_fields['id'])]] = $child;


}
return $children;

}

// ********************************************************
//		Get children of Specific nodes only.
// ********************************************************

function list_by_id($id) //return an array with the categories under the given ID and ordered by name
{
$this_category  = $this->fetch($id);

$positions = explode(">",$this_category[$this->table_fields['position']]);
$pCount = count($positions);
$i = 0;

// lets fetch from top to center
while($i < $pCount){
$pos_id	   = $positions["$i"];
if($pos_id == ""){$i++; continue;}
$list = $this->browse_by_id($pos_id);

foreach($list as $key=>$value){
$this->c_list["$key"] = $value;
$ni = $i + 1;
$nxt_id = $positions[$ni];
if($key == $nxt_id ) break;
} $i++;
}

//center to end
$i = $pCount-1;

while($i >= 0){
$pos_id	 = $positions["$i"];
if($pos_id == ""){$i--; continue;}
$list = $this->browse_by_id($pos_id);

foreach($list as $key=>$value){
$ni = $i - 1;
if($ni < 0) $ni =0;
$nxt_id = $positions[$ni];
if($key == $nxt_id ) break;
$this->c_list["$key"] = $value;
} $i--;
}

}

/***************************************
    Get array of nodes under specific category.
 ****************************************/
 
function browse_by_id($id) // return array of categories under specific category.
{
$children 		= array();
$this_category  = $this->fetch($id);
$position       = $this_category[$this->table_fields['position']];

$sql = "SELECT *
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['position']."	RLIKE '^".$position."(([0-9])+\>){1}$' ".$this->sql_condition."
		order by ".$this->table_fields['ord']." ";
$res = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

while($child = mysql_fetch_array($res)){
$child["prefix"] = $this->get_prefix($child['position']);
$children[$child[$this->table_fields['id']]] = $child;
}
return $children;
}

// ********************************************************
//		Get Position
// ********************************************************

function get_position($id)
{
if($id == 0)return "";
$sql = "SELECT ".$this->table_fields['position']." as position
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['id']." = '".$id."' ".$this->sql_condition;
$res = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
$record =  mysql_fetch_array($res);
return $record['position'];
}

function get_deep($id)
{
if($id == 0)return "";
$sql = "SELECT ".$this->table_fields['position']." as position
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['id']." = '".$id."' ".$this->sql_condition;
$res = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
$record =  mysql_fetch_array($res);
return count(preg_split('/>/',$record['position']))-1;
}



// ********************************************************
//		Get Prefix Count
// ********************************************************

function get_prefix($position)
{
$prefix = "";
$position_slices = explode(">",$position);
$count = count($position_slices) - 1;
return ($count < 1 ) ? 1 : $count;
}

// ********************************************************
//		Fetch Node Record
// ********************************************************

function fetch ($id)
{

$sql=sprintf("select * from %s where %s=%d %s",
$this->table_name,
$this->table_fields['id'],
$id,
$this->sql_condition
);
$res = mysql_query($sql);


if($record = mysql_fetch_array($res)){



$record["prefix"] = $this->get_prefix($record[ preg_replace('/`/','',$this->table_fields['position'])  ]);		
$position_slices  = explode(">",$record[ preg_replace('/`/','',$this->table_fields['position'])]);
$key              = count($position_slices)-3;
if($key < 0) $key = 0;
$record["parent"] = $position_slices["$key"];
}
return $record;
}



// ********************************************************
//		Build HTML output
// ********************************************************

function html_output($id=0 , $clickable = false)
{
	 
	 if(!$clickable){
	 	$tree  = $this->build_list( 0 , 0 ); // display the full list
	 }else{
	 	$tree  = $this->build_list($id , $clickable); // display clickable list (one sub-level list)
	 }
		

$output  = "\n<!-- Using OpenTag -->\n";
$output .= $this->HtmlTree["OpenTag"];




			if(is_array($tree))
			{
$start		  = 1;			
$next_loop_level = 1;
$tree = array_values($tree);

$end		  = count($tree);
				for($i=0; $i<$end ;$i++)
				{

						$body = "";	
						$c				 =  $tree[$i];
						$i2 = $i + 1;

						if($i2 < $end){
							$next_loop 		 =  $tree[$i2];
							$next_loop_level = 	$next_loop['prefix'];
						}else $next_loop_level = 1;
						// are we getting into sub-level the next loop ?
						
						if( $next_loop_level > $c['prefix']){
							
							if($c['prefix'] > 1){
								// if so then lets use the LevelOpenTag
								$body .= "\n<!-- Using LevelOpenTag -->\n";
								if($c[$this->table_fields['id']] == $id) 				$body .= $this->HtmlTree['LevelOpenTagSelected'];
								else   						  	 	$body .= $this->HtmlTree['LevelOpenTag'];
							}else{
								$body .= "\n<!-- Using FirstLevelOpenTag -->\n";
								// we are on the roots.
								if($c[$this->table_fields['id']] == $id) 				$body .= $this->HtmlTree['FirstLevelOpenTagSelected'];
								else   						  	 	$body .= $this->HtmlTree['FirstLevelOpenTag'];
							}
						}elseif( $next_loop_level < $c['prefix'] AND $next_loop_level >= 1){
					
										                            			
							if($next_loop_level == 1 && $c['prefix'] == 2){
								// we are on the roots.
								$body .= "\n<!-- Using Node -->\n";
								if($c['id'] == $id)	$body .= $this->HtmlTree['NodeSelected'];
								else 				$body .= $this->HtmlTree['Node'];
								$body .= "\n<!-- Using FirstLevelCloseTag -->\n";
								if($c['id'] == $id)	$body .= $this->HtmlTree['FirstLevelCloseTagSelected'];
								else 				$body .= $this->HtmlTree['FirstLevelCloseTag'];
																					
							}else{
								// if so then lets use the LevelCloseTag
								$body .= "\n<!-- Using Node -->\n";
								if($c[$this->table_fields['id']] == $id)	$body .= $this->HtmlTree['NodeSelected'];				
								else				$body .= $this->HtmlTree['Node']; 						  	 	
									
								
								for($j = $c['prefix']; $j > $next_loop_level ; $j--){
								   if($j == 2){
                                        $body .= "\n<!-- Using FirstLevelCloseTag -->\n";
								        if($c[$this->table_fields['id']] == $id)	$body .= $this->HtmlTree['FirstLevelCloseTagSelected'];
								        else 				$body .= $this->HtmlTree['FirstLevelCloseTag'];
                                    }else{
                                        $body .= "\n<!-- Using LevelCloseTag -->\n"; 
                                        if($c[$this->table_fields['id']] == $id)	$body .= $this->HtmlTree['LevelCloseTagSelected'];
									    else				$body .= $this->HtmlTree['LevelCloseTag'];
                                    }
								}							

							}
							
						}else{
							// neither getting in or out of a level .. use the normal node tags
							$body .= "\n<!-- Using Node -->\n";
							if($c[$this->table_fields['id']] == $id) 				$body .= $this->HtmlTree['NodeSelected'];
							else   						  	 	$body .= $this->HtmlTree['Node'];
						
						}			
					
					foreach($c as $key => $value)
					{	
						$body = str_replace("[$key]" ,$value, $body);	
					}
					
				 $next_loop_level--;

				$output .= $body;
									
				}
			}
			
$output  .= "\n<!-- Using CloseTag -->\n";
$output  .= $this->HtmlTree['CloseTag'];
return $output;
}


// This function output a You-Are-Here like menu.
// Home > Articles > "Progamming"
// it takes the current Node id.
////////////////////////////////////////////////////

function html_row_output($id){

// first we get the position chain
$position_chain  = $this->get_position($id);
$positions 		 = explode(">" , $position_chain);
// we loop through the chain and echo every node using the template.
$output  = $this->HtmlRow['OpenTag'];
foreach($positions  as $nid){
	$body = ""; // we initialize the body;
	if(!$nid) continue; // thats for the last position its always a null space, so we just ignore it!
	$c = $this->fetch($nid); // we fetch the node from the database;
	if($id == $nid)
		$body .=  $this->HtmlRow['NodeSelected'];
	else{
	$body .=  $this->HtmlRow['NodeUnselected'];
	$body .=  $this->HtmlRow['Seprator'];	
	}
	

	// now lets replace the keys in the templates with the values
	foreach($c as $key => $value)
	{	
		$body = str_replace("[$key]" ,$value, $body);	
	}
	$output .= $body;
}

$output .= $this->HtmlRow['CloseTag'];

return $output;
}

// get the count of sub-nodes of a parent node
// requires an ID of the parent node
//////////////////////////////////////////////////////

function count_nodes($cat_id)
{
$thisPosition = $this->get_position($cat_id);
$sql = "SELECT *
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['position']." LIKE '".$thisPosition."%' ".$this->sql_condition;
$res   = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
$count = mysql_num_rows($res);
$count-= 1; // remove the category itself from the count
return $count;
}

// Change the order of a inside its level.
// requires an ID of and the New ORDER ... 
// new order must be between 1 and $max ( max = total nodes in the level)
//////////////////////////////////////////////////////

function order_node($id , $new_order){

$myNode 	  = $this->fetch($id);
$thisPosition = $this->get_position($id);
$PositionS	  = explode(">" , $thisPosition);

array_pop($PositionS);
array_pop($PositionS);

if(count($PositionS) > 0)
$parentPosition = implode(">", $PositionS).">";

// ok lets count the nodes in the same level;
$sql = "SELECT *
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['position']." RLIKE '^".$parentPosition."(([0-9])+\>){1}$' ".$this->sql_condition;
$res   = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));		

$current_order = $myNode[$this->table_fields['ord']];

$max 		   = mysql_num_rows($res); // total;

// lets check the new order;

if($new_order > $max or $new_order == -1)
	$new_order = $max;
elseif($new_order < 1)
	$new_order = 1;
// return false;
// update the replaced Node.
$sql2 = "UPDATE ".$this->table_name."
		SET ".$this->table_fields['ord']." = '".$current_order."'
		WHERE ".$this->table_fields['position']." RLIKE '^".$parentPosition."(([0-9])+\>){1}$' AND ".$this->table_fields['ord']." = '".$new_order."' ".$this->sql_condition;
$res2   = mysql_query($sql2) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql2."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));		

// update the selected Node.
$sql3 = "UPDATE ".$this->table_name."
		SET ".$this->table_fields['ord']." = '".$new_order."'
		WHERE ".$this->table_fields['position']." = '".$thisPosition."' ".$this->sql_condition;
$res3   = mysql_query($sql3) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql3."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));		

// done.

$this->_optimize_orders($thisPosition);

}

// Walk through the level and fix false nodes order.
// requires a given level position.
////////////////////////////////////////////////////
function _optimize_orders($position){


$PositionS	  = explode(">" , $position);
array_pop($PositionS);
array_pop($PositionS);
if(count($PositionS) > 0)
$parentPosition = implode(">", $PositionS).">";
else
$parentPosition=0;

// ok lets count the nodes in the same level;
$sql = "SELECT *,".$this->table_fields['id']." as id,".$this->table_fields['ord']." as ord
		FROM ".$this->table_name."
		WHERE ".$this->table_fields['position']." RLIKE '^".$parentPosition."(([0-9])+\>){1}$' ".$this->sql_condition." order by ".$this->table_fields['ord']."  ASC";
//print $sql;
$res   = mysql_query($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));		

$max 		   = mysql_num_rows($res); // total;
// now we got an ordered list of the nodes inside level .. it should be 1 , 2, 3 ... $max , what if something wasn't there ? lets fix that.
for($i = 1; $i <= $max ; $i++){
$node = mysql_fetch_array($res);

if(!$node) break;
	if($i != $node['ord']){
	
		$sql2 = "UPDATE ".$this->table_name."
				SET ".$this->table_fields['ord']." = '".$i."' 
				WHERE ".$this->table_fields['id']." = '".$node['id']."' ".$this->sql_condition." LIMIT 1";
		$res2   = mysql_query($sql2) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql2."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));		

	}
}

}


function load_comb(){
  $id_field=preg_replace('/`/','',$this->table_fields['id']);
  $this->comb=array();
  $this->root=array();
  foreach($this->get_children('')    as $child){
  //print_r($child);
  
    $this->root[]=$child[$id_field];
    $this->comb[$child[$id_field]]=
      array(
	    'name'=>$child[ preg_replace('/`/','',$this->table_fields['name'])]
	    ,'has_child'=>$child['has_children']
	     ,'contador'=>$child['contador']
	    );
    if($child['has_children']){
      $this->get_teeth($child[preg_replace('/`/','',$this->table_fields['position'])],$child[$id_field]);
    }

  }
  
  //print_r($this->c_list);
  //print_r($this->comb);
  

}


function get_teeth($position,$father){
  $id_field=preg_replace('/`/','',$this->table_fields['id']);
  $position_field=preg_replace('/`/','',$this->table_fields['position']);
  $is_default_field=preg_replace('/`/','',$this->table_fields['is_default']);
  $teeth=array();
  $the_default=0;
  foreach( $this->get_children($position)   as $child){
    
    $parent=preg_replace('/>$/','',$position);
    $parent=preg_replace('/^.*>/','',$parent);

    if($child[$is_default_field]=='Yes')
      $the_default=$child[$id_field];
    $this->comb[$father]['teeth'][$position]['elements'][$child[$id_field]]=array(
											'key'=>$child[$id_field]
											,'name'=>$child[ preg_replace('/`/','',$this->table_fields['name'])]
											,'has_child'=>$child['has_children']
											,'selected'=>0
											,'parent'=>$parent
											,'position'=>$child[$position_field]
											,'default'=>($child[$is_default_field]=='Yes'?1:0)
											,'contador'=>$child['contador']
											,'mod5'=>fmod($child['contador'],5)
								      );
    

    if($child['has_children']){
      $this->get_teeth($child[$position_field],$father);
    }
  }
  $this->comb[$father]['teeth'][$position]['default_id']=$the_default;
  


}




function load_tree(){
  $this->tree=array();

  foreach($this->get_children('')    as $child){
    $this->tree[$child[ preg_replace('/`/','',$this->table_fields['id'])]]=
      array(
	    'name'=>$child[ preg_replace('/`/','',$this->table_fields['name'])]
	    ,'has_child'=>$child['has_children']
	    );
    if($child['has_children']){
      $this->tree[$child[ preg_replace('/`/','',$this->table_fields['id'])]]['children']=$this->get_branch($child[preg_replace('/`/','',$this->table_fields['position'])]);
    }

  }
  
  //print_r($this->c_list);
  // print_r($this->tree);
  

}

function get_branch($position){

  // print "$position\n";
  $branch=array();

  foreach( $this->get_children($position)   as $child){
    $branch[$child[ preg_replace('/`/','',$this->table_fields['id'])]]=
      array(
	    'name'=>$child[ preg_replace('/`/','',$this->table_fields['name'])]
	    ,'has_child'=>$child['has_children']
	    );
      if($child['has_children']){
      $branch[$child[ preg_replace('/`/','',$this->table_fields['id'])]]['children']=$this->get_branch($child[preg_replace('/`/','',$this->table_fields['position'])]);
     }

  }
  

  return $branch;

}

} // Class END
?>
