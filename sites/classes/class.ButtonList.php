<?php
include_once('class.Product.php');
include_once('class.Family.php');


class ButtonList {
    function ButtonList($method='default',$code=false) {

        $this->products=array();
        $this->method=$method;

        switch ($this->method) {
        case 'awr':
            $this->store_key=1;
        }

        if (is_string($code) and $code!='') {

            $this->get_products_from_family($code,$this->store_key);

        } else if (is_array($code)) {




            $this->get_products_from_array($code,$this->store_key);
        }

    }
    function display() {

        switch ($this->method) {
        case 'awr':
        global $site_checkout_address,$site_checkout_id,$site_url;
        $currency='EUR';
        $i=0;
        $desc_len='';
        $code_len='';
        $order_txt='Pedir';
        $reset_txt='Borrar';
        $caca='table.order {width:%sem}td.first{width:%fem}';
         $style=sprintf('<link rel="stylesheet" type="text/css" href="../order.css" /><link rel="stylesheet" type="text/css" href="order.css" /><style type="text/css">table.order {font-size:11px;font-family:arial;}span.price{float:right;margin-right:5px}span.desc{margin-left:5px}span.outofstock{color:red;font-weight:800;float:right;margin-right:5px;}input.qty{width:100%%}td.qty{width:3em}</style>
                           <style type="text/css">.prod_info{text-align:left;} .prod_info span{magin:0;color:red;font-family:arial;;font-weight:800;font-size:12px}</style>');


            $form=sprintf('%s<table class="Order" border=0><FORM METHOD="POST" ACTION="%s"><INPUT TYPE="HIDDEN" NAME="userid" VALUE="%s"><input type="hidden" name="return" value="%s">'
                          ,$style
                          ,addslashes($site_checkout_address)
                          ,addslashes($site_checkout_id)
                          ,$site_url.$_SERVER['PHP_SELF']
                         );

            $form.="\n";
        
        
        
        
        
        echo $form;
            foreach($this->products as $product){
            echo $product->get_order_list_form(array('counter'=>$i,'options'=>'','currency'=>$currency));
            $i++;
            }
         
            
            $form=sprintf('<tr id="submit_tr"><td id="submit_td" colspan="3" ><input name="Submit" type="submit" class="text" value="%s"> <input name="Reset" type="reset" class="text"  id="Reset" value="%s"></td></tr></form></table>',$order_txt,$reset_txt);

           //echo $this->product->get('Full Order Form');
            echo $form;


        }

    }
    function get_products_from_array($codes,$store) {
        foreach($codes as $code) {
            $this->products[$code]=new Product('code_store',$code,$store);

        }

    }
    function get_products_from_family($code,$store) {
        $family=new Family('code_store',$code,$store);
        if ($family->id) {
            $sql=sprintf("select `Product ID`,`Product Code` from `Product Dimension` where `Product Sales Type`='Public Sale' and  `Product Family Key`=%d  ",$family->id);
           // print $sql;
            $res=mysql_query($sql);
            while ($row=mysql_fetch_assoc($res)) {
                $this->products[$row['Product Code']]=new Product('pid',$row['Product ID']);
            }
        }
    }
}


?>