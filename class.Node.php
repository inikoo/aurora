<?php

/***************************************************************
 * Nodes Class
 * Author: Shadi Ali
 * Em@il: write2shadi@gmail.com
 *
 * You Can use this class freely in your Commercial Applications.
 * ---------------------------------
 *
 * SQL TABLE:
 * -- at min you must have the following fields in your table structure ..
 *
 * CREATE TABLE `nodes` (
 * `id` BIGINT NOT NULL AUTO_INCREMENT ,
 * `position` VARCHAR( 255 ) NOT NULL ,
 * `ord` int NOT NULL ,
 *
 * -- add your fields here
 * --  ie -> `node_name` varchar(160),
 * -- add your fields here
 *
 * PRIMARY KEY ( `id` ) ,
 * INDEX ( `position` )
 * );
 *
 * class summary:
 * ------------------
 *
 * ->add_new($parent , $name , $fields )  // add new node
 * ->delete($id) // delete existing node and all sub-nodes, returns the affected Ids .. so you could run any other operations later .. maybe deleting linked records or deleting linked image files based on the returned ids.
 * ->update($id , $parent , $fields  ) // update existing node
 * ->build_list($id=0,$clickable=TRUE) // return array with the nodes ordered by "ord" , it could be clickable by setting $clickable = true, or else , it will be fully expanded
 * ->browse_by_id($id) // return array with sub nodes under a specific node only.
 * ->fetch ($id) // return existing node info.
 * ->count_nodes($id) // get sub nodes count below a TOP-LEVEL node $id.
 * ->order_node($id , $new_order) // change the order of a node Inside Its LEVEL.
 * ->html_output($id , $clickable) // output a html list ( customizable via the class variable $HtmlTree );
 * ->html_row_output($id) // out put a You>Are>Here like menu .. requires the current node id.
 ********************************************************************/
class nodes {

    var $id = 0;
    var $HtmlTree;
    var $HtmlRow;
    var $table_name = "`Category Dimension`";
    var $table_fields = array(
        'id'         => '`Category Key`',
        'position'   => '`Category Position`',
        'deep'       => '`Category Deep`',
        'ord'        => '`Category Order`',
        'name'       => '`Category Code`',
        'is_default' => '`Category Default`',
        'root_key'   => '`Category Root Key`'
    );


    var $sql_condition;


    var $sql_condition_where; // DON'T CHANGE THIS
    var $c_list = array();  // DON'T CHANGE THIS

    function __construct($table_name = null) {

        $this->table_name = $table_name;
        global $db;
        $this->db = $db;


        if ($this->sql_condition != "") {
            $this->sql_condition_where = " WHERE ".$this->sql_condition;
        }
    }


    function add_new($parent = 0, $fields = array()) { // add new category

        $position = $this->get_position($parent);

        $fields['Category Parent Key'] = $parent;
        $fields['Category Properties'] = '{}';


        $_keys   = '';
        $_values = '';
        foreach ($fields as $key => $value) {

            $_key = $key;

            if (!preg_match('/^\`.+\`$/', $key)) {
                $key = "`".$key."`";
            }

            $_keys .= ",".$key."";


            if ($_key == 'Category Main Image Key'
                or $_key == 'aiku_invoice_id'
                or $_key == 'aiku_part_id'
                or $_key == 'aiku_family_id'
                or $_key == 'aiku_department_id'
                or $_key == 'aiku_id'
                or $_key == 'staging_aiku_id'

            ) {
                $_values .= ','.prepare_mysql($value, true);
            } else {
                $_values .= ','.prepare_mysql($value, false);
            }


        }
        $_values = preg_replace('/^,/', '', $_values);
        $_keys   = preg_replace('/^,/', '', $_keys);

        $sql = "insert into ".$this->table_name." ($_keys) values ($_values)";



        $this->db->exec($sql);

        $this->id = $this->db->lastInsertId();
        if(!$this->id){

          //  print_r($fields);

        //    print "$sql\n";
return;
           // throw new Exception('Error inserting category');
        }

        $node_id  = $this->id;
        $position .= $node_id.">";
        $deep     = count(preg_split('/>/', $position)) - 1;

        $inserted_key = $this->id;

        $sql = "UPDATE ".$this->table_name."
               SET ".$this->table_fields['position']." = '".$position."' ,  ".$this->table_fields['deep']." = '".$deep."'
               WHERE ".$this->table_fields['id']." = '".$inserted_key."' ".$this->sql_condition;

        $this->db->exec($sql);
        if ($fields['Category Branch Type'] == 'Root') {
            $sql = sprintf(
                "UPDATE %s SET %s=%s WHERE %s=%d %s  ", $this->table_name, $this->table_fields['root_key'], $inserted_key, $this->table_fields['id'], $inserted_key, $this->sql_condition
            );
            $this->db->exec($sql);

        }


        $this->_optimize_orders($position);
    }


    function get_position($id) {
        if ($id == 0) {
            return "";
        }
        $sql = "SELECT ".$this->table_fields['position']." as position
               FROM ".$this->table_name."
               WHERE ".$this->table_fields['id']." = '".$id."' ".$this->sql_condition;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return $row['position'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


    function _optimize_orders($position) {


        $PositionS = explode(">", $position);
        array_pop($PositionS);
        array_pop($PositionS);
        if (count($PositionS) > 0) {
            $parentPosition = implode(">", $PositionS).">";
        } else {
            $parentPosition = 0;
        }


        // ok lets count the nodes in the same level;
        $sql = "SELECT *,".$this->table_fields['id']." as id,".$this->table_fields['ord']." as ord
               FROM ".$this->table_name."
               WHERE ".$this->table_fields['position']." RLIKE '^".$parentPosition."(([0-9])+\>){1}$' ".$this->sql_condition." order by ".$this->table_fields['ord']."  ASC";

        $i = 1;
        if ($result = $this->db->query($sql)) {
            foreach ($result as $node) {

                if ($i != $node['ord']) {

                    $sql2 = "UPDATE ".$this->table_name."
                        SET ".$this->table_fields['ord']." = '".$i."'
                        WHERE ".$this->table_fields['id']." = '".$node['id']."' ".$this->sql_condition." LIMIT 1";
                    $this->db->exec($sql2);

                }

                $i++;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


}
