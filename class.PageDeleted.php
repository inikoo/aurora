<?php
/*

 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/


class PageDeleted {



    function PageDeleted($a1=false,$a2=false) {

        $this->table_name='Page Store Deleted';
        $this->ignore_fields=array('Page Store Deleted Key');
if($a1){

	if($a2){
	        $this->get_data($a1,$a2);

	}else{

        $this->get_data('id',$a1);
}
}

    }


    function get_data($key,$tag) {


        if ($key=='id')
            $sql=sprintf("select * from `Page Store Deleted Dimension` where `Page Store Deleted Key`=%d",$tag);
            elseif ($key=='page_key')
            $sql=sprintf("select * from `Page Store Deleted Dimension` where `Page Key`=%d",$tag);
        
        else
            return;
        
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Page Store Deleted Key'];
        }




    }



    function create($data) {
        $this->new=false;
        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
            if (preg_match('/Page Title|Page Description/i',$key))
                $values.="'".addslashes($value)."',";
            else if (preg_match('/Page Valid To/i',$key))
                $values.=addslashes($value).",";

            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Page Store Deleted Dimension` %s %s",$keys,$values);




        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->msg="Page Deleted Created";
            $this->get_data('id',$this->id);
            $this->new=true;


            return;
        } else {
            $this->msg="Error can not create deleted page";
        }
    }


    function get($key,$data=false) {
        switch ($key) {

        default:
            if (isset($this->data[$key]))
                return $this->data[$key];
            else
                return '';
        }
        return '';
    }
    
    
    function get_snapshot_date() {

		if ($this->data['Page Snapshot Last Update']!='')
			return strftime("%a %e %b %Y %H:%M %Z", strtotime($this->data['Page Snapshot Last Update'].' UTC')) ;
	}


    

  function get_formated_store_section() {
     

        switch ($this->data['Page Store Section']) {
        case 'Front Page Store':
            $formated_store_section=_('Front Page Store');
            break;
        case 'Search':
            $formated_store_section=_('Search');
            break;
        case 'Product Description':
            $formated_store_section=_('Product Description');
            break;
        case 'Information':
            $formated_store_section=_('Information');
            break;
        case 'Category Catalogue':
            $formated_store_section=_('Category Catalogue');
            break;
        case 'Family Catalogue':
            $formated_store_section=_('Family Catalogue').' <a href="family.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
            break;
        case 'Department Catalogue':
            $formated_store_section=_('Department Catalogue').' <a href="department.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
            break;
        case 'Store Catalogue':
            $formated_store_section=_('Store Catalogue').' <a href="store.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
            break;
        case 'Registration':
            $formated_store_section=_('Registration');
            break;
        case 'Client Section':
            $formated_store_section=_('Client Section');
            break;
        case 'Check Out':
            $formated_store_section=_('Check Out');
            break;
        default:
            $formated_store_section=$this->data['Page Store Section'];
            break;
        }

        return $formated_store_section;
    }



}

?>