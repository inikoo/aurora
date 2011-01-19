//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2010 LW

function get_thumbnails(data) {
    parent=data.parent;
    parent_key=data.parent_key;

    tipo=data.tipo;

    var table_id=0;
    var request='ar_assets.php?tipo='+tipo+'&parent='+parent+'&parent_key='+parent_key;
YAHOO.util.Connect.asyncRequest('POST',request , {success:function(o) {
        //alert(o.responseText)
        var r =  YAHOO.lang.JSON.parse(o.responseText);
        if (r.resultset.state==200) {
            var container=Dom.get('thumbnails'+table_id);
            for (x in r.resultset.data) {
                if (r.resultset.data[x].type=='item') {
                    var img = new YAHOO.util.Element(document.createElement('img'));
                    img.set('src', r.resultset.data[x].image);
                    img.set('alt', r.resultset.data[x].image);
                    var code_div = new YAHOO.util.Element(document.createElement('span'));
                    var description_div = new YAHOO.util.Element(document.createElement('div'));
                    var price_div = new YAHOO.util.Element(document.createElement('div'));
                    var rrp_div = new YAHOO.util.Element(document.createElement('div'));
                    var style_minus_button='';
                    qty=r.resultset.data[x].order_qty;
                    if (r.resultset.data[x].order_qty==0) {
                        style_minus_button='visibility:hidden'
                                           qty='';
                    }

                    var tr_image="<tr><td class='image' style=''>" +"<img style=''  src='"+r.resultset.data[x].image+"'></td></tr>";
                    var tr_description="<tr><td class='description' style=''>"+r.resultset.data[x].code+'<br/>'+r.resultset.data[x].units+' ' +r.resultset.data[x].name+"<br/>"+r.resultset.data[x].formated_price+"<br/>"+r.resultset.data[x].formated_rrp+"</td></tr>";
                    var tr_order="<tr><td class='order' style='' ><input onClick='change_item("+r.resultset.data[x].id+")' type='text' style='' value='"+qty+"' ovalue='"+qty+"' id='order_qty_"+r.resultset.data[x].id+"' ><button onClick='order_item("+r.resultset.data[x].id+")' id='order_button"+r.resultset.data[x].id+"' style='display:none'>Order</button><button onClick='undo_item("+r.resultset.data[x].id+")' id='undo_button"+r.resultset.data[x].id+"' style='display:none'>Undo</button><button id='fast_add"+r.resultset.data[x].id+"' onClick='fast_change_item(\"add\","+r.resultset.data[x].id+")' class='fast' style=''>+</button><button id='fast_remove"+r.resultset.data[x].id+"' onClick='fast_change_item(\"remove\","+r.resultset.data[x].id+")' class='fast' style='"+style_minus_button+"'>-</button></td></tr>";

                    var caca="<table border=0 style='width:100%'>"+tr_image+tr_description+tr_order+"</table>";
                    //code_div.set('innerHTML', r.resultset.data[x].code);
                    //description_div.set('innerHTML', r.resultset.data[x].units+' ' +r.resultset.data[x].name);
                    //price_div.set('innerHTML', r.resultset.data[x].formated_price);
                    //rrp_div.set('innerHTML', r.resultset.data[x].formated_rrp);

                    var div = new YAHOO.util.Element(document.createElement('div'));
                    div.set('id','product_container_'+r.resultset.data[x].id);
                    div.set('innerHTML',caca);
                    //img.appendTo(div);
                    //code_div.appendTo(div);
                    //description_div.appendTo(div);
                    //		price_div.appendTo(div);
                    //		rrp_div.appendTo(div);


                    div.appendTo(container);

                    Dom.addClass('product_container_'+r.resultset.data[x].id,'product_container')
                
                }

            }
              var div = new YAHOO.util.Element(document.createElement('div'));
                div.set('innerHTML','');
                div.setStyle('clear', 'both');


               div.appendTo(container);
              //alert(div)
        }

    }

                                                     });
}